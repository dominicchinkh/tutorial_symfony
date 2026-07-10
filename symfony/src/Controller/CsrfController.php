<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

#[Route('/csrf', name: 'csrf-')]
class CsrfController extends AbstractController
{
    #[Route('/token', name: 'show-token', methods: ['GET'])]
    public function getCsrfToken(): Response
    {
        return $this->render(
            'security/csrf_token.html.twig', ['id' => 1]
        );
    }

    #[Route('/token', name: 'check-token', methods: ['POST'])]
    #[IsCsrfTokenValid(new Expression('"update-item-" ~ request.query.get("id")'))]
    public function checkCsrfToken(Request $request): Response
    {
        $id             = $request->query->get('id');
        $submittedToken = $request->request->get('_token');

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
