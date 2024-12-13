<?php

// src/Form/LivreType.php
namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // Ajouter cette ligne pour le checkbox

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('auteur', TextType::class)
            ->add('etat', TextType::class)
            ->add('disponible', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false, // Définir comme non obligatoire
            ])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter un livre']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}


