<?php

namespace App\Form;

use App\Entity\Cat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du chat *",
                'attr' => ['placeholder' => "Mistigris"],
                'required' => true,
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => "Sexe",
                'choices' => [
                    'Femelle' => "F",
                    'Mâle' => "M",
                ],
                'required' => false,
            ])
            ->add('race', TextType::class, [
                'label' => "Race",
                'attr' => ['placeholder' => "European shorthair"],
                'required' => false,
            ])
            ->add('coat', TextType::class, [
                'label' => "Couleur de robe",
                'attr' => ['placeholder' => "Gris"],
                'required' => false,
            ])
            ->add('isSterilized', ChoiceType::class, [
                'label' => "Stérilisation",
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'required' => false,
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => "Date de naissance",
                'widget' => "choice",
                'placeholder' => [
                    'day' => "Jour", 'month' => "Mois", 'year' => "Année",
                ],
                'years' => range(date('Y') - 25, date('Y')),
                'required' => false,
                'empty_data' => '',
            ])
            ->add('dateOfDeath', DateType::class, [
                'label' => "Date de décès",
                'widget' => "choice",
                'placeholder' => [
                    'day' => "Jour", 'month' => "Mois", 'year' => "Année",
                ],
                'years' => range(date('Y') - 25, date('Y')),
                'required' => false,
                'empty_data' => '',
            ])
            ->add('microchip', TextType::class, [
                'label' => "N° de puce électronique",
                'attr' => ['placeholder' => "250260000000000"],
                'required' => false,
            ])
            ->add('tattoo', TextType::class, [
                'label' => "N° de tatouage",
                'attr' => ['placeholder' => "AAA000 ou 000AAA"],
                'required' => false,
            ])
            ->add('ownerName', TextType::class, [
                'label' => "Nom du proprétaire",
                'attr' => ['placeholder' => "Mère Michelle"],
                'required' => false,
            ])
            ->add('veterinaryName', TextType::class, [
                'label' => "Nom du vétérinaire",
                'attr' => ['placeholder' => "Dr Dolittle"],
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
            'data_class' => Cat::class,
        ]);
    }
}
