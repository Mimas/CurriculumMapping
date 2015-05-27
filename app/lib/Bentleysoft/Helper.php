<?php
/**
 * Created by PhpStorm.
 * User: pdiveris
 * Date: 16/12/2013
 * Time: 12:58
 */
namespace Bentleysoft;

use Cartalyst\Sentry\Facades\CI\Sentry;
use MIMAS\Service\Jorum\Bitstream;
use Whoops\Example\Exception;

/**
 * Class Helper
 * @package Bentleysoft
 * @author Petros Diveris
 */
class Helper {
  /**
   * Method to check if a user has permissions for a specific operation
   * @param array $rights
   * @param $id
   * @throws \Exception
   * @return bool
   */

  static $user = null;
  static $users = array();

  public static function userHasAccess(array $rights, $id = -1) {
    $ret = false;

    /**
     * TODO: Remove the if (false) check after you have thoroughly checked and confirmed that it indeed obsolete
     */

    if (  false && ! \Sentry::check()) {
      throw new \Exception("Not authorised");
    } else {
      try {
        if (null == self::$user) {
          if ($id == -1) {  // the current user
            self::$user = \Sentry::getUser();

          } else { // or the user being managed...
            self::$user = \Sentry::findUserById($id);
          }
        }
      } catch (Exception $e) {
        // TODO: Make use of Sentry specific exceptions?!
        return false;
      }
      try {
        $rightsKey = md5(serialize($rights));
        $expiresAt = \Carbon\Carbon::now()->addSeconds(60*60*2);
        if ($expiresAt);

        $users = \Cache::get('user_rights');

        if (!$users ) {
          $users = array();
        }

        if (! array_key_exists($rightsKey, $users)) {
          $tmp = \Sentry::findAllUsersWithAccess( $rights );
          $users[$rightsKey] = $tmp;

          \Cache::put('user_rights', $users, $expiresAt);
        }

        // $users = \Sentry::findAllUsersWithAccess( $rights );
      } catch (Exception $e) {
        // TODO: Make it Sentry Exception
        return false;
      }

      if (null != self::$user) {
        foreach ($users[$rightsKey] as $u) {
          # code...
          if (null!==$u && $u->email == self::$user->email)
            return true;
        }
      }
      return false;
    }
  }

  public static function superUser($id = -1) {
    return self::userHasAccess(array('application.admin'));
  }

  public static function isMenuSlected($path) {
    $ret = '';

    $pathBits = explode('/', \Request::path());
    $actualPath = $pathBits[count($pathBits)-1 ];

    if ($actualPath == $path)
      $ret = 'cta';

    return $ret;
  }

  /**
   * Get user's subject areas
   * If a user is not specified then get the current one's
   * @param int $id | null
   * @throws \Exception
   * @return array
   */
  public static function getUserSubjectAreas($id=-1) {
    if ($id<0) {
      if (false && ! \Sentry::check()) {
        // TODO: Throw Exception
        throw new \Exception("Not authorised");
      } else {
        try {
          if ($id == -1) {  // the current user

            $user = \Sentry::getUser();
            $id = $user->getId();
          }
        } catch (Exception $e) {
          // TODO: Make use of Sentry specific exceptions?!
          return false;
        }
      }
    }

    $userSubjectsRecs = \UserSubjectareas::where('users_id','=',intval($id))->get();

    $userSubjects = array();

    foreach ($userSubjectsRecs as $us) {
      $userSubjects[] = $us->subjectareas_id;
    }
    return $userSubjects;
  }




  /**
   * Create a temporary file with a unique random name, write to it and return the filename
   *
   * @author      Petros Diveris
   * @copyright
   * @license
   * @param       string  $contents  Text stream to write
   * @param       string  $dir
   * @return      string  filename
   *
   * @author pd
   */
  public static function writeTempFile($contents,  $dir = '/tmp') {
    $fname = $dir . '/' . \Config::get('app.shortname', 'partisan'). '-'. uniqid();
    if ( $result = file_put_contents($fname, $contents) ) {
      return($fname);
    } else {
      return(false);
    }
  }

  /**
   * Paginator supported page sizes.. Just a helper
   * @return array
   */
  public static function pageSizes() {
    return array(10=>10,25=>25,50=>50,100=>100);
  }


  /**
   * Return human readable file size information i.e. 12K, 1.3M etc
   *
   * @internal param $size
   * @internal param int $precision
   * @param $bytes
   * @param int $precision
   * @internal param $size
   * @return string
   */
  public static function formatBytes($bytes, $precision = 2)
  {
    $units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
  }

  /**
   * @param $fileName
   * @param string $extension
   * @return string
   */
  protected static function swapExtensionTo($fileName, $extension = 'pdf') {
    $bits = explode('.',$fileName);
    if (count($bits)>1) {
      return $bits[0].'.'.$extension;
    } else {
      return $fileName.'.'.$extension;
    }
  }


  /**
   * Make a PDF file out of an MS Office one (excel / word / ppt /rtf )
   * @param Bitstream $bitstream
   * @return mixed
   */
  public static function makePdf(Bitstream $bitstream) {

    $originalName = $bitstream->getName();
    $originalName = str_replace(array(' ','/','&'), '_', $originalName);

    $pdfName = self::swapExtensionTo($originalName, 'pdf');
    $pdfPath = public_path().'/resourcecache/'.$pdfName;

    if (!file_exists($pdfPath)) {

      if (!file_exists( public_path().'/resourcecache/originals/'.$originalName)) {
        $stream = $bitstream->retrieveStream();
        file_put_contents(public_path().'/resourcecache/originals/'.$originalName, $stream);
      }

      $binPath = \Config::get('app.soffice');
      shell_exec('export HOME=/tmp && '. $binPath.' --headless -convert-to pdf --outdir ' .public_path().'/resourcecache '.public_path().'/resourcecache/originals/'.$originalName);

      //// exec("$cmd");

    } else {
    }
    return $pdfName;
  }

  /**
   * Get a stream from DSPACE and make it local (cache it)
   * @param Bitstream $bitstream
   * @return mixed
   */
  public static function makeLocal(Bitstream $bitstream) {

    $originalName = $bitstream->getName();
    $originalName = str_replace(array(' ','/'), '_', $originalName);

    $filePath = public_path().'/resourcecache/'.$originalName;

    if (!file_exists($filePath)) {
      $stream = $bitstream->retrieveStream();

      if (!file_exists( public_path().'/resourcecache/'.$originalName)) {
        file_put_contents(public_path().'/resourcecache/'.$originalName, $stream);
      }
    } else {
    }
    return $originalName;
  }

  /**
   * Helper function to get number of entries in REDIS
   * Probably not needed, it's an indirect way of calling a static
   * method of a controller with a singleton reference to the connection
   * Anyway, it works..
   *
   * @return int
   */
  public static function getRedisCount() {
    //$num = Controller::call('\Redis@getNumKeys', array());
    $num = \RedisController::getNumKeys();
    return $num;
  }

  /**
   * Iterate throw an Eloquent rowset, pick up a field and shove it in an array
   * Upon completion, return that array
   * @param array $rows
   * @param string $field e.g. 'label'
   * @return array
   */
  public static function fieldList($rows, $field) {
    $ret = array();

    if (count($rows)>0) {
      foreach ($rows as $i=>$row) {
        $ret[] = $row->$field;
      }

    }
    return $ret;
  }



} 