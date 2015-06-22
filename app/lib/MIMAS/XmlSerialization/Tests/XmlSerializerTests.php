<?php
/**
 * XML Serialization tests
 */
namespace MIMAS\XmlSerialization\Tests;

use MIMAS\XmlSerialization\XmlSerializer;

/**
 * Class XmlSerializerTests
 * @package MIMAS\XmlSerialization\Tests
 */
class XmlSerializerTests
{

    /**
     * test_serialize
     */
    function test_serialize()
    {
        $o = new Sample7;

        $o->dateAttr = new \DateTime("Jun 14, 1984");
        $o->dateElement = new \DateTime("2011-12-13 14:15:16");

        $o->array[] = 1;
        $o->array[] = "one";
        $o->array[] = new Sample8;

        $o->array2[] = "2";

        $o->collection[] = false;
        $o->collection[] = "True";
        $o->collection[] = new Sample8;

        $o->stack->push("A");
        $o->stack->push("B");

        $o->inner = new Sample8;

        $doc = XmlSerializer::serialize($o);

        $expected = $this->shrinkXml(file_get_contents(dirname(__FILE__) . "/sample7-1.xml"));
        $actual = $this->shrinkXml($doc->saveXML());

        assert($expected == $actual);
    }

    /**
     * test_unserialize
     */
    function test_unserialize()
    {


        $xml = file_get_contents(dirname(__FILE__) . "/sample7-2.xml");
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        /* @var $o Sample7 */
        $o = XmlSerializer::unserialize($doc, "Lexa\XmlSerialization\Tests\Sample7");

        assert($o instanceof Sample7);

        assert(!property_exists($o, "garbage"));

        assert($o->stringAttr === "b&a");
        assert($o->intAttr === 321);
        assert($o->doubleAttr === 4.13);
        assert($o->falseAttr === true);
        assert($o->trueAttr === false);
        assert($o->nullAttr === "NOT NULL");
        assert($o->dateAttr->format("Y m d H:i") === "2010 09 01 23:07");

        assert($o->stringElement === "<OVERRIDEN>");
        assert($o->intElement === 321);
        assert($o->doubleElement === 4.13);
        assert($o->falseElement === true);
        assert($o->trueElement === false);
        assert($o->nullElement === "NOT NULL");
        assert($o->dateElement->format("Y m d H:i:s") === "1984 06 14 21:01:00");

        assert($o->inner->attr === -5);
        assert($o->inner->element === "zyx");

        assert(is_array($o->array));
        assert(count($o->array) == 4);
        assert($o->array[0] === 9);
        assert($o->array[1] === "nine");
        assert($o->array[2]->attr === 5);
        assert($o->array[2]->element === "xyz");
        assert($o->array[3]->attr === 55);
        assert($o->array[3]->element === "XYZ");

        assert(is_array($o->array2));
        assert(count($o->array2) == 1);
        assert($o->array2[0] === "Single item");

        assert($o->collection instanceof \ArrayObject);
        assert($o->collection->count() == 4);
        assert($o->collection[0] === true);
        assert($o->collection[1] === "False");
        assert($o->collection[2]->attr === 5);
        assert($o->collection[2]->element === "xyz");
        assert($o->collection[3]->attr === 555);
        assert($o->collection[3]->element === "X-Y-Z");

        assert($o->stack instanceof \SplStack);
        assert($o->stack->count() == 1);
        assert($o->stack->top() === "Single item");
    }

    /**
     * test_generateSchema
     */
    function test_generateSchema()
    {
        $schema = XmlSerializer::generateSchema(new Sample7);
        $schema->formatOutput = true;
        $actual = $this->shrinkXml($schema->saveXML());

        $expected = $this->shrinkXml(file_get_contents(dirname(__FILE__) . "/sample7.xsd"));

        assert($actual == $expected);
    }

    /**
     * test_nestedObjects
     */
    function test_nestedObjects()
    {

        $parent = new Sample9;
        $child = new Sample9;
        $grand = new Sample9;

        $parent->child = $child;
        $child->child = $grand;
        $grand->str = "abc";

        $dom = XmlSerializer::serialize($parent);
        $actual = $this->shrinkXml($dom->saveXML());

        $expected = $this->shrinkXml(file_get_contents(dirname(__FILE__) . "/sample9-1.xml"));

        assert($actual == $expected);
    }

    /**
     * test_inheritedProperties
     */
    function test_inheritedProperties()
    {

        $o = new Sample10;
        $o->setPrivate("SUCCESS");
        $o->setProtected("SUCCESS");
        $o->setPublic("SUCCESS");

        $dom = XmlSerializer::serialize($o);

        assert(substr_count($dom->saveXML(), "SUCCESS") == 3);
    }

    /**
     * test_stringKeys
     */
    function test_stringKeys()
    {
        $o = new Sample7;
        $o->array["test"] = 1;
        shit_must_happen(function () {
            XmlSerializer::serialize($o);
        });
    }

    /**
     * shrinkXml
     * @param $xml
     * @return mixed
     */
    private function shrinkXml($xml)
    {
        return preg_replace("/>\\s+</", "><", trim($xml));
    }

}