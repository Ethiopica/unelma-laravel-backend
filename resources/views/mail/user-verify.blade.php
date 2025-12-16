<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 40px auto;">
        <tr>
            <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; border-radius: 16px 16px 0 0;">
                <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">
                    ✉️ Verify Your Email
                </h1>
            </td>
        </tr>
        <tr>
            <td style="background-color: #ffffff; padding: 40px 30px; border-radius: 0 0 16px 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                    Hello! 👋
                </p>
                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                    Please verify your email address to secure your account and unlock all features. 
                    Simply click the button below to complete the verification.
                </p>
                
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="text-align: center; padding: 20px 0;">
                            <a href="{{ $link }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                Verify Email Address
                            </a>
                        </td>
                    </tr>
                </table>
                
                <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 30px 0 0; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    If the button doesn't work, copy and paste this link into your browser:
                </p>
                <p style="color: #667eea; font-size: 12px; word-break: break-all; margin: 10px 0 0;">
                    {{ $link }}
                </p>
                
                <p style="color: #9ca3af; font-size: 13px; margin: 30px 0 0;">
                    If you didn't create an account, you can safely ignore this email.
                </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding: 20px;">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                    © {{ date('Y') }} Admin Panel. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
