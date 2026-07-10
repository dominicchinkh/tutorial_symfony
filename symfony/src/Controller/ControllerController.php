<?php

namespace App\Controller;

use App\Dto\Item;
use App\Dto\User;
use App\Resolver\ItemResolver;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestHeader;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/controller', name: 'controller-')]
final class ControllerController extends AbstractController
{
    #[Route('/auto-wire', name: 'auto-wire', methods: ['GET'])]
    public function autoWire(

        //-----------------------------------
        // Injecting Services and Parameters

        // Inject a logger service
        // LoggerInterface $logger,

        // Inject a specific logger service
        #[Autowire(service: 'monolog.logger.request')]
        LoggerInterface $logger,

        // Inject parameter values
        #[Autowire('%kernel.project_dir%')]
        string $projectDir

    ): Response
    {
        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <p>Project Directory: $projectDir</p>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/query/parameter', name: 'mapping-query-parameter', methods: ['GET'])]
    public function mapQueryParameter(

        //--------------------------
        // Mapping Query Parameters

        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^\w+$/'])] string $firstName,
        #[MapQueryParameter] string $lastName,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT)] int $age,

    ): Response
    {
        // Testing: http://localhost:8000/controller/mapping/query/parameter?firstName=dominic&lastName=chin&age=18

        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <p>First name: $firstName</p>
                        <p>Last name: $lastName</p>
                        <p>Age: $age</p>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/query/string', name: 'mapping-query-string', methods: ['GET'])]
    public function mapQueryString(

        //--------------------------------------------------------------------------------------
        // Map the entire query string into an object that will hold available query parameters

        #[MapQueryString(
            // You can customize the validation groups used during the mapping and also the HTTP status to 
            // return if the validation fails
            validationGroups: ['user:retrieve'],

            validationFailedStatusCode: Response::HTTP_UNPROCESSABLE_ENTITY
        )] 

        // Note: If you need a valid DTO even when the request query string is empty, set a default value for your 
        // controller arguments
        User $user = new User("", "", 0, "user"),

    ): Response
    {
        // Testing: http://localhost:8000/controller/mapping/query/string?firstName=dominic&lastName=chin&age=18&role=admin

        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <p>First name: $user->firstName</p>
                        <p>Last name: $user->lastName</p>
                        <p>Age: $user->age</p>
                        <p>Type: $user->type</p>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/query/string-with-key', name: 'mapping-query-string-with-key', methods: ['GET'])]
    public function mapQueryStringWithKey(

        //--------------------------------------------------------------------------------------
        // Map the entire query string into an object that will hold available query parameters

        // If you want to map your object to a nested array in your query using a specific key, set the `key` 
        // option in the `#[MapQueryString]` attribute

        #[MapQueryString(key: 'user')] User $user

    ): Response
    {
        // Testing: http://localhost:8000/controller/mapping/query/string-with-key?user[firstName]=dominic&user[lastName]=chin&user[age]=18&user[type]=user

        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <p>First name: $user->firstName</p>
                        <p>Last name: $user->lastName</p>
                        <p>Age: $user->age</p>
                        <p>Type: $user->type</p>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/request/payload/item', name: 'mapping-request-payload-item', methods: ['POST'])]
    public function mapRequestPayloadItem(

        //-------------------------------------------------------------------------------------------
        // Map the entire request payload into an object that will hold available request parameters

        #[MapRequestPayload(
            // A context to use for the deserialization of the request payload into the object
        
            // Specify serialization groups so that only certain properties are populated based on the current 
            // API endpoint.
            serializationContext: ['groups' => ['item:create']],
        
            // A custom resolver to use for mapping the request payload into the object
            resolver: ItemResolver::class,
        
            acceptFormat: 'json',

            // After mapping the JSON payload to the Item object, only run the validation rules that belong to the 
            // 'item:retrieve' groups
            validationGroups: ['item:create'],

            // You can also use expressions to define validation groups dynamically based on controller arguments
            // validationGroups: [new Expression('args["item"].getType()')],

            validationFailedStatusCode: Response::HTTP_NOT_FOUND

        )] 
        Item $item = new Item("", 0),

    ): Response
    {
        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <p>Name: $item->name</p>
                        <p>Price: $item->price</p>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/request/payload/items', name: 'mapping-request-payload-items', methods: ['POST'])]
    public function mapRequestPayloadItems(

        //-------------------------------------------------------------------------------------------
        // Map the entire request payload into an object that will hold available request parameters

        // You can tell Symfony to transform each DTO object into an array. 

        // Note: This is only supported from Symfony 8.1
        // https://symfony.com/blog/new-in-symfony-8-1-improved-request-payload-mapping#variadic-controller-arguments

        #[MapRequestPayload] Item ...$items

        // As an alternative, instead of variadic arguments you can map the parameter as an array and configure the 
        // type of each element using the type option of the attribute

        // #[MapRequestPayload(type: Item::class)] array $items

    ): Response
    {
        $list = "";
        foreach ($items as $item) {
            $list .= <<<HTML
                <li>
                    <p>Name: $item->name</p>
                    <p>Price: $item->price</p>
                </li>
            HTML;
        }

        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <ul>
            HTML .
                $list .
            <<<HTML
                        </ul>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/uploaded-file', name: 'mapping-uploaded-file', methods: ['POST'])]
    public function mappingUploadedFile(

        //--------------------------------------------------------------
        // Map one or more UploadedFile objects to controller arguments

        #[MapUploadedFile(
            constraints: [
                new Assert\File(mimeTypes: ['image/png', 'image/jpeg']),
                new Assert\Image(maxWidth: 3840, maxHeight: 2160),
            ],

            // Rename the uploaded file
            name: 'my-cat'
        )]
        UploadedFile $picture

    ): BinaryFileResponse
    {
        return new BinaryFileResponse($picture->getRealPath());
    }

