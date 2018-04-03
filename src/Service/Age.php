<?php
/**
 * Created by PhpStorm.
 * User: saidi
 * Date: 4/3/2018
 * Time: 11:20 PM
 */

namespace App\Service;


class Age
{
    public function playerAge(\DateTime $dob)
    {

        $now = new \DateTime();
        $interval = $dob->diff($now);
        return $interval->y;
    }
}