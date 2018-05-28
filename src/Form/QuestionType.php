<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Choix unique' => 'radio',
                    'Choix multiple' => 'checkbox',
                    'Champ texte' => 'text',
                    'Champ nomnbre' => 'nombre',
                    'Échelle' => 'range'
                ]
            ])
            ->add('options', QuestionOptionsType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'allow_extra_fields' => true
        ]);
    }
}
