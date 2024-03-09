<?php

namespace AbelOlguin\OpenPayPlans\Exceptions;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{

    private $requiredPlans = [];

    public static function missingTraitHasPlans(): self
    {
        return new static(403, __("User must use AbelOlguin\Models\Traits\HasPlans trait."), null, []);
    }

    public static function notLoggedIn(): self
    {
        return new static(403, __('User is not logged in.'), null, []);
    }


    public static function forPlans(array $plans): self
    {
        $message = __('User does not have the right plan.');

        $exception = new static(403, $message, null, []);
        $exception->requiredPlans = $plans;

        return $exception;
    }

    public static function forActivePlan(): self
    {
        $message = __('User does not have any active plan.');
        $exception = new static(403, $message, null, []);
        return $exception;
    }

    public function getRequiredPlanes(): array
    {
        return $this->requiredPlans;
    }
}
