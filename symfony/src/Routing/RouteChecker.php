<?php

namespace App\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Attribute\AsRoutingConditionService;
use Symfony\Component\HttpFoundation\Request;

#[AsRoutingConditionService(alias: 'route_checker')]
class RouteChecker
{
    public function check(Request $request): bool
    {
        $num = rand(0, 10);

        // 70% of change of "No route found for "GET /route4"
        if ($num < 7) {
            return false;
        }

        return true;
    }
}
