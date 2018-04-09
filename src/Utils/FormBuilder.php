<?php
namespace App\Utils;

class FormBuilder
{

    protected $form = [];

    public function __construct()
    {

    }


    public function getForm(){
        return serialize($this->form);
    }

    public function import(string $form){
        return unserialize($form);
    }
}