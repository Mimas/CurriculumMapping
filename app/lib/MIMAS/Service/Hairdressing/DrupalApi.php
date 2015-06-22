<?php
/**
 * Hairdressing API
 *
 * Base class for HT implementations of API elements
 *
 * Base class for Item, Community, Collection, Bitstream and Metadata
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing;

/**
 *
 * Created by PhpStorm.
 * User: pedro
 * Date: 02/06/2014
 * Time: 14:03
 */
use MIMAS\Service\Context;

/**
 * Class DatabaseApi
 * @package MIMAS\Service\Hairdressing
 */
class DrupalApi implements \MIMAS\Service\RepositoryInterface, \JsonSerializable, \IteratorAggregate
{
  /**
   * servicePoint
   * @var string $servicePoint
   */
  public static $servicePoint = "/hairdressing/api";

  /**
   * model
   * @var string
   */
  protected static $model = '';

  /**
   * expandRequested
   * @var array
   */
  private $expandRequested = array();

  /**
   * extra options e.g. limit and offset
   * @var array
   */
  private $options = array();

  /**
   * DOUBLIN_CORE prefix
   * @todo: read from DoublicCore\Core.php
   *
   */
  const DOUBLIN_CORE = 'field_dc_';

  /**
   * Guess and set the model
   */
  public static function setModel()
  {
    $classArray = explode('\\', get_called_class());

    // one of Item, Collection, Community
    if (is_array($classArray) && count($classArray) > 0) {
      self::$model = ucfirst(strtolower($classArray[count($classArray) - 1]));
    }
  }

  /**
   * Constructor
   * @param string $params
   * @param array $expand
   * @param array $options
   */
  public function __construct($params = '', $expand = array(), $options = array())
  {
    self::setModel();

    // set options e.g. offset and limit
    $this->setOptions($options);

    $this->setExpandRequested($expand);

    /**
     * Populate with data passed
     */
    if (is_array($params)) {
      foreach ($params as $key => $value) {
        if ($key == 'ID') { // XML returns the id in Upper Case.
          $key = 'id';
        }

        /*
         * Below we check if the property exists. If it's a standard property such as 'title' then simply set it
         * If the property is a reference to a collection of known classes then instantiate them and push in their data
         * If it's a collection of METADATA then instantiate a Metadata object and create the pseudo attributes
         */
        if (property_exists($this, $key)) {
          $singKey = $key <> 'metadata' ? str_singular($key) : 'metadata'; // this is to avoid the conversion of metadata to metadatum
          $className = '\\MIMAS\\Service\\Hairdressing\\' . studly_case($singKey); // studly case means 'some_array' gets converted 'SomeArray'

          // right. if this is an object (i.e. a Bitstream or Metadata) then create it
          if (class_exists(studly_case($className))) {
            /**
             * @todo: expand metadata etc
             * @todo: expand ALL
             */
            // metadata, bitstreams, collections etc...
            //  $this->$key = self::factory($className, $key, $value);

          } else { // otherwise, set it as raw data e.g. as string
            $this->$key = $value;
          }
        }
      }
    }

  }

  /**
   * Create a list of objects
   * @param $className
   * @param $key
   * @param $value
   */
  public static function factory($className, $key, $value)
  {
  }

  /**
   * PHP magic function for unresolved metjod calls
   * Used only for testing
   * @param $name
   * @param $arguments
   */
  public function __call($name, $arguments)
  {

  }

  /**
   * Find a record by id or handle. Handle is in the form of 'node/id'
   *
   * @param string $id
   * @param array $options
   * @return mixed|void
   */
  public function findByIdOrHandle($id = '', $options = array())
  {
    $result = Db\Models\Node::where('nid', '=', $id)->get();
    if ($result->getItems() > 0) {
      $items = $result->getItems();

      $class = get_called_class();

      $model = new $class(
        (array)$items[0],
        $options
      );
      return $model;

    }
    return null;
  }

  /**
   * Return number of siblings for depth / depths
   * @param $mlid
   * @param int $p
   * @param array $levels
   * @return int
   */
  public static function getSiblingsCount($mlid, $p = 1, $levels = array())
  {
    if (is_string($levels) || is_numeric($levels)) {
      $levels = array($levels);
    }

    if (!empty($levels)) {
      $depth = implode(',', $levels);
      $sql = "select count(*) as kids from menu_links where p{$p}={$mlid} and depth in ({$depth}) and hidden=0";
    } else {
      $sql = "select count(*) as kids from menu_links where p{$p}={$mlid} and hidden=0";
    }

    $result = \DB::select($sql);

    if (count($result) > 0) {
      return (int)$result[0]->kids;
    }
    return 0;
  }

