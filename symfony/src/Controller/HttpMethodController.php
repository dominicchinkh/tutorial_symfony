<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HttpMethodController extends AbstractController
{
    #[Route('/http-method', methods: ['GET'])]
    public function get(): Response
    {
        return $this->render(
            'api/http_method.twig.html', []
        );
    }

    #[Route('/http-method', methods: ['PUT'])]
    public function edit(): Response
    {
        return new Response(
            '<html><body>PUT</body></html>'
        );
    }

    #[Route('/http-method', methods: ['POST'])]
    public function add(): Response
    {
        return new Response(
            '<html><body>POST</body></html>'
        );
    }

    #[Route('/http-method', methods: ['DELETE'])]
    public function delete(): Response
    {
        return new Response(
            '<html><body>DELETE</body></html>'
        );
    }
}
