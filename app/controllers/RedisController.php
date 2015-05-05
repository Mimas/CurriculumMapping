<?php

class RedisController extends BaseController {

	/**
	|--------------------------------------------------------------------------
	|  Redis Controller
	|--------------------------------------------------------------------------
	| @author Petros Diveris
	| 
	*/
  public $lang = 'en';

  protected static $redis = null;
  /*
  |--------------------------------------------------------------------------
  | REDIS Controller
  |--------------------------------------------------------------------------
  |
  */

  public function __construct() {
   $config = Config::get('database.redis.default');

   if (self::$redis == null)
    self::$redis = new Predis\Client("tcp://{$config['host']}:{$config['port']}");

  }

  protected static function getData() {
    $keys = self::$redis->keys('*');

    if (is_string($keys) && $keys<>'')  
    {
      $keys = explode(' ',$keys);
    }

    $data = array();
    if (count($keys) > 0) 
    {
      foreach ($keys as $key) {
        $ttl = self::$redis->ttl($key);
        $type = self::$redis->type($key);
        $len = strlen(self::$redis->get($key));

        $data[$key] = array('len'=>$len, 'ttl'=>$ttl, 'type'=>$type );
      }
    }
    return $data;
  }


	public function getIndex() {
    return View::make('redis.index')->with(array('data'=>self::getData(), 'lang'=>'en'));
   
	}

  /**
   * Method to delete a key from the datastore
   * @param string id
   *
   * @return \Illuminate\Http\RedirectResponse
   * @author Petros Diveris
   * @version 1
   */
  public function getDelete( $key = '' ) {
    self::$redis->del($key);
    return Redirect::to('redis/');
  }

  
  /**
  * Method to flush the DB
  * probably a bit dangerous?!
  *
  * @author Petros Diveris
  * @version 1
  */
  public function getFlushdb( ) {
    self::$redis->flushdb();
    return Redirect::to('redis/');
  }

  /**
  * Method to asynchronously write REDIS data to disk
  *
  * @author Petros Diveris
  * @version 1
  */
  public function getBgsave( ) {
    self::$redis->bgsave();
    return Redirect::to('redis/');
  }


  /**
  * Get request to flush DB
  *
  * @author Petros Diveris
  * @return array data json 
  */
  public function postRefresh() {
    if (isset($_POST['key'])) {
      $data = self::$redis->get($_POST['key']);
      echo (json_encode(array('data'=>$data, 'error'=>'')));
    } else {
      echo (json_encode(array('data'=>'', 'error'=>'Cannot fetch value for some reason')));
    }
  }

  /**
   * Method to edit a value
   *
   * TODO: Allow inserting (i.e., no key passed, add key field to the form)
   *
   * @param string id
   *
   * @return $this
   * @author Petros Diveris
   * @version 1
   *
   */
  public function getEdit( $key = '' )
  { 
    $data = self::$redis->get($key);

    return View::make('redis.edit')->with(array('data'=>$data, 'key'=>$key, 'ttl'=> self::$redis->ttl($key) , 'lang'=>'en' ));
  }

  /**
  * Helper function to JSON decode values stored in REDIS
  *
  * @author Petros Diveris
  * @return array data json (json object contains decoded string)
  *
  */
  public function postDjson() {
    if (isset($_POST['data'])) {
      $data = json_decode($_POST['data']);
      // json_decode returns null when inalid JSON passed
      if ( null !==  $data) {
        echo (json_encode(array('data'=>print_r($data, 1), 'error'=>'')));
      }  else {
         echo (json_encode(array('data'=>'', 'error'=>'Cannot decode this stream')));
      }
    } else {
      echo ($_POST['key']);
    }
  }

