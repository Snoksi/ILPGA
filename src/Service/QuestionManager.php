<?php
/**
 * Created by PhpStorm.
 * User: saidi
 * Date: 6/19/2018
 * Time: 12:03 PM
 */

namespace App\Service;


class QuestionManager
{
    public function randomQuestion($questions, $answers)
    {
        $result = [];
        foreach ($questions as &$question) {
            foreach ($answers as &$answer) {
                if ($question->getId() != $answer->getPage()){
                    $result[] = $question;
                }
            }
        }
        if (count($result) != 0){
            return  rand(0, count($result)-1);
        } else {
            // if the table is empty that means the end of the test
            return -1;
        }
    }
}