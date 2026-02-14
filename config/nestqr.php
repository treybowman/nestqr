<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Primary Domain
    |--------------------------------------------------------------------------
    |
    | The primary domain used for generating QR code short URLs and other
    | public-facing links throughout the NestQR application.
    |
    */

    'primary_domain' => env('NESTQR_PRIMARY_DOMAIN', 'nestqr.com'),

    /*
    |--------------------------------------------------------------------------
    | Logo Path
    |--------------------------------------------------------------------------
    |
    | The default logo path used when generating branded QR codes.
    |
    */

    'logo_path' => env('NESTQR_LOGO_PATH', 'logos/nestqr-logo.png'),

    /*
    |--------------------------------------------------------------------------
    | Short Code Length
    |--------------------------------------------------------------------------
    |
    | The number of characters used for short URL codes.
    |
    */

    'short_code_length' => 6,

    /*
    |--------------------------------------------------------------------------
    | QR Code Sizes
    |--------------------------------------------------------------------------
    |
    | The pixel dimensions for QR code generation. "web" is optimized for
    | digital screens while "print" is suitable for high-resolution print.
    |
    */

    'qr_sizes' => [
        'web' => 600,
        'print' => 3000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Brand Logo Ratio
    |--------------------------------------------------------------------------
    |
    | The ratio of the brand logo size relative to the QR code dimensions.
    |
    */

    'brand_logo_ratio' => 0.20,

    /*
    |--------------------------------------------------------------------------
    | Icon Ratio
    |--------------------------------------------------------------------------
    |
    | The ratio of the icon size relative to the QR code dimensions.
    |
    */

    'icon_ratio' => 0.13,

    /*
    |--------------------------------------------------------------------------
    | Icon Lock Hours
    |--------------------------------------------------------------------------
    |
    | The number of hours an icon remains locked after assignment.
    |
    */

    'icon_lock_hours' => 24,

    /*
    |--------------------------------------------------------------------------
    | Photo Limits
    |--------------------------------------------------------------------------
    |
    | Maximum number of photos per listing and the maximum file size
    | in kilobytes for each uploaded photo.
    |
    */

    'photo_max_count' => 10,

    'photo_max_size' => 5120, // KB

    /*
    |--------------------------------------------------------------------------
    | Thumbnail Width
    |--------------------------------------------------------------------------
    |
    | The width in pixels for generated thumbnail images.
    |
    */

    'thumbnail_width' => 400,

    /*
    |--------------------------------------------------------------------------
    | IP Anonymization
    |--------------------------------------------------------------------------
    |
    | The number of days after which IP addresses in scan analytics
    | should be anonymized for privacy compliance.
    |
    */

    'ip_anonymize_days' => 30,

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Configuration for each subscription tier including limits, features,
    | and Stripe price IDs for paid plans.
    |
    */

    'plans' => [
        'free' => [
            'name' => 'Free',
            'qr_slots' => 10,
            'icons' => 10,
            'analytics' => 'basic',
            'custom_branding' => false,
            'price' => 0,
        ],
        'pro' => [
            'name' => 'Pro',
            'qr_slots' => 25,
            'icons' => 30,
            'analytics' => 'advanced',
            'custom_branding' => true,
            'price' => 2500,
            'stripe_price_id' => env('STRIPE_PRO_PRICE_ID'),
        ],
        'unlimited' => [
            'name' => 'Unlimited',
            'qr_slots' => PHP_INT_MAX,
            'icons' => 30,
            'analytics' => 'advanced',
            'custom_branding' => true,
            'price' => 5000,
            'stripe_price_id' => env('STRIPE_UNLIMITED_PRICE_ID'),
        ],
        'company' => [
            'name' => 'Company',
            'qr_slots' => PHP_INT_MAX,
            'icons' => 30,
            'analytics' => 'advanced',
            'custom_branding' => true,
            'base_price' => 10000,
            'per_agent_price' => 1500,
            'stripe_price_id' => env('STRIPE_COMPANY_PRICE_ID'),
        ],
    ],

];
