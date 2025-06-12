@component('mail::message')
    # MeowMart Receipt

    Meow! Thank you for ordering with us. 🐈

    **Order Summary**

    @foreach ($order->items as $index => $item)
        - **{{ $item['name'] }}** (x{{ $item['quantity'] }}) - ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
    @endforeach

    **Subtotal:** ₱{{ number_format($order->subtotal, 2) }}
    **Discount:** -₱{{ number_format($order->discount, 2) }}
    **Total:** ₱{{ number_format($order->total, 2) }}

    It only takes two minutes to complete our feedback survey. Your input helps us improve our service and products.

    Feedback here 👉 https://forms.gle/Q5sU7n43M9uWTPqn6

    See you again soon!
    **MeowMart**
@endcomponent
