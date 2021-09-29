<?php

namespace App\Form;

use App\Entity\HealthCare;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CatHealthCareFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'label' => "Date *",
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
            ->add('vaccine', ChoiceType::class, [
                'label' => "Type de vaccin *",
                'choices' => [
                    'Typhus (Panleucopénie)' => 'typhus',
                    'Coryza' => 'coryza',
                    'Leucose féline (FeLV)' => 'leucose féline',
                    'Rage' => 'rage',
                    'Autre' => 'autre',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('injectionSite', TextType::class, [
                'label' => "Site d'injection",
                'attr' => ['placeholder' => "postérieur gauche/droit"],
                'required' => false,
            ])
            ->add('parasite', ChoiceType::class, [
                'label' => "Type de parasite *",
                'choices' => [
                    'Puces' => 'puces',
                    'Poux' => 'poux',
                    'Tiques' => 'tiques',
                    'Aoûtats' => 'aoutats',
                    'Gale' => 'gale',
                    'Autre' => 'autre',
                ],
                'required' => false,
            ])
            ->add('treatment', TextType::class, [
                'label' => "Motif du traitement *",
                'attr' => ['placeholder' => "A mordu un hérisson"],
                'required' => false,
            ])
            ->add('productName', TextType::class, [
                'label' => "Nom du produit",
                'required' => false,
            ])
            ->add('dosage', TextType::class, [
                'label' => "Dose",
                'attr' => ['placeholder' => "1 comprimé/pipette"],
                'required' => false,
            ])
            ->add('descaling', TextType::class, [
                'label' => "Observations",
                'attr' => ['placeholder' => "PicPic"],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer"
            ])
        ;

        $builder
            ->get('vaccine')
            ->addModelTransformer(new CallbackTransformer(
                // Transform a string to an array
                function ($vaccineString) {
                    return explode(',', $vaccineString);
                },
                // Transform an array to a string
                function ($vaccineArray) {
                    return implode(',', $vaccineArray);
                },
                
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HealthCare::class,
        ]);
    }
}
