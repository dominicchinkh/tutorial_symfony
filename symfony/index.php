<?php

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\LiveComponent\LiveComponentBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;
use Twig\Environment;


require_once dirname(__DIR__).'/symfony/vendor/autoload_runtime.php';

/*
 *  Standalone MicroKernel front controller (not public/index.php / App\Kernel).
 *
 *  Both kernels share symfony/var/cache/, so clear the cache before switching
 *  or the compiled matcher from App\Kernel will shadow these routes:
 *
 *    # from the tutorial_symfony repo root
 *    rm -rf symfony/var/cache/dev
 *    symfony server:start --document-root=symfony --passthru=index.php --port=8888
 *
 *  Then open http://localhost:8888/random/10
 * 
 *  Refer to https://symfony.com/doc/current/configuration/micro_kernel_trait.html for
 *  more details
 * 
 */
class Kernel extends BaseKernel implements EventSubscriberInterface, CompilerPassInterface
{
    use MicroKernelTrait;

    //-----------------------------------------------------------------------------------
    // Restrict which configuration environment names are valid for your application. By 
    // default, it returns an empty array, meaning any environment name is allowed
    
    private function getAllowedEnvs(): array
    {
        return ['dev', 'prod', 'test'];
    }

    //----------------------------------------------------------------------------------------
    // This method builds and configures the container. In practice, you will use extension()
    // to configure different bundles (this is the equivalent of what you see in a normal 
    // config/packages/* file)
    
    // You can also register services directly in PHP or load external configuration files

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();

        // Load package configs
        $container->import($configDir.'/{packages}/*.{php,xml,yaml,yml}');
        $container->import($configDir.'/{packages}/'.$this->environment.'/*.{php,xml,yaml,yml}');

        // Load main services config if present
        if (is_file($configDir.'/services.yaml')) {
            $container->import($configDir.'/services.yaml');
        }

        // Configure Twig default template directory
        $container->extension('twig', [
            'default_path' => '%kernel.project_dir%/templates/microkernel',
        ]);

        /* 
         *   // Register all classes in /src/ as service
         *   $container->services()
         *       ->load('App\\', __DIR__.'/*')
         *       ->autowire()
         *       ->autoconfigure()
         *   ;
         *
         *   // Configure WebProfilerBundle only if the bundle is enabled
         *   if (isset($this->bundles['WebProfilerBundle'])) {
         *       $container->extension('web_profiler', [
         *           'toolbar' => true,
         *           'intercept_redirects' => false,
         *       ]);
         *   }
         */
    }

    //-------------------------------------------------------------------------------------
    // In this method, you can use the RoutingConfigurator object to define routes in your 
    // application and associate them to the controllers defined in this very same file

    // However, it's more convenient to define the controller routes using PHP attributes. 
    // That's why this method is commonly used only to load external routing files (e.g. from 
    // bundles)

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        // Import #[Route] attributes directly from this file
        $routes->import(__FILE__, 'attribute');

        /*
         *   // Import the WebProfilerRoutes, only if the bundle is enabled
         *   if (isset($this->bundles['WebProfilerBundle'])) {
         *       $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.php', 'php')->prefix('/_wdt');
         *       $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.php', 'php')->prefix('/_profiler');
         *   }
         *
         *   // load the routes defined as PHP attributes
         *   $routes->import(__DIR__.'/Controller/', 'attribute');
         * 
         */
    }
        
    #[Route('/random/{limit}', name: 'random_number')]
    public function randomNumber(int $limit, Environment $twig): Response
    {
        $number = random_int(0, $limit);

        return new Response($twig->render('random.html.twig', [
            'number' => $number,
            'limit'  => $limit,
        ]));
    }

    //---------------------------------------------------------------------------------
    // By default, the micro kernel only registers the FrameworkBundle. If you need to 
    // register more bundles, override this method:

    public function registerBundles(): iterable
    {
        yield new DoctrineBundle();
        yield new FrameworkBundle();
        yield new LiveComponentBundle();
        yield new MonologBundle();
        yield new NelmioApiDocBundle();
        yield new SecurityBundle();
        yield new StimulusBundle();
        yield new TwigBundle();
        yield new TwigComponentBundle();

        if ('dev' === $this->getEnvironment()) {
            yield new DoctrineMigrationsBundle();
            yield new WebProfilerBundle();
        }
    }

    //-----------------------------------------------------------------------------------------
    // It is possible to implement the EventSubscriberInterface to handle events directly from 
    // the kernel:

    public function onKernelException(ExceptionEvent $event): void
    {
        $event->setResponse(new Response('It\'s dangerous to go alone. Take this ⚔'));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    //-------------------------------------------------------------------------------------------
    // Compiler passes give you an opportunity to manipulate other service definitions that have 
    // been registered with the service container

    // Refer to https://symfony.com/doc/current/service_container/compiler_passes.html for more 
    // information

    public function process(ContainerBuilder $container): void
    {
        // In this method you can manipulate the service container:
        // for example, changing some container service:
        $container->getDefinition('app.some_private_service')->setPublic(true);

        // Or processing tagged services:
        foreach ($container->findTaggedServiceIds('some_tag') as $id => $tags) {
            // ...
        }
    }

    //-------------------------------------------------------
    // How to Override Symfony's default Directory Structure

    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir().'/var/log';
    }
}

return static function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
