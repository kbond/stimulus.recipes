<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

#[Route('/', name: 'homepage')]
class HomepageController extends AbstractController
{
    public function __invoke(CacheInterface $cache): Response
    {
        return $this->render('homepage.html.twig', [
            'controller_name' => 'HomepageController',
            'random' => $cache->get('random', fn () => random_int(0, 100)),
        ]);
    }
}
