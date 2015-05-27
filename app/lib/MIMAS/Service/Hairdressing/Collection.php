<?php
/**
 * HT API
 *
 * HT implementation of the Collection
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @version      0.9.0
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 *
 */
namespace MIMAS\Service\Hairdressing;

use MIMAS\Service\Hairdressing\Db\Models\ContentTypePage;
use MIMAS\Service\Hairdressing\Db\Models\MenuLink;

/**
 * Class Collection
 * @package MIMAS\Service\Hairdressing
 */
class Collection extends DrupalApi
{
    /**
     * The id
     * @var string
     */
    public $id = '';

    /**
     * Handle - this is emulated by using 'node/ID'
     * @var string
     */
    public $handle = '';

    /**
     * Name
     *
     * @var string
     */
    public $name = '';

    /**
     * Type. Fixed to community.
     *
     * @var string
     */
    public $type = 'collection';

    /**
     * Link
     *
     * @var string
     */
    public $link = '';

    /**
     * Array of attributes that can be expanded
     * @var array
     */
    public $expand = array(
      'parentCommunityList',
      'parentCommunity',
      'items',
      'license',
      'all',
    );

    /**
     * Array of expandable attributes
     *
     * @var array
     */
    public static $expandable = array(
      "parentCommunityList",
      "parentCommunity",
      "items",
      "license",
      "all"
    );

    /**
     * Logo
     *
     * @var string $logo
     */
    public $logo = null;

    /**
     * This will be always null in HT
     *
     * @var array $parentCommunity
     */
    public $parentCommunity = null;

    /**
     * Always null in HT
     *
     * @var array $parentCommunityList
     */
    public $parentCommunityList = null;

    /**
     * List of items
     *
     * @var array $items
     */
    public $items = array();

    /**
     * License stamp
     *
     * @var string $license
     */
    public $license = '';

    /**
     * Number of items in this Collection
     *
     * @var int $numberItems
     */
    public $numberItems = 0;

    /**
     * Constructor. See base class for use of params
     *
     * @see DrupalApi
     * @param string $params
     * @param array $expands
     */
    public function __construct($params = '', $expands = array())
    {
        parent::__construct($params, $expands);

        if (isset($params['nid'])) {
            $this->id = $params['nid'];
            $this->handle = 'node/' . $this->id;
            $this->link = '/hairdressing/api/community/' . $this->id;

        }
        if (isset($params['title'])) {
            $this->name = $params['title'];
        }

        if (isset($params['type'])) {
            $this->type = $params['type'];
        }

        if (isset($params['changed'])) {
            $this->lastModified = date('Y-m-d H:i:s\.02', $params['changed']);
        }

        if (isset($params['expand'])) {
            $this->expand = $params['expand'];
        }

        //  "metadata", "collections", "parentCommunity", "subCommunities", "logo", "all"
        //foreach ($expands as $expand) {
        //  $method = "populate".ucfirst($expand);
        //  $this->$method();
        //}

    }

    /**
     * Wrapper method to find something and return it
     *
     * @param string $id
     * @param array $options
     * @internal param string $outputFormat
     * @internal param string $inputFormat
     * @return \MIMAS\Service\Jorum\Item $item
     */
    public static function find($id = '', $options = array())
    {
        $item = new Collection();
        return $item->findByIdOrHandle($id, $options);
    }

    /**
     * Find the extra fields and map them to the metadata
     */
    public function populateMetadata()
    {
        $nid = str_replace('node/', '', $this->handle);
        $contentTypePage = ContentTypePage::where('nid', '=', "'$nid'")->get();

        if ($contentTypePage->getItems() > 0) {

            $item = $contentTypePage->getItems()[0];
            $metaData = new \MIMAS\Service\Hairdressing\Metadata((array)$item);

            $this->metadata = $metaData;
        }
    }


    /**
     * Get list of expandable attributes for this class
     * @return array
     */
    public function getExpandable()
    {
        return self::$expandable;
    }

    /**
     * Set expandable
     * @param array $expandable
     */
    public function setExpandable($expandable = array())
    {
        self::$expandable = $expandable;
    }
}