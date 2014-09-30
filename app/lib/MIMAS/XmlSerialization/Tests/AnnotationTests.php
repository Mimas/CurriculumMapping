<?php
/**
 * Annotation unit tests
 */
namespace MIMAS\XmlSerialization\Tests;

use MIMAS\XmlSerialization\Annotation;

/**
 * Class AnnotationTests
 * @package MIMAS\XmlSerialization\Tests
 */
class AnnotationTests
{
    /**
     * test parse
     */
    function test_parse()
    {
        $comment =
          <<<TEXT
             /**
     * @xmlattribute  ( )
     * @XMLELEMENT (
            1,
            *,
            Test
        )
     * XmlElement(s, s)
     * @XmlElement
     * @XmlGarbage()
     * @XmlRoot(root  )
     */
TEXT;
        $annotations = Annotation::parse($comment);

        assert(count($annotations) == 4);

        /* @var $a Annotation */
        $a = $annotations[0];
        assert($a->getName() == "attribute");
        assert($a->getParamCount() == 0);

        $a = $annotations[1];
        assert($a->getName() == "element");
        assert($a->getParam(0) == "1");
        assert($a->getParam(1) == "*");
        assert($a->getParam(2) == "Test");

        $a = $annotations[2];
        assert($a->getName() == "element");
        assert($a->getParamCount() == 0);

        $a = $annotations[3];
        assert($a->getName() == "root");
        assert($a->getParam(0) == "root");
    }

}