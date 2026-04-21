<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Lấy tab từ URL, mặc định là info
        $tab = $request->get('tab', 'info');
        return view('client.profile', compact('user', 'tab'));
    }

    // ===== UPDATE INFO & AVATAR =====
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check email trùng (trừ chính mình ra)
        $check = User::where('email', $request->email)
            ->where('user_id', '!=', $user->user_id)
            ->first();

        if ($check) {
            return back()->with('error', 'Email này đã có người khác sử dụng rồi!');
        }

        // Xử lý Upload Ảnh
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar && File::exists(public_path('uploads/avatars/' . $user->avatar))) {
                File::delete(public_path('uploads/avatars/' . $user->avatar));
            }

            // Lưu ảnh mới
            $file = $request->file('avatar');
            $fileName = time() . '_' . $user->user_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $fileName);
            
            // Cập nhật tên file vào model
            $user->avatar = $fileName;
        }

        // Cập nhật các thông tin khác
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return redirect()->to('/profile?tab=info')
            ->with('success', 'Đã lưu thay đổi thông tin cá nhân!');
    }

    // ===== DELETE AVATAR =====
    public function deleteAvatar()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->avatar) {
            // Xóa file vật lý
            if (File::exists(public_path('uploads/avatars/' . $user->avatar))) {
                File::delete(public_path('uploads/avatars/' . $user->avatar));
            }
            // Xóa tên file trong DB
            $user->avatar = null;
            $user->save();

            return back()->with('success', 'Đã xóa ảnh đại diện!');
        }

        return back()->with('error', 'Bạn chưa có ảnh đại diện để xóa');
    }

    // ===== CHANGE PASSWORD =====
    public function changePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check mật khẩu cũ (Laravel dùng hàm getAuthPassword đã định nghĩa ở Model)
        if (!Hash::check($request->current_password, $user->password_hashed) && $request->current_password !== $user->PASSWORD) {
            return back()->with('error', 'Mật khẩu hiện tại bạn nhập không đúng');
        }

        // Check confirm
        if ($request->new_password != $request->confirm_password) {
            return back()->with('error', 'Hai lần nhập mật khẩu mới không khớp nhau');
        }

        // Check độ dài
        if (strlen($request->new_password) < 6) {
            return back()->with('error', 'Mật khẩu mới phải từ 6 ký tự trở lên');
        }

        // Update cả 2 cột mật khẩu cho chắc ăn như ông muốn
        $user->password_hashed = Hash::make($request->new_password);
        $user->PASSWORD = $request->new_password;
        $user->save();

        return redirect()->to('/profile?tab=password')
            ->with('success', 'Chúc mừng! Bạn đã đổi mật khẩu thành công');
    }
}