<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Inscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom')
        ->add('prenom')
        ->add('email')
        ->add('adresse')
        ->add('matricule')
        ->add('sexe', ChoiceType::class, [
            'choices' => [
                'masculin' => 0,
                'féminin' => 1,
            ],
        ])
        ->add('classes', EntityType::class, [
            'choices' => [
                "class" => Classe::class,
                "choice_label" => "libelle"
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
