<?php
/**
 * HT API Term Hierarchy
 *
 * Drupal 6.x TermHierarchy
 *
 * Hierarchy of terms. In HT this is flat, i.e. all terms have no parent.
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing\Db\Models;

/**
 * Class TermHierarchy
 * This is a join table (id/parent)
 * Model hierarchy table as defined in Drupal 6.x
 * @version      0.9.0
 * @author Petros Diveris
 *
 */
class TermHierarchy extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'term_hierarchy';

    /**
     * The PK is not id but fid
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'tid';

    /**
     * Parent id. As mentioned above, this is 0 in HT
     * @var int $parent
     */
    public $parent = 0;

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
