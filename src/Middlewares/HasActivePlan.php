<?php

namespace AbelOlguin\OpenPayPlans\Middlewares;

use AbelOlguin\OpenPayPlans\Exceptions\UnauthorizedException;
use AbelOlguin\OpenPayPlans\Middlewares\Interfaces\MiddlewareInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasActivePlan implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next, $params): Response
    {
        $user = auth()->user();

        if (! $user) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! method_exists($user, 'hasActivePlan') || ! method_exists($user, 'plans')) {
            throw UnauthorizedException::missingTraitHasPlans();
        }

        if (! $user->hasActivePlan()) {
            throw UnauthorizedException::forActivePlan();
        }

        return $next($request);
    }
}
