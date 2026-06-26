<?php

namespace Symfony\Component\Routing\Loader\Configurator;

return Routes::config([
    'controllers' => [
        'resource' => '../../src/Controller/RouteController.php',
        'type' => 'attribute',

        // This is added to the beginning of all imported route URLs
        'prefix' => '/route_testing',

        //--------------------------------------------------------------------------------
        // A common requirement for internationalized applications is to prefix all routes 
        // with a locale. This can be done by defining a different prefix for each locale 
        // (and setting an empty prefix for your default locale if you prefer it)

        // 'prefix' => [
        //     'en' => '', // don't prefix URLs for English, the default locale
        //     'fr' => '/fr',
        // ],

        //----------------------------------------------------------------------------------
        // Another common requirement is to host the website on a different domain according 
        // to the locale. This can be done by defining a different host for each locale.

        // 'host' => [
        //     'en' => 'www.example.com',
        //     'fr' => 'www.example.fr',
        // ],

        // This is added to the beginning of all imported route names
        'name_prefix' => 'route_testing_',

        // these requirements are added to all imported routes
        'requirements' => ['_locale' => 'en|es|fr'],

        // An imported route with an empty URL will become "/route_testing/"
        // Uncomment this option to make that URL "/route_testing" instead
        // 'trailing_slash_on_root' => false,

        // You can optionally exclude some files/subdirectories when loading attributes
        // (the value must be a string or an array of PHP glob patterns)
        // 'exclude' => '../../src/Controller/{Debug*Controller.php}',
    ],
]);
