<?php

namespace App\Form;

use App\Entity\PetCare;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CatPetCareFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => "Date",
                'widget' => "choice",
                'placeholder' => [
                    'day' => "Jour", 'month' => "Mois", 'year' => "Année"
                ],
                'years' => range(date('Y') - 25, date('Y') + 1),
                'required' => true,
                'by_reference' => true,
            ])
            ->add('endDate', DateType::class, [
                'label' => "Date de fin",
                'widget' => "choice",
                'placeholder' => [
                    'day' => "Jour", 'month' => "Mois", 'year' => "Année"
                ],
                'years' => range(date('Y') - 25, date('Y') + 1),
                'required' => false,
                'by_reference' => true,
            ])
            ->add('foodType', TextType::class, [
                'label' => "Type de nourriture",
                'attr' => ['placeholder' => "croquettes"],
                'required' => false,
            ])
            ->add('foodBrand', TextType::class, [
                'label' => "Marque",
                'attr' => ['placeholder' => "Croquy"],
                'required' => false,
            ])
            ->add('foodQuantity', NumberType::class, [
                'label' => "Quantité (g)",
                'scale' => 2,
                'attr' => [
                    'min' => 0,
                    'max' => 500,
                ],
                'required' => false
            ])
            ->add('grooming', ChoiceType::class, [
                'label' => "Toiletage",
                'choices' => [
                    'Bain' => 'bain',
                    'Brossage' => 'brossage',
                ],
                'required' => false,
            ])
            ->add('eyesEars', ChoiceType::class, [
                'label' => "Toiletage",
                'choices' => [
                    'Yeux' => 'yeux',
                    'Oreilles' => 'oreilles',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('teeth', TextType::class, [
                'label' => "Type de soin",
                'attr' => ['placeholder' => "Brossage"],
                'required' => false,
            ])
            ->add('notes', TextType::class, [
                'label' => "Notes",
                'attr' => ['placeholder' => "Refuse sa nourriture"],
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
            'data_class' => PetCare::class,
        ]);
    }
}
