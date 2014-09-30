<?php
/**
 * HT API Vocabulary
 *
 * Drupal 6.x Vocabulary
 *
 * A Taxonomy-related module.
 * The basic "out of the box" functionality is to provide a listing of vocabulary's taxonomy terms at taxonomy/vid.
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
 * Class Vocabulary
 *
 * Vocabulary table as in Drupal 6.x
 * @author Petros Diveris
 *
 */
class Vocabulary extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'vocabulary';

    /**
     * The PK is not id but fid
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'vid';

    /**
     * Name
     *
     * @var string $name
     */
    public $name = '';

    /**
     * Description
     *
     * @var string $description
     */
    public $description = '';

    /**
     * Help
     *
     * @var string $help
     */
    public $help = '';

    /**
     * Relations
     *
     * @var int $relations
     */
    public $relations = 0;

    /**
     * Hierarchy
     *
     * @var int $hierarchy
     */
    public $hierarchy = 0;

    /**
     * Is multiple
     *
     * @var int $multiple
     */
    public $multiple = 0;

    /**
     * Is required
     *
     * @var int $required
     */
    public $required = 0;

    /**
     * Number of tags
     *
     * @var int $tags
     */
    public $tags = 0;

    /**
     * Module it belongs to
     *
     * @var string $module
     */
    public $module = '';

    /**
     * Weight for ordering
     *
     * @var int $weight
     */
    public $weight = 0;

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
