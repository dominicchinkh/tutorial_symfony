<?php
namespace App\Controller;

use App\Service\JsonEnvVarLoader;
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
}
