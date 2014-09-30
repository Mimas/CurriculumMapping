<?php
/**
 * HT API TermData
 *
 * Drupal 6.x TermData
 *
 * TermData model
 * In Hairdressing Training the term_data table is used exclusively for the Guides
 * Thus, we can use this table to guess which Nodes are part of guides, and how many Guides there are
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing\Db\Models;

/**
 * Class TermData
 * This models the previous_next_node wizzardy type of relation table as defined in Drupal 6.x
 * @version      0.9.0
 * @author Petros Diveris
 *
 */
class TermData extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'term_data';

    /**
     * The PK is not id but tid
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'tid';

    /**
     * Version id
     *
     * @var int $vid
     */
    public $vid = 0;

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
     * Weight
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
