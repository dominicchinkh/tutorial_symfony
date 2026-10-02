<?php

namespace App\Service\Expression;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Receives values computed by the service-container expression language.
 *
 * https://symfony.com/doc/current/service_container.html#injecting-values-based-on-expressions
 *
 * This class is registered twice:
 * - App\Service\Expression\Mailer uses the #[Autowire(expression: ...)] attributes below
 * - app.expression_mailer in config/services.yaml passes the same values with the '@=' prefix
 *
 * Expressions can also be used in properties, configurator arguments, method calls, and factories.
 * Inside an expression you can call service(), parameter(), and env(), and you can read the
 * container through the container variable.
 */
class Mailer
{
    public function __construct(
        // Because of PHP string escaping, each namespace separator needs four backslashes.
        #[Autowire(expression: 'service("App\\\\Service\\\\Expression\\\\MailerConfiguration").getMailerMethod()')]
        private string $mailerMethod,

        #[Autowire(expression: 'parameter("app.admin_email")')]
        private string $adminEmail,

        #[Autowire(expression: 'env("APP_ENV")')]
        private string $kernelEnvironment,

        // app.mailer_method is not defined, so the expression uses the fallback.
        #[Autowire(expression: 'container.hasParameter("app.mailer_method") ? parameter("app.mailer_method") : "sendmail"')]
        private string $mailerMethodFallback,
    ) {
    }

    /**
     * @return array{
     *     service: string,
     *     parameter: string,
     *     env: string,
     *     container: string
     * }
     */
    public function describe(): array
    {
        return [
            'service' => $this->mailerMethod,
            'parameter' => $this->adminEmail,
            'env' => $this->kernelEnvironment,
            'container' => $this->mailerMethodFallback,
        ];
    }
}
