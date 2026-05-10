<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/testing', methods: ['GET'])]
    public function get(): Response
    {
        // How to check the user exists in the database for every route (except admin account)?

        // TODO: DTO and validation

        return $this->render(
            'testing/testing.twig.html', []
        );
    }

    #[Route('/testing', methods: ['PUT'])]
    public function edit(): Response
    {
        // TODO: DTO and validation

       /*
        * TODO: What is the best way to share business logic between controller functions?
        * 
        */

        return new Response(
            '<html><body>PUT: Testing</body></html>'
        );
    }

    #[Route('/testing', methods: ['POT'])]
    public function add(): Response
    {
        // TODO: DTO and validation

       /*
        * TODO: What is the best way to share business logic between controller functions?
        * 
        */

        return new Response(
            '<html><body>POST: Testing</body></html>'
        );
    }

    #[Route('/testing', methods: ['DELETE'])]
    public function delete(): Response
    {
        // TODO: DTO and validation

        return new Response(
            '<html><body>DELETE: Testing</body></html>'
        );
    }
}
