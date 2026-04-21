<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $page = $request->get('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $query = DB::table('coupons');

        if (!empty($search)) {
            $query->where('code', 'LIKE', "%$search%");
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $total_coupons = $query->count();
        $coupons_list = $query->orderBy('coupon_id', 'asc')
                             ->limit($limit)
                             ->offset($offset)
                             ->get();

        $total_pages = ceil($total_coupons / $limit);

        return view('admin.coupons.index', compact(
            'coupons_list', 'total_coupons', 'search', 'status', 'page', 'total_pages'
        ));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        DB::table('coupons')->insert([
            'code' => strtoupper($request->code),
            'discount' => $request->discount,
            'status' => $request->status,
            'created_at' => now()
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function edit($id)
    {
        $coupon = DB::table('coupons')->where('coupon_id', $id)->first();
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        DB::table('coupons')->where('coupon_id', $id)->update([
            'code' => strtoupper($request->code),
            'discount' => $request->discount,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cập nhật thành công!');
    }

    public function delete($id)
    {
        DB::table('coupons')->where('coupon_id', $id)->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa mã giảm giá!');
    }
}