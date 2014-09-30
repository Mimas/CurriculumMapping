<?php
/**
 * Jorum API Base Class
 *
 * Jorum API Base Class - it constructs the URLs and fires requests to the JorumAPI
 *
 * @package      MIMAS
 * @subpackage   Service
 * @category     API
 * @author       Petros Diveris <petros.diveris@manchester.ac.uk>
 * @version      0.1
 *
 */
namespace MIMAS\Service\Jorum;

use jyggen\Curl;


/**
 * Class JorumApi
 * Jorum API Base Class - it constructs the URLs and fires requests to the JorumAPI
 *
 * It can cache results - currently in REDIS only - depending on the configuration.
 *
 * Date: 25/04/2014
 * Time: 14:59
 *
 * @package MIMAS\Service\Jorum
 * @version 1.0.1
 * @todo Inject Cache object
 *
 *
 */
class JorumApi implements \JsonSerializable, \IteratorAggregate
{
    /**
     * JSON
     */
    const ACCEPT_JSON = 'application/json';

    /**
     * XML
     */
    const ACCEPT_XML = 'application/xml';

    /**
     * Service point for getting list of handles - as requested by K-Int
     */
    const SERVICE_HANDLES = 'items/handles';

    /**
     *  The API access URL.
     *
     * @var string apiUrl
     * @todo testing todos
     *
     */
    protected static $apiUrl;

    /**
     * The URI specific to the service i.e. /items, /collections etc.
     *
     * @var string servicePoint
     */
    protected static $servicePoint;

    /**
     * The model name e.g. 'Item'.
     * @var string
     */
    protected static $model = '';

    /**
     * Parameters specific to the service, e.g. search patterns and keys for /search.
     *
     * @var mixed serviceParams
     */
    private static $serviceParams;

    /**
     * Boolean cached. Whether is cached or not.
     * @var bool cached
     */
    private static $cached = false;

    /**
     * Mime type of requested content e.g. application/xml.
     *
     * @var string
     *
     */
    private static $httpAccept = 'application/json';

    /**
     * Output format e.g. xml.
     *
     * @var string $outputFormat
     */
    private static $outputFormat = 'php'; // xml | json | html | vardump

    /**
     * Raw data buffer for whatever we got back.
     *
     * @var string
     */
    private static $rawData = '';

    /**
     * Parsed data.
     *
     * @var string
     */
    private static $data = '';

    /**
     * extra options e.g. limit and offset
     * @var array
     */
    protected $options = array();

    /**
     *
     * Constructor, accepts a string or an array as a paramater
     *
     * If an associative array of data is passed such as (id=>10, 'title'=>'Booklet: St John Ambulance Association Preliminary First Aid')
     * then an instance of the object will be created and populated.
     *
     * Alternatively, an output format can be passed here, such as json, xml, html and php, the latter for debugging
     *
     * @param string $params
     * @param string $inputFormat
     * @param array $options
     *
     */
    public function __construct($params = '', $inputFormat = 'json', $options = array())
    {
        self::$httpAccept = "application/$inputFormat";

        // set options e.g. offset and limit
        $this->setOptions($options);

        $reflectionClass = new \ReflectionClass($this);

        if (null !== ($constructor = $reflectionClass->getConstructor())) {
            /**
             * 1. Set the API access point.
             * Generally this should be just getting the parameter from the right config (local,dev,production) and setting it
             * However, we might modify it here forcibly to accommodate for the fact that for example staging is throwing an out of memory error
             */
            self::$apiUrl = \Config::get('app.apiUrl');

            // NOPE, staging is DEAD
            self::$apiUrl = 'http://dspace.jorum.ac.uk/rest/';
            // self::$apiUrl = 'http://10.99.120.145:8080/rest/';

            /**
             * 2. get my class
             * The aim of this is to guess the access point from the daughter Model class e.g. Items->items
             */
            $classArray = explode('\\', $constructor->class);

            if (is_array($classArray) && count($classArray) > 0) {
                self::$model = strtolower($classArray[count($classArray) - 1]);

                // access points in this API are plural words..
                self::$servicePoint = str_plural(self::$model); // so if this was a call coming from the invocation of Item()->something() then resolve to /item
            }

            if (is_array($params)) { // initial data in the form if key=>'val'

                foreach ($params as $key => $value) {
                    if ($key == 'ID') { // XML returns the id in Upper Case.
                        $key = 'id';
                    }

                    /*
                     * Below we check if the property exists. If it's a standard property such as 'title' then simply set it
                     * If the property is a reference to a collection of known classes then instantiate them and push in their data
                     * If it's a collection of METADATA then instantiate a Metadata object and create the pseudo attributes
                     */

                    if (property_exists($this, $key)) {
                        $singKey = $key <> 'metadata' ? str_singular($key) : 'metadata'; // this is to avoid the conversion of metadata to metadatum

                        $className = '\\MIMAS\\Service\\Jorum\\' . studly_case($singKey); // studly case means 'some_array' gets converted 'SomeArray'

                        // right. if this is an object (i.e. a Bitstream or Metadata) then create it
                        if (class_exists(studly_case($className))) {
                            // metadata, bitstreams, collections etc...
                            $this->$key = self::factory($className, $key, $value);

                        } else { // otherwise, set it as raw data e.g. as string
                            $this->$key = $value;
                        }
                    }
                }
            } elseif ($params <> '') {
                self::$outputFormat = $params; // We were called with a format suffix (i.e. json | xml)
            }
        }

    }

