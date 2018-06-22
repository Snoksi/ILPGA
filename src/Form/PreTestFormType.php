<?php

namespace App\Form;

use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreTestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', CheckboxType::class, [
                'label' => "Quel est votre sexe ?",
                'required' => false,
            ])
            ->add('age', CheckboxType::class, [
                'label' => "Quel est votre âge ?",
                'required' => false,
            ])
            ->add('has_headphones', CheckboxType::class, [
                'label' => "Utilisez-vous un casque ?",
                'required' => false,
            ])
            ->add('code', CheckboxType::class, [
                'label' => "Si vous passez ce test dans le cadre d’un cours, indiquez le code indiqué par l’enseignant.",
                'required' => false,
            ])
            ->add('questions',  CollectionType::class, [
                'entry_type' => QuestionType::class,
                'allow_add' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }
}