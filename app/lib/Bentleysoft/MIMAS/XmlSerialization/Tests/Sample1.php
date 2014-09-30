<?php
/**
 * Unit test 1
 * namespace MIMAS\XmlSerialization;
 */
namespace MIMAS\XmlSerialization\Tests;

/**
 * Class Sample1
 * @package MIMAS\XmlSerialization\Tests
 */
class Sample1
{

    /**
     * private attr1
     * @XmlAttribute
     */
    private $attr1;

    /**
     * Private attr2
     * @XmlAttribute(int, attr-2)
     */
    private $attr2;

    /**
     * private el1
     * @XmlElement
     */
    private $el1;

    /**
     * Private el2
     *
     * @XmlElement(bool, el-2)
     */
    private $el2;

    /**
     * Test get
     * @param $name
     * @return mixed
     */
    function get($name)
    {
        return $this->$name;
    }

    /**
     * Test set
     * @param $name
     * @param $value
     */
    function set($name, $value)
    {
        $this->$name = $value;
    }

}
