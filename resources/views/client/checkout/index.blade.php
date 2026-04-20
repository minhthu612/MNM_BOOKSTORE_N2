@extends('layouts.client')

@section('title', 'Thanh toán')

@section('content')
<div class="container mt-4">
    <h2>Thanh toán</h2>
    
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-header">Thông tin nhận hàng</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Họ tên *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Số điện thoại *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Địa chỉ *</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Đặt hàng</button>
                <a href="{{ route('cart.index') }}" class="btn btn-secondary">Quay lại giỏ hàng</a>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Đơn hàng của bạn</div>
                <div class="card-body">
                    @foreach($cart as $item)
                    <div class="d-flex justify-content-between">
                        <span>{{ $item['title'] }} x {{ $item['quantity'] }}</span>
                        <span>{{ number_format($item['price'] * $item['quantity']) }}đ</span>
                    </div>
                    <hr>
                    @endforeach
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($total) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection