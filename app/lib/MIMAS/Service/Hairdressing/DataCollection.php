<?php
/**
 * Jorum API Context
 *
 * DataCollection
 *
 * Holds a list of Items
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Jorum;

/**
 *
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 *
 */
class DataCollection extends \MIMAS\Collection
{
    /**
     * Context object
     * @var \MIMAS\Service\Context
     */
    private $context;

    /**
     * Items array
     * @var array
     */
    protected $items = array();

    /**
     * Constructor
     *
     * @param array $items
     * @param null $context
     */
    public function __construct(array $items = array(), $context = null)
    {
        parent::__construct($items);
        $this->setContext(new \MIMAS\Service\Jorum\Context($context));
    }

    /**
     * Get context
     * @return \MIMAS\Service\Jorum\Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set context
     * @param \MIMAS\Service\Jorum\Context $context
     * @return \MIMAS\Service\Jorum\Context
     */
    public function setContext(\MIMAS\Service\Jorum\Context $context)
    {
        return $this->context = $context;
    }

    /**
     * Set items
     * @param array $items
     */
    public function setItems($items = array())
    {
        $this->items = $items;
    }

    /**
     * Get items
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * findByIdOrHandle
     * @param string $id
     * @return \MIMAS\Service\Jorum\Collection | \MIMAS\Service\Jorum\Item
     */
    public function findByIdOrHandle($id = '')
    {
        foreach ($this->items as $i => $object) {
            if ($object->getId() == $id || $object->getHandle() == $id) {
                return $object;
            }
        }
    }


}