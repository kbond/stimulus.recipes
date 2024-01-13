<?php

namespace App\Controller;

use App\RecipeRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'homepage', methods: 'GET')]
class HomepageController extends AbstractController
{
    public function __invoke(Request $request, RecipeRegistry $recipes): Response
    {
        if ('json' === $request->getPreferredFormat()) {
            return $this->json(['recipes' => array_keys($recipes->all())]);
        }

        return $this->render('homepage.html.twig', ['recipes' => $recipes]);
    }
}
