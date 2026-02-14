<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>if(localStorage.getItem('darkMode')==='true'||(!localStorage.getItem('darkMode')&&window.matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About - NestQR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100">
    <nav class="border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <span class="text-xl font-bold text-primary-500">NestQR</span>
            </a>
            <a href="/" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-500">Back to Home</a>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-extrabold mb-6">About NestQR</h1>
        <div class="prose dark:prose-invert max-w-none">
            <p>NestQR is a modern QR code platform built specifically for real estate professionals. We help agents and brokerages create beautiful, trackable QR codes that link directly to stunning property listing pages.</p>
            <h2>Our Mission</h2>
            <p>We believe every property deserves a modern, mobile-friendly presentation. NestQR bridges the gap between physical signage and digital experiences by giving agents a simple way to connect potential buyers with detailed property information.</p>
            <h2>What We Offer</h2>
            <ul>
                <li><strong>Dynamic QR Codes</strong> - Reuse the same physical QR code across different listings by simply reassigning the destination.</li>
                <li><strong>Beautiful Listing Pages</strong> - Mobile-friendly property pages with photos, details, and agent contact information.</li>
                <li><strong>Scan Analytics</strong> - Track when and how often your QR codes are scanned to make data-driven decisions.</li>
                <li><strong>Custom Branding</strong> - Add your logo and brand colors to make every listing page uniquely yours.</li>
            </ul>
        </div>
    </main>
    <footer class="border-t border-gray-200 dark:border-gray-800 mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} NestQR. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
