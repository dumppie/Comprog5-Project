<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $pdfPath = null
    ) {
        $this->order->load(['orderItems.product', 'paymentMethod', 'orderStatus']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation #' . $this->order->transaction_id . ' - La Petite Pâtisserie',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }

    public function attachments(): array
    {
        if (!$this->pdfPath || !file_exists($this->pdfPath)) {
            return [];
        }
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('receipt-' . $this->order->transaction_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
