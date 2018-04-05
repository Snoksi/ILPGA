<?php
// src/Form/ProductType.php
namespace App\Form\Upload;

use App\Entity\Stimulus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class StimulusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('stimulus', FileType::class, array(

                'data_class' => null,
                'label' => 'Fichier mp3')
            )
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Stimulus::class,
        ));
    }
}