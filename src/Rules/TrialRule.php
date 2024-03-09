<?php

namespace AbelOlguin\OpenPayPlans\Rules;

use AbelOlguin\OpenPayPlans\Models\UserPlan;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrialRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = boolval($value);
        if($value && UserPlan::where('user_id', auth()->id())->exists()){
            $fail(__('You are not eligible to have a trial.'));
        }
    }
}
