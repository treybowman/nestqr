<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>if(localStorage.getItem('darkMode')==='true'||(!localStorage.getItem('darkMode')&&window.matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service - NestQR</title>
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
        <h1 class="text-3xl font-extrabold mb-2">Terms of Service</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Last updated: {{ date('F j, Y') }}</p>
        <div class="prose dark:prose-invert max-w-none">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing or using NestQR, you agree to be bound by these Terms of Service. If you do not agree, you may not use the service.</p>
            <h2>2. Description of Service</h2>
            <p>NestQR provides QR code generation, property listing page creation, and scan analytics for real estate professionals. The service is offered in free and paid subscription tiers.</p>
            <h2>3. Account Responsibilities</h2>
            <p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You must provide accurate information when creating your account.</p>
            <h2>4. Acceptable Use</h2>
            <p>You agree to use NestQR only for lawful purposes related to real estate marketing. You may not use the service to distribute misleading, fraudulent, or illegal content.</p>
            <h2>5. Subscription and Billing</h2>
            <p>Paid plans are billed monthly. You may cancel at any time and will retain access until the end of your billing period. Refunds are not provided for partial months.</p>
            <h2>6. Intellectual Property</h2>
            <p>You retain ownership of all content you upload to NestQR. By uploading content, you grant NestQR a license to display it as part of the service.</p>
            <h2>7. Limitation of Liability</h2>
            <p>NestQR is provided "as is" without warranties of any kind. We are not liable for any indirect, incidental, or consequential damages arising from your use of the service.</p>
            <h2>8. Contact</h2>
            <p>For questions about these terms, contact us at <a href="mailto:support@nestqr.com">support@nestqr.com</a>.</p>
        </div>
    </main>
    <footer class="border-t border-gray-200 dark:border-gray-800 mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} NestQR. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
