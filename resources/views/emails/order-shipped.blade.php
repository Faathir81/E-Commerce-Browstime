<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Sedang Dikirim</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f6f3; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f6f3; padding: 20px 0;">
        <tr>
            <td>
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" align="center" style="margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e4e0da;">
                    <tr>
                        <td style="padding: 20px 24px; background-color: #2d2216; color: #ffffff;">
                            <h2 style="margin: 0; font-size: 20px;">Pesanan dalam perjalanan</h2>
                            <p style="margin: 4px 0 0; font-size: 14px;">Halo {{ $customerName }}, kami sedang mengirimkan pesananmu.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            <p style="margin: 0 0 12px; font-size: 15px; color: #3d2d20;">Ringkasan pengiriman:</p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size: 14px; color: #3d2d20;">
                                <tr>
                                    <td style="padding: 6px 0; width: 180px; color: #6a5948;">Kode Pesanan</td>
                                    <td style="padding: 6px 0;"><strong>{{ $orderCode }}</strong></td>
                                </tr>
                                @if ($etaText)
                                    <tr>
                                        <td style="padding: 6px 0; color: #6a5948;">Estimasi Tiba</td>
                                        <td style="padding: 6px 0;">{{ $etaText }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="padding: 6px 0; color: #6a5948;">Nomor Resi</td>
                                    <td style="padding: 6px 0;">{{ $resiText }}</td>
                                </tr>
                            </table>
                            <p style="margin: 20px 0 12px; font-size: 15px; color: #3d2d20;">
                                Lacak progres pesanan dan detail lengkap melalui halaman tracking berikut:
                            </p>
                            <p style="margin: 0 0 18px;">
                                <a href="{{ $trackingUrl }}" style="display: inline-block; padding: 12px 18px; background-color: #7a4b24; color: #ffffff; text-decoration: none; border-radius: 6px;">Buka Halaman Pesanan</a>
                            </p>
                            <p style="margin: 0; font-size: 13px; color: #6a5948;">Butuh bantuan? Balas email ini, kami siap membantu.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 24px; background-color: #f3eee7; color: #8a7766; font-size: 12px; text-align: center;">
                            Jika nomor resi belum tersedia, kami akan mengirimkan pembaruan setelah paket diproses ekspedisi.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
