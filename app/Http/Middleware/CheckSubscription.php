<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature  The feature to check: 'qr_slots', 'icons', 'custom_branding', 'advanced_analytics'
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Authentication required.');
        }

        $planConfig = $user->planConfig();

        return match ($feature) {
            'qr_slots' => $this->checkQrSlots($user, $planConfig, $request, $next),
            'icons' => $this->checkIcons($user, $planConfig, $request, $next),
            'custom_branding' => $this->checkCustomBranding($planConfig, $request, $next),
            'advanced_analytics' => $this->checkAdvancedAnalytics($planConfig, $request, $next),
            default => $next($request),
        };
    }

    protected function checkQrSlots($user, array $planConfig, Request $request, Closure $next): Response
    {
        $maxSlots = $planConfig['qr_slots'] ?? 1;
        $currentCount = $user->qrSlots()->count();

        if ($currentCount >= $maxSlots) {
            abort(403, "You have reached your QR code limit ({$maxSlots}). Please upgrade your plan to create more.");
        }

        return $next($request);
    }

    protected function checkIcons($user, array $planConfig, Request $request, Closure $next): Response
    {
        $iconId = $request->input('icon_id') ?? $request->route('icon');

        if ($iconId) {
            $icon = \App\Models\Icon::find($iconId);

            if ($icon && !$user->canAccessIcon($icon)) {
                abort(403, "This icon requires a Pro plan or higher. Please upgrade to access premium icons.");
            }
        }

        return $next($request);
    }

    protected function checkCustomBranding(array $planConfig, Request $request, Closure $next): Response
    {
        $hasCustomBranding = $planConfig['custom_branding'] ?? false;

        if (!$hasCustomBranding) {
            abort(403, 'Custom branding is not available on your current plan. Please upgrade to access this feature.');
        }

        return $next($request);
    }

    protected function checkAdvancedAnalytics(array $planConfig, Request $request, Closure $next): Response
    {
        $analyticsLevel = $planConfig['analytics'] ?? 'basic';

        if ($analyticsLevel !== 'advanced') {
            abort(403, 'Advanced analytics is not available on your current plan. Please upgrade to access this feature.');
        }

        return $next($request);
    }
}
