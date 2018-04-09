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

class PreTestFormGenerator extends FormBuilder
{
    protected $request;

    protected $form;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->generateGeneralQuestions();
        $this->generateCustomQuestions();
    }

    public function generateGeneralQuestions(){
        if($this->request->request->getBoolean('gender') == true){
            $form[] = [
                'type' => 'radio',
                'name' => 'gender',
                'label' => "Quel est votre sexe ?",
                'choices' => [
                    'Homme' => "H",
                    'Femme' => "F",
                ]
            ];
        }

        if($this->request->request->getBoolean('gender') == true){
            $form[] = [
                'type' => 'number',
                'name' => 'age',
                'label' => "Quel est votre age ?"
            ];
        }

        if($this->request->request->getBoolean('has_headphones') == true){
            $form[] = [
                'type' => 'checkbox',
                'name' => 'has_headphones',
                'label' => "Utilisez-vous un casque",
                'choices' => [
                    'Oui' => "Oui",
                    'Non' => "Non",
                ]
            ];
        }

        if($this->request->request->getBoolean('code') == true){
            $form[] = [
                'type' => 'text',
                'name' => 'code',
                'label' => "Si vous passez ce test dans le cadre d'un cours, indiquez le code transmis par l'enseignant.",
            ];
        }
    }

    public function generateCustomQuestions()
    {
        $questions = $this->request->get('questions');


    }
}