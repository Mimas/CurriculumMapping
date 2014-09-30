<?php
/**
 * Jorum XML Serialization
 *
 * Jorum API XML serialization - Annotation
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\XmlSerialization;

/**
 * Class Annotation
 * @package MIMAS\XmlSerialization
 */
class Annotation
{

    /**
     * Parse
     * @param $docComment
     * @return array
     */
    static function parse($docComment)
    {
        preg_match_all("/\\@xml(element|attribute|root)\\s*(?:\\((.*?)\\))?/is", $docComment, $matches, PREG_SET_ORDER);
        $result = array();
        foreach ($matches as $match) {
            $annotation = new Annotation;
            $annotation->name = strtolower($match[1]);
            if (count($match) > 2)
                $annotation->params = preg_split("/\\s*\\,\\s*/", trim($match[2]), -1, PREG_SPLIT_NO_EMPTY);
            $result[] = $annotation;
        }
        return $result;
    }

    /**
     * Name
     * @var $name
     */
    private $name;

    /**
     * Params
     * @var $params
     */
    private $params;

    /**
     * Constructor - it's jst a stub
     */
    protected function __construct()
    {
    }

    /**
     * Getter for $name
     * @return mixed
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Getter for $paramCount
     * @return int
     */
    function getParamCount()
    {
        return count($this->params);
    }

    /**
     * Return a parameter by index
     * @param $index
     * @return mixed
     */
    function getParam($index)
    {
        return $this->params[$index];
    }

}