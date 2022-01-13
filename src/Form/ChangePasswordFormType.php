<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => "Nouveau mot de passe *",
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => "Veuillez entrer un nouveau mot de passe",
                        ]),
                        new Length([
                            'min' => 8,
                            'max' => 30,
                            'minMessage' => "Votre mot de passe doit faire entre 8 et 30 caractères",
                        ]),
                        new Regex([
                            'pattern' => "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,30}/",
                            'match' => true,
                            'message' => "Votre mot de passe doit contenir une majuscule, une minuscule et un chiffre"
                        ])
                    ],
                ],
                'second_options' => [
                    'label' => "Répétez le mot de passe *",
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
