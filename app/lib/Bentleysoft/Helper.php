<?php
/**
 * Created by PhpStorm.
 * User: pdiveris
 * Date: 16/12/2013
 * Time: 12:58
 */
namespace Bentleysoft;

use Cartalyst\Sentry\Facades\CI\Sentry;
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
	public static function userHasAccess(array $rights, $id = -1) {
		$ret = false;

		$x = new \Sentry;

		if (! \Sentry::check()) {
			// TODO: Throw Exception
      throw new \Exception("Not authorised");
		} else {
			try {

				if ($id == -1) {  // the current user
					$user = \Sentry::getUser();

				} else { // or the user being managed...

					$user = \Sentry::findUserById($id);
				}
			} catch (Exception $e) {
				// TODO: Make use of Sentry specific exceptions?!
				return false;
			}
			try {
				$users = \Sentry::findAllUsersWithAccess( $rights );
			} catch (Exception $e) {
				// TODO: Make it Sentry Exception
				return false;
			}

			foreach ($users as $u) {
				# code...
				if ($u->email == $user->email)
					return true;
			}
			return false;
		}
	}

  public static function superUser($id = -1) {
    return self::userHasAccess(array('application.admin'));
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
      if (! \Sentry::check()) {
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
  public static function  formatBytes($bytes, $precision = 2)
  {
    $units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
  }

} 