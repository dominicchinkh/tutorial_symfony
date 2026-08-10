<?php
namespace App\Controller;

use App\Service\JsonEnvVarLoader;
use Doctrine\ORM\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/configuration', name: 'configuration-')]
class ConfigurationController extends AbstractController
{
    #[Route('/json-env', name: 'json-env', methods: ['GET'])]
    public function getJsonEnv(JsonEnvVarLoader $loader): Response
    {
        $data = [
            // In controllers extending from the AbstractController, use the getParameter() 
            // helper to access the configuration parameters
            'project_dir' => $this->getParameter('kernel.project_dir'),
            'admin_email' => $this->getParameter('app.admin_email'),

            'env_vars'    => $loader->loadEnvVars(),
        ];
    
        return $this->json($data);
    }

    #[Route('/env-var-processor', name: 'env-var-processor', methods: ['GET'])]
    public function getEnvVarProcessor(): Response
    {
        $data = [
            "env(base64:FOO)" => [
                "configuration" => [
                    "env(JWT_PRIVATE_KEY_BASE64): ZG9taW5pYwo=",
                    "jwt_private_key: '%env(base64:JWT_PRIVATE_KEY_BASE64)%'"
                ],
                "data" => $this->getParameter('jwt_private_key')
            ],

            "env(json:FOO)" => [
                "configuration" => <<<JSON_ENV
                             env(ALLOWED_LANGUAGES): '["en","de","es"]'
                             JSON_ENV,
                "data" => $this->getParameter('app_allowed_languages_1')
            ],

            "env(resolve:FOO)" => [
                "configuration" => [
                    "sentry_host: '10.0.0.1'",
                    "env(SENTRY_DSN): 'http://%sentry_host%/project'",
                    "dsn: '%env(resolve:SENTRY_DSN)%'"
                ],
                "data" => $this->getParameter('dsn')
            ],

            "env(csv:FOO)" => [
                "configuration" => 'env(ALLOWED_LANGUAGES_2): "en,de,es"',
                "data" => $this->getParameter('app_allowed_languages_2')
            ],

            "env(shuffle:FOO)" => [
                "configuration" => [
                    'env(REDIS_NODES): "127.0.0.1:6380,127.0.0.1:6381"',
                    'redis_nodes_list: [null, "%env(shuffle:csv:REDIS_NODES)%"]'
                ],
                "data" => $this->getParameter('redis_nodes_list')
            ],

            "env(file:FOO)" => [
                "configuration" => [
                    "env(AUTH_FILE): '%kernel.project_dir%/config/.runtime-evaluated.php'",
                    "auth: '%env(file:AUTH_FILE)%'"
                ],
                "data" => $this->getParameter('auth_file')
            ],

            "env(require:FOO)" => [
                "configuration" => [
                    "env(PHP_FILE): '%kernel.project_dir%/config/.runtime-evaluated.php'",
                    "php_file: '%env(require:PHP_FILE)%'"
                ],
                "data" => $this->getParameter('php_file')
            ],

            "env(trim:FOO)" => [
                "configuration" => [
                    "env(TRIMMED_ENV_FILE): '%kernel.project_dir%/config/.env.yaml'",
                    "trimmed_env: '%env(trim:file:TRIMMED_ENV_FILE)%'"
                ],
                "data" => $this->getParameter('trimmed_env')
            ],

            "env(key:FOO:BAR)" => [
                "configuration" => [
                    "env(SECRETS_FILE): '%kernel.project_dir%/config/env_var_processor/secret.json'",
                    "database_password: '%env(key:database_password:json:file:SECRETS_FILE)%'"
                ],
                "data" => $this->getParameter('database_password')
            ],

            "env(default:fallback_param:BAR)" => [
                "configuration" => [
                    "raw_key: '%env(JWT_PRIVATE_KEY_BASE64)%'",
                    "private_key: '%env(default:raw_key:file:PRIVATE_KEY)%'"
                ],
                "data" => $this->getParameter('private_key')
            ],

            "env(url:FOO)" => [
                "configuration" => [
                    'env(POSTGRESQL_DATABASE_URL): "postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=12.19&charset=utf8"',
                    "database_parts: '%env(url:POSTGRESQL_DATABASE_URL)%'"
                ],
                "data" => $this->getParameter('database_parts')
            ],

            "env(query_string:FOO)" => [
                "configuration" => [
                    'env(POSTGRESQL_DATABASE_URL): "postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=12.19&charset=utf8"',
                    "database_queries: '%env(query_string:POSTGRESQL_DATABASE_URL)%'"
                ],
                "data" => $this->getParameter('database_queries')
            ],

            "env(enum:FOO)" => [
                "configuration" => [
                    "env(Review): 'review'",
                    "review_enum: '%env(enum:App\Enum\PullRequestState:Review)%'",
                ],
                "data" => $this->getParameter('review_enum')
            ],

            // Refer to App\DependencyInjection/LowercasingEnvVarProcessor
            "env(lowercase:FOO)" => [
                "configuration" => [
                    "lower_cased_jwt_private_key: '%env(lowercase:JWT_PRIVATE_KEY_BASE64)%'"
                ],
                "data" => $this->getParameter('lower_cased_jwt_private_key')
            ]
        ];
    
        return $this->json(
            $data,
            Response::HTTP_OK,
            [],
            ['json_encode_options' => JSON_PRETTY_PRINT]
        );
    }
}
