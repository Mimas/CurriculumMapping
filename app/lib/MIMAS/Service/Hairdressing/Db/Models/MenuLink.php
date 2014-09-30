<?php
/**
 * HT API MenuLink
 *
 * Drupal 6.x File
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

/**
 * Class MenuLink
 * This models the files table as defined in Drupal 6.x
 * @author Petros Diveris
 *
 */
class MenuLink extends \MIMAS\Service\Hairdressing\Db\JorumDbModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected static $table = 'menu_links';

    /**
     * The PK is not id but fid
     *
     * @var string $primaryKey
     */
    protected static $primaryKey = 'mlid';

    /**
     * The id
     * @var int $mlid
     */
    public $mlid = 0;

    /**
     * Menu name e.g. 'navigation', 'primary-links' etc.
     * In our case we are mostly interested in primary-links
     *
     * @var string $menu_name
     */
    public $menu_name = '';

    /**
     * Parent id
     * @var int $plid
     */
    public $plid = 0;

    /**
     * E.g. node/693
     * This is how we get a link to the node. There is no nid in the table, we have to extract from this string
     * That would not be that terrible if it wasn't for the fact that menu_links of type 'navigation' have
     * link_paths like 'node/%/delete' and 'admin/build/themes'.
     * Programmers like consistency or rather they hate the lack of.
     *
     *
     * @var string $link_path
     *
     */
    public $link_path = '';

    /**
     * Not quite sure what the router path is for. In many cases is the same as link_path
     * In instances where the link_path 'node/355' the router_path will be 'node/%'.
     *
     * @var string $router_path
     */
    public $router_path = '';

    /**
     * The readable title
     *
     * @var string $link_title
     */
    public $link_title = '';

    /**
     * PHP serialized array of stuff
     *
     * @var string $options
     */
    public $options = '';

    /**
     * Module (system, menu etc)
     * @var string $module
     */
    public $module = '';

    /**
     * Hidden - possible values -1,0,1
     *
     * @var int $hidden
     */
    public $hidden = 0;

    /**
     * Whether is external
     *
     * @var bool $external
     */
    public $external = 0;

    /**
     * Has it got children
     *
     * @var bool $has_children
     */
    public $has_children = 0;

    /**
     * Is it expanded
     *
     * @var bool $expanded
     */
    public $expanded = 0;

    /**
     * Its relative weight for sort ordering
     * @var int $weight
     */
    public $weight = 0;

    /**
     * Depth (1..9)
     * @var int
     */
    public $depth = 0;

    /**
     * Is it customized
     *
     * @var bool $depth
     */
    public $customized = 0;

    /**
     * Path 1 - horizontal tree representation, e.g. 114.
     * If this is top level then it will be the same as the mlid
     *
     * @var int $p1
     */
    public $p1 = 0;

    /**
     * Path 2 - horizontal tree representation, e.g 269
     * If his is depth two then it will be the same as its mlid. p1 will be its daddy, e.g. 114
     *
     * @var int
     */
    public $p2 = 0;

    /**
     * Path 3 - horizontal tree representation, e.g. 267
     * If we are three levels down in depth this will be the same as its mlid
     * Following the previous examples, p1 114 will be granddad and p2 269 it immediate parent
     * @var int $p3
     */
    public $p3 = 0;

    /**
     * Path 4, same logic as before
     * @var int $p4
     */
    public $p4 = 0;

    /**
     * Path 5, same logic as before
     * @var int $p5
     */
    public $p5 = 0;

    /**
     * Path 6, same logic as before
     * @var int $p6
     */
    public $p6 = 0;

    /**
     * Path 7, same logic as before
     * @var int $p7
     */
    public $p7 = 0;

    /**
     * Path 8, same logic as before
     * @var int $p8
     */
    public $p8 = 0;

    /**
     * Path 9, same logic as before
     * @var int $p9
     */
    public $p9 = 0;

    /**
     * Boolean updated
     * @var int $updated
     */
    public $updated = 0;

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
