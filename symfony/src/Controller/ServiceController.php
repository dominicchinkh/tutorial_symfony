<?php
namespace App\Controller;

use App\Service\ServiceService;
use App\Service\Tag\GreetingHandlerCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    // Tagged services: App\Service\Tag\GreetingHandlerInterface and its implementations.
    //   GET /service/tags
    //   GET /service/tags?name=Ada&index=formal
    
    #[Route('/tags', name: 'tags', methods: ['GET'])]
    public function showTags(Request $request, GreetingHandlerCollection $handlers): JsonResponse
    {
        $name = $request->query->getString('name', 'Symfony');
        if (1 !== preg_match('/\A[A-Za-z0-9 ._-]{1,40}\z/', $name)) {
            return $this->json(
                ['error' => 'Name must be 1-40 characters: letters, digits, spaces, dots, underscores, or hyphens.'],
                Response::HTTP_BAD_REQUEST,
            );
        }

        $index = $request->query->getString('index', 'casual');
        $selected = $handlers->greetWith($index, $name);
        if (null === $selected) {
            return $this->json(
                [
                    'error' => sprintf('No greeting handler is tagged with index "%s".', $index),
                    'indexes' => $handlers->getLocatorIndexes(),
                ],
                Response::HTTP_NOT_FOUND,
            );
        }

        return $this->json(
            [
                'iterator' => $handlers->greetAll($name),
                'indexed_iterator' => $handlers->greetByIndex($name),
                'locator' => [
                    'index' => $index,
                    'message' => $selected,
                    'indexes' => $handlers->getLocatorIndexes(),
                ],
            ],
            Response::HTTP_OK,
            [],
            ['json_encode_options' => JSON_PRETTY_PRINT],
        );
    }
}
