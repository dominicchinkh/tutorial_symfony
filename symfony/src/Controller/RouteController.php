<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    public function route5(string $uuid, int $id, string $slug): Response
    {
        return new Response(
            '<html><body>Route 5: ' . $id . ', ' . $slug . ', ' . $uuid  . '</body></html>'
        );
    }

    #[Route('/route6', methods: ['GET'])]
    public function route6(): Response
    {
        return new Response(
            '<html><body>Route 6</body></html>'
        );
    }

    #[Route('/route7/{_locale}/data.{_format}', methods: ['GET'])]
    public function route7(string $_locale, string $_format, int $page = 1): Response
    {
        /*
         *  _format
         *      The matched value is used to set the "request format" of the Request object. This is used for such 
         *      things as setting the Content-Type of the response (e.g. a json format translates into a Content-Type 
         *      of application/json).
         *
         *  _locale
         *      Used to set the locale on the request.
         * 
         *  _query
         *      An array of query parameters to add to the generated URL.
         * 
         */

        return new Response(
            '<html><body>Route 7: ' . $_locale . ', ' . $_format . ', ' . $page . '</body></html>'
        );
    }

    #[Route('/route10', methods: ['GET'])]
    public function route10(): Response
    {
        return new Response(
            '<html><body>Route 10</body></html>'
        );
    }

    #[Route('/route10/{_locale}', name: "route10_with_locale", methods: ['GET'])]
    public function route10WithLocale(string $_locale): Response
    {
        return new Response(
            '<html><body>Route 10: ' . $_locale . '</body></html>'
        );
    }

    #[Route('/route11', methods: ['GET'])]
    public function route11(): Response
    {
        // Generate a URL with no route arguments
        $url1 = $this->generateUrl('route10_with_locale');

        // Generate a URL with route arguments
        $url2 = $this->generateUrl('route10_with_locale', [
            '_locale' => 'en',
        ]);

        // Generated URLs are "absolute paths" by default. Pass a third optional
        // argument to generate different URLs (e.g. an "absolute URL")
        $url3 = $this->generateUrl('route10_with_locale', [], UrlGeneratorInterface::ABSOLUTE_URL);

        // When a route is localized, Symfony uses by default the current request locale
        // pass a different '_locale' value if you want to set the locale explicitly
        $url4 = $this->generateUrl('route10_with_locale', ['_locale' => 'fr']);

        # If you pass to the generateUrl() method some parameters that are not part of the route 
        # definition, they are included in the generated URL as a query string:
        $url5 = $this->generateUrl('route10_with_locale', ['page' => 2, 'category' => 'Symfony']);

        return new Response(
            "<html>
                <body>
                    <p>Route 11</p>
                    <ul>
                        <li><a href='$url1'>A URL with no route arguments</a></li>
                        <li><a href='$url2'>A URL with route arguments</a></li>
                        <li><a href='$url3'>An absolute URL</a></li>
                        <li><a href='$url4'>A URL with a different locale</a></li>
                        <li><a href='$url5'>A URL with query parameters</a></li>
                    </ul>
                </body>
            </html>"
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
