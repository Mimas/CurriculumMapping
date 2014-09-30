<?php
/**
 * Jorum XML Serialization
 *
 * Jorum API XML serialization - Class Meta Store
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\XmlSerialization;

/**
 * Class ClassMetaStore
 * @package MIMAS\XmlSerialization
 */
class ClassMetaStore
{
    /**
     * instance
     * @var
     */
    private static $instance;

    /**
     * Getter method
     * @return ClassMetaStore
     */
    static function get()
    {
        if (!self::$instance)
            self::$instance = new ClassMetaStore();
        return self::$instance;
    }

    /**
     * Setter
     * @param ClassMetaStore $store
     */
    static function set(ClassMetaStore $store)
    {
        self::$instance = $store;
    }

    /**
     * getMeta
     * @param $class
     * @return ClassMeta
     */
    static function getMeta($class)
    {

        if (is_object($class)) {
            $class = get_class($class);

        }
        $store = self::get();
        $meta = $store->getMetaCore($class);
        if (!$meta) {
            $meta = new ClassMeta($class);
            $store->registerMeta($meta);
        }
        return $meta;
    }

    /**
     * Data
     * @var array
     */
    protected $data = array();

    /**
     * getMetaCore
     * @param $className
     * @return null
     */
    protected function getMetaCore($className)
    {
        $key = $this->getKey($className);

        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        return null;
    }

    /**
     * registerMeta
     * @param ClassMeta $meta
     */
    protected function registerMeta(ClassMeta $meta)
    {
        $key = $this->getKey($meta->getClassName());
        $this->data[$key] = $meta;
    }

    /**
     * getKey
     * @param $className
     * @return string
     */
    protected function getKey($className)
    {
        return ltrim(strtolower($className), "\\");
    }

}