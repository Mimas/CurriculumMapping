<?php
/**
 * Unit test
 */
namespace MIMAS\XmlSerialization\Tests;

/** Bad: duplicate name-type pair */
class Sample5
{

    /**
     * Private a
     * @XmlElement(int, test)
     * @XmlAttribute(int, test)
     */
    private $a;

}