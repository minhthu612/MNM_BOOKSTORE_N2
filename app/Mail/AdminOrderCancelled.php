<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;

    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('🔔 THÔNG BÁO: ĐƠN HÀNG #' . $this->order->order_id . ' VỪA BỊ HỦY')
                    ->view('emails.order')
                    ->with([
                        'title' => 'CẢNH BÁO: KHÁCH HỦY ĐƠN HÀNG',
                        'content' => 'Thông báo Admin, khách hàng ' . $this->user->fullname . ' đã thực hiện hủy đơn hàng',
                        'content_suffix' => 'trên hệ thống. Vui lòng kiểm tra lại để cập nhật kho hàng.'
                    ]);
    }
}