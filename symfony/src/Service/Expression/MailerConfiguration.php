<?php

namespace App\Service\Expression;

/**
 * Source service for an expression argument.
 *
 * https://symfony.com/doc/current/service_container.html#injecting-values-based-on-expressions
 *
 * getMailerMethod() stands in for configuration logic that returns a mailer
 * transport name such as "sendmail".
 */
class MailerConfiguration
{
    public function getMailerMethod(): string
    {
        return 'sendmail';
    }
}
