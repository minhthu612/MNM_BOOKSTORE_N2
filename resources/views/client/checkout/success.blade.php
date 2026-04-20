@extends('layouts.client')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mt-5 text-center">
    <div class="alert alert-success">
        <h3>Đặt hàng thành công!</h3>
        <p>Mã đơn hàng: <strong>{{ $order->order_code }}</strong></p>
        <p>Cảm ơn bạn đã mua hàng. Email xác nhận đã được gửi đến {{ Auth::user()->email }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Về trang chủ</a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Xem đơn hàng</a>
    </div>
</div>
@endsection