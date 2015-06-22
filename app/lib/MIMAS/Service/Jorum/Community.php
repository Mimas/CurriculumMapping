<?php
/**
 * Jorum API Community
 *
 * Community
 *
 * Top level grouping of collections and ultimately items
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service\Jorum;

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 */
class Community extends JorumApi
{
    /**
     * Id
     * @var string $id
     */
    protected $id = '';

    /**
     * handle
     * @var string $handle
     */
    protected $handle = '';

    /**
     * name
     * @var string $name
     */
    protected $name = '';

    /**
     * type
     * @var string $type
     */
    protected $type = '';

    /**
     * link
     * @var string $link
     */
    protected $link = '';

    /**
     * Expand array (e.g. metadata, all)
     * @var array $expand
     */
    protected $expand = array();

    /**
     * logo
     * @var string
     */
    protected $logo = '';

    /**
     * parentCommunity
     * @var array $parentCommunity
     */
    protected $parentCommunity = array();

    /**
     * copyrightText
     * @var string $copyrightText
     */
    protected $copyrightText = '';

    /**
     * introductoryText
     * @var string $introductoryText
     */
    protected $introductoryText = '';

    /**
     * shortDescription
     * @var string $shortDescription
     */
    protected $shortDescription = '';

    /**
     * sidebarText
     * @var string $sidebarText
     */
    protected $sidebarText = '';

    /**
     * countItems
     * @var int $countItems
     */
    protected $countItems = 0;

    /**
     * collections
     * @var array $collections
     */
    protected $collections = array();

    /**
     * subCommunities
     * @var array $subcommunities
     */
    protected $subCommunities = array();

    /**
     * Constructor. Data or format passed to Base model
     *
     * @param string $params
     * @param string $inputFormat
     * @param array $options
     */
    public function __construct($params = '', $inputFormat = 'application/json', $options = array())
    {
        parent::__construct($params, $inputFormat, $options);
    }

    /**
     * Get Id
     * @return string id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Id
     * @param string $id
     */
    public function setId($id = '')
    {
        $this->id = $id;
    }

    /**
     * Get handle
     * @return string handle
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Set handle
     * @param string $handle
     */
    public function setHandle($handle = '')
    {
        $this->handle = $handle;
    }

    /**
     * Get name
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name = '')
    {
        $this->name = $name;
    }

    /**
     * Get type
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     * @param string $type
     */
    public function setType($type = '')
    {
        $this->type = $type;
    }

    /**
     * Get link
     * @return string link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link
     * @param string $link
     */
    public function setLink($link = '')
    {
        $this->link = $link;
    }

    /**
     * Get expand array (e.g. (0=>'all',1=>'metadata')
     * @return array
     */
    public function getExpand()
    {
        return $this->expand;
    }

    /**
     * Set expand array (e.g. (0=>'all',1=>'metadata')
     * @param string $expand
     */
    public function setExpanded($expand = '')
    {
        $this->expand = $expand;
    }

    /**
     * Get logo
     * @return string logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logo
     * @param string $logo
     */
    public function setLogo($logo = '')
    {
        $this->logo = $logo;
    }

    /**
     * Get [[arentCommunity
     * @return array parentCommunity
     */
    public function getParentCommunity()
    {
        return $this->parentCommunity;
    }

    /**
     * Set parentCommunity
     * @param string $parentCommunity
     */
    public function setParentCommunity($parentCommunity = '')
    {
        $this->parentCommunity = $parentCommunity;
    }

    /**
     * Get copyrightText
     * @return string copyrightText
     */
    public function getCopyrightText()
    {
        return $this->copyrightText;
    }

    /**
     * Set copyrightText
     * @param string $copyrightText
     */
    public function setCopyrightText($copyrightText = '')
    {
        $this->copyrightText = $copyrightText;
    }

    /**
     * Get introductoryText
     * @return string introductoryText
     */
    public function getIntroductoryText()
    {
        return $this->introductoryText;
    }

    /**
     * Set introductoryText
     * @param string $introductoryText
     */
    public function setIntroductoryText($introductoryText = '')
    {
        $this->introductoryText = $introductoryText;
    }

    /**
     * Get shortDescription
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set shortDescription
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription = '')
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * get sidebarText
     * @return string sidebarText
     */
    public function getSidebarText()
    {
        return $this->sidebarText;
    }

    /**
     * Set sidebarText
     * @param string $sidebarText
     */
    public function setSidebarText($sidebarText = '')
    {
        $this->sidebarText = $sidebarText;
    }

    /**
     * get countItems
     * @return int countItems
     */
    public function getCountItems()
    {
        return $this->countItems;
    }

    /**
     * Set countItems
     * @param int $countItems
     */
    public function setCountItems($countItems = 0)
    {
        $this->countItems = $countItems;
    }

    /**
     * Get collections
     * @return array collections
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * Set collections
     * @param array $collections
     */
    public function setCollections($collections = array())
    {
        $this->collections = $collections;
    }

    /**
     * Get subCommunities
     * @return array subCommunities
     */
    public function getSubCommunities()
    {
        return $this->subCommunities;
    }

    /**
     * Set subCommunities
     * @param array $subCommunities
     */
    public function setSubCommunities($subCommunities = array())
    {
        $this->subCommunities = $subCommunities;
    }

    /**
     * Wrapper function for findByIdOrHandle
     *
     * @param string $id
     * @param array $options
     * @param string $outputFormat
     * @param string $inputFormat
     * @return \MIMAS\Service\Jorum\Item $item
     */
    public static function find($id = '', $options = array(), $outputFormat = '', $inputFormat)
    {
        $community = new Community($outputFormat, $inputFormat);
        return $community->findByIdOrHandle($id, $options);
    }

}