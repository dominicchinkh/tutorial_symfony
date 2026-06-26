<?php

namespace Symfony\Component\Routing\Loader\Configurator;

use App\Controller\RouteController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Routing\Route;

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

        //-------------------------------------------------------------------------------------------------------
        // Invoke the controller as a service, without specifying the method name. The __invoke() method will be 
        // called by default.

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

        //-----------------------------------------------------------------------------------------------------------
        // Route checker example: http://localhost/route4 will have a 70% chance of resulting in "No route found for 
        // "GET /route4"

        'route4' => [
            'path' => '/route4',
            'controller' => [RouteController::class, 'route4'],
            'condition' => "service('route_checker').check(request)",
        ],

        //--------------------------------------------------
        // Route with parameters, defaults, and requirements

        'route5' => [
            'path' => '/route5/{id}/{slug}/{uuid}',
            'controller' => [RouteController::class, 'route5'],
            'defaults' => [
                'id'   => 1,
                'slug' => 'today-weather',
                'uuid' => generateUuidV4(rand(0, 9999)),
                // 'env' => 'dev'
            ],
            'requirements' => [
                'id'   => Requirement::DIGITS,
                'slug' => Requirement::ASCII_SLUG,
                'uuid' => Requirement::UUID_V4,
                // 'env' => 'dev|prod'
            ],

            // Routes can configure a host option to require that the HTTP host of the incoming requests matches some 
            // specific value.

            // 'host' => '{env}.example.com',
        ],

        //---------------------------------------------------------------------------------------------------------------
        // `route6` and `route7` are defined as attributes in the RouteController. They will be imported by the `step 3`
        // of this function.

        //----------------------------------------------------------------------------------------------------------------
        // Special parameters: _locale and _format. They are used by Symfony to set the locale and format of the request, 
        // which can be useful for internationalization and content negotiation.

        // https://symfony.com/doc/current/routing.html#special-parameters

        'route7' => [
            'path' => '/route7/{_locale}/data.{_format}',
            'controller' => [RouteController::class, 'route7'],
            'locale' => 'en',
            'format' => 'html',
            'query' => ['page' => 1],
            'requirements' => [
                '_locale' => 'en|cn',
                '_format' => 'html|json'
            ]
        ],

        //---------------------------------------------------------------------------------------------------------------
        // Static route example: http://localhost/route8 will render the template templates/route/route8.html.twig

        'route8' => [
            'path' => '/route8',
            'controller' => RouteController::class,
            'defaults' => [
                // the path of the template to render
                'template'  => 'templates/route/route8.html.twig',

                // the response status code (default: 200)
                'statusCode' => 200,

                // special options defined by Symfony to set the page cache
                'maxAge'    => 86400,
                'sharedAge' => 86400,

                // whether or not caching should apply for client caches only
                'private' => true,

                // optionally you can define some arguments passed to the template
                'context' => [
                    'site_name' => 'ROUTER',
                    'theme' => 'dark',
                ],

                // optionally you can define HTTP headers to add to the response
                'headers' => [
                    'Content-Type' => 'text/html',
                ]
            ],
        ],

        //---------------------------------------------------------------------------------------------------------------
        // Redirection example: http://localhost/route9 will redirect to http://localhost/route1

        'route9' => [
            'path' => '/route9',
            'controller' => [RouteController::class, 'route9'],
            'defaults' => [
                'route' => 'route1',

                // optionally you can define some arguments passed to the route
                'page' => 'index',
                'version' => 'current',

                // redirections are temporary by default (code 302) but you can make them permanent (code 301)
                'permanent' => true,

                // add this to keep the original query string parameters when redirecting
                'keepQueryParams' => true,

                // add this to keep the HTTP method when redirecting. The redirect status changes
                // * for temporary redirects, it uses the 307 status code instead of 302
                // * for permanent redirects, it uses the 308 status code instead of 301
                'keepRequestMethod' => true,

                // add this to remove all original route attributes when redirecting
                'ignoreAttributes' => true,

                // or specify which attributes to ignore:
                // 'ignoreAttributes' => ['offset', 'limit'],
            ],
        ],

        'route10' => [
            'path' => [
                'en' => '/route10/en',
                'fr' => '/route10/fr',

                // optionally, you can define a path without a locale. It will be used
                // for any locale that does not match the locales above
                '/route10',
            ],
            'controller' => [RouteController::class, 'route10'],
        ],
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
