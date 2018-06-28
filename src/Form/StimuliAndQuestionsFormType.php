<?php

namespace App\Form;

use App\Entity\PageGroup;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class StimuliAndQuestionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du bloc'
            ])
            ->add('excel', FileType::class, [
                'label' => 'Fichier Excel des stimuli (et questions)',
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ],
                        'mimeTypesMessage' => "Seuls les fichiers .xls et .xlsx sont acceptés"
                    ])
                ]
            ])
            ->add('audio', FileType::class, [
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => new File([
                            'maxSize' => '5M',
                            'mimeTypes' => ["audio/mpeg", "audio/mp3", "video/mpeg"],
                            'mimeTypesMessage' => "Seuls les fichiers audio et vidéos sont acceptés"
                        ])
                    ])
                ]
            ], ['label' => 'Fichiers mp3 des stimuli'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
