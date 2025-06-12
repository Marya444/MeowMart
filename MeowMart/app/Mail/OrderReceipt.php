<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Receipt',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.receipt',
        );
    }

    public function build()
    {
        $email = $this->markdown('emails.orders.receipt')
            ->subject('Order Receipt')
            ->with(['order' => $this->order]);

        // Dynamically embed all product images
        foreach ($this->order->items as $index => $item) {
            $imagePath = public_path($item['image']); // example: storage/products/whiskas.png
            if (file_exists($imagePath)) {
                $cid = 'product-image-' . $index;
                $email->withSwiftMessage(function ($message) use ($imagePath, $cid) {
                    $message->embed($imagePath, $cid);
                });
            }
        }

        return $email;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
