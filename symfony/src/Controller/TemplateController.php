<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/template', name: 'template-')]
final class TemplateController extends AbstractController
{
    #[Route('/notification', name: 'notification', methods: ['GET'])]
    public function notification(): Response
    {
        // get the user information and notifications somehow
        $userFirstName = 'Dominic';
        $userNotifications = ['PR ready', 'PR approved'];

        // The template path is the relative file path from `templates/`
        return $this->render('template/user/notification.html.twig', [

            // This array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')

            'user_first_name' => $userFirstName,
            'notifications' => $userNotifications,
        ]);
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
        return $this->render('template/component/index.html.twig', [
        ]);
    }
}