    /**
     *
     * Method to instantiate a number of object instances
     * and return them as an array to populate for example an ItemList
     * when retrieving a list of items rather then a single one
     *
     * @param $className
     * @param $key
     * @param $value
     * @return array|Metadata
     *
     */
    public static function factory($className, $key, $value)
    {
        $objects = array();

        if ($key == 'metadata') {

            return new \MIMAS\Service\Jorum\Metadata($value);
        }

        if (is_array($value) && count($value) > 0) {

            foreach ($value as $i => $object) {
                $objects[] = new $className((array)$object);
            }
        }
        return $objects;
    }

    /**
     * Perform an apiCall and return our stream
     * This is essentially a cURL encapsulator
     * to a Http Helper class
     *
     * @param $url
     * @param string $data
     * @param array $params 'method'=>('GET', 'POST'), 'options'=>('expand','offset')
     * @return bool
     * @throws \Exception
     *
     * @todo Make the cache an injectable dependency so that it doesn't rely on a specific framework
     *
     */
    public static function apiCall($url, $data = '', $params = array())
    {
        // add params to service url such as ?expand=metadata,bitstreams
        if (array_key_exists('options', $params)) {
            $i = 0;
            foreach ($params['options'] as $key => $value) {
                $url .= ($i == 0) ? '?' : '&';
                $url .= $key . '=' . $value;
                $i++;
            }
        }
        // generate a unique cache identifier based on the URL after we added the various options
        $id = md5($url);

        $stream = false;

        if (\Config::get('app.apiCache') > 0) { // only try to read from the cache if we have set a an apiCache > 0 in app.php
            $stream = Cache::get($id);
        }

        $method = strtoupper(array_key_exists('method', $params) ? $params['method'] : 'get');

        if (!$stream) { // if we have no cached copy then fetch it
            if (isset($params['headers'])) {
                if (self::$httpAccept == 'application/xml') {
                    $response = \Httpful\Request::$method($url)
                      ->addHeaders($params['headers']) // Or use the addHeader method
                      ->expectsXml()
                      ->parseWith(function ($body) {
                          return $body;
                      })
                      ->send();

                } else {
                    $response = \Httpful\Request::$method($url)
                      ->addHeaders($params['headers']) // Or use the addHeader method
                      ->expectsJson()
                      ->parseWith(function ($body) {
                          return $body;
                      })
                      ->send();
                }
            } else {
                if (self::$httpAccept == 'application/xml') {
                    $response = \Httpful\Request::$method($url)
                      ->expectsXml()
                      ->parseWith(function ($body) {
                          return $body;
                      })
                      ->expectsXml()
                      ->send();
                } else {
                    $response = \Httpful\Request::$method($url)
                      ->expectsJson()
                      ->parseWith(function ($body) {
                          return $body;
                      })
                      ->send();
                }
            }

            if (!$response->hasErrors()) {
                $stream = $response->body;

                if (\Config::get('app.apiCache') > 0) {
                    $expiresAt = \Carbon\Carbon::now()->addSeconds(Config::get('app.apiCache'));
                    \Cache::put($id, $stream, $expiresAt);
                }
            } else {
                throw new \Exception("CURL problems $url ");
            }
            return ($response->body);
        } else {
            self::setCached(true);
        }
        return $stream;
    }

    /**
     * Setter method for private property $cached
     * @param $cached
     */
    public static function setCached($cached)
    {
        self::$cached = $cached;
    }

    /**
     * Getter method for private property $servicePoint
     * @return string
     */
    public static function getServicePoint()
    {
        return self::$servicePoint;
    }

    /**
     * Setter method for private property $servicePoint
     * @param $servicePoint
     */
    public static function setServicePoint($servicePoint)
    {
        self::$servicePoint = $servicePoint;
    }

    /**
     * Getter method for private property $model
     * The model is one of Item, Metadata, Community, Collection or Bitstream
     * @return string
     */
    public static function getModel()
    {
        return self::$model;
    }

