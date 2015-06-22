<?php
/**
 * Jorum API Context
 *
 * Item
 *
 * Item held in Jorum
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 */
namespace MIMAS\Service\Jorum;

use MIMAS\Service\JorumModel;

use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 25/04/2014
 * Time: 14:59
 */
class Metadata extends JorumModel
{
    /**
     * attributes
     * @var array $attributes
     */
    protected $attributes = array();

    /**
     * dcContributorAuthor
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
     * dcDescription
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
     * dcSubject
     * @var array $dcSubject
     */
    protected $dcSubject = array();

    /**
     * dcTitle
     * @var array $dcTitle
     */
    protected $dcTitle = array();

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
     * dcSubjectLdcode
     * @var array $dcSubjectLdcode
     */
    protected $dcSubjectLdcode = array();

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
     * jmdResourceclass
     * @var array $jmdResourceclass
     */
    protected $jmdResourceclass = array();

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
        return implode(',', $this->dcDescription);
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
        return implode(',', $this->dcRightsUri);
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
        return implode(',', $this->dcSubject);
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
        return implode(',', $this->dcTitle);
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
        return implode(',', $this->dcSubjectJacs3);
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
        return implode(',', $this->dcSubjectJacs3code);
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
        return implode(',', $this->dcSubjectLdcode);
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
     * Set jmdResourceclass
     * @param array $jmdResourceclass
     */
    public function setJmdResourceclass($jmdResourceclass = array())
    {
        $this->jmdResourceclass = $jmdResourceclass;
    }

    /**
     * Get jmdResourceclass
     * @return array getJmdResourceclass
     */
    public function getJmdResourceclass()
    {
        return implode(',', $this->jmdResourceclass);
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
     * Constructoe
     * @param array $data
     */
    public function __construct($data = array())
    {
        /*
        array (size=2)
          'key' => string 'dc.contributor.author' (length=21)
          'value' => string 'Matthew Ramirez' (length=15)
        */

        foreach ($data as $i => $datum) {
            $pair = (array)$datum;

            if (array_key_exists('key', $pair)) {

                $key = $pair['key'];
                $value = array_key_exists('value', $pair) ? $pair['value'] : "";

                $key = str_replace('.', '_', $key);
                $key = camel_case($key);

                $this->setAttributes($pair);

                if (method_exists($this, "set$key")) {
                    if (!is_array($this->$key)) {
                        echo "<h3>HORROR: $key</h3>";
                    } else {
                        $array = array_merge($this->$key, array($value));
                        $this->$key = $array;
                    }
                }

            } else {
                echo "<h3>Uknown: $key / $value</h3>";
            }
        }
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
     *
     */
    public function __set($name, $args)
    {
        echo "Some magic function for $name<br/>";
    }

    /**
     *
     * Catch all function to provide resolution for arbitrary attribute getters such as getSomeAttribute()
     * This would suppose that there is an item in the attributes array with some.attribute as its key
     *
     * @param $name
     * @param $args
     * @return mixed
     *
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
     *
     * Return a value associated with the attribute
     * The attribute needs to be a Jorum/DSPACE namespaced key such as dc.subject.iso32e4
     *
     * @param string $attribute
     * @return string|bool
     *
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
     *
     * Convert a snake_case string to a name.space string
     *
     * @param string $name
     * @return mixed|string
     *
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
     *
     */
    public function __toString()
    {
        return var_export($this, true);
    }

}