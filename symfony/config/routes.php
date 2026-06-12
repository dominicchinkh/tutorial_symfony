<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use App\Controller\RouteController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Requirement\Requirement;

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

function generateUuidV4(int $seed): string{
    // Initialize the engine with an integer seed
    $engine = new \Random\Engine\Xoshiro256StarStar($seed);
    
    // Feed the engine into the Randomizer
    $randomizer = new \Random\Randomizer($engine);
    
    // Generate the pseudo-random bytes
    $data = $randomizer->getBytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Set variant to 10
    
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

return function (RoutingConfigurator $routes): void {

    // 1. Import all your attribute-based routes first
    $routes->import('../src/Controller/', 'attribute');

    // 2. Define custom routes in an array
    $customRoutes = [
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
        ],

        'route5' => [
            'path' => '/route5/{id}/{slug}/{uuid}',
            'controller' => [RouteController::class, 'route5'],
            'defaults' => [
                'id'   => 1,
                'slug' => 'today-weather',
                'uuid' => generateUuidV4(rand(0, 9999))
            ],
            'requirements' => [
                'id'   => Requirement::DIGITS,
                'slug' => Requirement::ASCII_SLUG,
                'uuid' => Requirement::UUID_V4
            ],
        ]
    ];

    // 3. Loop through the array and register them into Symfony
    foreach ($customRoutes as $name => $config) {
        $route = $routes->add($name, $config['path'])
                        ->controller($config['controller']);

        if (isset($config['methods'])) {
            $route->methods($config['methods']);
        }
        if (isset($config['condition'])) {
            $route->condition($config['condition']);
        }
        if (isset($config['defaults'])) {
            $route->defaults($config['defaults']);
        }
        if (isset($config['requirements'])) {
            $route->requirements($config['requirements']);
        }
    }
};
