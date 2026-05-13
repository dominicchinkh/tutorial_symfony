<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

// TODO: using route group and prefix

class DefaultController extends AbstractController
{
    /*
     * TODO: What is the practice?
     * 
     * 1. Separate GET, PUT/POST and DELETE route
     * 
     */

    /*
     * TODO: What is the best URL naming strategy?
     * 
     */

    /*
     * TODO: What should and should not be in the query string?
     * 
     */

    /*
     * TODO: What is the best logging strategy? 
     * 
     * 1. External and internal user
     * 2. Different and/or multiple destination: log file, flash messages, chat message, RabbitMQ message, database
     * 
     */

    // TODO: demostrate using Symfony Requirement enum for route parameters pattern matching

    // TODO: test #[MapEntity]

    // TODO: test using API platform core: https://github.com/api-platform/core

    /*
     * TODO: How should we handle AJAX calls?
     * 
     * 1. TODO: test using _format to match different format
     * 
     */

    /*
     * TODO: What is the best way to share business logic between controller functions?
     * 
     */

    // TODO: DTO and validation

    // How to check the user exists in the database for every route (except admin account)?

    /*----------------------------------------
     * Leveraging HTTP Verbs in Symfony Forms
     * 
     * https://github.com/dominicchinkh/tutorial_symfony/wiki/Leveraging-HTTP-Verbs-in-Symfony-Forms
     * 
     */

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

    #[Route('/http-method', methods: ['POT'])]
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

    /*-----------------------------------
     * Manual CSRF Protection in Symfony
     * 
     * https://github.com/dominicchinkh/tutorial_symfony/wiki/Manual-CSRF-Protection-in-Symfony
     * 
     */

    #[Route('/csrf-token', methods: ['GET'])]
    public function getCsrfToken(): Response
    {
        return $this->render(
            'security/csrf_token.html.twig', ['id' => 1]
        );
    }

    #[Route('/csrf-token', name: 'csrf-token', methods: ['POST'])]
    #[IsCsrfTokenValid(new Expression('"update-item-" ~ request.query.get("id")'))]
    public function checkCsrfToken(Request $request): Response
    {
        $id             = $request->query->get('id');
        $submittedToken = $request->getPayload()->get('_token');

        if (!$this->isCsrfTokenValid('update-item-' . $id, $submittedToken)) {
            return new Response(
                '<html><body>Invalid CSRF token</body></html>'
            );            
        }

        return new Response(
            '<html><body>Valid CSRF token</body></html>'
        );
    }
}

// TODO: say something about "http_method_override: true" in HTTP method wiki and also the code implementation
// TODO: say something about the GET-REDIRECT-POST pattern in the HTTP method wiki
