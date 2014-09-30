<?php
/**
 * Test Metastore
 */
namespace MIMAS\XmlSerialization\Tests;

use MIMAS\XmlSerialization\ClassMeta;
use MIMAS\XmlSerialization\ClassMetaStore;

/**
 * Class ClassMetaStoreTests
 * @package MIMAS\XmlSerialization\Tests
 */
class ClassMetaStoreTests
{
    /**
     * Stop hassling me for documentation
     */
    function test_1()
    {
        $class = __NAMESPACE__ . "\\Sample1";
        $meta = ClassMetaStore::getMeta($class);
        assert($meta->getClassName() == $class);
    }


}