<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Store!</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 40px 30px; text-align: center;">
                            <h1 style="font-size: 28px; color: #2c3e50; margin: 0;">Welcome, {{ $customer->name }}!</h1>
                            <p style="font-size: 16px; line-height: 1.5; color: #666; margin: 20px 0;">
                                Thank you for joining our e-commerce family! We’re excited to have you as a customer and can’t wait for you to explore our amazing products.
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align: left; font-size: 16px; color: #666; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 5px 0;"><strong>Your Email:</strong> {{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px 0;"><strong>Your Phone:</strong> {{ $customer->phone ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                            <p style="font-size: 16px; line-height: 1.5; color: #666; margin: 20px 0;">
                                Got questions? Reach out to us anytime at <a href="mailto:support@yourstore.com" style="color: #3498db; text-decoration: none;">support@yourstore.com</a>.
                            </p>
                            <a href="{{ url('/') }}" style="display: inline-block; padding: 12px 25px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px; margin-top: 20px;">
                                Start Shopping Now
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 30px; background-color: #ecf0f1; text-align: center; font-size: 14px; color: #999; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                            Best regards,<br>
                            The {{ env('APP_NAME') }} Team
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>