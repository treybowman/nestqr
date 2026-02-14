<x-layouts.guest title="Verify Email">
    <div class="text-center mb-8">
        <div class="mx-auto w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Check your email</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-sm mx-auto">
            We've sent a verification link to your email address. Please click the link to verify your account before continuing.
        </p>
    </div>

    <!-- Success Status -->
    @if(session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg text-sm flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="space-y-4">
        <!-- Resend verification email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold shadow-sm shadow-primary-600/30 hover:shadow-primary-700/30 focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition-all duration-200">
                Resend verification email
            </button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition-all duration-200">
                Sign out
            </button>
        </form>
    </div>

    <!-- Help text -->
    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Didn't receive the email? Check your spam folder, or try resending.
        </p>
    </div>
</x-layouts.guest>
