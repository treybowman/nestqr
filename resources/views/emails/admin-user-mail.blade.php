<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1f2937; }
        .wrapper { width: 100%; max-width: 600px; margin: 0 auto; padding: 40px 20px; }
        .card { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #8e63f5 0%, #7c3aed 100%); padding: 32px; text-align: center; }
        .logo { font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.02em; }
        .body-content { padding: 32px; }
        .body-content p { font-size: 15px; line-height: 1.7; color: #4b5563; margin: 0 0 16px 0; white-space: pre-wrap; }
        .footer { padding: 24px 32px; text-align: center; border-top: 1px solid #f3f4f6; }
        .footer p { font-size: 13px; color: #9ca3af; margin: 0; }
        .footer a { color: #8e63f5; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div class="logo">NestQR</div>
            </div>
            <div class="body-content">
                <p>Hi {{ $user->name }},</p>
                <p>{{ $body }}</p>
            </div>
            <div class="footer">
                <p>Sent by {{ $fromName }} &mdash; <a href="{{ url('/') }}">NestQR</a></p>
                <p style="margin-top: 8px;">&copy; {{ date('Y') }} NestQR. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
