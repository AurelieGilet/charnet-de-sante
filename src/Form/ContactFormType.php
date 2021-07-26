<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom",
                'attr' => ['placeholder' => 'Nom'],
                'required' => false,
                'constraints' => [
                    new Length(['max' => 30, 'maxMessage' => "Votre nom ne doit pas faire plus de 30 caractères"]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'attr' => ['placeholder' => 'Prénom'],
                'required' => false,
                'constraints' => [
                    new Length(['max' => 30, 'maxMessage' => "Votre prénom ne doit pas faire plus de 30 caractères"]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => "Adresse email",
                'attr' => ['placeholder' => 'Ex: michelle@mail.com'],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir un email"]),
                    new Email(['message' => "Merci de saisir un email valide"]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => "Message",
                'attr' => ['placeholder' => 'Votre message ...', 'rows' => 10], 
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir un message"]),
                    new Length([
                        'min' => 10, 
                        'max' => 2000, 
                        'minMessage' => "Votre message doit faire au moins 10 caractères", 
                        'maxMessage' => "Votre message ne doit pas faire plus de 2000 caractères"]),
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => "Envoyer"
            ]);
        ;
    }
}
