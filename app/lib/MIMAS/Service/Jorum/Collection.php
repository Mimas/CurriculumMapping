<?php
/**
 * Jorum API Collection
 *
 * Collection
 *
 * A collection of Items. A collection cannot contain collections, however a community can
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
class Collection extends JorumApi
{
    /**
     * Id
     * @var string
     */
    protected $id = '';

    /**
     * handle
     * @var string
     */
    protected $handle = '';

    /**
     * name
     * @var string
     */
    protected $name = '';

    /**
     * type
     * @var string
     */
    protected $type = '';

    /**
     * link
     * @var string
     */
    protected $link = '';

    /**
     * Expand array (e.g. metadata, all)
     * @var array $expand
     */
    protected $expand = array();

    /**
     * logo
     * @var string
     */
    protected $logo = '';

    /**
     * parentCommunity
     * @var array $parentCommunity
     */
    protected $parentCommunity = array();

    /**
     * items
     * @var array $ite ,s
     */
    protected $items = array();

    /**
     * license
     * @var string
     */
    protected $license = '';


    /**
     * numberItems
     * @var int $numberItems
     */
    protected $numberItems = 0;

    /**
     * Constructor. Data or format passed to Base model
     *
     * @param string $params
     * @param string $inputFormat
     * @param array $options
     */
    public function __construct($params = '', $inputFormat = 'application/json', $options = array())
    {
        parent::__construct($params, $inputFormat, $options);
    }

    /**
     * Get id
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     * @param string $id
     */
    public function setId($id = '')
    {
        $this->id = $id;
    }

    /**
     * get handl
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Set handle
     * @param string $handle
     */
    public function setHandle($handle = '')
    {
        $this->handle = $handle;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name = '')
    {
        $this->name = $name;
    }

    /**
     * Get type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     * @param string $type
     */
    public function setType($type = '')
    {
        $this->type = $type;
    }

    /**
     * Get link
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link
     * @param string $link
     */
    public function setLink($link = '')
    {
        $this->link = $link;
    }

    /**
     * Get expand array (e.g. (0=>'all',1=>'metadata')
     * @return array
     */
    public function getExpand()
    {
        return $this->expand;
    }

    /**
     * Set expand array (e.g. (0=>'all',1=>'metadata')
     * @param string $expand
     */
    public function setExpanded($expand = '')
    {
        $this->expand = $expand;
    }


    /**
     * Get logo
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logo
     * @param string $logo
     */
    public function setLogo($logo = '')
    {
        $this->logo = $logo;
    }

    /**
     * parentCommunity
     * @return string $parentCommunity
     */
    public function getParentCommunity()
    {
        return $this->parentCommunity;
    }

    /**
     * Set parentCommunity
     * @param string $parentCommunity
     */
    public function setParentCommunity($parentCommunity = '')
    {
        $this->parentCommunity = $parentCommunity;
    }

    /**
     * Get items
     * @return string
     */
    public function getItems()
    {
        return $this->items;
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
     * Set license
     * @param string $license
     */
    public function setLicense($license)
    {
        $this->license = $license;
    }

    /**
     * Get license
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Get numberItems
     * @return int $numberItems
     */
    public function getNumberItems()
    {
        return $this->numberItems;
    }

    /**
     * Set numberItems
     * @param $numberItems
     */
    public function setNumberItems($numberItems)
    {
        $this->numberItems = $numberItems;
    }

    /**
     * Wrapper for findByIdOrHandle
     *
     * @param string $id
     * @param array $options
     * @param string $outputFormat
     * @param string $inputFormat
     * @return \MIMAS\Service\Jorum\Item $item
     */
    public static function find($id = '', $options = array(), $outputFormat = '', $inputFormat)
    {
        $collection = new Collection($outputFormat, $inputFormat);
        return $collection->findByIdOrHandle($id, $options);
    }


}