  /**
  * Helper function to deserialize values stored in REDIS
  * Response to ajax calls and returns a JSON encoded array
  *
  * @author Petros Diveris
  * @return array data json
  *
  */
  public function postDephp() {
    if (isset($_POST['data'])) {
      $data = unserialize($_POST['data']);
      // whereas unserialize returns null when inalid JSON passed (how consistent...)
      if ($data) {
        echo (json_encode(array('data'=>print_r($data, 1), 'error'=>'')));
      }  else {
         echo (json_encode(array('data'=>'', 'error'=>'Cannot decode this stream')));
      }
    } else {
         echo (json_encode(array('data'=>'', 'error'=>'Cannot get POST values')));

    }
  }

  /**
  * Very dangerous function for encoding simple arrays
  * This function EXECUTES the php code as written to a /tmp
  * file and therefore is VERY dangerous.
  * This is solely for internal use during development and it will
  * be completely out of bouds on UAT and LIVE systems
  *
  * @author Petros Diveris
  *
  * TODO: only allow data arrays to ne parsed
  */
  public function postEjson() {
    if (isset($_POST['data'])) {
      $buffer = $_POST['data'];
      $buffer = str_replace(';', '', $buffer );

      $output = array();

      $buffer = '<?php echo json_encode('.$buffer.');';
      //$fname
      if ($fname = Bentleysoft\Helper::writeTempFile($buffer)) {
        $result = exec('/usr/bin/php '.$fname, $output );
        $data = implode("\n", $output);
      //    unlink($fname);

        echo( json_encode(array('data'=>$data, 'error'=>'' )) );
      } else {
         echo (json_encode(array('data'=>'', 'error'=>'Cannot serialize this stream')));
      }
    } else {
      echo (json_encode(array('data'=>'', 'error'=>'No POST data received')));
    }
  }

  /**
  * Very dangerous function for emcoding simple arrays
  * This function EXECUTES the php code as written to a /tmp
  * file and there fore is VERY dangerous.
  * This is solely for internal use during development and it will
  * be completely out of bouds on UAT and LIVE systems
  *
  * @author Petros Diveris
  *
  * TODO: only allow data arrays to ne parsed
  */
  public function postEnphp() {
    if (isset($_POST['data'])) {
      $buffer = $_POST['data'];
      $buffer = str_replace(';', '', $buffer );

      $output = array();

      $buffer = '<?php echo serialize('.$buffer.');';
      //$fname
      if ($fname = Bentleysoft\Helper::writeTempFile($buffer)) {
        $result = exec('/usr/bin/php '.$fname, $output );
        $data = implode("\n", $output);
        unlink($fname);

        echo( json_encode(array('data'=>$data, 'error'=>'' )) );
      } else {
         echo (json_encode(array('data'=>'', 'error'=>'Cannot serialize this stream')));
      }
    } else {
      echo (json_encode(array('data'=>'', 'error'=>'No POST data received')));
    }
  }

  /**
  *
  * AJAX Update/Add to REDIS
  * @author: Petros Diveris
  * @version 1.0
  */
  public function postUpdate() {
    if (Config::get('app.debug')) {
      PhpConsole\Handler::getInstance()->debug(print_r($_POST,1), 'redis.update');
    }

    if ( Input::get('data', false) && Input::get('key', false)) {
      $ttl = Input::get('ttl', Config::get('app.redis.ttl'));

      if (Config::get('app.debug')) {
        PhpConsole\Handler::getInstance()->debug($ttl, 'redis.update');
      }

      self::$redis->set(Input::get('key'), Input::get('data'));
      self::$redis->expire(Input::get('key'), $ttl);

      $ttl = self::$redis->ttl(Input::get('key')); // will be 3600 seconds
      echo ( json_encode( array( 'data'=>Input::get('data'), 'ttl'=>$ttl, 'key'=>Input::get('key') ) ));

    } else {
      echo (json_encode(array('data'=>'', 'error'=>'No POST data received')));
    }


  }

  /**
   * return ALL keys
   * @return mixed
   */
  public static function getKeys() {
    return self::$redis->keys('*');
  }

  /**
   * Number of keys in REDIS
   * @return int
   */
  public static function getNumKeys() {
    $x = new RedisController();
    return count(self::getData());
  }
}



