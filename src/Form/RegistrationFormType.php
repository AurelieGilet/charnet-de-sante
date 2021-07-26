<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Adresse email",
                'attr' => ['placeholder' => "Ex: michelle@mail.com"],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => "Merci de saisir un email"]),
                    new Email(['message' => "Merci de saisir un email valide"]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => ['placeholder' => "Nom d'utilisateur"],
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne correspondent pas",
                'first_options' => [
                    'label' => "Mot de passe", 
                    'attr' => ['placeholder' => "Entrez votre mot de passe"]
                ],
                'second_options' => [
                    'label' => "Répétez votre mot de passe", 
                    'attr' => ['placeholder' => "Entrez le même mot de passe"]
                ],
                'required' => true,                
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider"
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
