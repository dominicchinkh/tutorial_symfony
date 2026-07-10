<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/http', name: 'http-')]
class HttpMethodController extends AbstractController
{
    #[Route('/method', name: 'get', methods: ['GET'])]
    public function get(): Response
    {
        return $this->render(
            'api/http_method.twig.html', []
        );
    }

    #[Route('/method', name: 'put', methods: ['PUT'])]
    public function edit(): Response
    {
        return new Response(
            '<html><body>PUT</body></html>'
        );
    }

    #[Route('/method', name: 'post', methods: ['POST'])]
    public function add(): Response
    {
        return new Response(
            '<html><body>POST</body></html>'
        );
    }

    #[Route('/method', name: 'delete', methods: ['DELETE'])]
    public function delete(): Response
    {
        return new Response(
            '<html><body>DELETE</body></html>'
        );
    }
}
