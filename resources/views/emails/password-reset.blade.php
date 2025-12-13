<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BROWSTIME - Reset Password</title>
</head>
<body style="margin:0;padding:0;background:#f9f1e8;font-family:'Figtree',Arial,sans-serif;color:#3b241a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f9f1e8;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:20px;box-shadow:0 18px 40px rgba(0,0,0,0.05);overflow:hidden;border:1px solid #f0e5d9;">
                    <tr>
                        <td style="padding:28px 32px;text-align:center;background:#fffaf5;border-bottom:1px solid #f0e5d9;">
                            <div style="display:inline-flex;align-items:center;gap:10px;color:#7a4b24;font-weight:700;font-size:18px;letter-spacing:0.03em;">
                                <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 2.66667C13.3629 2.66667 10.785 3.44865 8.59236 4.91374C6.39971 6.37882 4.69074 8.4612 3.68157 10.8976C2.6724 13.3339 2.40836 16.0148 2.92283 18.6012C3.4373 21.1876 4.70717 23.5634 6.57188 25.4281C8.43658 27.2928 10.8123 28.5627 13.3988 29.0771C15.9852 29.5916 18.6661 29.3276 21.1024 28.3184C23.5388 27.3092 25.6211 25.6003 27.0862 23.4076C28.5513 21.2149 29.3333 18.6371 29.3333 16C28.4066 16.2853 27.4197 16.3127 26.4787 16.079C25.5377 15.8454 24.6782 15.3597 23.9926 14.6741C23.307 13.9885 22.8212 13.1289 22.5876 12.1879C22.354 11.2469 22.3813 10.26 22.6666 9.33333C21.74 9.61866 20.7531 9.64599 19.8121 9.41236C18.871 9.17874 18.0115 8.693 17.3259 8.0074C16.6403 7.3218 16.1546 6.46227 15.9209 5.52125C15.6873 4.58023 15.7146 3.59332 16 2.66667Z" stroke="#8B4513" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.3334 11.3333V11.3467" stroke="#8B4513" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21.3334 20.6667V20.68" stroke="#8B4513" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16 16V16.0133" stroke="#8B4513" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.6666 22.6667V22.68" stroke="#8B4513" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.33337 18.6667V18.68" stroke="#8B4513" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                BROWSTIME
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px 10px 32px;color:#3b241a;text-align:left;font-size:15px;line-height:1.6;">
                            <p style="margin:0 0 16px 0;font-weight:600;">Hello!</p>
                            <p style="margin:0 0 12px 0;">We received a password reset request for your BROWSTIME account ({{ $email }}).</p>
                            <p style="margin:0 0 24px 0;">Tap the button below to set a new password.</p>
                            <div style="text-align:center;margin:28px 0;">
                                <a href="{{ $resetUrl }}" style="display:inline-block;background:#7a4b24;color:#fff;padding:12px 24px;border-radius:999px;text-decoration:none;font-weight:700;font-size:14px;box-shadow:0 10px 20px rgba(122,75,36,0.2);">Reset Password</a>
                            </div>
                            <p style="margin:0 0 12px 0;color:#7a5b44;">This link expires in 60 minutes.</p>
                            <p style="margin:0 0 20px 0;color:#7a5b44;">If you didn’t request this, you can safely ignore this email.</p>
                            <p style="margin:0 0 4px 0;">Regards,</p>
                            <p style="margin:0 0 12px 0;font-weight:600;">BROWSTIME</p>
                            <hr style="border:none;border-top:1px solid #f0e5d9;margin:20px 0;">
                            <p style="margin:0 0 6px 0;color:#9b7a64;font-size:13px;">If the button doesn’t work, copy and paste this URL into your browser:</p>
                            <a href="{{ $resetUrl }}" style="word-break:break-all;color:#7a4b24;font-size:13px;">{{ $resetUrl }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 32px 24px 32px;text-align:center;background:#fffaf5;color:#9b7a64;font-size:12px;border-top:1px solid #f0e5d9;">
                            © {{ now()->year }} BROWSTIME. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
