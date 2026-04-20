<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;
    
    public $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function build()
    {
        return $this->subject('Xác nhận đơn hàng #' . $this->order->order_code)
                    ->view('emails.order');
    }
}