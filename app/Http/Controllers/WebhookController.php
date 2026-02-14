<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle a cancelled/deleted Stripe subscription.
     *
     * When Stripe reports a subscription deletion, downgrade
     * the user back to the free tier after Cashier processes it.
     */
    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        parent::handleCustomerSubscriptionDeleted($payload);

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->update(['plan_tier' => 'free']);
        }
    }

    /**
     * Look up a user by their Stripe customer ID.
     */
    protected function getUserByStripeId(string $stripeId): ?User
    {
        return User::where('stripe_id', $stripeId)->first();
    }
}
