<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use App\Controller\RouteController;

/**
 * How it works:
 *   1. Delete or rename config/routes.yaml 
 *   2. Go to http://localhost/route1
 *   3. Symfony will redirect to either http://localhost/route2?num=... or http://localhost/route3?num=..., 
 *      depending on the random number generated in the __invoke() method of RouteController
 *   4. Route4 has a 70% chance of not being found, based on the RouteChecker service
 *
 * Use these commands for debugging:
 *   1. php bin/console debug:router
 *   2. php bin/console debug:match /route1
 * 
 */

return Routes::config([
    'route1' => [
        'path' => '/route1',

        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        'controller' => RouteController::class,
        'methods' => ['GET', 'HEAD'],

        //-------------------------------------------------------
        // expressions can also include configuration parameters:
        // 'condition' => 'request.headers.get("User-Agent") matches "%app.allowed_browsers%"',

        //-------------------------------------------------
        // expressions can even use environment variables:
        // https://github.com/symfony/symfony/blob/8.1/src/Symfony/Component/Routing/RequestContext.php

        // 'condition' => 'context.getHost() == env("APP_MAIN_HOST")',

        //-----------------------------------------------------------------------------
        // expressions can retrieve route parameter values using the "params" variable
        // 'condition' => 'params["id"] < 1000',
        
    ],
    'route2' => [
        'path' => '/route2',
        'controller' => [RouteController::class, 'route2'],
    ],
    'route3' => [
        'path' => '/route3',
        'controller' => [RouteController::class, 'route3'],
    ],
    'route4' => [
        'path' => '/route4',
        'controller' => [RouteController::class, 'route4'],
        'condition' => "service('route_checker').check(request)",
    ]
]);
