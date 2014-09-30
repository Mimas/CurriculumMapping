<?php
/**
 * Jorum XML Serialization
 *
 * Jorum API XML serialization - Schema Generator
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\XmlSerialization;

/**
 * Class SchemaGenerator
 * @package MIMAS\XmlSerialization
 */
class SchemaGenerator
{
    /**
     * pendingTypes
     * @var $pendingTypes
     */
    private $pendingTypes;

    /**
     * generatedTypes
     * @var $generatedTypes
     */
    private $generatedTypes;

    /**
     * generate
     *
     * @param $rootClassName
     * @return \DOMDocument
     */
    function generate($rootClassName)
    {
        $doc = new \DOMDocument();

        $root = $doc->createElement("xs:schema");
        $root->setAttribute("xmlns:xs", "http://www.w3.org/2001/XMLSchema");

        $meta = ClassMetaStore::getMeta($rootClassName);

        $element = $doc->createElement("xs:element");
        $element->setAttribute("name", $meta->getXmlRoot());
        $element->setAttribute("type", $this->getXsdType($meta->getClassName()));
        $root->appendChild($element);

        $this->pendingTypes = array($meta->getClassName() => 1);
        $this->generatedTypes = array();

        while (count($this->pendingTypes)) {
            reset($this->pendingTypes);
            $type = key($this->pendingTypes);
            $this->generatedTypes[$type] = 1;
            unset($this->pendingTypes[$type]);

            $root->appendChild($this->createSchemaNodeForObject($doc, new $type));
        }

        $doc->appendChild($root);
        return $doc;
    }

    /**
     * createSchemaNodeForObject
     *
     * @param \DOMDocument $doc
     * @param $obj
     * @return \DOMElement
     */
    private function createSchemaNodeForObject(\DOMDocument $doc, $obj)
    {
        $meta = ClassMetaStore::getMeta($obj);

        $complexType = $doc->createElement("xs:complexType");
        $complexType->setAttribute("name", $this->getXsdType($meta->getClassName()));

        $sequence = $doc->createElement("xs:sequence");
        $attributeBag = array();

        foreach ($meta->getPropertyNames() as $propName) {
            $elementNames = $meta->getElementNamesForProperty($propName);
            if (count($elementNames) > 0) {

                $value = $meta->getPropertyValue($obj, $propName);
                $isCollection = is_array($value) || $value instanceof \Traversable;


                $propertyElement = count($elementNames) == 1
                  ? $this->createSchemaNodeForSingleElementProperty($doc, $meta, $elementNames[0])
                  : $this->createSchemaNodeForMultiElementProperty($doc, $meta, $elementNames);

                if ($isCollection) {
                    $collectionChoice = $doc->createElement("xs:choice");
                    $collectionChoice->appendChild($propertyElement);
                    $collectionChoice->setAttribute("minOccurs", 0);
                    $collectionChoice->setAttribute("maxOccurs", "unbounded");
                    $propertyElement = $collectionChoice;
                }
                $sequence->appendChild($propertyElement);
            }

            $attrNames = $meta->getAttributeNamesForProperty($propName);
            foreach ($attrNames as $attrName) {
                $type = $meta->getPropertyTypeForAttribute($attrName);
                $this->mentionType($type);
                $attribute = $doc->createElement("xs:attribute");
                $attribute->setAttribute("name", $attrName);
                $attribute->setAttribute("type", $this->getXsdType($type));
                $attributeBag[] = $attribute;
            }
        }

        if ($sequence->hasChildNodes())
            $complexType->appendChild($sequence);

        foreach ($attributeBag as $attribute)
            $complexType->appendChild($attribute);

        return $complexType;
    }

    /**
     * createSchemaNodeForSingleElementProperty
     *
     * @param \DOMDocument $doc
     * @param ClassMeta $meta
     * @param $elementName
     * @return \DOMElement
     */
    private function createSchemaNodeForSingleElementProperty(\DOMDocument $doc, ClassMeta $meta, $elementName)
    {
        $result = $doc->createElement("xs:element");
        $result->setAttribute("name", $elementName);

        $type = $meta->getPropertyTypeForElement($elementName);
        $this->mentionType($type);
        $result->setAttribute("type", $this->getXsdType($type));

        $result->setAttribute("minOccurs", 0);
        return $result;
    }

    /**
     * createSchemaNodeForMultiElementProperty
     *
     * @param \DOMDocument $doc
     * @param ClassMeta $meta
     * @param array $names
     * @return \DOMElement
     */
    private function createSchemaNodeForMultiElementProperty(\DOMDocument $doc, ClassMeta $meta, array $names)
    {
        $result = $doc->createElement("xs:choice");
        foreach ($names as $name)
            $result->appendChild($this->createSchemaNodeForSingleElementProperty($doc, $meta, $name));
        $result->setAttribute("minOccurs", 0);
        return $result;
    }

    /**
     * getXsdType
     *w
     * @param $type
     * @return string
     */
    private function getXsdType($type)
    {
        if ($type == "integer" || $type == "double" || $type == "boolean" || $type == "string")
            return "xs:$type";
        if ($type == "DateTime")
            return "xs:string";

        return str_replace("\\", "-", $type) . "-Type";
    }

    /**
     * mentionType
     * @param $type
     */
    private function mentionType($type)
    {
        if ($type == "string" || $type == "integer" || $type == "boolean" || $type == "double" || $type == "DateTime")
            return;

        if (!array_key_exists($type, $this->generatedTypes))
            $this->pendingTypes[$type] = 1;
    }


}