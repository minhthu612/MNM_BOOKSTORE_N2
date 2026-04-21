@extends('layouts.app')
@section('title', 'Chỉnh sửa mã giảm giá')
@section('content')
<div class="container-fluid">
    <div class="card the-bang border-0 shadow-sm col-md-6 mx-auto">
        <div class="card-body p-4">
            <h5 class="fw-bold text-primary mb-4"><i class="fas fa-edit me-2"></i>CHỈNH SỬA COUPON</h5>
            <form action="{{ route('admin.coupons.update', $coupon->coupon_id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="fw-bold small mb-1">Mã Code</label>
                    <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required style="text-transform: uppercase">
                </div>
                <div class="mb-3">
                    <label class="fw-bold small mb-1">Mức giảm</label>
                    <input type="number" name="discount" class="form-control" value="{{ $coupon->discount }}" required>
                </div>
                <div class="mb-3">
                    <label class="fw-bold small mb-1">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $coupon->status == 'active' ? 'selected' : '' }}>Kích hoạt (Active)</option>
                        <option value="expired" {{ $coupon->status == 'expired' ? 'selected' : '' }}>Hết hạn (Expired)</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">CẬP NHẬT</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">QUAY LẠI</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection