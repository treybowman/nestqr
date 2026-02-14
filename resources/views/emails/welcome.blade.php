<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to NestQR</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1f2937;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #8e63f5 0%, #7c3aed 100%);
            padding: 40px 32px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 16px 0 0 0;
            line-height: 1.3;
        }

        .body-content {
            padding: 32px;
        }

        .body-content p {
            font-size: 15px;
            line-height: 1.7;
            color: #4b5563;
            margin: 0 0 16px 0;
        }

        .body-content .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #8e63f5 0%, #7c3aed 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 14px 32px;
            border-radius: 10px;
            margin: 8px 0 24px 0;
        }

        .tips {
            background: #f9fafb;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0 0 0;
        }

        .tips h3 {
            font-size: 15px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 16px 0;
        }

        .tip-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .tip-number {
            display: inline-block;
            width: 24px;
            height: 24px;
            min-width: 24px;
            background: #8e63f5;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            margin-right: 12px;
        }

        .tip-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.5;
        }

        .footer {
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
        }

        .footer p {
            font-size: 13px;
            color: #9ca3af;
            margin: 0;
            line-height: 1.6;
        }

        .footer a {
            color: #8e63f5;
            text-decoration: none;
        }

        @media only screen and (max-width: 600px) {
            .wrapper {
                padding: 20px 12px;
            }
            .header {
                padding: 32px 24px;
            }
            .body-content {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <div class="logo">NestQR</div>
                <h1>Welcome to NestQR!</h1>
            </div>

            <!-- Body -->
            <div class="body-content">
                <p class="greeting">Hi {{ $user->name ?? 'there' }},</p>

                <p>
                    Thanks for joining NestQR! You now have everything you need to create stunning QR codes
                    for your property listings.
                </p>

                <p>
                    Your QR codes link directly to beautiful, mobile-friendly listing pages that your clients
                    will love. Plus, you can track every scan with detailed analytics.
                </p>

                <div style="text-align: center; margin: 24px 0;">
                    <a href="{{ url('/dashboard') }}" class="cta-button">Go to Your Dashboard</a>
                </div>

                <!-- Tips -->
                <div class="tips">
                    <h3>Get started in 3 easy steps:</h3>

                    <div class="tip-item">
                        <span class="tip-number">1</span>
                        <span class="tip-text"><strong>Create a QR code slot</strong> -- this is your reusable physical QR code that you can print and place on signs or flyers.</span>
                    </div>

                    <div class="tip-item">
                        <span class="tip-number">2</span>
                        <span class="tip-text"><strong>Build a listing page</strong> -- add photos, property details, your contact info, and any custom fields you want.</span>
                    </div>

                    <div class="tip-item">
                        <span class="tip-number">3</span>
                        <span class="tip-text"><strong>Link and go live</strong> -- assign your listing to a QR code, download the PDF, and start sharing with buyers.</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>
                    Need help? Just reply to this email or visit our
                    <a href="{{ url('/') }}">website</a>.
                </p>
                <p style="margin-top: 12px;">
                    &copy; {{ date('Y') }} NestQR. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
