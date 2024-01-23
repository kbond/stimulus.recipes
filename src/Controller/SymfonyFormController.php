<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class SymfonyFormController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(),
                'help' => 'Your full name.',
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new NotBlank(), new Email()],
            ])
            ->add('bio', TextareaType::class, [
                'required' => false,
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Prefer not to say' => null,
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'expanded' => $request->query->getBoolean('expanded'),
            ])
            ->add('interests', ChoiceType::class, [
                'choices' => [
                    'PHP' => 'php',
                    'JavaScript' => 'js',
                    'Python' => 'python',
                    'Ruby' => 'ruby',
                    'Go' => 'go',
                ],
                'multiple' => true,
                'expanded' => $request->query->getBoolean('expanded'),
            ])
            ->add('profileImage', FileType::class, [
                'required' => false,
                'constraints' => new Image(),
            ])
            ->add('terms', CheckboxType::class, [
                'constraints' => new IsTrue(),
                'help' => 'Do you agree to our terms?',
            ])
            ->add('submit', SubmitType::class)
            ->getForm()
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            // do something with the data
        }

        return $this->render('template.html.twig', [
            'form' => $form,
        ]);
    }
}
