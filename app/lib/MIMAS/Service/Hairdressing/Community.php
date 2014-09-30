<?php
/**
 * Hairdressing API
 *
 * Item
 *
 * Community implementation for HT
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
use MIMAS\Service\Jorum\Metadata;

/**
 * Class Community
 * @package MIMAS\Service\Hairdressing
 */
class Community extends DrupalApi
{
    /**
     * The ID
     * @var string
     */
    public $id = '';

    /**
     * A handle in the form of node/id
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
     * Type. Fixed to community
     *
     * @var string
     */
    public $type = 'community';

    /**
     * Link string
     *
     * @var string
     */
    public $link = '';

    /**
     * Array of attributes that can be expanded
     * @var array
     */
    public $expand = array(
      'metadata',
      'collections',
      'all'
    );

    /**
     * Array of expandable attributes
     * @var array
     */
    public static $expandable = array(
      'parentCommunity',
      'collections',
      'metadata',
      'subCommunities',
      'logo',
      'all',
    );

    /**
     * Logo URI
     *
     * @var string
     */
    public $logo = null;

    /**
     * No parent child relationship in HT means that this will be always null
     * @var array
     */
    public $parentCommunity = null;

    /**
     * Copyright text
     *
     * @var string
     */
    public $copyrightText = '';

    /**
     * Intro text. Not implemented in HT so always empty.
     * @var string
     */
    public $introductoryText = '';

    /**
     * Short description
     *
     * @var string
     */
    public $shortDescription = '';

    /**
     * Sidebar text
     *
     * @var string
     */
    public $sidebarText = '';

    /**
     * Item count
     *
     * @var int
     */
    public $countItems = 0;

    /**
     * Collections grouped under this Community
     *
     * @var array
     */
    public $collections = array();

    /**
     * Subcommunities - always empty
     * @var array
     */
    public $subCommunities = array();

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

            $this->populateCollections();

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
        $item = new Community();
        return $item->findByIdOrHandle($id, $options);
    }

    /**
     * Get Collections that fall under this Community
     * Locally implemented as it's specific to Communities
     *
     * @todo: use class constants for link (/hairdressing/api/)
     */
    public function populateCollections()
    {

        $result = Db\Models\MenuLink::where('menu_name', '=', "'primary-links'")
          ->andWhere('p1', '=', ' (select p1 from menu_links where link_path=\'node/' . $this->id . '\') ')
          ->andWhere('depth', '=', 2)
          ->andWhere('hidden', '=', 0)
          ->get();

        foreach ($result->getItems() as $item) {
            $collection = new Collection(
              array(
                'id' => str_replace('node/', '', $item->link_path), // Drupal clever non-link 'node/1'=>1. Why?,
                'name' => $item->link_title,
                'handle' => $item->link_path,
                'parentCommunity' => $this->id,
                'link' => '/hairdressing/api/collection/' . str_replace('node/', '', $item->link_path),
                'numberItems' => self::getSiblingsCount($item->mlid, 2, array(3))

              )
            );
            $collectionList[] = $collection;
        }
        $this->collections = $collectionList;

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


}