<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class PaidPlanGate
{
    /**
     * This allows you to restrict some endpoints/routes to only be available to some paid plan types.
     * There are currently 2 plans: starter, classic, [and soon "premium"]
     *
     * You can use if in the middleware in any of the following ways:
     *
     * $this->middleware(['pay_gate']) - this will enforce that the user must be on a paid plan
     * $this->middleware(['pay_gate:classic']) - this will enforce that the user must be on the classic paid plan
     *
     * @param         $request
     * @param Closure $next
     * @param mixed   ...$plans
     *
     * @return mixed
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$plans)
    {
        if (!$request->user()) {
            throw new AuthenticationException();
        }
        $company = $request->user()->company();
        # get the company
        $pricingTier = $company['plan']['data'] ?? null;
        # get the pricing tier data
        if (empty($pricingTier)) {
            throw new AuthorizationException('We could not find a subscription for your account.');
        }
        $cost = (float) $pricingTier['price_monthly']['raw'];
        # cast the cost
        if (empty($plans) && $cost == 0) {
            # no specific plans specified, we just make sure it's not a free plan
            throw new AuthorizationException('You do not have access to this feature. Please upgrade to a paid plan.');
        } elseif (!empty($plans) && !in_array($pricingTier['name'], $plans)) {
            # we check based on the plan names
            throw new AuthorizationException(
                'You need to be on one of the following plans to access this feature: '.implode(', ', array_map('title_case', $plans))
            );
        }
        return $next($request);
    }
}
