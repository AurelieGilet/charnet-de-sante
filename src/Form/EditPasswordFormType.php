<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'label' => "Ancien mot de passe",
                'attr' => ['placeholder' => "Entrez votre mot de passe actuel"],
                'required' => true,
                'mapped' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne correspondent pas",
                'first_options' => [
                    'label' => "Nouveau mot de passe", 
                    'attr' => ['placeholder' => "Entrez votre nouveau mot de passe"]
                ],
                'second_options' => [
                    'label' => "Répétez le mot de passe", 
                    'attr' => ['placeholder' => "Entrez le même mot de passe"]
                ],
                'required' => true,                
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
