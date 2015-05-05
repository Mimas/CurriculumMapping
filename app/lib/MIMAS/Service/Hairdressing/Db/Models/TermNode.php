<?php
/**
 * HT API Term Hierarchy
 *
 * Drupal 6.x TermHierarchy
 *
 * Link Nodes to Terms. We use this to count Nodes that constitute 'Guides' etc.
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
 * Class TermNode
 * This is a join table (node id/term id/version id)
 * Node to Term of relation table as defined in Drupal 6.x
 * @author Petros Diveris
 *
 */
class TermNode extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'term_node';

    /**
     * The PK is not id but tid
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'tid';

    /**
     * Node id
     * @var int $nid
     */
    public $nid = 0;

    /**
     * Version id
     *
     * @var int $vid
     */
    public $vid = 0;

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
