<?php
/**
 * Jorum API Context
 *
 * Item
 *
 * Item held in Jorum
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service\Jorum;

/**
 * Class Item
 * @package MIMAS\Service\Jorum
 */
class Item extends JorumApi
{
  /**
   * A single Ite, within Jorum
   *
   * This is a long description.
   *
   * @Method({"GET", "POST"})
   */

  /**
   * id
   * @var string $id
   */
  protected $id = '';

  /**
   * handle
   * @var string $handle
   */
  protected $handle = '';

  /**
   * name
   * @var string $name
   */
  protected $name = '';

  /**
   * link
   * @var string $link
   */
  protected $link = '';

  /**
   * type
   * @var string $type
   */
  protected $type = '';

  /**
   * archived
   * @var bool $archived
   */
  protected $archived = false;

  /**
   * lastModified
   * @var string $lastModified
   */
  protected $lastModified = '1970-01-01 01:00:00';

  /**
   * withdrawn
   * @var bool $withdrawn
   */
  protected $withdrawn = false;

  /**
   * expand
   * @var array $expand
   */
  protected $expand = array();

  /**
   * metadata
   * @var array $metadata
   */
  protected $metadata = array();

  /**
   *  parentCollection
   * @var array $parentCollection
   */
  protected $parentCollection = array();

  /**
   * parentCommunityList
   * @var array $parentCommunityList
   */
  protected $parentCommunityList = array();

  /**
   * bitstreams
   * @var array $bitstreams
   */
  protected $bitstreams = array();

  /**
   * Constructor. See base class for details
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
   * Get handle
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
   * Get archived
   * @return string
   */
  public function getArchived()
  {
    return $this->archived;
  }

  /**
   * Set archived
   * @param bool $archived
   */
  public function setArchived($archived = false)
  {
    $this->archived = $archived;
  }

  /**
   * Get lastModified
   * @return string
   */
  public function getLastModified()
  {
    return $this->lastModified;
  }

  /**
   * Set lastModified
   * @param string $lastModified
   */
  public function setLastModified($lastModified = '')
  {
    $this->lastModified = $lastModified;
  }

  /**
   * Get withdrawn
   * @return string
   */
  public function getWithdrawn()
  {
    return $this->withdrawn;
  }

  /**
   * Set withdrawn
   * @param bool $withdrawn
   */
  public function setWithdrawn($withdrawn = false)
  {
    $this->withdrawn = $withdrawn;
  }

  /**
   * Get expand
   * @return array
   */
  public function getExpand()
  {
    return $this->expand;
  }

  /**
   * Set expand
   * @param array $expand
   */
  public function setExpand($expand = array())
  {
    $this->expand = $expand;
  }

  /**
   * Get metadata
   * @return \MIMAS\Service\Jorum\Metadata metadata
   */
  public function getMetadata()
  {
    return $this->metadata;
  }

  /**
   * Set metadata
   * @param $metadata
   */
  public function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }

  /**
   * Wrapper method for findByIdOrHandle
   *
   * @param string $id
   * @param array $options
   * @param string $outputFormat
   * @param string $inputFormat
   * @return \MIMAS\Service\Jorum\Item $item
   */
  public static function find($id = '', $options = array(), $outputFormat = '', $inputFormat)
  {
    $item = new Item($outputFormat, $inputFormat);
    return $item->findByIdOrHandle($id, $options);
  }

  /**
   * Set bitstreams
   * @param array $bitStreams
   */
  public function setBitstreams($bitStreams = array())
  {
    $this->bitstreams = $bitStreams;
  }

  /**
   * Get bitstreams
   * @return array
   */
  public function getBitstreams()
  {
    return $this->bitstreams;
  }

}