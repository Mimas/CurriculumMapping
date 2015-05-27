<?php
/**
 * MIMAS API
 *
 * Collection
 *
 * A Collection of stuff that can be JSON serialised and can be iterated
 *
 * @package      MIMAS
 * @subpackage
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 * @version      0.9.0
 *
 * @todo This needs to change to something like List in order to avoid confusion with DSPACE Collection
 */
namespace MIMAS;

/**
 * Class Collection
 * @package MIMAS
 */
class Collection implements \JsonSerializable, \IteratorAggregate
{
    /**
     * The items contained in the collection.
     *
     * @var array
     */
    protected $items = array();

    /**
     * Constructor with array of items
     *
     * @param array $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        return value($default);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get the last item from the collection.
     *
     * @return mixed|null
     */
    public function last()
    {
        return count($this->items) > 0 ? end($this->items) : null;
    }

    /**
     * Put an item in the collection by key.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof ArrayableInterface ? $value->toArray() : $value;

        }, $this->items);
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }


    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Output HTML
     * @return void
     */
    public function toHtml()
    {
        $this->getContext()->toHtml();

        foreach ($this->items as $item) {
            echo $item->toHtml();
            echo '<hr/>';
        }
    }

    /**
     * Function gets called when encoded with json_encode
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


}