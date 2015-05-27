<?php
/**
 * Test Meta Class
 *
 */
namespace MIMAS\XmlSerialization\Tests;

use MIMAS\XmlSerialization\ClassMeta;

/**
 * Class ClassMetaTests
 * @package MIMAS\XmlSerialization\Tests
 */
class ClassMetaTests
{

    /**
     * test_getClassName
     */
    function test_getClassName()
    {
        $o = new Sample1;
        $meta = new ClassMeta($o);
        assert($meta->getClassName() == get_class($o));
    }

    /**
     * test_getNamespacetop hassling me for documentation
     */
    function test_getNamespace()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getNamespace() == __NAMESPACE__);
    }

    /**
     * test_getXmlRoot
     */
    function test_getXmlRoot()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getXmlRoot() == "Sample1");

        $meta = new ClassMeta(new Sample2);
        assert($meta->getXmlRoot() == "test-root");
    }

    /**
     * test_getAttributeName
     */
    function test_getAttributeName()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getAttributeName("attr1", "string") == "attr1");
        assert($meta->getAttributeName("attr2", "integer") == "attr-2");
        assert($meta->getAttributeName("attr2", "eprst") === null);
        assert($meta->getAttributeName("el1", "string") === null);
    }

    /**
     * test_getAttributeNames
     */
    function test_getAttributeNames()
    {
        $meta = new ClassMeta(new Sample1);
        $names = $meta->getAttributeNames();
        assert(count($names) == 2);
        assert(in_array("attr1", $names));
        assert(in_array("attr-2", $names));
    }

    /**
     * test_getAttributeNamesForProperty
     */
    function test_getAttributeNamesForProperty()
    {
        $meta = new ClassMeta(new Sample1);

        $names = $meta->getAttributeNamesForProperty("el1");
        assert(is_array($names));
        assert(count($names) == 0);

        $names = $meta->getAttributeNamesForProperty("attr2");
        assert(count($names) == 1);
        assert($names[0] == "attr-2");

        $meta = new ClassMeta(new Sample2);

        $names = $meta->getAttributeNamesForProperty("prop");
        assert(count($names) == 2);
        assert(in_array("a1", $names));
        assert(in_array("a2", $names));
    }

    /**
     * test_getElementName
     */
    function test_getElementName()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getElementName("el1", "string") == "el1");
        assert($meta->getElementName("el2", "boolean") == "el-2");
        assert($meta->getElementName("el2", "eprst") === null);
        assert($meta->getElementName("attr1", "string") === null);
    }

    /**
     * test_getElementNames
     */
    function test_getElementNames()
    {
        $meta = new ClassMeta(new Sample1);
        $names = $meta->getElementNames();
        assert(count($names) == 2);
        assert(in_array("el1", $names));
        assert(in_array("el-2", $names));
    }

    /**
     * test_getElementNamesForProperty
     */
    function test_getElementNamesForProperty()
    {
        $meta = new ClassMeta(new Sample1);

        $names = $meta->getElementNamesForProperty("attr1");
        assert(is_array($names));
        assert(count($names) == 0);

        $names = $meta->getElementNamesForProperty("el2");
        assert(count($names) == 1);
        assert($names[0] == "el-2");

        $meta = new ClassMeta(new Sample2);

        $names = $meta->getElementNamesForProperty("prop");
        assert(count($names) == 2);
        assert(in_array("e1", $names));
        assert(in_array("e2", $names));
    }

    /**
     * test_getPropertyNameForAttribute
     */
    function test_getPropertyNameForAttribute()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getPropertyNameForAttribute("attr1") == "attr1");
        assert($meta->getPropertyNameForAttribute("attr-2") == "attr2");
        assert($meta->getPropertyNameForAttribute("eprst") === null);
    }

    /**
     * test_getPropertyNameForElement
     */
    function test_getPropertyNameForElement()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getPropertyNameForElement("el1") == "el1");
        assert($meta->getPropertyNameForElement("el-2") == "el2");
        assert($meta->getPropertyNameForElement("eprst") === null);
    }

    /**
     * test_getPropertyTypeForAttribute
     */
    function test_getPropertyTypeForAttribute()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getPropertyTypeForAttribute("attr1") == "string");
        assert($meta->getPropertyTypeForAttribute("attr-2") == "integer");
        assert($meta->getPropertyTypeForAttribute("eprst") === null);
    }

    /**
     * test_getPropertyTypeForElement
     */
    function test_getPropertyTypeForElement()
    {
        $meta = new ClassMeta(new Sample1);
        assert($meta->getPropertyTypeForElement("el1") == "string");
        assert($meta->getPropertyTypeForElement("el-2") == "boolean");
        assert($meta->getPropertyTypeForElement("eprst") === null);
    }

    /**
     * test_getPropertyValue
     */
    function test_getPropertyValue()
    {
        $o = new Sample1;
        $meta = new ClassMeta($o);
        $o->set("attr1", "test123");
        assert($meta->getPropertyValue($o, "attr1") == "test123");
    }

    /**
     * test_setPropertyValue
     */
    function test_setPropertyValue()
    {
        $o = new Sample1;
        $meta = new ClassMeta($o);
        $meta->setPropertyValue($o, "attr2", "abc");
        assert($o->get("attr2") == "abc");
    }

    /**
     * test_DuplicateAttribute
     */
    function test_DuplicateAttribute()
    {
        shit_must_happen(function () {
            new ClassMeta(new Sample3);
        });
    }

    /**
     * test_DuplicateElement
     */
    function test_DuplicateElement()
    {
        shit_must_happen(function () {
            new ClassMeta(new Sample4);
        });
    }

    /**
     * test_DuplicateNameTypePair
     */
    function test_DuplicateNameTypePair()
    {
        shit_must_happen(function () {
            new ClassMeta(new Sample5);
        });
    }

    /**
     * test_StaticProperty
     */
    function test_StaticProperty()
    {
        shit_must_happen(function () {
            new ClassMeta(new Sample6);
        });
    }

}