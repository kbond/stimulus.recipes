<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
#[Route('/_demo/post-link', name: 'post_link_demo')]
final class PostLinkDemoController extends AbstractController
{
    public function __invoke(Request $request, CsrfTokenManagerInterface $tokenManager): Response
    {
        $validToken = $tokenManager->isTokenValid(new CsrfToken('post-link', $request->request->get('_csrf_token')));

        $request->getSession()
            ->getFlashBag()
            ->add($validToken ? 'success' : 'warning',
                sprintf(
                    'Submitted as "%s" method. The CSRF token is "%s"',
                    $request->getMethod(),
                    $validToken ? 'valid' : 'invalid',
                )
            )
        ;

        return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('recipe', ['name' => 'post-link']));
    }
}
