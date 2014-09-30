<?php
/**
 * Jorum API Context
 *
 * JorumModel
 *
 * Base class for Community, Collection, Item. Metadata and Bitstream
 * It provides JSON serialisation. HTML output as well as an Iterator
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service;

use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 */
class JorumModel implements \JsonSerializable, \IteratorAggregate
{
    /**
     * The constructor. Accepts an associative array with values to populate the Model (e.g. handle, title)
     * @param array $data
     */
    public function __construct($data = array())
    {

    }


    /**
     * function called when encoded with json_encode
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


    /**
     * getIterator
     *
     * @access public
     * @return \RecursiveDirectoryIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator((array)$this);
    }

    /**
     * Iterate and output properties and attriibutes
     * @todo return a string instead of outputting directly
     * @todo use XSLT from XML output to decorate
     */
    public function toHtml()
    {
        $iterator = new \ArrayIterator((array)$this);
        echo "<br/>";

        while ($iterator->valid()) {
            $value = $iterator->current();
            $key = $iterator->key();
            echo \MIMAS\Service\Jorum\Formatters\Html::get($key, $value);

            $iterator->next();
        }
        echo "<br/>";
    }


}