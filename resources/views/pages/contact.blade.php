<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>if(localStorage.getItem('darkMode')==='true'||(!localStorage.getItem('darkMode')&&window.matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark');</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact - NestQR</title>
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
        <h1 class="text-3xl font-extrabold mb-6">Contact Us</h1>
        <div class="prose dark:prose-invert max-w-none">
            <p>Have questions or need help? We'd love to hear from you.</p>
            <div class="not-prose grid md:grid-cols-2 gap-8 mt-8">
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/40 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Email Support</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">For general questions and support.</p>
                    <a href="mailto:support@nestqr.com" class="text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">support@nestqr.com</a>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/40 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Sales</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">For enterprise and brokerage plans.</p>
                    <a href="mailto:sales@nestqr.com" class="text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">sales@nestqr.com</a>
                </div>
            </div>
        </div>
    </main>
    <footer class="border-t border-gray-200 dark:border-gray-800 mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ date('Y') }} NestQR. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
