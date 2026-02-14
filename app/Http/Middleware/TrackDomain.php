<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class TrackDomain
{
    /**
     * Handle an incoming request.
     *
     * Parse the current domain from the request, share it with views, and store in session.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // Strip www. prefix if present
        $domain = preg_replace('/^www\./', '', $host);

        // Look up the active domain record
        $activeDomain = \App\Models\ActiveDomain::where('domain', $domain)
            ->orWhere('domain', str_replace('.com', '', $domain))
            ->active()
            ->first();

        $domainData = [
            'domain' => $domain,
            'market_name' => $activeDomain?->market_name ?? 'National',
            'is_known_domain' => $activeDomain !== null,
        ];

        // Share with all views
        View::share('currentDomain', $domainData);

        // Store in session for later use
        if ($request->hasSession()) {
            $request->session()->put('current_domain', $domainData);
        }

        return $next($request);
    }
}
