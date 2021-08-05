<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class EditEmailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => "Nouvelle adresse email",
            'attr' => ['placeholder' => "Ex: michelle@mail.com"],
            'required' => true,
            ])
        ->add('confirm_password', PasswordType::class, [
            'label' => "Confirmez par mot de passe",
            'attr' => ['placeholder' => "Entrez votre mot de passe"],
            'required' => true,
            'mapped' => false,
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
