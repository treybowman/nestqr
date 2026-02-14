<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<script>if(localStorage.getItem('darkMode')==='true'||(! localStorage.getItem('darkMode')&&window.matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark');</script>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'NestQR' }} - NestQR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen">
    <!-- Sidebar Navigation -->
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-600/75 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:z-auto">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-primary-500">NestQR</span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('qr-slots.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('qr-slots.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        QR Codes
                    </a>
                    <a href="{{ route('listings.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('listings.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Listings
                    </a>
                    <a href="{{ route('analytics.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('analytics.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Analytics
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Settings
                    </a>

                    @if(auth()->user()->is_admin)
                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Admin Panel
                        </a>
                    </div>
                    @endif
                </nav>

                <!-- User menu at bottom -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if(auth()->user()->photo_path)
                                <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-600 dark:text-primary-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate capitalize">{{ auth()->user()->plan_tier }} Plan</p>
                        </div>
                        <!-- Dark mode toggle -->
                        <button @click="$store.darkMode.toggle()" class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg x-show="!$store.darkMode.on" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            <svg x-show="$store.darkMode.on" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 h-16 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800 flex items-center px-4 lg:px-8">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100 ml-2 lg:ml-0">{{ $title ?? 'Dashboard' }}</h1>
                <div class="ml-auto flex items-center space-x-4">
                    @if(session('impersonating'))
                        <form method="POST" action="{{ route('admin.stop-impersonating') }}">
                            @csrf
                            <button type="submit" class="text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium hover:bg-yellow-200 transition-colors">Stop Impersonating</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8">
                <!-- Flash messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg text-sm flex items-center" x-data="{ show: true }" x-show="show" x-transition>
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
