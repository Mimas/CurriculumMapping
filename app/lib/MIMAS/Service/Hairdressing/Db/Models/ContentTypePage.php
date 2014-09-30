<?php
/**
 * HT API ContentTypePage
 *
 * Drupal 6.x ContentTypePage
 *
 * This model deals with the Drupal 6.x Custom Fields
 * It is used for the dc.attributes and any other extra fields we need to cater for
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing\Db\Models;

/**
 * Class ContentTypePage
 *
 * The extra fields dc.contributor.author etc
 * IMPORTANT
 * @todo: relations
 *
 * Extra fields table as defined Drupal 6.x
 * @author Petros Diveris
 *
 */
class ContentTypePage extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'content_type_page';

    /**
     * The PK
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'nid';

    /**
     * Version id
     * @var int $vid
     */
    public $vid = 0;

    /**
     * Metadata mapping
     * @var string $field_dc_contributor_author
     */
    public $field_dc_contributor_author_value = '';

    /**
     * Metadata mapping dc.description
     * @var string $field_dc_description_value
     */
    public $field_dc_description_value = '';

    /**
     * Metadata mapping
     * @var string $field_dc_format_value
     */
    public $field_dc_format_value = '';

    /**
     * Metadata mapping
     * @var string
     */
    public $field_dc_publisher_value = '';

    /**
     * Metadata mapping
     * @var string
     */
    public $field_dc_rights_value = '';

    /**
     * Metadata mapping
     * @var string
     */
    public $field_dc_rights_uri_value = '';

    /**
     * Metadata mapping
     * @var string
     */
    public $field_dc_subject_value = '';

    /**
     * Metadata mapping
     * @var string
     */
    public $field_dc_audience_value = '';

    /**
     * Constructor. Check base class for use of attributes.
     * @see JorumDbModel
     *
     * @param array $attributes
     */

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
    }

}
