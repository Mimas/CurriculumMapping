<?php
/**
 * MIMAS API
 *
 * DublinCore
 *
 * Dublin Core Terms we expose and perhaps transform in the API
 *
 * @package      MIMAS
 * @subpackage   Classification
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 * Will eventually become of the classification term translation engine
 *
 */
namespace MIMAS\Schemas\DublinCore;

/**
 * Class Terms
 *
 * @package MIMAS\DublinCore
 * @version 0.9.1
 * @specification 1.1
 * @author Petros Diveris
 * @copyright MIMAS, 2014
 */
class Terms
{
    /**
     * @var $title
     * substitutionGroup="dc:title"
     */
    public $title;

    /**
     * @var $creator
     * substitutionGroup="dc:creator"
     */
    public $creator;

    /**
     * @var $subject
     * substitutionGroup="dc:subject"
     */
    public $subject;

    /**
     * @var $description
     * substitutionGroup="dc:description"
     */
    public $description;

    /**
     * @var $publisher
     * substitutionGroup="dc:publisher"
     */
    public $publisher;

    /**
     * @var $contributor
     * substitutionGroup="dc:contributor"
     */
    public $contributor;

    /**
     * @var $date
     * substitutionGroup="dc:date"
     */
    public $date;
    /**
     * @var $type
     * substitutionGroup="dc:type"
     */
    public $type;

    /**
     * @var $format
     * substitutionGroup="dc:format"
     */
    public $format;

    /**
     * @var $identifier
     *  substitutionGroup="dc:identifier"
     */
    public $identifier;

    /**
     * @var $source
     * substitutionGroup="dc:source"
     */
    public $source;

    /**
     * @var $language
     *  substitutionGroup="dc:language"
     */
    public $language;

    /**
     * @var $relation
     * substitutionGroup="dc:relation"
     */
    public $relation;

    /**
     * @var $coverage
     * substitutionGroup="dc:coverage"
     */
    public $coverage;

    /**
     * @var $rights
     * substitutionGroup="dc:rights"
     */
    public $rights;

    /**
     * @var $alternative
     * substitutionGroup="title"
     */
    public $alternative;

    /**
     * @var $tableOfContents
     * substitutionGroup="description"
     */
    public $tableOfContents;

    /**
     * @var $abstract
     * substitutionGroup="description"
     */
    public $abstract;

    /**
     * @var $created
     * substitutionGroup="date"
     */
    public $created;

    /**
     * @var $valid
     * substitutionGroup="date"
     */
    public $valid;

    /**
     * @var $available
     * substitutionGroup="date"
     */
    public $available;

    /**
     * @var $issued
     * substitutionGroup="date"
     */
    public $issued;

    /**
     * @var $modified
     *  substitutionGroup="date"
     */
    public $modified;

    /**
     * @var $dateAccepted
     * substitutionGroup="date"
     */
    public $dateAccepted;

    /**
     * @var $dateCopyrighted
     * substitutionGroup="date"
     */
    public $dateCopyrighted;

    /**
     * @var $dateSubmitted
     * substitutionGroup="date"
     */
    public $dateSubmitted;

    /**
     * @var $extent
     * substitutionGroup="format"
     */
    public $extent;

    /**
     * @var $medium
     *  substitutionGroup="format"
     */
    public $medium;

    /**
     * @var $isVersionOf
     * substitutionGroup="relation"
     */
    public $isVersionOf;

    /**
     * @var $hasVersion
     * substitutionGroup="relation"
     */
    public $hasVersion;

    /**
     * @var $isreplacedBy
     * substitutionGroup="relation"
     */
    public $isReplacedBy;

    /**
     * @var $replaces
     * substitutionGroup="relation"
     */
    public $replaces;

    /**
     * @var $isRequiredBy
     * substitutionGroup="relation"
     */
    public $isRequiredBy;

    /**
     * @var $requires
     * substitutionGroup="relation"
     */
    public $requires;

    /**
     * @var $isPartOf
     * substitutionGroup="relation"
     */
    public $isPartOf;

    /**
     * @var $hasPart
     * substitutionGroup="relation"
     */
    public $hasPart;

    /**
     * @var $isReferencedBy
     * substitutionGroup="relation"
     */
    public $isReferencedBy;

    /**
     * @var $references
     * substitutionGroup="relation"
     */
    public $references;

    /**
     * @var $isFormatOf
     * substitutionGroup="relation"
     */
    public $isFormatOf;

    /**
     * @var $hasFormat
     * substitutionGroup="relation"
     */
    public $hasFormat;

    /**
     * @var $conformsTo
     * substitutionGroup="relation"
     */
    public $conformsTo;

    /**
     * @var $spatial
     * substitutionGroup="coverage"
     */
    public $spatial;

    /**
     * @var $temporal
     * substitutionGroup="coverage"
     */
    public $temporal;

    /**
     * @var $audience
     * substitutionGroup="dc:any"
     */
    public $audience;

    /**
     * @var $accrualMethod
     * substitutionGroup="dc:any"
     */
    public $accrualMethod;

    /**
     * @var $accrualPeriodicity
     * substitutionGroup="dc:any"
     */
    public $accrualPeriodicity;

    /**
     * @var $accrualPolicy
     * substitutionGroup="dc:any"
     */
    public $accrualPolicy;

    /**
     * @var $instructionalMethod
     * substitutionGroup="dc:any"
     */
    public $instructionalMethod;

    /**
     * @var $provenance
     * substitutionGroup="dc:any"
     */
    public $provenance;

    /**
     * @var $rightsHolder
     * substitutionGroup="dc:any"
     */
    public $rightsHolder;

    /**
     * @var $mediator
     * substitutionGroup="audience"
     */
    public $mediator;

    /**
     * @var $educationLevel
     * substitutionGroup="audience"
     */
    public $educationLevel;

    /**
     * @var $accessRights
     * substitutionGroup="rights"
     */
    public $accessRights;

    /**
     * @var $license
     * substitutionGroup="rights"
     */
    public $license;

    /**
     * @var $bibliographicCitation
     * substitutionGroup="identifier"
     */
    public $bibliographicCitation;


}