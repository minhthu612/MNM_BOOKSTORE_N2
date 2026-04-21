<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class OrderCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;

    public function __construct($order)
    {
        $this->order = $order;
        $this->user = Auth::user(); // Lấy user hiện tại
    }

    public function build()
    {
        return $this->subject('Xác nhận hủy đơn hàng #' . $this->order->order_id)
                    ->view('emails.order')
                    ->with([
                        'title' => 'ĐƠN HÀNG ĐÃ HỦY THÀNH CÔNG',
                        'content' => 'Chào ' . $this->user->fullname . ', chúng tôi xác nhận đơn hàng',
                        'content_suffix' => 'đã được hủy theo yêu cầu của bạn.'
                    ]);
    }
}