<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
#[Route('/_demo/form', name: 'form_demo')]
final class FormDemoController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->renderBlock('recipes/form.twig', 'demo_wrapper', [
            'data' => $request->isMethod('POST') ? [...$request->getPayload(), ...$request->files->all()] : null,
        ]);
    }
}
