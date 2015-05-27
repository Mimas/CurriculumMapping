<?php
/**
 * HT API
 *
 * Drupal 6.x Access
 *
 * This is the base class from which all the models derive
 * It creates a static instance of the model if one doesn't exist
 * Essentially it mimics the various Query Builders one can see in established frameworks such as Laravel
 * and ORMs such as Doctrine.
 *
 * This class does NOT write to the database, therefore do not expect a ->save() or ->delete() method
 * It is only here to minimise dependencies to external ORMs but you are quite welcome to bypass it and feed
 * your API directly from whatever you are using.
 *
 * It does use an instance of an SQLManager, which in turn uses something to execute teh SQL. I am using LARAVEL's
 * underlying SQL Manager but this is an injected dependency which means that you can use anything that
 * implements an SQLManager interface and can somehow execute the queries - preferably with prepared statements
 * and some sanity checks.
 *
 * To create an instance of a model call it like
 *
 * $node = new Node(array('nid'=>249,'title'=>'A Marvellous Node',...);
 *
 * To perform a single find do something like
 *
 * $file = File::find(234);
 *
 * The use of magic functions in this mini Active Record implementation has been avoided. All models explicitly
 * declare their attributes so that you get code hinting in your IDE
 *
 * To find a list of stuff you can do something like
 *
 *
 *
 *   $node = Node::where('nid', '<', 20)
 *               ->orWhere('nid','=', 100)
 *               ->orderBy('nid', 'DESC')
 *               ->get();
 *
 * This should return a list of nodes.
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing\Db;

/**
 * Class JorumDbModel
 * @package MIMAS\Service\Hairdressing\Db
 *
 * @todo add offset and limit
 *
 */
class JorumDbModel
{
    /**
     * Class
     * @var string $class
     */
    protected static $class = '';

    /**
     * Primary Key PK
     * @var string $primaryKey
     */
    protected static $primaryKey = 'id';

    /**
     * instance
     * @var $instance
     */
    protected static $instance = array();

    /**
     * Fields to select, default is all
     * @var string
     */
    protected static $select = "*";

    /**
     * where array
     * @var array
     */
    protected static $where = array();

    /**
     * orWhere array
     * @var array
     */
    protected static $orWhere = array();

    /**
     * orderBy array
     * @var array
     */
    protected static $orderBy = array();

    /**
     * groupBy array
     * @var array
     */
    protected static $groupBy = array();

    /**
     * Limit
     * @var int $limit
     */
    protected static $limit = 0;

    /**
     * Offset
     * @var int $offset
     */
    protected static $offset = 0;

    /**
     * MIMAS\Collection $items
     * @var MIMAS\Collection $items
     */
    protected $items;

    /**
     * Query string
     * @var string
     */
    protected static $query = '';

    /**
     * Query time in a standard format
     * @var string
     */
    protected static $queryDateTime = '1969-03-31T15:19:21+00:00';


    /**
     * Constructor
     * @param $attributes
     */
    public function __construct($attributes)
    {
        $this->setClass(get_called_class());

        foreach ($attributes as $var => $val) {
            $this->$var = $val;
        }
    }

    /**
     * Get the QUERY string
     * @return string
     */
    public static function getQuery()
    {
        return self::$query;
    }

    /**
     * Set the QUERY string
     * @param $query
     */
    public static function setQuery($query)
    {
        self::$query = $query;
    }

    /**
     * Get the QUERY string
     * @return string $queryDateTime
     */
    public static function getQueryDateTime()
    {
        return self::$queryDateTime;
    }

    /**
     * Set the QUERY string
     * @param $queryDateTime
     */
    public static function setQueryDateTime($queryDateTime)
    {
        self::$queryDateTime = $queryDateTime;
    }


    /**
     * Sets class variable
     * @param string $class
     */
    public static function setClass($class)
    {
        self::$class = $class;
    }

    /**
     * Gets class var
     * @return string
     */
    public static function getClass()
    {
        return self::$class;
    }

    /**
     * Sets groupBy
     * @param array $groupBy
     */
    public static function setGroupBy($groupBy)
    {
        self::$groupBy = $groupBy;
    }

    /**
     * Gets groupBy
     * @return array
     */
    public static function getGroupBy()
    {
        return self::$groupBy;
    }

