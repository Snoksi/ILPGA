<?php
/**
 * Created by PhpStorm.
 * User: Brendan
 * Date: 09/04/2018
 * Time: 07:51
 */

namespace App\Utils\FormGenerator;

use App\Utils\FormBuilder;
use Symfony\Component\HttpFoundation\Request;

class PreTestFormSerializer extends QuestionSerializer
{
    protected $data;

    protected $form;

    public function __construct($data)
    {
        $this->data = $data;
        $this->generateGeneralQuestions();
        $this->generateCustomQuestions();
    }

    public function generateGeneralQuestions(){
        $input = $this->data;

        foreach($this->data as $question)
        {

        }



        if(isset($input['gender'])){
            $this->form[] = [
                'type' => 'radio',
                'name' => 'gender',
                'label' => "Quel est votre sexe ?",
                'choices' => [
                    'Homme' => "H",
                    'Femme' => "F",
                ]
            ];
        }

        if(isset($input['age'])){
            $this->form[] = [
                'type' => 'number',
                'name' => 'age',
                'label' => "Quel est votre age ?"
            ];
        }

        if(isset($input['has_headphones'])){
            $this->form[] = [
                'type' => 'checkbox',
                'name' => 'has_headphones',
                'label' => "Utilisez-vous un casque",
                'choices' => [
                    'Oui' => "Oui",
                    'Non' => "Non",
                ]
            ];
        }

        if(isset($input['code'])){
            $this->form[] = [
                'type' => 'text',
                'name' => 'code',
                'label' => "Si vous passez ce test dans le cadre d'un cours, indiquez le code transmis par l'enseignant.",
            ];
        }
    }

    public function generateCustomQuestions()
    {

    }
}