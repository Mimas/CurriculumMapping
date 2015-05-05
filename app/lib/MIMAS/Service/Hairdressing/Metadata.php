<?php
/**
 * Hairdressing API
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

/**
 * Class Metadata
 * @package MIMAS\Service\Hairdressing
 */
class Metadata extends DrupalApi
{
    /**
     * attributes
     * @var array $attributes
     */
    protected $attributes = array();

    /**
     *
     * dcContributorAuthor
     * field_dc_contributor_author_value
     * This is the concatenated and comma separated author and contributor
     * field_dc_author_value, field_dc_contributor_value
     * USED
     * @var array $dcContributorAuthor
     */
    protected $dcContributorAuthor = array();

    /**
     * dcDateAccessioned
     * @var array $dcDateAccessioned
     */
    protected $dcDateAccessioned = array();

    /**
     * dcDateAvailable
     * @var array $dcDateAvailable
     */
    protected $dcDateAvailable = array();

    /**
     * dcDateIssued
     * @var array $dcDateIssued
     */
    protected $dcDateIssued = array();

    /**
     * dcIdentifierUri
     * @var array $dcIdentifierUri
     */
    protected $dcIdentifierUri = array();

    /**
     * dc.description
     * dcDescription
     * USED
     * @var array $dcDescription
     */
    protected $dcDescription = array();

    /**
     * dcRights
     * @var array $dcRights
     */
    protected $dcRights = array();

    /**
     * dcRightsUri
     * @var array $dcRightsUri
     */
    protected $dcRightsUri = array();

    /**
     * dc.subject
     * dcSubject
     * USED
     * @var array $dcSubject
     */
    protected $dcSubject = array();

    /**
     * dc.title
     * dcTitle
     * USED
     * @var array $dcTitle
     */
    protected $dcTitle = array();

    /**
     * dc.type
     * dcType
     * USED
     * @var array $dcType
     */
    protected $dcType = array();

    /**
     * dcSubjectJacs3
     * @var array $dcSubjectJacs3
     */
    protected $dcSubjectJacs3 = array();

    /**
     * dcSubjectJacs3code
     * @var array $dcSubjectJacs3code
     */
    protected $dcSubjectJacs3code = array();

    /**
     * dcSubjectLd
     * @var array $dcSubjectLd
     */
    protected $dcSubjectLd = array();

    /**
     * dc.subject.ldcode
     * dcSubjectLdcode
     * @var array $dcSubjectLdcode
     */
    protected $dcSubjectLdcode = array();


    /**
     * dc.publisher
     * dcPublisher
     * @var array $dcPublisher
     */
    protected $dcPublisher = array();

    /**
     * dc.format
     * $dcFormat
     * @var array
     */
    protected $dcFormat = array();

    /**
     * dc.audience
     * $dcAudience
     * @var array
     */
    protected $dcAudience = array();

    /**
     * dc.language
     * @var string $dcLanguage
     */
    protected $dcLanguage = '';

    /**
     * jmdOer
     * @var array $jmdOer
     */
    protected $jmdOer = array();

    /**
     * jmdResourceClass
     * @var array $jmdResourceClass
     */
    protected $jmdResourceClass = array();


    /**
     * jmdCommunity
     * @var array $jmdCommunity
     */
    protected $jmdCommunity = array();

    /**
     * Set attributes
     * @param array $attributes
     */
    public function setAttributes($attributes = array())
    {
        $this->attributes[$attributes['key']] = array_key_exists('value', $attributes) ? $attributes['value'] : "";
    }

    /**
     * Get attributes
     * @return array attributes
     */
    public function getAttributes()
    {
        return implode(',', $this->attributes);
    }

    /**
     * Set dcContributorAuthor
     * @param array $dcContributorAuthor
     */
    public function setDcContributorAuthor($dcContributorAuthor = array())
    {
        $this->dcContributorAuthor = $dcContributorAuthor;
    }

    /**
     * Get dcContributorAuthor
     * @return array dcContributorAuthor
     */
    public function getDcContributorAuthor()
    {
        return implode(',', $this->dcContributorAuthor);
    }

    /**
     * Set dcDateAccessioned
     * @param array $dcDateAccessioned
     */
    public function setDcDateAccessioned($dcDateAccessioned = array())
    {
        $this->dcDateAccessioned = $dcDateAccessioned;
    }

    /**
     * Get dcDateAccessioned
     * @return array dcDateAccessioned
     */
    public function getDcDateAccessioned()
    {
        return implode(',', $this->dcDateAccessioned);
    }

    /**
     * Set dcDateAccessioned
     * @param array $dcDateAvailable
     */
    public function setDcDateAvailable($dcDateAvailable = array())
    {
        $this->dcDateAccessioned = $dcDateAvailable;
    }