    /**
     * Setter method for private property $model
     * The model is one of Item, Metadata, Community, Collection or Bitstream
     * @param $model
     */
    public static function setModel($model)
    {
        self::$model = $model;
    }

    /**
     * Getter for the private property $rawData which holds the data is returned from the API
     * @return string
     */
    public static function getRawData()
    {
        return self::$rawData;
    }

    /**
     * Setter for the private property $rawData which holds the data is returned from the API
     * @param $data
     */
    public static function setRawData($data)
    {
        self::$rawData = $data;
    }

    /**
     * Getter for the private property $data which holds the converted data
     * @return string
     */
    public static function getData()
    {
        return self::$data;
    }

    /**
     * Setter for the private property $data which holds the converted data
     * @param $data
     */
    public static function setData($data)
    {
        self::$rawData = $data;
    }

    /**
     * Magic PHP function, gets called when a method cannot be resolved
     * This is a stub and should go but it's currently used for testing purposes
     * @param $name
     * @param $qrguments
     */
    public function __call($name, $qrguments)
    {
        echo "<br/>CALLING magic $name<br/>";
    }

    /**
     * Method to retrieve a Jorum item by id OR handle
     * @param string $id
     * @param array $options
     * @return mixed
     */
    public function findByIdOrHandle($id = '', $options = array())
    {

        $url = self::$apiUrl . self::$servicePoint . '/' . $id;

        // get a raw response
        self::$rawData = self::apiCall($url, '', array('headers' => array('Accept: ' . self::$httpAccept), 'options' => $options));

        // decode it and set php native rough data....
        if (self::$httpAccept == 'application/xml') {
            self::$data = simplnexml_load_string(self::$rawData);
        } elseif (self::$httpAccept == 'application/json') {
            self::$data = json_decode(self::$rawData);
        }

        $model = self::$model;

        $class = 'MIMAS\\Service\Jorum\\' . strtoupper(substr($model, 0, 1)) . substr($model, 1);

        $ret = new $class((array)self::getData());

        return $ret;
    }

    /**
     * Fetch a list of stuff such as Items or Collections
     * Used the data to populate a collection
     * Later to add a Context
     *
     * @return DataCollection
     */
    public function all()
    {
        // set the url
        $url = self::$apiUrl . self::$servicePoint;

        // get a raw response
        self::$rawData = self::apiCall($url, '', array('headers' => array('Accept: ' . self::$httpAccept), 'options' => $this->getOptions()));

        // decode it and set php native rough data....
        if (self::$httpAccept == 'application/xml') {
            self::$data = simplexml_load_string(self::$rawData);
        } elseif (self::$httpAccept == 'application/json') {
            self::$data = json_decode(self::$rawData);
        }

        $data = self::getData();

        if (is_object($data) && property_exists($data, 'context')) {
            $context = (array)$data->context;
        } else {
            $context = null;
        }

        $model = self::$model;
        $class = 'MIMAS\\Service\Jorum\\' . strtoupper(substr($model, 0, 1)) . substr($model, 1);

        $data = array();

        foreach (self::getData()->$model as $i => $object) {
            $data[] = new $class((array)$object);
        }

        $ret = new \MIMAS\Service\DataCollection($data, $context);

        return ($ret);
    }

    /**
     * Return an array of all handles to be json encoded
     * I would rather this was a static function but let's just forget this for now
     */
    public static function allHandles()
    {
        // set the url
        $item = new Item('json', 'json');
        if ($item) ;

        $url = self::$apiUrl . self::SERVICE_HANDLES;
        $counter = 0;

        // get a raw response
        $options = array('offset' => $counter);

        self::$rawData = self::apiCall($url, '', array('headers' => array('Accept: ' . self::ACCEPT_JSON), 'options' => $options));
        self::$data = json_decode(self::$rawData);

        return self::$data;
    }

    /**
     * Set options i.e. limit and offset
     * @param $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Get options i.e. limit and offset
     * @return array $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return a JSON serialized instance of self/this
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * getIterator
     *
     * @access public
     * @return \RecursiveDirectoryIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator((array)$this);
    }

    /**
     * Write out as HTML
     * @todo return a string instead and echo elsewhere
     */
    public function toHtml()
    {
        $iterator = new \ArrayIterator((array)$this);

        echo "<div style=\"padding-left: 16px\">";

        $itemLink = '';

        while ($iterator->valid()) {
            $value = $iterator->current();
            $key = $iterator->key();

            if (strpos($key, 'link') !== false) {
                $itemLink = $value;
            }

            echo \MIMAS\Service\Jorum\Formatters\Html::get($key, $value, $itemLink);

            $iterator->next();
        }
        echo "</div>";
    }

}