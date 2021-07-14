<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom",
                'attr' => ['placeholder' => 'Nom'],
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'attr' => ['placeholder' => 'Prénom'],
                'required' => false,
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
                'attr' => ['placeholder' => 'Votre message ...'],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir un message"]),
                    new Length(['min' => 10, 'minMessage' => "Votre message doit au moins faire 10 caractères"]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
