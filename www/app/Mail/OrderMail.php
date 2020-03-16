<?php

namespace App\Mail;

use App\Http\Resources\OrderResource;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OrderResource $order)
    {
        $this->order = collect($order)->all();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.new-order')->subject('Seu pedido foi recebido e está sendo preparado.');
    }
}
