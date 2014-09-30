<?php
/**
 * UNIT Test 6
 */
namespace MIMAS\XmlSerialization\Tests;

/** Bad: static property */
class Sample6
{

    /**
     * var a
     * @XmlElement
     */
    private static $a;

}
