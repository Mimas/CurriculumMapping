<?php
/**
 * Summary for Unit test 7
 */
namespace MIMAS\XmlSerialization\Tests;

/**
 * Sample7
 * @XmlRoot(sample-7)
 */
class Sample7
{

    /**
     * stringAttr
     * @XmlAttribute
     */
    public $stringAttr = "a&b";

    /**
     * intAttr
     * @XmlAttribute(int)
     */
    public $intAttr = 123;

    /**
     * doubleAttr
     * @XmlAttribute(float)
     */
    public $doubleAttr = 3.14;

    /**
     * falseAttr
     * @XmlAttribute(bool)
     */
    public $falseAttr = false;

    /**
     * trueAttr
     * @XmlAttribute(bool)
     */
    public $trueAttr = true;

    /**
     * nullAttr
     * @XmlAttribute
     */
    public $nullAttr = null;

    /**
     * dateAttr
     * @XmlAttribute(date)
     */
    public $dateAttr;

    ##############################

    /**
     * stringElement
     * @XmlElement
     */
    public $stringElement = "a&b";

    /**
     * doubleElement
     * @XmlElement(int)
     */
    public $intElement = 123;

    /**
     * doubleElement
     * @XmlElement(float)
     */
    public $doubleElement = 3.14;

    /**
     * falseElement
     * @XmlElement(bool)
     */
    public $falseElement = false;

    /**
     * trueElement
     * @XmlElement(bool)
     */
    public $trueElement = true;

    /**
     * nullElement
     * @XmlElement
     */
    public $nullElement = null;

    /**
     * dateElement
     * @XmlElement(\DateTime)
     */
    public $dateElement;

    ##############################

    /**
     * inner
     * @var Sample8
     * @XmlElement(Sample8)
     */
    public $inner;

    ##############################

    /**
     * array
     * @XmlElement(int, array-int)
     * @XmlElement(string, array-string)
     * @XmlElement(Sample8, array-obj)
     */
    public $array = array();

    /**
     * array2
     * @xmlelement
     */
    public $array2 = array();

    /**
     * collection
     * @XmlElement(string, collection-string)
     * @XmlElement(bool, collection-bool)
     * @XmlElement(Sample8, collection-obj)
     */
    public $collection;

    /**
     * stack
     * @xmlelement
     */
    public $stack;

    ##############################
    /**
     * constructor
     */
    public function __construct()
    {
        $this->collection = new \ArrayObject;
        $this->stack = new \SplStack;
    }
}