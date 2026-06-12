<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class RouteController extends AbstractController
{
    public function route2(Request $request): Response
    {
        $num = $request->query->getInt('num', 0);

        return new Response(
            '<html><body>Route 2: ' . $num . '</body></html>'
        );
    }

    public function route3(Request $request): Response
    {
        $num = $request->query->getInt('num', 0);

        return new Response(
            '<html><body>Route 3: ' . $num . '</body></html>'
        );
    }

    public function route4(): Response
    {
        return new Response(
            '<html><body>Route 4</body></html>'
        );
    }

    public function __invoke(): Response
    {
        $num = rand(0, 10);
        if ($num < 6) {
            return $this->redirectToRoute('route2', [
                'num' => $num,
            ]);
        }

        return $this->redirectToRoute('route3', [
            'num' => $num,
        ]);
    }
}
