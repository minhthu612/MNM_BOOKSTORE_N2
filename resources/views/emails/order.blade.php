<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
</head>
<body>
    <h2>Cảm ơn bạn đã đặt hàng!</h2>
    <p>Mã đơn hàng: <strong>{{ $order->order_code }}</strong></p>
    <p>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    
    <h3>Thông tin nhận hàng</h3>
    <p>Họ tên: {{ $order->name }}</p>
    <p>Điện thoại: {{ $order->phone }}</p>
    <p>Địa chỉ: {{ $order->address }}</p>
    
    <h3>Chi tiết đơn hàng</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr><th>Sách</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
            <tr>
                <td>{{ $detail->book->title ?? 'Sản phẩm' }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->price) }}đ</td>
                <td>{{ number_format($detail->price * $detail->quantity) }}đ</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Tổng cộng</th>
                <th>{{ number_format($order->total_amount) }}đ</th>
            </tr>
        </tfoot>
    </table>
    
    <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!</p>
</body>
</html>