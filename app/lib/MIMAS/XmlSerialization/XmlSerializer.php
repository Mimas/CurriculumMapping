<?php
/**
 * Jorum XML Serialization
 *
 * Jorum API XML serialization - XML Serializer main class
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\XmlSerialization;

/**
 * Class XmlSerializer
 * @package MIMAS\XmlSerialization
 */
class XmlSerializer
{

    /**
     * serialize
     *
     * @param $obj
     * @return \DOMDocument
     */
    static function serialize($obj)
    {
        $doc = new \DOMDocument();
        $root = $doc->createElement(ClassMetaStore::getMeta($obj)->getXmlRoot());
        self::serializeObject($obj, $root);
        $doc->appendChild($root);
        return $doc;
    }

    /**
     * serializeObject
     *
     * @param $obj
     * @param \DOMElement $target
     * @throws \RuntimeException
     */
    private static function serializeObject($obj, \DOMElement $target)
    {
        $meta = ClassMetaStore::getMeta($obj);


        foreach ($meta->getPropertyNames() as $propName) {

            $value = $meta->getPropertyValue($obj, $propName);

            if (is_array($value) || $value instanceof \Traversable) {
                foreach ($value as $key => $item) {
                    if (!is_int($key))
                        throw new \RuntimeException("Collections with associative indexing cannot be serialized");
                    self::serializeProperty($meta, $propName, $item, $target);
                }
            } else {
                self::serializeProperty($meta, $propName, $value, $target);
            }
        }
    }

    /**
     * serializeProperty
     *
     * @param ClassMeta $meta
     * @param $propName
     * @param $value
     * @param \DOMElement $target
     * @throws \RuntimeException
     */
    private static function serializeProperty(ClassMeta $meta, $propName, $value, \DOMElement $target)
    {
        if ($value === null)
            return;

        $valueType = self::getValueType($value);

        $attrName = $meta->getAttributeName($propName, $valueType);
        if ($attrName) {
            $target->setAttribute($attrName, self::formatAtomicValue($value));
        }

        $elementName = $meta->getElementName($propName, $valueType);
        if ($elementName) {
            $child = $target->ownerDocument->createElement($elementName);
            if (self::isObject($value)) {
                self::serializeObject($value, $child);
            } else {
                $text = self::formatAtomicValue($value);
                $child->appendChild($target->ownerDocument->createTextNode($text));
            }
            $target->appendChild($child);
        }

        if (!$attrName && !$elementName)
            throw new \RuntimeException("Don't know how to serialize value of type '$valueType' for property '$propName' of class '{$meta->getClassName()}'");
    }

    /**
     * getValueType
     *
     * @param $value
     * @return string
     */
    private static function getValueType($value)
    {
        if (is_object($value))
            return get_class($value);
        return gettype($value);
    }

    /**
     * isObject other than \DateTime
     *
     * @param $value
     * @return bool
     */
    private static function isObject($value)
    {
        return is_object($value) && !($value instanceof \DateTime);
    }

    /**
     * formatAtomicValue
     *
     * @param $value
     * @return string
     */
    private static function formatAtomicValue($value)
    {
        if (is_bool($value))
            return $value ? "true" : "false";

        if ($value instanceof \DateTime) {
            $result = $value->format("Y-m-d");
            $time = $value->format("H:i:s");
            if ($time != "00:00:00")
                $result .= " $time";
            return $result;
        }

        return (string)$value;
    }

    /**
     * unserialize
     *
     * @param \DOMDocument $doc
     * @param $className
     * @return mixed
     */
    static function unserialize(\DOMDocument $doc, $className)
    {
        $result = new $className;
        self::unserializeObject($result, $doc->documentElement);
        return $result;
    }

    /**
     * unserializeObject
     *
     * @param $obj
     * @param \DOMElement $source
     */
    private static function unserializeObject($obj, \DOMElement $source)
    {
        $meta = ClassMetaStore::getMeta($obj);

        $bag = array();

        foreach ($source->attributes as $attribute) {
            $propName = $meta->getPropertyNameForAttribute($attribute->name);
            if (!$propName)
                continue;
            $valueType = $meta->getPropertyTypeForAttribute($attribute->name);
            $value = self::parseAtomicValue($attribute->value, $valueType);
            self::addPropertyToBag($propName, $value, $bag);
        }

        foreach ($source->childNodes as $child) {
            if (!($child instanceof \DOMElement))
                continue;
            $propName = $meta->getPropertyNameForElement($child->tagName);
            if (!$propName)
                continue;
            $valueType = $meta->getPropertyTypeForElement($child->tagName);
            $isObject = !self::isAtomicType($valueType);
            $value = $isObject
              ? new $valueType
              : self::parseAtomicValue(trim($child->textContent), $valueType);
            self::addPropertyToBag($propName, $value, $bag);
            if ($isObject)
                self::unserializeObject($value, $child);
        }

        foreach ($bag as $propName => $data) {
            $currentValue = $meta->getPropertyValue($obj, $propName);
            if (is_array($currentValue)) {
                if (is_array($data)) {
                    $meta->setPropertyValue($obj, $propName, array_merge($currentValue, $data));
                } else {
                    array_push($currentValue, $data);
                    $meta->setPropertyValue($obj, $propName, $currentValue);
                }
            } elseif ($currentValue instanceof \ArrayAccess) {
                if (is_array($data)) {
                    foreach ($data as $item)
                        $currentValue[] = $item;
                } else {
                    $currentValue[] = $data;
                }
            } else {
                $meta->setPropertyValue($obj, $propName, is_array($data) ? $data[count($data) - 1] : $data);
            }
        }
    }

    /**
     * addPropertyToBag
     *
     * @param $name
     * @param $value
     * @param array $bag
     */
    private static function addPropertyToBag($name, $value, array &$bag)
    {
        if (!array_key_exists($name, $bag)) {
            $bag[$name] = $value;
        } else {
            if (is_array($bag[$name])) {
                array_push($bag[$name], $value);
            } else {
                $bag[$name] = array($bag[$name], $value);
            }
        }
    }

    /**
     * isAtomicType
     *
     * @param $type
     * @return bool
     */
    private static function isAtomicType($type)
    {
        return $type == "string"
        || $type == "integer"
        || $type == "boolean"
        || $type == "double"
        || $type == "DateTime";
    }

    /**
     * Parse Atomic Value
     * @param $value
     * @param $type
     * @return bool|\DateTime|float|int
     */
    private static function parseAtomicValue($value, $type)
    {
        if ($type == "integer")
            return intval($value);

        if ($type == "boolean")
            return strtolower($value) == "true" || intval($value) > 0;

        if ($type == "double")
            return doubleval($value);

        if ($type == "DateTime")
            return new \DateTime($value);

        return $value;
    }

    /**
     * generateSchema
     *
     * @param $className
     * @return \DOMDocument
     */
    static function generateSchema($className)
    {
        $g = new SchemaGenerator();
        return $g->generate($className);
    }


}
