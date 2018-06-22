<?php
/**
 * Created by PhpStorm.
 * User: Brendan
 * Date: 02/06/2018
 * Time: 20:10
 */

namespace App\Service\ExcelStimuliParser;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RowReader
{

    protected $sheet;

    protected $row = 2;

    public function __construct(Worksheet $sheet)
    {
        $this->sheet = $sheet;
    }

    /**
     * Set row to read
     * @param $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * Shortcut method to read cell
     * @param $cell
     * @return null|\PhpOffice\PhpSpreadsheet\Cell\Cell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function getCell($cell)
    {
        return $this->sheet->getCell($cell.$this->row)->getCalculatedValue();
    }


    public function getStimulusName()
    {
        return $this->getCell('A');
    }

    public function getSpeakerAge()
    {
        return $this->getCell('B');
    }

    public function getSpeakerGender()
    {
        return $this->getCell('C');
    }

    public function getSpeakerLang()
    {
        return $this->getCell('D');
    }

    public function getPlayCount()
    {
        return $this->getCell('E');
    }

    public function getQuestion()
    {
        return $this->getCell('F');
    }

    public function getType()
    {
        switch($this->getCell('G'))
        {
            case "checkbox":
            case "choix_multiple":
                return "checkbox";
            case "radio":
            case "choix_unique":
                return "radio";
            case "nombre":
                return "number";
            case "range":
                return "range";
            default:
                return "text";
        }
    }
}