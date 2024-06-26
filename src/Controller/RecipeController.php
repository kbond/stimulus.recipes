<?php

namespace App\Controller;

use App\RecipeRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    '/{name}.{_format?}',
    name: 'recipe',
    methods: 'GET',
    priority: -1000,
)]
class RecipeController extends AbstractController
{
    public function __invoke(Request $request, string $name, RecipeRegistry $recipes): Response
    {
        $recipe = $recipes->get($name) ?? throw $this->createNotFoundException('Recipe not found.');

        if ('json' === $request->getPreferredFormat()) {
            return $this->json($recipe);
        }

        return $this->render('recipe.html.twig', [
            'recipe' => $recipe,
            'recipes' => $recipes,
        ]);
    }
}
