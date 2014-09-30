<?php
/**
 * Unit test 9
 */
namespace MIMAS\XmlSerialization\Tests;

/**
 * Class Sample9
 * @package MIMAS\XmlSerialization\Tests
 */
class Sample9
{

    /**
     * child
     * @XmlElement(Sample9)
     */
    public $child;

    /**
     * str
     * @XmlElement
     */
    public $str;

}
