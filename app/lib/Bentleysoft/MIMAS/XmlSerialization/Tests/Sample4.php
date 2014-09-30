<?php
/**
 * Unit test 4
 */
namespace MIMAS\XmlSerialization\Tests;

/** Bad: duplicate element */
class Sample4
{

    /**
     * a
     * @XmlElement(int, test)
     */
    private $a;

    /**
     * b
     * @XmlElement(string, test)
     */
    private $b;

}
