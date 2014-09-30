<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 24/06/2014
 * Time: 15:38
 */
namespace MIMAS\Schemas\DublinCore;

/**
 * Class Core
 * Stub. Will expand in 0.9.1
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

class Core
{
    /**
     * DC Terms
     * @var array
     */
    public static $terms = array(
      'accessRights',
      'accrualMethod',
      'accrualPeriodicity',
      'accrualPolicy',
      'alternative',
      'audience',
      'available',
      'bibliographicCitation',
      'conformsTo',
      'contributor',
      'coverage',
      'created',
      'creator',
      'date',
      'dateAccepted',
      'dateCopyrighted',
      'dateSubmitted',
      'description',
      'educationLevel',
      'extent',
      'format',
      'hasFormat',
      'hasPart',
      'hasVersion',
      'identifier',
      'instructionalMethod',
      'isFormatOf',
      'isPartOf',
      'isReferencedBy',
      'isReplacedBy',
      'isRequiredBy',
      'issued',
      'isVersionOf',
      'language',
      'license',
      'mediator',
      'medium',
      'modified',
      'provenance',
      'publisher',
      'references',
      'relation',
      'replaces',
      'requires',
      'rights',
      'rightsHolder',
      'source',
      'spatial',
      'subject',
      'tableOfContents',
      'temporal',
      'title',
      'type',
      'valid'
    );

    /**
     * DC Elements
     * @var array
     */
    public static $elements = array(
      'contributor',
      'coverage',
      'creator',
      'date',
      'description',
      'format',
      'identifier',
      'language',
      'publisher',
      'relation',
      'rights',
      'source',
      'subject',
      'title',
      'type'
    );

    /**
     * Constructor
     */
    public function __construct()
    {

    }


}