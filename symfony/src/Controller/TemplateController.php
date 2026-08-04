<?php

namespace App\Controller;

use App\Dto\Item;
use App\Dto\Notification;
use App\Dto\SearchFilters;
use App\Repository\ProductRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route('/template', name: 'template-')]
final class TemplateController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly Environment $twig,
    ) {}

    #[Route('/notification', name: 'notification', methods: ['GET'])]
    public function notification(): Response
    {
        // Get the user information and notifications somehow
        $userFirstName = 'Dominic';
        $userNotifications = ['PR ready', 'PR approved'];

        /*
         *  The `renderBlock()` method returns a `Response` object with the
         *  block contents
         * 
         *    return $this->renderBlock('template/user/notification.html.twig', 'page_contents', [
         *        // ...
         *    ]);
         */

        /*
         *  The `renderBlockView()` method only returns the contents created by the template block, 
         *  so you can use those contents later in a `Response` object
         * 
         *    $contents = $this->renderBlockView('template/user/notification.html.twig', 'page_contents', [
         *        // ...
         *    ]);
         */

        // The template path is the relative file path from `templates/`
        return $this->render('template/user/notification.html.twig', [

            // This array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')

            'user_first_name' => $userFirstName,
            'notifications' => $userNotifications,
        ]);
    }

    /*
     *  Another option is to use the #[Template] attribute on the controller method to define 
     *  the template to render:
     * 
     *  You can use the #[Template] attribute on the controller to specify a block to render
     * 
     *    #[Template('template/user/notification.html.twig', block: 'page_contents')]
    */

    #[Route('/notification/template-attribute', name: 'notification-template-attribute', methods: ['GET'])]
    #[Template('template/user/notification.html.twig')]
    public function notificationTemplateAttribute(): array
    {
        $userFirstName = 'Dominic';
        $userNotifications = ['PR ready', 'PR approved'];
        
        // When using the #[Template] attribute, you only need to return
        // an array with the parameters to pass to the template (the attribute
        // is the one which will create and return the Response object)

        return [
           'user_first_name' => $userFirstName,
           'notifications'   => $userNotifications,
        ];
    }

    /*
     *  Rendering a Template in Services
     * 
     *  Inject the twig Symfony service into your own services and use its render() method
     * 
     */
    #[Route('/notification/twig', name: 'notification-twig', methods: ['GET'])]
    public function notificationTwig(): Response
    {
        $userFirstName = 'Dominic';
        $userNotifications = ['PR ready', 'PR approved'];

        // Templates are loaded in the application using a Twig template loader
        $loader = $this->twig->getLoader();

        // Checking if a template exists
        if (!$loader->exists('template/user/notification.html.twig')) {
            return new Response("<h3>Template template/user/notification.html.twig does not exist</h3>");
        }

        return new Response(
            $this->twig->render('template/user/notification.html.twig', [
                'user_first_name' => $userFirstName,
                'notifications'   => $userNotifications,
            ])
        );
    }

    #[Route('/blog', name: 'blog', methods: ['GET'])]
    public function blog(): Response
    {
        return $this->render('template/blog/index.html.twig', [
            'blog_posts' => [
                [
                    "title" => "Ancient Artifacts discovered",
                    "slug" => "unearthing-forgotten-relics",
                    "excerpt" => "Archaeologists uncover deeply buried secrets."
                ],
                [
                    "title" => "Future of Quantum Computing",
                    "slug" => "unlocked-quantum-processing-power",
                    "excerpt" => "Tech leaders race to build stable processors."
                ]
            ]
        ]);
    }

    #[Route('/blog/articles/recent', name: 'latest_articles', methods: ['GET'])]
    public function recentArticles(int $max = 3): Response
    {
        // get the recent articles somehow (e.g. making a database query)
        $articles = [
            [
                "title" => "Ancient Artifacts discovered",
                "slug" => "unearthing-forgotten-relics",
                "excerpt" => "Archaeologists uncover deeply buried secrets."
            ],
            [
                "title" => "Future of Quantum Computing",
                "slug" => "unlocked-quantum-processing-power",
                "excerpt" => "Tech leaders race to build stable processors."
            ]
        ];

        return $this->render('template/blog/_recent_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/{slug}', name: 'article', methods: ['GET'])]
    public function article(string $slug): Response
    {
        return $this->render('template/blog/article.html.twig', [
            'slug' => $slug,
            'user' => [
                'profileImageUrl' => 'https://images.pexels.com/photos/20338832/pexels-photo-20338832.jpeg',
                'fullName'        => 'Dominic',
                'email'           => 'dominic@example.com'
            ]
        ]);
    }

    #[Route('/variable/{slug}', name: 'variable', methods: ['GET'])]
    public function variable(string $slug): Response
    {
        $this->addFlash('notice', 'Your changes were saved!');
        
        return $this->render('template/variable/index.html.twig', [
        ]);
    }

    // To lists all your application components that live in templates/components/
    //   php bin/console debug:twig-component

    #[Route('/component', name: 'component', methods: ['GET'])]
    public function component(): Response
    {
        $items        = [ new Item('Wallet', 16.7) ];
        $notification = new Notification("Hello", "alert");
        $products     = $this->productRepository->findAll();

        return $this->render('template/component/index.html.twig', [
            'items'           => $items,
            'notification'    => $notification,
            'products'        => $products,
            'search_filters'  => new SearchFilters('books', 10),
        ]);
    }

    /*
     *  Template Namespaces
     *
     *  Extra directories are configured in twig.paths (config/packages/twig.yaml).
     *  Use @ + namespace to refer to namespaced templates, e.g.:
     *    @email/welcome.html.twig  -> email/default/templates/welcome.html.twig
     *    @admin/dashboard.html.twig -> backend/templates/dashboard.html.twig
     *
     *  Inspect paths with:
     *    php bin/console debug:twig @email/welcome.html.twig
     *    php bin/console debug:twig @admin/dashboard.html.twig
     */
    #[Route('/namespace', name: 'namespace', methods: ['GET'])]
    public function namespace(): Response
    {
        return $this->render('template/namespace/index.html.twig');
    }

    #[Route('/namespace/email', name: 'namespace-email', methods: ['GET'])]
    public function namespaceEmail(): Response
    {
        return $this->render('@email/welcome.html.twig', [
            'user_first_name' => 'Dominic',
        ]);
    }

    #[Route('/namespace/admin', name: 'namespace-admin', methods: ['GET'])]
    public function namespaceAdmin(): Response
    {
        return $this->render('@admin/dashboard.html.twig');
    }
}
