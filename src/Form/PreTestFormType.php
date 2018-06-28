<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PreTestFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Length(["min" => 5]),
                ]
            ])
            ->add('optional_questions', ChoiceType::class, array(
                'choices' => $this->getOptions(),
                'required' => false,
                'choice_label' => function($question, $key, $index) {
                    /** @var Question $question */
                    return strtoupper($question->getLabel());
                },
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('questions',  CollectionType::class, [
                'entry_type' => QuestionType::class,
                'allow_add' => true
            ])
        ;
    }

    protected function getOptions()
    {
        $options['gender'] = new Question();
        $options['gender']->setLabel("Quel est votre sexe ?");
        $options['gender']->setType('radio');
        $options['gender']->setOptions([
            'choices' => [
                'Homme',
                'Femme'
            ]
        ]);

        $options['age'] = new Question();
        $options['age']->setLabel("Quel est votre age ?");
        $options['age']->setType('number');
        $options['age']->setOptions([
            'min' => 0,
            'max' => 100
        ]);

        $options['headphones'] = new Question();
        $options['headphones']->setLabel("Utilisez-vous un casque ?");
        $options['headphones']->setType('radio');
        $options['headphones']->setOptions([
            'choices' => [
                'Oui',
                'Non'
            ]
        ]);

        $options['headphones'] = new Question();
        $options['headphones']->setLabel("Si vous êtes étudiant, indiquez le code fourni par l'enseignant.");
        $options['headphones']->setType('text');

        return $options;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }
}
