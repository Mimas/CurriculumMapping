<?php
/**
 * HT API File
 *
 * Drupal 6.x File
 *
 * This model deals with the various uploaded files in Drupal 6.x
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
 * Class File
 * This models the files table as defined in Drupal 6.x
 *
 * @author Petros Diveris
 *
 */
class File extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'files';

    /**
     * The PK is not id but fid
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'fid';

    /**
     * The id
     * @public int $fid;
     */
    public $fid = 0;

    /**
     * ui
     * @var int $uid
     */
    public $uid = 0;

    /**
     * filename
     * @var string $filename
     */
    public $filename = '';

    /**
     * mime type
     * @var string $filemime
     */
    public $filemime = '';

    /**
     * size
     * @var int $filesize
     */
    public $filesize = 0;

    /**
     * status (enabled/disabled)
     * @var int $status
     */
    public $status = 0;

    /**
     * timestamp
     * @var int $timestamp
     */
    public $timestamp = 0;

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
