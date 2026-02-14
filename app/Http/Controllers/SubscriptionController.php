<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription management page with available plans.
     */
    public function index(Request $request)
    {
        return view('settings.subscription', [
            'plans' => config('nestqr.plans'),
            'currentPlan' => $request->user()->plan_tier,
            'intent' => $request->user()->createSetupIntent(),
        ]);
    }

    /**
     * Subscribe the user to a new plan via Stripe/Cashier.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:pro,unlimited,company',
            'payment_method' => 'required|string',
        ]);

        $plan = $request->plan;
        $priceId = config("nestqr.plans.{$plan}.stripe_price_id");

        if (! $priceId) {
            return back()->with('error', 'Invalid plan selected.');
        }

        $user = $request->user();

        try {
            $user->newSubscription('default', $priceId)
                ->create($request->payment_method);

            $user->update(['plan_tier' => $plan]);

            return redirect()->route('settings.subscription')
                ->with('success', "Successfully subscribed to the {$plan} plan!");
        } catch (\Exception $e) {
            return back()->with('error', 'Subscription failed: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the user's current subscription.
     * Access is retained until the end of the billing period.
     */
    public function cancel(Request $request)
    {
        $request->user()->subscription('default')?->cancel();

        return redirect()->route('settings.subscription')
            ->with('success', 'Your subscription has been cancelled. You will retain access until the end of your billing period.');
    }

    /**
     * Resume a previously cancelled subscription.
     */
    public function resume(Request $request)
    {
        $request->user()->subscription('default')?->resume();

        return redirect()->route('settings.subscription')
            ->with('success', 'Your subscription has been resumed!');
    }

    /**
     * Redirect the user to the Stripe billing portal.
     */
    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('settings.subscription'));
    }
}
