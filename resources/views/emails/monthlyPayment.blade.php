<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Payment Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 30px; color: #333;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <h2 style="color: #2c3e50;">Xin chào, {{ $ownerName }}!</h2>
    <p>Đây là thông báo đến kỳ thanh toán tháng cho sân <strong>{{ $venueName }}</strong>.</p>
    <p>Vui lòng nhấn nút bên dưới để tiến hành thanh toán:</p>
    <div style="text-align: center; margin: 30px 0;">
        <a href="http://localhost:3001/venues/venuePayment"
           style="padding: 12px 24px; background-color: #27ae60; color: white; text-decoration: none; border-radius: 6px; font-size: 16px;">
            Thanh toán ngay
        </a>
    </div>
    <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
    <p style="margin-top: 40px;">Trân trọng,<br><strong>Hệ thống Quản lý Sân</strong></p>
</div>
</body>
</html>
