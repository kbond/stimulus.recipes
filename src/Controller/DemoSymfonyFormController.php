<?php

namespace App\Controller;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Override the demo source too add extra logic we don't want to show users.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
#[Route('/_demo/symfony-form', name: 'symfony_form_demo')]
final class DemoSymfonyFormController extends SymfonyFormController
{
    private bool $expanded;

    public function __invoke(Request $request): Response
    {
        $this->expanded = $request->query->getBoolean('expanded');

        return parent::__invoke($request);
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        /** @var FormInterface $form */
        $form = $parameters['form'];

        return $this->renderBlock('recipes/symfony-form.twig', 'demo_wrapper', [
            'form' => $form,
            'data' => $form->isSubmitted() && $form->isValid() ? $form->getData() : null,
            'expanded' => $this->expanded,
        ]);
    }

    protected function createFormBuilder(mixed $data = null, array $options = []): FormBuilderInterface
    {
        return parent::createFormBuilder($data, $options)
            ->setAction($this->generateUrl('symfony_form_demo', ['expanded' => $this->expanded]))
        ;
    }
}
