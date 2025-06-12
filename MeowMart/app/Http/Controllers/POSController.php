<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Mail\OrderReceipt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with products that have stock greater than 0
        $productsQuery = Product::where('stock', '>', 0);

        // Check if a search query is present
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            // Filter products by name, case-insensitive
            $productsQuery->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $productsQuery->get();
        $cart = Session::get('cart', []);

        $discountType = session('discount');
        $discountPercent = 0;

        switch ($discountType) {
            case 'student':
            case 'pwd':
            case 'senior':
                $discountPercent = 20;
                break;
            case 'others':
                $discountPercent = 10;
                break;
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discountAmount = ($subtotal * $discountPercent) / 100;
        $total = $subtotal - $discountAmount;

        return view('pos.index', compact(
            'products',
            'cart',
            'subtotal',
            'total',
            'discountAmount',
            'discountPercent'
        ));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);
        return back();
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return back();
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = Session::get('cart', []);
        unset($cart[$productId]);
        Session::put('cart', $cart);
        return back();
    }

    public function clearCart()
    {
        Session::forget('cart');
        return redirect()->route('pos.index');
    }

    public function checkout(Request $request)
    {
        $paymentMethod = $request->input('payment_method');

        $cart = Session::get('cart', []);
        $totals = $this->calculateCartTotals();
        $subtotal = $totals['subtotal'];
        $total = $totals['total'];
        $discountAmount = $totals['discountAmount'];

        return view('pos.checkout', compact('cart', 'subtotal', 'total', 'discountAmount', 'paymentMethod'));
    }

    public function confirmOrder(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('pos.index')->with('error', 'Cart is empty.');
        }

        $totals = $this->calculateCartTotals();
        $subtotal = $totals['subtotal'];
        $total = $totals['total'];
        $discountAmount = $totals['discountAmount'];
        $discountType = session('discount');

        $order = Order::create([
            'items' => $cart,
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'total' => $total,
            'discount_type' => $discountType,
            'customer_email' => $request->customer_email,
            'payment_method' => $request->payment_method,
            'user_id' => Auth::id(),
        ]);

        // Reduce product stock
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        // --- START: Add Slack Notification Logic ---

        // Prepare data for Slack
        $orderData = [
            'order_id' => $order->id,
            'total_amount' => $order->total,
            'subtotal' => $order->subtotal,
            'discount' => $order->discount,
            'customer_email' => $order->customer_email,
            'payment_method' => $order->payment_method,
            'discount_type' => $order->discount_type,
            'processed_by' => Auth::user() ? Auth::user()->name : 'Guest', // Get staff name, fallback if not logged in
            'order_items' => $order->items, // This is already cast to array by model
            'order_date' => $order->created_at->toDateTimeString(), // Formatted datetime
            // You can add more data from the $order object if needed
        ];

        // Make.com Webhook URL (replace with YOUR URL if you regenerated it)
        $makeWebhookUrl = 'https://hook.us2.make.com/qm4fa6ef0f3vepbmf3afyt1fm2jd85we';

        try {
            Http::post($makeWebhookUrl, $orderData);
            // Optionally, log a success message or handle response if Make.com sends one
            Log::info('Order confirmation webhook sent to Make.com for Order ID: ' . $order->id);
        } catch (\Exception $e) {
            // Log any errors if the webhook fails to send
            Log::error('Failed to send order confirmation webhook for Order ID: ' . $order->id . ' Error: ' . $e->getMessage());
            // You might want to implement more robust error handling here,
            // e.g., queueing the notification for retry.
        }

        // --- END: Add Slack Notification Logic ---

        // Send email
        if ($request->customer_email) {
            Mail::to($request->customer_email)->send(new OrderReceipt($order));
        }

        // Clear session
        Session::forget('cart');
        Session::forget('discount');

        return view('pos.confirmation', compact('order'));
    }

    private function calculateCartTotals()
    {
        $cart = session('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discountType = session('discount');
        $discountPercent = 0;

        switch ($discountType) {
            case 'others':
                $discountPercent = 0.10;
                break;
            case 'student':
            case 'pwd':
            case 'senior':
                $discountPercent = 0.20;
                break;
        }

        $discountAmount = $subtotal * $discountPercent;
        $total = $subtotal - $discountAmount;

        return compact('subtotal', 'total', 'discountAmount');
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_type' => 'nullable|in:others,student,pwd,senior',
        ]);

        session(['discount' => $request->discount_type]);
        return redirect()->route('pos.index')->with('success', 'Discount applied.');
    }
}
