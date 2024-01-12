<?php

namespace App\Controller;

use App\RecipeRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe/{name}', name: 'recipe', methods: 'GET')]
class RecipeController extends AbstractController
{
    public function __invoke(string $name, RecipeRegistry $recipes): Response
    {
        return $this->render('recipe.html.twig', [
            'recipe' => $recipes->get($name) ?? throw $this->createNotFoundException('Recipe not found.'),
        ]);
    }
}
