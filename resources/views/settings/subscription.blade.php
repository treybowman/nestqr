@section('title', 'Subscription')

<x-layouts.app>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Subscription</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your plan and billing.</p>
        </div>

        <!-- Current Plan -->
        <div class="card p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Current Plan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        You are on the <span class="font-semibold text-primary-600 dark:text-primary-400 capitalize">{{ auth()->user()->plan_tier }}</span> plan.
                    </p>
                </div>
                @if(auth()->user()->subscribed('default'))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400">
                        Free
                    </span>
                @endif
            </div>

            @if(auth()->user()->subscription('default')?->onGracePeriod())
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg text-sm text-yellow-800 dark:text-yellow-300">
                    Your subscription has been cancelled and will end on {{ auth()->user()->subscription('default')->ends_at->format('M j, Y') }}.
                    <form method="POST" action="{{ route('settings.subscription.resume') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold underline hover:no-underline">Resume subscription</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Plan Options -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            @foreach($plans as $key => $plan)
                <div class="card p-6 relative {{ auth()->user()->plan_tier === $key ? 'ring-2 ring-primary-500' : '' }}">
                    @if(auth()->user()->plan_tier === $key)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary-500 text-white text-xs font-bold px-3 py-1 rounded-full">Current</span>
                    @endif

                    <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 capitalize">{{ $key }}</h4>
                    <div class="mt-2">
                        <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">${{ $plan['price'] ?? 0 }}</span>
                        @if(($plan['price'] ?? 0) > 0)
                            <span class="text-sm text-gray-500 dark:text-gray-400">/month</span>
                        @endif
                    </div>

                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $plan['max_qr_slots'] === -1 ? 'Unlimited' : $plan['max_qr_slots'] }} QR Codes
                        </li>
                        <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $plan['max_icons'] === -1 ? 'All 30' : $plan['max_icons'] }} Icons
                        </li>
                        @if($plan['custom_branding'] ?? false)
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Custom Branding
                            </li>
                        @endif
                        @if($plan['advanced_analytics'] ?? false)
                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Advanced Analytics
                            </li>
                        @endif
                    </ul>

                    @if(auth()->user()->plan_tier !== $key && $key !== 'free')
                        <form method="POST" action="{{ route('settings.subscription.subscribe') }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $key }}">
                            <input type="hidden" name="payment_method" id="payment_method_{{ $key }}">
                            <button type="submit" class="btn-primary w-full text-center text-sm">
                                {{ auth()->user()->plan_tier === 'free' ? 'Subscribe' : 'Switch Plan' }}
                            </button>
                        </form>
                    @elseif($key === 'free' && auth()->user()->plan_tier !== 'free')
                        <form method="POST" action="{{ route('settings.subscription.cancel') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="btn-secondary w-full text-center text-sm" onclick="return confirm('Are you sure you want to cancel?')">
                                Downgrade to Free
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Stripe Billing Portal -->
        @if(auth()->user()->subscribed('default'))
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Billing</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage payment methods, download invoices, and more.</p>
                    </div>
                    <a href="{{ route('settings.subscription.portal') }}" class="btn-secondary text-sm">
                        Billing Portal
                        <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
