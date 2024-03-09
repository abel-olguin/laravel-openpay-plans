<?php

namespace AbelOlguin\OpenPayPlans\Middlewares;

use AbelOlguin\OpenPayPlans\Middlewares\Interfaces\MiddlewareInterface;
use AbelOlguin\OpenPayPlans\Exceptions\UnauthorizedException;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAnyPlan implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next, ...$plans): Response
    {
        $user = auth()->user();

        if (! $user) {
            throw UnauthorizedException::notLoggedIn();
        }
        if (! method_exists($user, 'hasAnyPlan') || ! method_exists($user, 'plans')) {
            throw UnauthorizedException::missingTraitHasPlans($user);
        }

        if (! $user->hasAnyPlan($plans)) {
            throw UnauthorizedException::forPlans($plans);
        }
        return $next($request);
    }


}
