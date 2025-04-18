<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thông báo thanh toán sân</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 30px; color: #333;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <h2 style="color: #2c3e50;">Thông báo thanh toán</h2>
    <p>Khách hàng <strong>{{ $customerName }}</strong> (SĐT: {{ $customerPhone }}) đã thanh toán cho sân <strong>{{ $fieldName }}</strong> tại <strong>{{ $venueName }}</strong>.</p>
    <p><strong>Số tiền:</strong> {{ number_format($price, 0, ',', '.') }} VNĐ</p>
    <p><strong>Nội dung:</strong> {{ $message_data }}</p>
    @foreach ($grouped as $item)
        <p><strong>Sân: </strong>{{ $item['court_name'] }}</p>
        <ul style="padding-left: 20px; margin: 0;">
            @foreach ($item['times'] as $time)
                <li>{{ $time }}</li>
            @endforeach
        </ul>
    @endforeach
    <p><strong>Ngày:</strong> {{ $date }}</p>
{{--    <p><strong>Sân:</strong> {{ $courtName }}</p>--}}
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ '#' }}"
           style="padding: 12px 24px; background-color: #3498db; color: white; text-decoration: none; border-radius: 6px; font-size: 16px;">
            Xem chi tiết đặt sân
        </a>
    </div>
    <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với quản trị viên.</p>
    <p style="margin-top: 40px;">Trân trọng,<br><strong>Hệ thống Quản lý Sân</strong></p>
</div>
</body>
</html>
