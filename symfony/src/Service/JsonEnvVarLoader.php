<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\EnvVarLoaderInterface;

final class JsonEnvVarLoader implements EnvVarLoaderInterface
{
    private const ENV_VARS_FILE = 'env.json';

    public function __construct(

        // Use #[Autowire] attribute to access the configuration parameters `kernel.project_dir`

        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir
    ) {}

    // The application will look for a env.json file in the `project_dir` directory to populate 
    // environment variables (in addition to the already existing .env files)

    // Inspect with
    //   php bin/console debug:container --env-vars

    // Note that `debug:container --env-vars` only displays environment variables that are actively 
    // referenced in your configuration files using %env(...)%.

    public function loadEnvVars(): array
    {
        $fileName = $this->projectDir . \DIRECTORY_SEPARATOR . self::ENV_VARS_FILE;

        if (!is_file($fileName)) {
            return [];
        }

        $content = json_decode((string) file_get_contents($fileName), true);
        
        return $content['vars'] ?? [];
    }
}
