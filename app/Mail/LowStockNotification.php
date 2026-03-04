<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public int $remainingStock,
        public string $stockStatus
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@shop.com', 'Shop System'),
            subject: "Low Stock Alert: {$this->product->name}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.low-stock-notification',
            with: [
                'productName' => $this->product->name,
                'category' => $this->product->category,
                'remainingStock' => $this->remainingStock,
                'stockStatus' => $this->stockStatus,
                'price' => $this->product->formatted_price,
                'productId' => $this->product->id,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
