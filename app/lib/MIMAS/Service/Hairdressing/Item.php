<?php
/**
 * Hairdressing API
 *
 * Item
 *
 * Single Item held in HT
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing;

use MIMAS\Service\Hairdressing\Db\Models\ContentTypePage;
use MIMAS\Service\Hairdressing\Db\Models\MenuLink;

/**
 *
 * Class Item
 * @package MIMAS\Service\Hairdressing
 *
 */
class Item extends DrupalApi
{
  /**
   * Id
   * @var id
   */
  protected $id;

  /**
   * handle
   * @var
   */
  protected $handle;

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
  protected $type = 'item';

  /**
   * archived
   * @var bool
   */
  protected $archived = false;

  /**
   * withdrawn
   * @var bool
   */
  protected $withdrawn = false;

  /**
   * Last modified in the form '2013-03-11 11:12:29.419'
   * @var string
   */
  protected $lastModified = '';

  /**
   * Metadata
   * @var mixed
   */
  protected $metadata;

  /**
   *  parentCollection
   * @var array $parentCollection
   */
  protected $parentCollection = null;

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
   * parameters
   * @var string
   */
  private $params;

  /**
   * Expandable
   * @var array
   */
  public static $expandable = array('metadata',
    'parentCollection',
    'parentCollectionList',
    'parentCommunityList',
    'bitstreams',
    'all');

  /**
   * $expand - list of attributes that can be expanded. expandable-expanded
   * @var array
   */
  public $expand = array();

  /**
   * Constructor. See base class for use of params
   * @see DrupalApi
   * @param string $params
   * @param array $expands
   * @param array $options
   *
   */
  public function __construct($params = '', $expands = array(), $options = array())
  {
    parent::__construct($params, $expands, $options);

    if (isset($params['nid'])) {
      $this->id = $params['nid'];
      $this->handle = 'node/' . $this->id;

      $this->link = '/hairdressing/api/item/' . $this->id;

    }
    if (isset($params['title'])) {
      $this->name = $params['title'];
    }

    if (isset($params['type'])) {
      $this->type = $params['type'];
    }

    if (isset($params['changed'])) {
      $this->lastModified = date('Y-m-d H:i:s\.02', $params['changed']);
    }

    if (isset($params['expand'])) {
      $this->expand = $params['expand'];
    }

    //  "metadata", "collections", "parentCommunity", "subCommunities", "logo", "all"
    foreach ($expands as $expand) {
      $method = "populate" . ucfirst($expand);
      $this->$method();
    }
    if (false) {   // uncomment when you get a minute to unit test etc
      $bitstream = new Bitstream;
      $bitstream->setBundleName('URL_BUNDLE');
      $this->setBitstreams(array($bitstream));
    }
  }

  /**
   * Getter for ID
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Setter for ID
   * @param string $id
   */
  public function setId($id = '')
  {
    $this->id = $id;
  }

  /**
   * Getter for handle
   * @return string
   */
  public function getHandle()
  {
    return $this->handle;
  }

  /**
   * Setter for handle
   * @param string $handle
   */
  public function setHandle($handle = '')
  {
    $this->handle = $handle;
  }

  /**
   * Get params
   * @return mixed | string
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * Wrapper for findByIdOrHandle
   * @param string $id
   * @param array $options
   * @internal param string $outputFormat
   * @internal param string $inputFormat
   * @return \MIMAS\Service\Jorum\Item $item
   */
  public static function find($id = '', $options = array())
  {
    $item = new Item();
    return $item->findByIdOrHandle($id, $options);
  }

  /**
   * Find and set the parent collection
   */
  public function populateParentCollection()
  {
    $handle = $this->handle;
    /**
     * p2 for immediate parent in Deerupal
     *
     */
    $menuLink = MenuLink::where('mlid', '=', "(select p2 from menu_links where link_path='{$handle}')")->get();

    if ($menuLink->getItems() > 0) {
      $item = $menuLink->getItems()[0];

      $id = str_replace('node/', '', $item->link_path);

      $collection = new \MIMAS\Service\Hairdressing\Collection(array(
        'id' => $id,
        'handle' => $item->link_path,
        'link' => self::$servicePoint . '/' . 'collection' . '/' . $id,
        'name' => $item->link_title,
      ));

      $this->parentCollection = $collection;
    }
  }

  /**
   * Find the extra fields and map them to the metadata
   */
  public function populateMetadata()
  {
    $nid = str_replace('node/', '', $this->handle);
    $contentTypePage = ContentTypePage::where('nid', '=', "'$nid'")->get();

    if ($contentTypePage->getItems() > 0) {

      $item = $contentTypePage->getItems()[0];
      $metaData = new \MIMAS\Service\Hairdressing\Metadata((array)$item);

      $this->metadata = $metaData;
    }
  }

  /**
   * Find and set list of parent collections
   */
  public function populateParentCollectionList()
  {

  }

  /**
   * Get list of expandable attributes for this class
   * @return array
   */
  public function getExpandable()
  {
    return self::$expandable;
  }

  /**
   * Set expandable
   * @param array $expandable
   */
  public function setExpandable($expandable = array())
  {
    self::$expandable = $expandable;
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