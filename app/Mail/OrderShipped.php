<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class OrderShipped extends Mailable implements ShouldQueue {

    use Queueable, SerializesModels;

    public $order;

    public function __construct ( $order ) {

        $this->order = $order;

    }
    public function envelope () {

        return new Envelope(
            subject: 'Order Shipped',
            tags: ['shipment'],
            metadata: [
                'order_id' => $this->order['id'],
                'product_name' => $this->order['product']['name'],
            ],
        );

    }
    public function attachments () {

        return [
            Attachment::fromPath(public_path('static/media/logo.png'))->as('logo.png'),
            Attachment::fromPath(Storage::path('file.text'))->as('My File'),
            Attachment::fromStorage("file.text")->as('File Storage'),
        ];

    }
    public function content () {

        return new Content(view: 'mails.order', with: ['order' => $this->order]);

    }

}
