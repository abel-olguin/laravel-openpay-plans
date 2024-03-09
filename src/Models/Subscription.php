<?php

namespace AbelOlguin\OpenPayPlans\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    protected $guarded = [];

    public function userPlan()
    {
        return $this->hasOne(UserPlan::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, UserPlan::class);
    }

    public function plan()
    {
        return $this->hasOneThrough(Plan::class, UserPlan::class);

    }

}
