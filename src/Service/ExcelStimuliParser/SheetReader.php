<?php
/**
 * Created by PhpStorm.
 * User: Brendan
 * Date: 02/06/2018
 * Time: 20:07
 */

namespace App\Service\ExcelStimuliParser;

use App\Entity\Question;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SheetReader implements \Iterator
{
    protected $reader;

    protected $row = 2;

    public function __construct(UploadedFile $excel)
    {
        try{
            $inputFileType = IOFactory::identify($excel->getRealPath());
            /**  Create a new Reader of the type that has been identified  **/
            $reader = IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $this->sheet = $reader->load($excel)->getActiveSheet();
        }
        catch(\Exception $exception){
            throw new \InvalidArgumentException("Inccorrect Excel file");
        }

        $this->reader = new RowReader($this->sheet);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return RowReader|null
     * @since 5.0.0
     */
    public function current()
    {
        $this->reader->setRow($this->row);
        return $this->reader;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->row++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->row;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        $stimulusName = $this->current()->getStimulusName();
        // If there is no stimulus, we stop
        if($stimulusName == "") return false;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->row = 1;
    }
}