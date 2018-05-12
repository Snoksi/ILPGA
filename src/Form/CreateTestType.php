<?php

namespace App\Form;

use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CreateTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom du test'])
            ->add('stimulus_test', FileType::class, ['label' => 'Stimulus test'])
            ->add('notified', CheckboxType::class, [
                'label' => 'Être notifié des réponses',
                'required' => false
            ])
            ->add('random', CheckboxType::class, [
                'label' => 'Bloc tirés aléatoirement',
                'required' => false
            ])
            ->add('instructionPage', InstructionPageType::class, ['label' => 'Instructions générales'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
        ]);
    }
}
