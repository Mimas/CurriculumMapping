<?php
/**
 * Jorum XML Serialization
 *
 * Jorum API XML serialization - Class Meta
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\XmlSerialization;

/**
 * Class ClassMeta
 * @package MIMAS\XmlSerialization
 */
class ClassMeta
{
    /**
     * shortName
     * @var string
     */
    private $shortName;

    /**
     * namespace
     * @var string
     */
    private $namespace;

    /**
     * xmlRoot
     * @var mixed
     */
    private $xmlRoot;

    /**
     * reflectors
     * @var array
     */
    private $reflectors = array();

    /**
     * props
     * @var array
     */
    private $props = array(); // [propName][valueType] -> array(xmlName, isElement)

    /**
     * attrs
     * @var array
     */
    private $attrs = array(); // [attrName] -> array(propName, valueType)

    /**
     * els
     * @var array
     */
    private $els = array(); // [elementName] -> array(propName, valueType)

    /**
     * Constructor
     * @param mixed $class An instance or a class name
     */
    public function __construct($class)
    {
        $r = new \ReflectionClass($class);
        $this->shortName = $r->getShortName();
        $this->namespace = $r->getNamespaceName();
        $this->xmlRoot = $this->receiveXmlRoot($r->getDocComment());

        $current = $r;
        while ($current) {
            foreach ($current->getProperties() as $p) {
                if ($p->getDeclaringClass()->name != $current->name)
                    continue;
                $this->processProperty($p);
            }
            $current = $current->getParentClass();
        }
    }

    /**
     * Receive XML root
     * @param $docComment
     * @return mixed
     */
    private function receiveXmlRoot($docComment)
    {
        foreach (Annotation::parse($docComment) as $a) {
            if ($a->getName() == "root" && $a->getParamCount() > 0)
                return $a->getParam(0);
        }
    }

    /**
     * Process a property
     * @param \ReflectionProperty $p
     */
    private function processProperty(\ReflectionProperty $p)
    {
        $registered = false;
        foreach (Annotation::parse($p->getDocComment()) as $a) {
            if ($a->getName() != "element" && $a->getName() != "attribute")
                continue;

            if ($p->isStatic())
                $this->fail("Static property '{$p->name}' cannot be serialized");

            if (!$registered) {
                $p->setAccessible(true);
                $this->reflectors[$p->name] = $p;
                $registered = true;
            }

            $type = "";
            if ($a->getParamCount() > 0)
                $type = $a->getParam(0);
            $type = $this->resolveType($type);

            $xmlName = $p->name;
            if ($a->getParamCount() > 1)
                $xmlName = $a->getParam(1);

            $isElement = $a->getName() == "element";

            if ($this->hasXmlNameForProperty($p->name, $type))
                $this->fail("Duplicate xml name for property name '{$p->name}' and value type '$type'");

            if (!array_key_exists($p->name, $this->props))
                $this->props[$p->name] = array();
            $this->props[$p->name][$type] = array($xmlName, $isElement);

            if ($isElement) {
                if ($this->hasPropertyForElement($xmlName))
                    $this->fail("Duplicate element '$xmlName'");
                $this->els[$xmlName] = array($p->name, $type);
            } else {
                if ($this->hasPropertyForAttribute($xmlName))
                    $this->fail("Duplicate attribute '$xmlName'");
                $this->attrs[$xmlName] = array($p->name, $type);
            }
        }
    }

    /**
     * Resolve type
     * @param $type
     * @return string
     */
    private function resolveType($type)
    {
        if (!$type || $type == "string")
            return "string";
        if ($type == "int" || $type == "integer")
            return "integer";
        if ($type == "bool" || $type == "boolean")
            return "boolean";
        if ($type == "float" || $type == "double")
            return "double";
        if ($type == "date" || $type == "datetime")
            return "DateTime";

        if (strpos($type, "\\") === false)
            $type = $this->getNamespace() . "\\" . $type;
        return ltrim($type, "\\");
    }

    /**
     * Get class name
     * @return string
     */
    function getClassName()
    {
        if ($this->namespace)
            return $this->namespace . "\\" . $this->shortName;
        return $this->shortName;
    }

    /**
     * Get Namespace
     * @return string
     */
    function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get the XML root
     * @return mixed|string
     */
    function getXmlRoot()
    {
        if (!$this->xmlRoot)
            return $this->shortName;
        return $this->xmlRoot;
    }

    /**
     * Get the array of property names
     * @return array
     */
    function getPropertyNames()
    {
        return array_keys($this->props);
    }

