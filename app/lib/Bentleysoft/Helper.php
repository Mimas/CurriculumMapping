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
	* @return bool
	*/
	public static function userHasAccess(array $rights, $id = -1) {
		$ret = false;

		$x = new \Sentry;

		if (! \Sentry::check()) {
			// TODO: Throw Exception
			return $ret;
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

} 