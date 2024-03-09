<?php
namespace AbelOlguin\OpenPayPlans\Models;
use AbelOlguin\OpenPayPlans\Types\PlanTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Plan extends Model
{
    protected $guarded = [];
    protected $casts = ['active' => 'boolean', 'plan_type' => PlanTypes::class];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