    /**
     * Sets the orWhere array
     * @param array $orWhere
     */
    public static function setOrWhere($orWhere)
    {
        self::$orWhere = $orWhere;
    }

    /**
     * gets the orWhere array
     * @return array
     */
    public static function getOrWhere()
    {
        return self::$orWhere;
    }

    /**
     * Sets the orderBy
     * @param array $orderBy
     */
    public static function setOrderBy($orderBy)
    {
        self::$orderBy = $orderBy;
    }

    /**
     * Gets orderBy
     * @return array
     */
    public static function getOrderBy()
    {
        return self::$orderBy;
    }

    /**
     * Sets Primary Key
     * @param string $primaryKey
     */
    public static function setPrimaryKey($primaryKey)
    {
        self::$primaryKey = $primaryKey;
    }

    /**
     * Get Primary Key
     * @return string
     */
    public static function getPrimaryKey()
    {
        return self::$primaryKey;
    }

    /**
     * Sets WHERE
     * @param array $where
     */
    public static function setWhere($where)
    {
        self::$where = $where;
    }

    /**
     * Gets WHERE
     * @return array
     */
    public static function getWhere()
    {
        return self::$where;
    }

    /**
     * Get limit
     * @return int $limit
     */
    public static function getLimit()
    {
        return (int)self::$limit;
    }

    /**
     * Get offset
     * @return int $offset
     */
    public static function getOffset()
    {
        return (int)self::$offset;
    }

    /**
     * Set offset
     * @param $offset
     * @return mixed
     */
    public static function offset($offset)
    {
        if (false) {
            \PhpConsole\Handler::getInstance()->debug('SQL', "Offset set to $offset");
        }

        self::$offset = $offset;
        return self::getInstance();
    }

    /**
     * Set limit
     * @param $limit
     * @return mixed
     */
    public static function limit($limit)
    {
        if (false) {
            \PhpConsole\Handler::getInstance()->debug('SQL', "Limit set to $limit");
        }

        self::$limit = $limit;
        return self::getInstance();
    }

    /**
     * Create a new instance of this if one doesn't already exist
     * Return the instance
     * @param $data
     * @return mixed
     */
    public static function getInstance($data = array())
    {
        $class = get_called_class();
        if (self::$class <> $class) {
            self::$instance[$class] = new $class($data);
        }
        return self::$instance[$class];
    }

    /**
     * Sets the fields to be selected e.g 'id, name, email'
     *
     * @param array $selectFields
     * @return mixed
     */
    public static function select($selectFields = array())
    {
        if (is_array($selectFields)) {
            $selectFields = implode(',', $selectFields);
        }
        if ($selectFields <> '') {
            self::$select = $selectFields;
        }

        return self::getInstance();
    }

    /**
     * Set initial $where
     *
     * @param $column
     * @param $operand
     * @param $value
     * @return mixed
     * @todo write Unit Tests
     *
     */
    public static function where($column, $operand, $value)
    {
        self::$where = array();
        self::$where[] = array('column' => $column, 'operand' => $operand, 'value' => $value);
        return self::getInstance();
    }

    /**
     * Add to the $orWhere list of parameters
     *
     * @param $column
     * @param $operand
     * @param $value
     * @return mixed
     * @todo write Unit Tests
     *
     */
    public static function orWhere($column, $operand, $value)
    {
        self::$orWhere[] = array('column' => $column, 'operand' => $operand, 'value' => $value);
        return self::getInstance();
    }

    /**
     * Add to the $where array ('title', 'like', 'Mario')
     * @param $column
     * @param $operand
     * @param $value
     * @return mixed
     * @todo write Unit Tests
     */
    public static function andWhere($column, $operand, $value)
    {
        self::$where[] = array('column' => $column, 'operand' => $operand, 'value' => $value);
        return self::getInstance();
    }

    /**
     * Add to the $orderBy list of parameters
     * @param $column
     * @param string $direction
     * @return mixed
     * @todo write Unit Tests
     */
    public static function orderBy($column, $direction = 'ASC')
    {
        self::$orderBy[] = array('column' => $column, 'direction' => $direction);
        return self::getInstance();
    }

