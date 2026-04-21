@extends('layouts.client')

@section('content')
<style>
    body { background:#f5f7fb; }
    .profile-box { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.06); margin-top: 30px;}
    .profile-header { background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; text-align:center; padding:40px 20px; }
    
    /* Avatar Styles */
    .avatar-wrapper { position: relative; width: 100px; margin: 0 auto 10px; }
    .avatar { width:100px; height:100px; border-radius:50%; background:#fff; color:#667eea; display:flex; align-items:center; justify-content:center; font-size:38px; border:4px solid rgba(255,255,255,0.3); object-fit: cover; overflow: hidden; }
    .avatar img { width: 100%; height: 100%; object-fit: cover; }
    
    /* Nút xóa ảnh */
    .delete-avatar { position: absolute; top: 0; right: -5px; background: #ff4d4d; color: white; border-radius: 50%; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 2px solid #fff; cursor: pointer; text-decoration: none; transition: 0.3s; z-index: 10; }
    .delete-avatar:hover { background: #cc0000; color: #fff; transform: scale(1.1); }

    .tab-menu { display:flex; border-bottom:2px solid #eee; }
    .tab-menu a { padding:12px 18px; text-decoration:none; font-weight:600; color:#666; border-bottom:3px solid transparent; }
    .tab-menu a.active { color:#667eea; border-bottom:3px solid #667eea; }
    .form-box { padding:25px; }
    .input { border-radius:10px !important; padding:12px; border:1px solid #ddd; }
    .btn-main { border-radius:10px; padding:10px 25px; font-weight:600; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-box">
                {{-- HEADER --}}
                <div class="profile-header">
                    <div class="avatar-wrapper">
                        <div class="avatar" id="avatarContainer">
                            @if($user->avatar)
                                <img src="{{ asset('uploads/avatars/'.$user->avatar) }}" id="avatarPreview" alt="Avatar">
                            @else
                                <div id="avatarPlaceholder"><i class="fas fa-user"></i></div>
                                <img src="" id="avatarPreview" alt="Avatar" style="display:none;">
                            @endif
                        </div>
                        {{-- Nút xóa ảnh đại diện --}}
                        @if($user->avatar)
                            <a href="{{ url('/profile/delete-avatar') }}" class="delete-avatar" onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh đại diện này không?')" title="Xóa ảnh">
                                <i class="fas fa-trash"></i>
                            </a>
                        @endif
                    </div>
                    <h4 class="mb-0">{{ $user->fullname }}</h4>
                    <small>{{ $user->role ?? 'Khách hàng thành viên' }}</small>
                </div>

                {{-- TAB MENU --}}
                <div class="tab-menu">
                    <a href="{{ url('/profile?tab=info') }}" class="{{ $tab == 'info' ? 'active' : '' }}">
                        <i class="fas fa-id-card me-2"></i>Thông tin cá nhân
                    </a>
                    <a href="{{ url('/profile?tab=password') }}" class="{{ $tab == 'password' ? 'active' : '' }}">
                        <i class="fas fa-shield-alt me-2"></i>Đổi mật khẩu
                    </a>
                </div>

                <div class="form-box">
                    {{-- THÔNG BÁO --}}
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
                    @endif

                    {{-- TAB INFO --}}
                    @if($tab == 'info')
                    <form method="POST" action="{{ url('/profile/update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label class="fw-bold small mb-1">Thay đổi ảnh đại diện</label>
                                <input type="file" name="avatar" id="avatarInput" class="form-control input" accept="image/*">
                                <div class="form-text mt-1 small text-muted">Hỗ trợ định dạng: JPG, PNG, GIF.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold small mb-1">Họ tên</label>
                                <input name="fullname" class="form-control input" value="{{ $user->fullname }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold small mb-1">Email</label>
                                <input name="email" type="email" class="form-control input" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold small mb-1">Số điện thoại</label>
                                <input name="phone" class="form-control input" value="{{ $user->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold small mb-1">Ngày tạo</label>
                                <input class="form-control input bg-light" value="{{ $user->created_at }}" readonly>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary btn-main shadow">LƯU THÔNG TIN</button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-main">QUAY LẠI</a>
                        </div>
                    </form>
                    @endif

                    {{-- TAB PASSWORD --}}
                    @if($tab == 'password')
                    <form method="POST" action="{{ url('/profile/password') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold small mb-1">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control input" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="fw-bold small mb-1">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold small mb-1">Xác nhận mật khẩu</label>
                                <input type="password" name="confirm_password" class="form-control input" required>
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <button type="submit" class="btn btn-dark btn-main shadow">ĐỔI MẬT KHẨU</button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-main">QUAY LẠI</a>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ XEM TRƯỚC ẢNH --}}
<script>
    document.getElementById('avatarInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection