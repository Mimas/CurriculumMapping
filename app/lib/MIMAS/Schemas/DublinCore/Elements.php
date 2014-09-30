<?php
/**
 * MIMAS API
 *
 * DublinCore
 *
 * Dublin Core Elements
 *
 * @package      MIMAS
 * @subpackage   Classification
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 * Will eventually become part of the  classification term translation engine
 *
 */

namespace MIMAS\Schemas\DublinCore;

/**
 * Class Elements
 *
 * @package MIMAS\DublinCore
 * @version 0.9.1
 * @specification 1.1
 * @author Petros Diveris
 * @copyright MIMAS, 2014
 *
 * <xs:element name="any" type="SimpleLiteral" abstract="true"/>
 *
 */
class Elements
{
    /**
     * @var $title
     * substitutionGroup="any"
     */
    public $title;

    /**
     * @var $creator
     * substitutionGroup="any"
     */
    public $creator;

    /**
     * @var $subject
     * substitutionGroup="any"
     */
    public $subject;

    /**
     * @var $description
     * substitutionGroup="any"
     */
    public $description;

    /**
     * @var $publisher
     * substitutionGroup="any"
     */
    public $publisher;

    /**
     * @var $contributor
     * substitutionGroup="any"
     */
    public $contributor;

    /**
     * @var $date
     * substitutionGroup="any"
     */
    public $date;

    /**
     * @var $type
     * substitutionGroup="any"
     */
    public $type;

    /**
     * @var $format
     * substitutionGroup="any"
     */
    public $format;

    /**
     * @var $identifier
     * substitutionGroup="any"
     */
    public $identifier;

    /**
     * @var $source
     * substitutionGroup="any"
     */
    public $source;

    /**
     * @var $language
     * substitutionGroup="any"
     */
    public $language;

    /**
     * @var $relation
     * substitutionGroup="any"
     */
    public $relation;

    /**
     * @var $coverage
     * substitutionGroup="any"
     */
    public $coverage;

    /**
     * @var $rights
     * substitutionGroup="any"
     */
    public $rights;


}