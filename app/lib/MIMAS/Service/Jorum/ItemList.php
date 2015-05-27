<?php
/**
 * Jorum API Context
 *
 * ItemList
 *
 * Holds a list of Items and provides rudimentary iteration means
 * Will be deprcated / merged to DataColleciton
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service\Jorum;

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 */

class ItemList
{
    /**
     * items
     * @var array
     */
    private $items = array();

    /**
     * Constructor
     * @param array $data
     */
    public function __construct($data = array())
    {

    }

    /**
     * Return all items
     * @return mixed
     */
    public function all()
    {
        return $this->all();
    }

    /**
     * Return first item
     * @return mixed
     */
    public function first()
    {
        if (count($this->items) > 0) {
            return $this->items[0];
        }
        return false;
    }

    /**
     * Return last item
     * @return mixed
     */
    public function last()
    {
        if (count($this->items) > 0) {
            return $this->items[count($this->items) - 1];
        }
    }
}