    /**
     * Get dcDateAccessioned
     * @return array $getDcDateAvailable
     */
    public function getDcDateAvailable()
    {
        return implode(',', $this->dcDateAvailable);
    }

    /**
     * Set dcDateIssued
     * @param array $dcDateIssued
     */
    public function setDcDateIssued($dcDateIssued = array())
    {
        $this->dcDateIssued = $dcDateIssued;
    }

    /**
     * Get dcDateIssued
     * @return array getDcDateIssued
     */
    public function getDcDateIssued()
    {
        return implode(',', $this->dcDateIssued);
    }

    /**
     * Set dcIdentifierUri
     * @param array $dcIdentifierUri
     */
    public function setDcIdentifierUri($dcIdentifierUri = array())
    {
        $this->dcIdentifierUri = $dcIdentifierUri;
    }

    /**
     * get dcIdentifierUri
     * @return array getDcIdentifierUri
     */
    public function getDcIdentifierUri()
    {
        return implode(',', $this->dcIdentifierUri);
    }

    /**
     * Set dcDescription
     * @param array $dcDescription
     */
    public function setDcDescription($dcDescription = array())
    {
        $this->dcDescription = $dcDescription;
    }

    /**
     * Get dcDescription
     * @return array getDcDescription
     */
    public function getDcDescription()
    {

        return $this->dcDescription;
    }

    /**
     * Set dcRights
     * @param array $dcRights
     */
    public function setDcRights($dcRights = array())
    {
        $this->dcRights = $dcRights;
    }

    /**
     * Get dcRights
     * @return array getDcRights
     */
    public function getDcRights()
    {
        return implode(',', $this->dcRights);
    }

    /**
     * Set dcRightsUri
     * @param array $dcRightsUri
     */
    public function setDcRightsUri($dcRightsUri = array())
    {
        $this->dcRightsUri = $dcRightsUri;
    }

    /**
     * Get dcRightsUri
     * @return array getDcRightsUri
     */
    public function getDcRightsUri()
    {
        return $this->dcRightsUri;
    }

    /**
     * Set dcSubject
     * @param array $dcSubject
     */
    public function setDcSubject($dcSubject = array())
    {
        $this->dcSubject = $dcSubject;
    }

    /**
     * Get dcSubject
     * @return array getDcSubject
     */
    public function getDcSubject()
    {
        return $this->dcSubject;
    }

    /**
     * Set dcTitle
     * @param array $dcTitle
     */
    public function setDcTitle($dcTitle = array())
    {
        $this->dcTitle = $dcTitle;
    }

    /**
     * Get dcTitle
     * @return array getDcTitle
     */
    public function getDcTitle()
    {
        return $this->dcTitle;
    }

    /**
     * Set dcSubjectJacs3
     * @param array $dcSubjectJacs3
     */
    public function setDcSubjectJacs3($dcSubjectJacs3 = array())
    {
        $this->dcSubjectJacs3 = $dcSubjectJacs3;
    }

    /**
     * Get dcSubjectJacs3
     * @return array getDcSubjectJacs3
     */
    public function getDcSubjectJacs3()
    {
        return $this->dcSubjectJacs3;
    }

    /**
     * Set dcSubjectJacs3code
     * @param array $dcSubjectJacs3code
     */
    public function setDcSubjectJacs3code($dcSubjectJacs3code = array())
    {
        $this->dcSubjectJacs3code = $dcSubjectJacs3code;
    }

    /**
     * Get dcSubjectJacs3code
     * @return array getDcSubjectJacs3code
     */
    public function getDcSubjectJacs3code()
    {
        return $this->dcSubjectJacs3code;
    }

    /**
     * Set dcSubjectLd
     * @param array $dcSubjectLd
     */
    public function setDcSubjectLd($dcSubjectLd = array())
    {
        $this->dcSubjectLd = $dcSubjectLd;
    }

    /**
     * Get dcSubjectLd
     * @return array getDcSubjectLd
     */
    public function getDcSubjectLd()
    {
        return implode(',', $this->dcSubjectLd);
    }

    /**
     * Get dcSubjectLdcode
     * @param array $dcSubjectLdcode
     */
    public function setDcSubjectLdcode($dcSubjectLdcode = array())
    {
        $this->dcSubjectLdcode = $dcSubjectLdcode;
    }

    /**
     * Set dcSubjectLdcode
     * @return array getDcSubjectLdcode
     */
    public function getDcSubjectLdcode()
    {
        return $this->dcSubjectLdcode;
    }

    /**
     * Set setJmdOer
     * @param array $jmdOer
     */
    public function setJmdOer($jmdOer = array())
    {
        $this->jmdOer = $jmdOer;
    }

    /**
     * get setJmdOer
     * @return array getJmdOer
     */
    public function getJmdOer()
    {
        return implode(',', $this->jmdOer);
    }

