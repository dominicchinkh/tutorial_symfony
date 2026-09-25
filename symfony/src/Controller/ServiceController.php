<?php
namespace App\Controller;

use App\Service\ServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/service', name: 'service-')]
class ServiceController extends AbstractController
{
    public function __construct(
        private ServiceService $service,
    ) {
    }

    #[Route('/dependency-injection', name: 'dependency-injection', methods: ['GET'])]
    public function checkCsrfToken(): JsonResponse
    {
        return $this->json(
            $this->service->getConstructorArguments(),
            Response::HTTP_OK,
            [],
            ['json_encode_options' => JSON_PRETTY_PRINT]
        );
    }
}