    /**
     * Get the array of Attribute names
     * @return array
     */
    function getAttributeNames()
    {
        return array_keys($this->attrs);
    }

    /**
     * Get array of Attribute names for given property
     * @param $propName
     * @return array
     */
    function getAttributeNamesForProperty($propName)
    {
        $result = array();
        foreach ($this->attrs as $attrName => $data) {
            if ($data[0] == $propName)
                $result[] = $attrName;
        }
        return $result;
    }

    /**
     * Return element names
     * @return array
     */
    function getElementNames()
    {
        return array_keys($this->els);
    }

    /**
     * Get Element names for given property
     * @param $propName
     * @return array
     */
    function getElementNamesForProperty($propName)
    {
        $result = array();
        foreach ($this->els as $elementName => $data) {
            if ($data[0] == $propName)
                $result[] = $elementName;
        }
        return $result;
    }

    /**
     * getPropertyValue
     * @param $obj
     * @param $propName
     * @return mixed
     */
    function getPropertyValue($obj, $propName)
    {
        return $this->reflectors[$propName]->getValue($obj);
    }

    /**
     * setPropertyValue
     * @param $obj
     * @param $propName
     * @param $value
     */
    function setPropertyValue($obj, $propName, $value)
    {
        $this->reflectors[$propName]->setValue($obj, $value);
    }

    /**
     * getAttributeName
     * @param $propName
     * @param $valueType
     * @return null
     */
    function getAttributeName($propName, $valueType)
    {
        if (!$this->hasAttributeForProperty($propName, $valueType))
            return null;
        return $this->props[$propName][$valueType][0];
    }

    /**
     * getElementName
     * @param $propName
     * @param $valueType
     * @return null
     */
    function getElementName($propName, $valueType)
    {
        if (!$this->hasElementForProperty($propName, $valueType))
            return null;
        return $this->props[$propName][$valueType][0];
    }

    /**
     * getPropertyNameForAttribute
     * @param $attrName
     * @return null
     */
    function getPropertyNameForAttribute($attrName)
    {
        if (!$this->hasPropertyForAttribute($attrName))
            return null;
        return $this->attrs[$attrName][0];
    }

    /**
     * getPropertyNameForElement
     * @param $elementName
     * @return null
     */
    function getPropertyNameForElement($elementName)
    {
        if (!$this->hasPropertyForElement($elementName))
            return null;
        return $this->els[$elementName][0];
    }

    /**
     * getPropertyTypeForAttribute
     * @param $attrName
     * @return null
     */
    function getPropertyTypeForAttribute($attrName)
    {
        if (!$this->hasPropertyForAttribute($attrName))
            return null;
        return $this->attrs[$attrName][1];
    }

    /**
     * getPropertyTypeForElement
     * @param $elementName
     * @return null
     */
    function getPropertyTypeForElement($elementName)
    {
        if (!$this->hasPropertyForElement($elementName))
            return null;
        return $this->els[$elementName][1];
    }

    /**
     * hasXmlNameForProperty
     * @param $propName
     * @param $valueType
     * @return bool
     */
    private function hasXmlNameForProperty($propName, $valueType)
    {
        return array_key_exists($propName, $this->props) && array_key_exists($valueType, $this->props[$propName]);
    }

    /**
     * hasAttributeForProperty
     * @param $propName
     * @param $valueType
     * @return bool
     */
    private function hasAttributeForProperty($propName, $valueType)
    {
        if (!$this->hasXmlNameForProperty($propName, $valueType))
            return false;
        return !$this->props[$propName][$valueType][1];
    }

    /**
     * hasElementForProperty
     * @param $propName
     * @param $valueType
     * @return bool
     */
    private function hasElementForProperty($propName, $valueType)
    {
        if (!$this->hasXmlNameForProperty($propName, $valueType))
            return false;
        return $this->props[$propName][$valueType][1];
    }

    /**
     * hasPropertyForAttribute
     * @param $attrName
     * @return bool
     */
    private function hasPropertyForAttribute($attrName)
    {
        return array_key_exists($attrName, $this->attrs);
    }

    /**
     * hasPropertyForElement
     * @param $elementName
     * @return bool
     */
    private function hasPropertyForElement($elementName)
    {
        return array_key_exists($elementName, $this->els);
    }

    /**
     * Fail by throwing an Exception
     * @param $message
     * @throws \RuntimeException
     */
    private function fail($message)
    {
        throw new \RuntimeException("Xml metadata error for {$this->getClassName()}: $message");
    }

}