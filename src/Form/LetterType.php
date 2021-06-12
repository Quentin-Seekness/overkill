<?php

namespace App\Form;

use App\Entity\Letter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LetterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jobName', TextType::class, [
                'label' => 'Intitulé du poste'
            ])
            // ->add('jobDStatus', ChoiceType::class, [
            //     'label' => 'L\'intitulé du poste doit s\'écrire avec :',
            //     'choices' => [
            //         'un " de "' => 0,
            //         'un " d\' "' => 1,
            //     ]
            // ])
            ->add('companyName', TextType::class, [
                'label' => 'Nom de l\'entreprise'
            ])
            // ->add('companyDStatus', ChoiceType::class, [
            //     'label' => 'Le nom de l\'entreprise doit s\'écrire avec :',
            //     'choices' => [
            //         'un " de "' => 0,
            //         'un " d\' "' => 1,
            //     ]
            // ])
            ->add('hrName', TextType::class, [
                'label' => 'Nom du recruteur (si communiqué)'
            ])
            ->add('hrGender', ChoiceType::class, [
                'label' => 'Genre du recruteur',
                'choices' => [
                    'Non communiqué' => 0,
                    'Homme' => 1,
                    'Femme' => 2,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Letter::class,
        ]);
    }
}
