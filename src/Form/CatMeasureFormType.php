<?php

namespace App\Form;

use App\Entity\Measure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CatMeasureFormType extends AbstractType
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
            ->add('weight', NumberType::class, [
                'label' => "Poids (Kg)",
                'scale' => 2,
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                ],
                'required' => false
            ])
            ->add('temperature', NumberType::class, [
                'label' => "Température (°C)",
                'attr' => [
                    'min' => 35,
                    'max' => 45,
                ],
                'required' => false
            ])
            ->add('isMated', ChoiceType::class, [
                'label' => "Saillie",
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'required' => false,
            ])
            ->add('isPregnant', ChoiceType::class, [
                'label' => "Fécondation",
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'required' => false,
            ])
            ->add('heatEndDate', DateType::class, [
                'label' => "Date de fin des chaleurs",
                'widget' => "choice",
                'placeholder' => [
                    'day' => "Jour", 'month' => "Mois", 'year' => "Année"
                ],
                'years' => range(date('Y') - 25, date('Y') + 1),
                'required' => false,
                'by_reference' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Measure::class,
        ]);
    }
}
