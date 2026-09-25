<?php

namespace App\Service;

use App\Enum\PullRequestState;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

// If you want to exclude a service from being registered in a specific environment, you 
// can use the #[WhenNot] attribute

// Test with
//   php bin/console debug:autowiring --all -e prod | grep ServiceService
//   php bin/console debug:autowiring --all -e test | grep ServiceService
//   php bin/console debug:autowiring --all -e dev | grep ServiceService

// You may use the Exclude attribute directly on your class to exclude it:
// use Symfony\Component\DependencyInjection\Attribute\Exclude;
// #[Exclude]

#[WhenNot(env: 'prod')]
#[WhenNot(env: 'test')]
class ServiceService
{
    /**
     * @param array{first: bool, second: string} $collection
     */
    public function __construct(
        private ContainerInterface $container,
        private string $string,
        private bool $boolean,
        private int $integer,
        private float $float,
        private int $errorReportingLevel,
        private int $pdoFetchMode,
        private string $kernelVersion,
        private PullRequestState $pullRequestState,
        private SomePrivateService $privateService,
        private ?SomePrivateService $optionalPrivateService,
        private string $binary,
        private array $collection,
    ) {
    }

    public function getConstructorArguments(): array
    {
        // checks if a parameter is defined (parameter names are case-sensitive)
        $this->container->hasParameter('app.admin_email');

        // gets value of a parameter
        $this->container->getParameter('app.admin_email');

        // adds a new parameter

        // ❌ You can only set a parameter before the container is compiled, not at runtime
        // $this->container->setParameter('app.admin_email', 'admin@example.com');

        $optionalPrivateServiceClass = null;
        if ($this->optionalPrivateService instanceof SomePrivateService) {
            $optionalPrivateServiceClass = $this->optionalPrivateService::class;
        }

        return [
            $this->string,
            $this->boolean,
            $this->integer,
            $this->float,
            $this->errorReportingLevel,
            $this->pdoFetchMode,
            $this->kernelVersion,
            $this->pullRequestState,
            $this->privateService::class,
            $optionalPrivateServiceClass,
            $this->binary,
            $this->collection,
        ];
    }
}
