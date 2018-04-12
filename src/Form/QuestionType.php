<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

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
                    'Échelle' => 'range'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data['type'] == 'radio' || $data['type'] == 'checkbox') {
                    $form->add('choices', CollectionType::class, [
                        'entry_type' => TextType::class
                    ]);
                }

                if($data['type'] == 'range'){
                    $form->add('min', NumberType::class);
                    $form->add('max', NumberType::class);
                }
            });
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
