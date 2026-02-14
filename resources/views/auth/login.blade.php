<x-layouts.guest title="Sign In">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Welcome back</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sign in to your NestQR account</p>
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-500/30 transition-colors text-sm"
            >
            @error('email')
                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">Forgot password?</a>
            </div>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
                class="block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-500/30 transition-colors text-sm"
            >
            @error('password')
                <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me -->
        <div class="flex items-center">
            <input
                id="remember"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-800 dark:checked:bg-primary-500"
            >
            <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</label>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold shadow-sm shadow-primary-600/30 hover:shadow-primary-700/30 focus:outline-none focus:ring-2 focus:ring-primary-500/50 transition-all duration-200">
            Sign in
        </button>
    </form>

    <!-- Divider -->
    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold">Create one free</a>
        </p>
    </div>
</x-layouts.guest>
