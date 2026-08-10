<?php

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

class LowercaseEnvVarProcessor implements EnvVarProcessorInterface
{
    public function getEnv(string $prefix, string $name, \Closure $getEnv): string
    {
        $env = $getEnv($name);

        return strtolower($env);
    }

    public static function getProvidedTypes(): array
    {
        return [
            'lowercase' => 'string',
        ];
    }
}
