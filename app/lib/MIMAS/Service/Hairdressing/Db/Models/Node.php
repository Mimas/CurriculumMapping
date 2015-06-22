<?php
/**
 * HT API Node
 *
 * Drupal 6.x Node
 *
 * This model deals with the Drupal twisted tree representation
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing\Db\Models;

use MIMAS\Service\Hairdressing\DrupalApi;

/**
 * Class Node
 * This models the node table as defined in Drupal 6.x
 *
 * @author Petros Diveris
 */
class Node extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     * @var string
     */
    protected static $table = 'node';

    /**
     * The PK 'nid'
     * @var string $primaryKey
     */
    protected static $primaryKey = 'nid';

    /**
     * Node id
     * @property int $nid
     */
    public $nid = 0;

    /**
     * Version id
     * @public int $vid;
     */
    public $vid = 0;

    /**
     * The Node type e.g. page, story, poll, step etc
     *
     * @public string $type
     */
    public $type = '';

    /**
     * Language. Hopefully a string like 'en-UK'
     *
     * @public string $language
     */
    public $language = '';

    /**
     * The title of the Node. It's also affected by versions
     *
     * @public string $title
     */
    public $title = '';

    /**
     * User id (owner)
     * @public int $uid
     */
    public $uid = 0;

    /**
     * Status flag
     *
     * @public int $status
     */
    public $status = 0;

    /**
     * Time created
     *
     * @public int $created
     */
    public $created = 0;

    /**
     * Time updated
     *
     * @public int $changed
     */
    public $changed = 0;

    /**
     * Number of comments I believe
     *
     * @public int $comment
     */
    public $comment = 0;

    /**
     * Flag - promote
     *
     * @public int $promote
     */
    public $promote = 0;

    /**
     * Flag - moderate
     *
     * @public int $moderate
     */
    public $moderate = 0;

    /**
     * Flag - sticky
     * @public int $sticky
     */
    public $sticky = 0;

    /**
     * Translation node id
     *
     * @public int $tnid
     */
    public $tnid = 0;

    /**
     * Flag - translate
     *
     * @public int $translate
     */
    public $translate = 0;

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
