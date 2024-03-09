<?php

namespace AbelOlguin\OpenPayPlans\Types;

enum PlanTypes:string
{
    case MONTHLY = 'month';
    case YEARLY = 'year';
    case DAILY = 'day';
}
