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
            ->add('companyName', TextType::class, [
                'label' => 'Nom de l\'entreprise'
            ])
            ->add('hrName', TextType::class, [
                'label' => 'Nom du recruteur (si communiqué)'
            ])
            ->add('hrGender', ChoiceType::class, [
                'label' => 'Genre du recruteur',
                'choices' => [
                    'Non communiqué' => 0,
                    'Homme' => 1,
                    'Femme' => 2,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 0
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
