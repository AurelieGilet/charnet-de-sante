<?php

namespace App\Form;

use App\Entity\Health;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CatHealthFormType extends AbstractType
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
                'label' => "Date de la guérison",
                'widget' => "choice",
                'placeholder' => [
                    'day' => "Jour", 'month' => "Mois", 'year' => "Année"
                ],
                'years' => range(date('Y') - 25, date('Y') + 1),
                'required' => false,
                'by_reference' => true,
            ])
            ->add('vetVisitMotif', TextType::class, [
                'label' => "Motif de la visite *",
                'attr' => ['placeholder' => "Visite annuelle"],
                'required' => false,
            ])
            ->add('allergy', ChoiceType::class, [
                'label' => "Type d'allergie *",
                'choices' => [
                    'Allergie aux puces' => 'allergie aux puces',
                    'Allergie atopique' => 'allergie atopique',
                    'Allergie alimentaire' => 'Allergie alimentaire',
                    'Allergie de contact' => 'Allergie de contact',
                    'Autre' => 'autre',
                ],
                'required' => false,
            ])
            ->add('disease', TextType::class, [
                'label' => "Nom de la maladie *",
                'attr' => ['placeholder' => "Gastrite"],
                'required' => false,
            ])
            ->add('wound', TextType::class, [
                'label' => "Type de blessure *",
                'attr' => ['placeholder' => "Piqûre d'insecte"],
                'required' => false,
            ])
            ->add('surgery', TextType::class, [
                'label' => "Type de chirurgie *",
                'attr' => ['placeholder' => "Stérilisation"],
                'required' => false,
            ])
            ->add('analysis', TextType::class, [
                'label' => "Motif de l'analyse *",
                'attr' => ['placeholder' => "Contrôle des reins"],
                'required' => false,
            ])
            ->add('details', TextareaType::class, [
                'label' => "Détails",
                'attr' => ['rows' => 10],
                'required' => false,
            ])
            ->add('documentName', TextType::class, [
                'label' => "Nom du document *",
                'attr' => ['placeholder' => "Analyses de sang"],
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
            'data_class' => Health::class,
        ]);
    }
}
