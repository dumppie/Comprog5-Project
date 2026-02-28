<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $previousStatus,
        public ?string $pdfPath = null
    ) {
        $this->order->load(['orderItems.product', 'paymentMethod', 'orderStatus', 'user']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Status Update: ' . $this->order->orderStatus->name . ' - #' . $this->order->transaction_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
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
