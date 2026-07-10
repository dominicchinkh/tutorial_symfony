<?php

namespace App\Controller;

use App\Dto\ItemDto;
use App\Dto\UserDto;
use App\Resolver\ItemDtoResolver;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQuerystring;
use Symfony\Component\Routing\Attribute\Route;

final class ControllerController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(

        //-----------------------------------
        // Injecting Services and Parameters

        // Inject a logger service
        // LoggerInterface $logger,

        // Inject a specific logger service
        #[Autowire(service: 'monolog.logger.request')]
        LoggerInterface $logger,

        // Inject parameter values
        #[Autowire('%kernel.project_dir%')]
        string $projectDir,

        //--------------------------
        // Mapping Query Parameters

        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^\w+$/'])] string $firstName,
        #[MapQueryParameter] string $lastName,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT)] int $age,

        //-------------------------------------------------------------------------------------------
        // Map the entire request payload into an object that will hold available request parameters

        #[MapRequestPayload(
            // A context to use for the deserialization of the request payload into the object
        
            // Specify serialization groups so that only certain properties are populated based on the current 
            // API endpoint.
            serializationContext: ['groups' => ['user:create']],
        
            // A custom resolver to use for mapping the request payload into the object
            resolver: ItemDtoResolver::class,
        
            acceptFormat: 'json',
            validationGroups: ['strict', 'read'],

            // You can also use expressions to define validation groups dynamically based on controller arguments
            // validationGroups: [new Expression('args["userDto"].getType()')],

            validationFailedStatusCode: Response::HTTP_NOT_FOUND

        )] 
        ItemDto $itemDto = new ItemDto("", 0),

        //--------------------------------------------------------------------------------------
        // Map the entire query string into an object that will hold available query parameters

        // If you want to map your object to a nested array in your query using a specific key, set the `key` 
        // option in the `#[MapQueryString]` attribute

        // #[MapQueryString(key: 'item')] ItemDto $itemDto

        #[MapQueryString(
            // You can customize the validation groups used during the mapping and also the HTTP status to 
            // return if the validation fails
            validationGroups: ['strict', 'edit'],
            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY
        )] 
        // If you need a valid DTO even when the request query string is empty, set a default value for your 
        // controller arguments
        UserDto $userDto = new UserDto("", "", 0, "user")

    ): Response
    {
        return new Response(
            '<html><body>Homepage</body></html>'
        );
    }

    #[Route('/redirect', name: 'controller-redirect')]
    public function redirectTo(): RedirectResponse
    {
        // redirects to the "homepage" route
        return $this->redirectToRoute('homepage');

        // redirectToRoute is a shortcut for:
        // return new RedirectResponse($this->generateUrl('homepage'));

        // does a permanent HTTP 301 redirect
        return $this->redirectToRoute('homepage', [], 301);
        // if you prefer, you can use PHP constants instead of hardcoded numbers
        return $this->redirectToRoute('homepage', [], Response::HTTP_MOVED_PERMANENTLY);

        // redirect to a route with parameters
        return $this->redirectToRoute('app_lucky_number', ['max' => 10]);
        // _fragment is a special parameter to point directly to a defined anchor
        return $this->redirectToRoute('app_lucky_number', ['_fragment' => 'result']);

        // redirects to a route and maintains the original query string parameters
        return $this->redirectToRoute('blog_show', $request->query->all());

        // redirects to the current route (e.g. for Post/Redirect/Get pattern):
        return $this->redirectToRoute($request->attributes->get('_route'));

        // redirects externally
        return $this->redirect('http://symfony.com/doc');
    }

    // If you build a JSON API, make sure to declare your route as using the JSON format
    #[Route('/json', name: 'controller-json', format: 'json')]
    public function responseWithJson(): JsonResponse
    {
        return $this->json([
            'message' => 'Hello World'
        ]);
    }
}
