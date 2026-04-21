@extends('layouts.app')
@section('title', 'Thêm mã giảm giá mới')
@section('content')
<div class="container-fluid">
    <div class="card the-bang border-0 shadow-sm col-md-6 mx-auto">
        <div class="card-body p-4">
            <h5 class="fw-bold text-primary mb-4"><i class="fas fa-plus-circle me-2"></i>TẠO COUPON MỚI</h5>
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold small mb-1">Mã Code</label>
                    <input type="text" name="code" class="form-control" placeholder="Ví dụ: GIAMGIA2026" required style="text-transform: uppercase">
                </div>
                <div class="mb-3">
                    <label class="fw-bold small mb-1">Mức giảm (Nhập % hoặc số tiền cụ thể)</label>
                    <input type="number" name="discount" class="form-control" placeholder="Ví dụ: 20 hoặc 50000" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small mb-1">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active">Kích hoạt (Active)</option>
                        <option value="expired">Hết hạn (Expired)</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">TẠO MÃ NGAY</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">HỦY</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection