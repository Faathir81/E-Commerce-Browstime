<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f6f3; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f6f3; padding: 20px 0;">
        <tr>
            <td>
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" align="center" style="margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e4e0da;">
                    <tr>
                        <td style="padding: 20px 24px; background-color: #2d2216; color: #ffffff;">
                            <h2 style="margin: 0; font-size: 20px;">Terima kasih, {{ $customerName }}!</h2>
                            <p style="margin: 4px 0 0; font-size: 14px;">Pesanan kamu sudah kami terima.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            <p style="margin: 0 0 12px; font-size: 15px; color: #3d2d20;">Berikut ringkasan pesananmu:</p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size: 14px; color: #3d2d20;">
                                <tr>
                                    <td style="padding: 6px 0; width: 180px; color: #6a5948;">Kode Pesanan</td>
                                    <td style="padding: 6px 0;"><strong>{{ $orderCode }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6a5948;">Status Awal</td>
                                    <td style="padding: 6px 0;">{{ $statusLabel }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; color: #6a5948;">Total Pembayaran</td>
                                    <td style="padding: 6px 0;">Rp {{ $totalDisplay }}</td>
                                </tr>
                            </table>
                            <p style="margin: 20px 0 12px; font-size: 15px; color: #3d2d20;">
                                Kamu bisa melihat detail dan progres pesanan melalui halaman tracking berikut:
                            </p>
                            <p style="margin: 0 0 18px;">
                                <a href="{{ $trackingUrl }}" style="display: inline-block; padding: 12px 18px; background-color: #7a4b24; color: #ffffff; text-decoration: none; border-radius: 6px;">Lihat Detail Pesanan</a>
                            </p>
                            <p style="margin: 0; font-size: 13px; color: #6a5948;">Jika ada pertanyaan, balas email ini atau hubungi kami.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 24px; background-color: #f3eee7; color: #8a7766; font-size: 12px; text-align: center;">
                            Email ini dikirim otomatis oleh sistem BROWSTIME. Abaikan jika kamu merasa tidak membuat pesanan.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
