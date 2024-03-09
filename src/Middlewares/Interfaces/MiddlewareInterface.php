<?php

namespace AbelOlguin\OpenPayPlans\Middlewares\Interfaces;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next, $params): Response;

}