    /**
     * Add to the list of columns to group by
     * @param $column
     *
     * @todo write Unit Tests
     *
     * @return mixed
     */
    public static function groupBy($column)
    {
        self::$groupBy[] = $column;
        return self::getInstance();
    }

    /**
     * Get ItemList
     * @return MIMAS\Collection
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * Construct a suitable SQL statement and pass it to our manager
     * Note that we don't do any pagination or anything here
     * PD
     *
     * @param bool $raw
     * @throws \Exception
     * @internal param $raw
     * @return mixed
     * @todo inject SQLManager dependency
     */
    public function get($raw = false)
    {
        // reset criteria for next query

        $instance = self::getInstance();

        if (!$instance) {
            // some horror here....
            throw new \Exception("No valid instance of a model given");
        }

        $sql = 'WHERE true ';

        // AND WHERE
        foreach (self::getWhere() as $key => $values) {
            $sql .= ' AND ' . $values['column'] . ' ' . $values['operand'] . ' ' . $values['value'];
        }
        $sql .= "\n";

        // OR WHERE
        foreach (self::getOrWhere() as $key => $values) {
            $sql .= ' OR ' . $values['column'] . ' ' . $values['operand'] . ' ' . $values['value'];
        }
        $sql .= "\n";

        // ORDER BY
        if (count(self::getOrderBy()) > 0) {
            $sql .= " ORDER BY ";
            $i = 0;
            foreach (self::getOrderBy() as $j => $orderBy) {
                $sql .= ($i > 0) ? " , {$orderBy['column']} {$orderBy['direction']}" : "  {$orderBy['column']} {$orderBy['direction']}";
            }
        }
        $sql .= "\n";

        if (count(self::getGroupBy()) > 0) {
            $sql .= " GROUP BY ";
            $i = 0;
            foreach (self::getGroupBy() as $j => $groupBy) {
                $sql .= ($i > 0) ? " , {$groupBy}" : "  {$groupBy}";
            }
            $sql .= "\n";
        }

        if (self::getLimit() > 0) {
            $sql .= ' limit ' . self::getOffset() . ',' . self::getLimit();
        }

        if (isset(static::$table)) {
            $table = static::$table;
        } else {
            $table = strtolower(self::$class);
        }

        $columns = self::$select;

        // store the query string
        self::setQuery("select {$columns} from {$table} $sql ");

        // store the query time
        self::setQueryDateTime(date('c'));

        if (false) {
            \PhpConsole\Handler::getInstance()->debug('SQL', self::getQuery());
        }

        // @todo: move into SQLManager Interface
        $result = \DB::select(self::getQuery());

        self::resetQueries();

        // return raw stuff
        if ($raw) {
            return $result;
        }

        if (count($result) > 0) {
            $instance = self::getInstance()->itemCollection();
            $instance->items = array();

            foreach ($result as $i => $data) {

                $instance->items[] = new self::$class($data);
            }

        }

        return self::getInstance();
    }

    /**
     * Resets the SQL criteria arrays
     */
    public static function resetQueries()
    {
        self::setWhere(array());
        self::setOrWhere(array());
        self::setGroupBy(array());
        self::setOrderBy(array());

    }

    /**
     * Remove the single item properites such as vid, nid etc
     * so that we can return a clean ItemList
     * @return mixed
     */
    public function itemCollection()
    {
        $reflect = new \ReflectionClass(get_called_class());

        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop) {
            $name = $prop->getName();
            unset(self::$instance->$name);
        }

        return self::getInstance();
    }


    /**
     * Find a single item by PK
     * @param int $id
     * @return mixed
     *
     * @todo write Unit Tests
     */
    public static function find($id = 0)
    {
        /**
         * Get the table from calling class or guess from the calling class' name
         */
        if (isset(static::$table)) {
            $table = static::$table;
        } else {
            $table = strtolower(self::$class);
        }

        if (isset(static::$primaryKey)) {
            $primaryKey = static::$primaryKey;
        } else {
            $primaryKey = self::$primaryKey;
        }

        // @todo: move into SQLManager Interface
        $result = \DB::select("select * from {$table} where {$primaryKey}='{$id}'");

        if (count($result) > 0) {
            unset (self::$instance[get_called_class()]); // ???!
            return self::getInstance((array)$result[0]);
        }

    }

}