    /**
     * Set jmdResourceClass
     * @param array $jmdResourceClass
     */
    public function setJmdResourceClass($jmdResourceClass = array())
    {
        $this->jmdResourceClass = $jmdResourceClass;
    }

    /**
     * Get jmdResourceClass
     * @return array $jmdResourceClass
     */
    public function getJmdResourceClass()
    {
        return implode(',', $this->jmdResourceClass);
    }

    /**
     * Set jmdCommunity
     * @param array $jmdCommunity
     */
    public function setJmdCommunity($jmdCommunity = array())
    {
        $this->jmdCommunity = $jmdCommunity;
    }

    /**
     * Get jmdCommunity
     * @return array getJmdCommunity
     */
    public function getJmdCommunity()
    {
        return implode(',', $this->jmdCommunity);
    }

    /**
     * Set dcAudience
     * @param array $dcAudience
     */
    public function setDcAudience($dcAudience)
    {
        $this->dcAudience = $dcAudience;
    }

    /**
     * Get dcAudience
     * @return array
     */
    public function getDcAudience()
    {
        return $this->dcAudience;
    }

    /**
     * Get dcFormat
     * @return array
     */
    public function getDcFormat()
    {
        return $this->dcFormat;
    }

    /**
     * Set dcFormat
     * @param array $dcFormat
     */
    public function setDcFormat($dcFormat)
    {
        $this->dcFormat = $dcFormat;
    }

    /**
     * Set dcPublisher
     * @param array $dcPublisher
     */
    public function setDcPublisher($dcPublisher)
    {
        $this->dcPublisher = $dcPublisher;
    }

    /**
     * Get dcPublisher
     * @return array
     */
    public function getDcPublisher()
    {
        return $this->dcPublisher;
    }

    /**
     * Set dcLanguage
     * @param string $dcLanguage
     */
    public function setDcLanguage($dcLanguage)
    {
        $this->dcLanguage = $dcLanguage;
    }

    /**
     * Get dcLanguage
     * @return string
     */
    public function getDcLanguage()
    {
        return $this->dcLanguage;
    }

    /**
     * Constructor
     * @param array $data
     */
    public function __construct($data = array())
    {
        /*
        array (size=2)
          'key' => string 'dc.contributor.author' (length=21)
          'value' => string 'Matthew Ramirez' (length=15)
        */


        foreach ($data as $key => $value) {

            // field_dc_contributor_author_value => dcContributorAuthor
            $key = str_replace('field_dc', 'dc', $key);
            $key = str_replace('_value', '', $key);

            $this->setAttributes(array('key' => $key, 'value' => $value));

            // dc_contributor_author -> setDcContributorAuthor
            $parts = explode('_', $key);
            $method = 'set';
            $i = 0;
            foreach ($parts as $part) {
                $method .= ucfirst($part);
                $i++;
            }
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        $this->setDcTitle($this->getDcDescription());
    }

    /**
     * Magic getSomething method
     * @param $name
     * @return bool|string
     *
     */
    public function __get($name)
    {
        $name = self::functionNameToKey($name);
        return $this->getAttribute($name);
    }

    /**
     * Magic setSomthing method
     * @param $name
     * @param $args
     */
    public function __set($name, $args)
    {
        echo "Auto call for $name<br/>";
    }

    /**
     * Catch all function to provide resolution for arbitrary attribute getters such as getSomeAttribute()
     * This would suppose that there is an item in the attributes array with some.attribute as its key
     *
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        $name = self::functionNameToKey($name);
        return $this->getAttribute($name);
    }

    /**
     * Return true if it has attribute
     * @param string $attribute
     * @return bool
     */
    public function hasAttribute($attribute = '')
    {
        foreach ($this->attributes as $key => $val) {
            if ($key == $attribute)
                return true;
        }
        return false;
    }

    /**
     * Return a value associated with the attribute
     * The attribute needs to be a Jorum/DSPACE namespaced key such as dc.subject.iso32e4
     *
     * @param string $attribute
     * @return string|bool
     */
    public function getAttribute($attribute = '')
    {
        foreach ($this->attributes as $key => $val) {
            if ($key == $attribute)
                return $val;
        }
        return false;
    }


    /**
     * Convert a snake_case string to a name.space string
     * @param string $name
     * @return mixed|string
     */
    public static function functionNameToKey($name = '')
    {
        $name = camel_case(substr($name, 3)); // take getSomeKey and convert to someKey
        $name = snake_case($name); // now, convert to some_key

        $name = str_replace('_', '.', $name);

        return $name;
    }


    /**
     * Magic to call when treating instance as a string
     * @return mixed this as string..
     */
    public function __toString()
    {
        return var_export($this, true);
    }

}