  /**
   * Return 'all'
   * It is possible to supply a list of fields to be returned.
   * If not, then all fields are selected - see if tht works
   *
   * @param array $fields
   * @return mixed|void
   */
  public function all($fields = array())
  {
    $class = '\MIMAS\Service\Hairdressing\\' . self::$model;

    if (self::$model == 'Item') {
      /************************************ ITEM **********************************************************/
      $statement = Db\Models\Node::where('nid', 'in', " (select replace(link_path, 'node/', '') from menu_links where hidden=0 and menu_name='primary-links' and depth >2 order by plid,p1,p2,p3)");

      $statement->select($fields);

      if (isset($this->options['offset'])) {
        $statement->offset($this->options['offset']);
      }

      if (isset($this->options['limit'])) {
        $statement->limit($this->options['limit']);
      }

      $result = $statement->get();

      $context = new Context(
        array(
          'totalCount' => count($result->getItems()),
          'offset' => Db\Models\Node::getOffset(),
          'limit' => Db\Models\Node::getLimit(),
          'query' => Db\Models\Node::getQuery(),
          'queryDate' => Db\Models\Node::getQueryDateTime(),
        )
      );

      $data = array();
      foreach ($result->getItems() as $item) {
        $item = new Item(array_merge((array)$item, array('expand' => array_diff(static::$expandable, $this->expandRequested))), $this->expandRequested);
        $data[] = $item;
      }

    } else {
      /******************************************** COMMUNITY or COLLECTION *********************************************/
      $depth = 0;
      if (self::$model == 'Community') {
        $depth = 1;
      } elseif (self::$model == 'Collection') {
        $depth = 2;
      }
      /*** Run query ***/
      $result = Db\Models\MenuLink::where('menu_name', '=', "'primary-links'")
        ->andWhere('depth', '=', $depth)
        ->andWhere('hidden', '=', 0)
        ->andWhere('has_children', '=', 1)
        ->get();

      $context = new Context(
        array(
          'totalCount' => count($result->getItems()),
          'offset' => Db\Models\MenuLink::getOffset(),
          'limit' => Db\Models\MenuLink::getLimit(),
          'query' => Db\Models\MenuLink::getQuery(),
          'queryDate' => Db\Models\Node::getQueryDateTime(),
        )
      );

      foreach ($result->getItems() as $item) {
        $model = new $class();
        $model->id = str_replace('node/', '', $item->link_path); // Drupal clever non-link 'node/1'=>1. Why?
        $model->handle = $item->link_path;
        $model->link = self::$servicePoint . '/' . $model->type . '/' . $model->id;
        $model->name = $item->link_title;

        $model->expand = array_diff(static::$expandable, $this->expandRequested);

        $model->countItems = self::getSiblingsCount($item->mlid, $depth, array(3, 4));

        //  "metadata", "collections", "parentCommunity", "subCommunities", "logo", "all"
        foreach ($this->expandRequested as $expand) {
          $method = "populate" . ucfirst($expand);
          $model->$method();
        }

        $data[] = $model;
      }

    }

    $ret = new \MIMAS\Service\DataCollection($data, $context);
    return $ret;
  }

  /**
   * Return all 'valid' handles, i.e. the 'permanent' IDs of all live items
   * This was constructed to support Rob Tice's request for a means to remove stuff form the index
   * since neither HR nor Jorum use the withdrawn flag
   *
   * @return array
   */
  public static function allHandles()
  {
    // set the model
    self::setModel();

    if (self::$model == 'Item') {
      $id = 'nid';
      $statement = Db\Models\Node::where('nid', 'in', " (select replace(link_path, 'node/', '') from menu_links where hidden=0 and menu_name='primary-links' and depth >2 order by plid,p1,p2,p3)");

    } else {
      $id = 'mlid';
      $depth = 0;
      if (self::$model == 'Community') {
        $depth = 1;
      } elseif (self::$model == 'Collection') {
        $depth = 2;
      }

      $statement = Db\Models\MenuLink::where('menu_name', '=', "'primary-links'")
        ->andWhere('depth', '=', $depth)
        ->andWhere('hidden', '=', 0)
        ->andWhere('has_children', '=', 1);

    }
    $columns = array($id);

    $statement->select($columns);
    $result = $statement->get(true);

    $ret = array();
    foreach ($result as $i => $object) {
      $ret[] = 'node/' . $object->$id;
    }

    return $ret;
  }

  /**
   * Get the array of expandable attributes
   * @return array
   */
  public function getExpand()
  {
    return $this->expand;
  }

  /**
   * Set expand
   * @param mixed $expand
   * @param bool $reset
   */
  public function setExpand($expand = array(), $reset = false)
  {
    if (is_array($expand)) {
      $this->expand = $expand;
    } elseif (is_string($expand)) {
      if ($reset) {
        $this->expand = array();
      }
      $this->expand[] = $expand;
    }
  }

  /**
   * Return HTML for pretty printing
   * @todo: implement
   */
  public function toHtml()
  {
    $ret = "";
    foreach (get_object_vars($this) as $key => $val) {
      if (!is_array($val))
        $ret .= "<span class=\"fieldname\">$key</span>=><span class=\"fieldvalue\">$val</span><br/>
      ";
    }
    return $ret;
  }

  /**
   * Return this as an array of vars to allow JSON serialization
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
   * Set $expandRequested
   * @param array $expand
   */
  public function setExpandRequested($expand = array())
  {
    $this->expandRequested = $expand;
  }

  /**
   * Get $expandRequested
   * @return array $expandRequested
   */
  public function getExpandRequested()
  {
    return $this->expandRequested;
  }

  /**
   * Set options i.e. limit and offset
   * @param $options
   */
  public function setOptions($options)
  {
    $this->options = $options;
  }

  /**
   * Get options i.e. limit and offset
   * @return array $options
   */
  public function getOptions()
  {
    return $this->options;
  }

}