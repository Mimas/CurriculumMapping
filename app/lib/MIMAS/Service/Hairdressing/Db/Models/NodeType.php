<?php
/**
 * HT API NodeType
 *
 * Drupal 6.x NodeType
 *
 * Model to describe the various Node types
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
 * Class NodeType
 * This models the node_types table as defined in Drupal 6.x
 * @author Petros Diveris
 *
 */
class NodeType extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'node_type';

    /**
     * PK is string 'type'
     * @var string $primaryKey
     */
    protected static $primaryKey = 'type';

    /**
     * PK type (e.g. 'page', 'step', 'long_answer', 'poll'
     *
     * @var string $name
     */
    public $name = '';

    /**
     * Module (e.g. 'quiz_question', 'node', 'poll' etc)
     *
     * @var string
     */
    public $module = '';

    /**
     * Description e.g. 'Quiz questions that allow a user to choose from a scale.'
     * It can (and does) contain HTML!
     *
     * @var string
     */
    public $description = '';

    /**
     * Help text
     *
     * @var string
     */
    public $help = '';

    /**
     * Has title
     *
     * @var boolean
     */
    public $has_title = 0;

    /**
     * Title label
     *
     * @var string
     */
    public $title_label = '';

    /**
     * Has body
     *
     * @var int
     */
    public $has_body = 0;

    /**
     * Label for body
     *
     * @var string
     */
    public $body_label = '';

    /**
     * Minimum word count
     *
     * @var int
     */
    public $min_word_count = 0;

    /**
     * Boolean custom
     *
     * @var int
     */
    public $custom = 0;

    /**
     * Boolean modified
     *
     * @var int
     */
    public $modified = 0;

    /**
     * Boolean locked
     *
     * @var int
     */
    public $locked = 0;

    /**
     * Original type
     *
     * @var string
     */
    public $orig_type = '';

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
