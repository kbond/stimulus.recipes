<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/getting-started', name: 'getting_started', methods: 'GET')]
class GettingStartedController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('getting_started.html.twig');
    }
}
