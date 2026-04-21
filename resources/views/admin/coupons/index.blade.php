@extends('layouts.app')
@section('title', 'Quản lý Mã giảm giá')
@section('content')

<style>
    .the-bang { background: #fff; border-radius: 12px; }
    .bang-du-lieu th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; color: #495057; }
    .form-control, .form-select { border-radius: 20px; }
   
    .nut-hanh-dong {
        border-radius: 20px !important;
        padding: 5px 15px !important;
        margin: 0 3px;
        font-size: 0.85rem;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s;
    }
    .nut-hanh-dong:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .code-badge {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 800;
        color: #0d6efd;
        background: #e7f1ff;
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px dashed #0d6efd;
    }

    /* ĐỔI MÀU BADGE THEO ẢNH CỦA BẠN */
    .badge-tron { 
        border-radius: 50px; 
        padding: 6px 16px; 
        font-weight: 800; 
        font-size: 11px; 
        color: #fff !important; /* Chữ trắng */
        text-transform: uppercase;
        display: inline-block;
        min-width: 100px;
    }
    
    /* Màu xanh lá đậm (ĐÃ GIAO) -> dùng cho Active */
    .status-active { background-color: #198754; } 
    
    /* Màu đỏ đậm (ĐÃ HỦY) -> dùng cho Expired */
    .status-expired { background-color: #dc3545; } 

    /* Màu xanh dương (ĐANG GIAO) hoặc vàng (CHỜ XỬ LÝ) bạn có thể dùng thêm nếu cần */
</style>

<div class="container-fluid">
    <div class="card the-bang border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 text-primary fw-bold">DANH SÁCH MÃ GIẢM GIÁ</h5>
                <p class="mb-0 small text-muted">Hiện có {{$total_coupons}} mã giảm giá trong hệ thống</p>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Tạo mã mới
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.coupons.index') }}" class="row g-2 mb-4">
                <div class="col-md-4">
                    <input type="text" class="form-control px-3" name="search" value="{{$search}}" placeholder="Tìm mã giảm giá...">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Trạng thái --</option>
                        <option value="active" @if($status=='active') selected @endif>Đang áp dụng</option>
                        <option value="expired" @if($status=='expired') selected @endif>Đã hết hạn</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100 rounded-pill fw-bold">Lọc</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Xóa lọc</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover bang-du-lieu align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>ID</th>
                            <th>Mã Code</th>
                            <th>Mức giảm</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($coupons_list as $c)
                            <tr class="text-center">
                                <td><span class="text-muted fw-bold">#{{$c->coupon_id}}</span></td>

                                <td>
                                    <span class="code-badge">{{$c->code}}</span>
                                </td>

                                <td>
                                    @if($c->discount > 100)
                                        <strong class="text-danger">{{number_format($c->discount)}}đ</strong>
                                    @else
                                        <strong class="text-danger">{{$c->discount}}%</strong>
                                    @endif
                                </td>

                                <td>
                                    {{-- CẬP NHẬT BADGE MÀU ĐẬM Ở ĐÂY --}}
                                    @if($c->status == 'active')
                                        <span class="badge-tron status-active">Đang áp dụng</span>
                                    @else
                                        <span class="badge-tron status-expired">Đã hết hạn</span>
                                    @endif
                                </td>

                                <td><small class="text-muted">{{ date('d/m/Y H:i', strtotime($c->created_at)) }}</small></td>

                                <td>
                                    <a href="{{ route('admin.coupons.edit', $c->coupon_id) }}" class="btn btn-info text-white nut-hanh-dong">
                                        <i class="fas fa-edit me-1"></i> Sửa
                                    </a>
                                    <a href="{{ route('admin.coupons.delete', $c->coupon_id) }}" class="btn btn-danger nut-hanh-dong" onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                        <i class="fas fa-trash-alt me-1"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    <ul class="pagination">
        <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
            <a class="page-link" href="?page={{ $page - 1 }}&search={{ $search }}&status={{ $status }}">«</a>
        </li>

        @for($i = 1; $i <= $total_pages; $i++)
            <li class="page-item {{ $page == $i ? 'active' : '' }}">
                <a class="page-link" href="?page={{ $i }}&search={{ $search }}&status={{ $status }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ $page >= $total_pages ? 'disabled' : '' }}">
            <a class="page-link" href="?page={{ $page + 1 }}&search={{ $search }}&status={{ $status }}">»</a>
        </li>
    </ul>
</div>
@endsection