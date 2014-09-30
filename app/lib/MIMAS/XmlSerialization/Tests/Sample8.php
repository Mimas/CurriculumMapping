<?php
/**
 *  Unit test 8
 *
 */
namespace MIMAS\XmlSerialization\Tests;

/**
 * Class Sample8
 * @package MIMAS\XmlSerialization\Tests
 */
class Sample8
{

    /**
     * element
     * @XmlElement
     * @XmlElement(bool, element-bool)
     */
    public $element = "xyz";

    /**
     * attr
     * @XmlAttribute(int)
     */
    public $attr = 5;

}
