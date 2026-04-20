<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Book;
use App\Mail\OrderPlaced;

class CheckoutController extends Controller
{
    public function index()
    {
        // Lấy giỏ hàng từ session (theo cấu trúc đã thống nhất)
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('client.checkout.index', compact('cart', 'total'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'note' => 'nullable|string',
        ]);
        
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống');
        }
        
        DB::beginTransaction();
        try {
            // Tạo order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_code' => 'ORD_' . time() . '_' . Auth::id(),
                'total_amount' => 0, // sẽ tính sau
                'status' => 'pending',
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note,
            ]);
            
            $total = 0;
            // Tạo order detail
            foreach ($cart as $bookId => $item) {
                $book = Book::find($bookId);
                if ($book) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'book_id' => $bookId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                    $total += $item['price'] * $item['quantity'];
                    
                    // Trừ tồn kho (nếu có inventory)
                    $book->decrement('stock', $item['quantity']);
                }
            }
            
            $order->update(['total_amount' => $total]);
            
            // Xóa giỏ hàng
            session()->forget('cart');
            
            DB::commit();
            
            // Gửi email
            Mail::to(Auth::user()->email)->send(new OrderPlaced($order));
            
            return redirect()->route('checkout.success', $order->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }
    
    public function success($orderId)
    {
        $order = Order::with('orderDetails.book')->findOrFail($orderId);
        // Kiểm tra order thuộc user hiện tại
        if ($order->user_id != Auth::id()) {
            abort(403);
        }
        return view('client.checkout.success', compact('order'));
    }
}