    #[Route('/mapping/uploaded-files', name: 'mapping-uploaded-files', methods: ['POST'])]
    public function mappingUploadedFiles(

        //--------------------------------------------------------------
        // Map one or more UploadedFile objects to controller arguments

        // Upload a collection of files
        #[MapUploadedFile(
            constraints: [
                new Assert\File(maxSize: '2M'),
                new Assert\File(mimeTypes: ['application/pdf'])
            ],

            // Change the status code of the HTTP exception thrown when there are constraint violations
            validationFailedStatusCode: Response::HTTP_REQUEST_ENTITY_TOO_LARGE
        )]
        UploadedFile ...$documents

    ): Response
    {
        $list = "";

        foreach ($documents as $document) {
            $list .= <<<HTML
                <li>
                    <p>Name: {$document->getClientOriginalName()}</p>
                </li>
            HTML;
        }

        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <h3>Documents:</h3>
                        <ul>
            HTML .
                $list .
            <<<HTML
                        </ul>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/mapping/request/header', name: 'mapping-request-header', methods: ['GET'])]
    public function mappingRequestHeader(

        //-----------------------------------------------------
        // Map an HTTP request header to a controller argument

        // Note: By default, the header name is converted from kebab-case to camelCase to 
        // match the argument name (e.g. the accept-language header maps to the $acceptLanguage 
        // argument)

        #[MapRequestHeader] string $acceptLanguage, // or `array $acceptLanguage` or `AcceptHeader $acceptLanguage`

        // Pass the HTTP header name explicitly
        #[MapRequestHeader(name: 'x-custom-token')] string $token,

    ): Response
    {
        return new Response(<<<HTML
            <html>
                <body>
                    <div>
                        <p>Accepted language: $acceptLanguage</p>
                        <p>Custom token: $token</p>
                    </div>
                </body>
            </html>
            HTML
        );
    }

    #[Route('/redirect', name: 'redirect')]
    public function redirectTo(): RedirectResponse
    {
        // Redirects to the "auto-wire" route
        return $this->redirectToRoute('auto-wire');

        // `redirectToRoute` is a shortcut for:
        // return new RedirectResponse($this->generateUrl('auto-wire'));

        // A permanent HTTP 301 redirect
        return $this->redirectToRoute('auto-wire', [], 301);

        // If you prefer, you can use PHP constants instead of hardcoded numbers
        return $this->redirectToRoute('auto-wire', [], Response::HTTP_MOVED_PERMANENTLY);

        // Redirect to a route with parameters
        return $this->redirectToRoute('auto-wire', ['max' => 10]);

        // `_fragment` is a special parameter to point directly to a defined anchor
        return $this->redirectToRoute('auto-wire', ['_fragment' => 'result']);

        // Redirects to a route and maintains the original query string parameters
        return $this->redirectToRoute('auto-wire', $request->query->all());

        // Redirects to the current route (e.g. for Post/Redirect/Get pattern):
        return $this->redirectToRoute($request->attributes->get('_route'));

        // Redirects externally
        return $this->redirect('http://symfony.com/doc');

        // Note: The redirect() method does not check its destination in any way. If you 
        // redirect to a URL provided by end-users, your application may be open to the 
        // un-validated redirects security vulnerability

        // https://cheatsheetseries.owasp.org/cheatsheets/Unvalidated_Redirects_and_Forwards_Cheat_Sheet.html
    }

    // If you build a JSON API, make sure to declare your route as using the JSON format
    #[Route('/json', name: 'json', format: 'json')]
    public function responseWithJson(): JsonResponse
    {
        return $this->json([
            'message' => 'Hello World'
        ]);
    }

    #[Route('/error', name: 'error')]
    public function responseWithError(): Response
    {
        // This is just a shortcut for:
        // throw new LogicException('Access Denied.');
        return $this->createAccessDeniedException();

        // This is just a shortcut for:
        // throw new NotFoundHttpException('Not Found');
        return $this->createNotFoundException();

        // This exception generates a 400 status error
        throw new HttpException(Response::HTTP_BAD_REQUEST);

        // This exception ultimately generates a 500 status error
        throw new \Exception('Something went wrong!');
    }
}
