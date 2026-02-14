<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code - {{ $shortUrl ?? 'NestQR' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: letter;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: #1f2937;
        }

        .page {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
        }

        .content {
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-container {
            background: #ffffff;
            border: 3px solid #e5e7eb;
            border-radius: 24px;
            padding: 40px;
            display: inline-block;
            margin-bottom: 32px;
        }

        .qr-image {
            width: 320px;
            height: 320px;
        }

        .short-url {
            font-size: 22px;
            font-weight: 700;
            color: #8e63f5;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .instructions {
            font-size: 16px;
            color: #6b7280;
            max-width: 380px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .address {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            padding-bottom: 40px;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .brand-icon {
            width: 28px;
            height: 28px;
            background: #8e63f5;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #8e63f5;
        }

        .brand-tagline {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 24px auto;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="content">
            <div class="qr-container">
                <img src="{{ $qrImageDataUri ?? '' }}" alt="QR Code" class="qr-image">
            </div>

            <div class="short-url">{{ $shortUrl ?? '' }}</div>

            <p class="instructions">
                Scan this QR code to view the property listing
            </p>

            @if(isset($address) && $address)
                <p class="address">{{ $address }}</p>
            @endif

            <div class="divider"></div>
        </div>

        <div class="footer">
            <div class="brand">
                <span class="brand-name">NestQR</span>
            </div>
            <p class="brand-tagline">QR Codes for Real Estate Agents</p>
        </div>
    </div>
</body>
</html>
