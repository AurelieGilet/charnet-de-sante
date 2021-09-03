<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address1', TextType::class, [
                'label' => "N° et Nom de rue",
                'attr' => ['placeholder' => "1 rue des chats"],
                'required' => false,
            ])
            ->add('address2', TextType::class, [
                'label' => "Complément d'addresse",
                'attr' => ['placeholder' => "Résidence des félins"],
                'required' => false,
            ])
            ->add('postalCode', TextType::class, [
                'label' => "Code Postal",
                'attr' => ['placeholder' => "78146"],
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => ['placeholder' => "Chatou"],
                'required' => false,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => "N° de téléphone",
                'attr' => ['placeholder' => 'fixe ou portable'],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => "Addresse email",
                'attr' => ['placeholder' => 'exemple@mail.com'],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
