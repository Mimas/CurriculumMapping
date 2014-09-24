<?php

class AdminController extends BaseController {

	/**
	|--------------------------------------------------------------------------
	|  Admin Controller
	|--------------------------------------------------------------------------
	| @author Petros Diveris
	| 
	*/
  public $lang = 'en';

  public function __construct() {
    $this->lang = Input::get('lang', 'en');
  }

	public function getIndex() {
    $view = 'dashboard';

    $output = View::make($view, array('lang'=>$this->lang) );
    return $output;
	}

	public function getUsers() {
    $users = DB::table('users')->paginate(15);
    return View::make('users', array('users'=>$users, 'lang'=>$this->lang));
	}

  /**
  * Method to respond to POST requests with a DELETE method
  * The method arrives in hidden field _method since http currently doesn't support DELETE
  * @author Petros Diveris
  * @todo: implement checks e.g. pesmissions
  * @todo: throw exception on error
  */
  public function deleteUser($id = 0) {
    try {    
      $id = intVal($id);
      $user = Sentry::findUserById($id);
      $user->delete();      
    } catch (Exception $e) {
      echo 'User could not be deleted';
    }
    return Redirect::to('admin/users');
  }

  public function User($id=0) {
    echo "edit haha";
  }

  /**
  * GET request responder for user permissions
  * PD, 2013-1024
  */
  public function getPermissions($id = 4) {
  	try {    
      $user = Sentry::findUserById(intVal($id));
    } 
    catch (Exception $e) {
      // todo: THROW exception!
    }

    $classes = DB::table('permissions')
                      ->select(DB::raw('class'))
                      ->groupBy('class')
                      ->orderBy('id')
                      ->get();


    $permissions = DB::table('permissions')->orderBy('id')->get();

    $output = View::make('edituserpermissions', 
                            array('lang'=>$this->lang, 
                                  'user'=>$user, 
                                  'classes'=>$classes, 
                                  'permissions'=>$permissions));
    
    return $output;
  }


  /**
  * Method to respond to POST requests with a PUT method
  * to update the user's attributes
  *
  * @author Petros Diveris
  * @TODO: implement checks e.g. permissions
  * @TODO: throw exception on error
  *
  */
  public function getUser($id = 0) {
   try {    
      $user = Sentry::findUserById(intVal($id));

    } catch (Exception $e) {
      $user = new User;
    }

    $output = View::make('edituser', array('lang'=>$this->lang, 'user'=>$user ));

    return $output;
  }

  /**
  * @TODO: Adapt validation to mode (edit/add);
  * @TODO: Add passwords comparison validation
  */
  public function postUser($id = 0) {
    $rules =  array('email' => array('required'), 'first_name'=>array('required'), 'last_name'=>array('required')  );

    $validator = Validator::make(Input::all(), $rules, array('required' => 'The :attribute is required.') );

    if( $validator->fails() ) {
        $messages = $validator->messages();
        return Redirect::back()->withInput()->withErrors($messages);
    } else {
      try {    
        $id = intVal($id);
        $password = Input::get('password');

        if ($id < 1) {
          $user = Sentry::createUser(array(
            'email'    => Input::get('email'),
            'password' => Input::get('password'),
            'first_name' => Input::get('first_name'),
            'last_name' => Input::get('last_name'),
            'activated' => Input::get('activated', 0),
            'permissions' => array(
                'user.create' => 1,
                'user.delete' => 1,
                'user.view'   => 1,
                'user.update' => 1
                )
            )
          );
        } else {
          $user = Sentry::findUserById($id);

          $user->email = Input::get('email');
          $user->first_name = Input::get('first_name');
          $user->last_name = Input::get('last_name');

          if ($password <> '' )
            $user->password = $password;

          $user->activated = Input::get('activated',0);

          if (!$user->save()) {
            throw new Exception('User could not be updated.');
          }
        }
      } catch (Exception $e) {
        throw new Exception('User could not be updated.');
      }
    }
    return Redirect::to('admin/users');

  }

  /**
  *
  * Method to respond to POST requests with a PATCH method
  * to Activate/de-Activate the user
  * @author Petros Diveris
  * @TODO: implement checks e.g. permissions
  * @TODO: throw exception on error
  *
  */
  public function putToggle($id = 0 ) {
    try {    
      $id = intVal($id);
      $user = Sentry::findUserById($id);
      $user->activated = ! $user->activated;
      if ($user->save()) {
        return Redirect::to('admin/users');
      } else {
        throw new Exception('Activated flag for user could not be updated.');
      }
    } catch (Exception $e) {
      throw new Exception('Activated flag for user could not be updated.');
    }
    return Redirect::to('admin/users');

  }


  public function getDashboard() {
    $view = 'dashboard';

    $output = View::make($view, array('lang'=>$this->lang) );
    return $output;
  }

	public function missingMethod($parameters = array())
	{
		echo 'catch all method';
    var_dump($_REQUEST);
		return;
	}


   /**
   *
   * The method below is just a bootstrap for creating a user
   * 
   */
   public function getSeed() {
      echo 'done...';
      return;
      try
      {
        // Find the user using the user id
        $user = Sentry::findUserById(4);

        // Attempt to activate the user
        if ($user->attemptActivation(''))
        {
          // User activation passed
        } else {
           // User activation failed
        }
      }
      catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
      {
        echo 'User was not found.';
      }
      catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
      {
        echo 'User is already activated.';
      }


      return;

      try
      {
        // Create the group
        $group = Sentry::createGroup(array(
          'name'        => 'Users',
          'permissions' => array(
            'admin' => 1,
            'users' => 1,
            ),
          ));
      }
      catch (Cartalyst\Sentry\Groups\NameR7equiredException $e)
      {
        echo 'Name field is required';
      }
      catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
      {
        echo 'Group already exists';
      }


      // Create the user
      $user = Sentry::createUser(array(
         'email'    => 'petros@cq2.co.uk',
         'password' => 'bassword',
         'permissions' => array(
             'user.create' => 1,
             'user.delete' => 1,
             'user.view'   => 1,
             'user.update' => 1,
         ),
      ));

      // Find the group using the group id
      // $adminGroup = Sentry::findGroupById(1);

      // Assign the group to the user
      $user->addGroup($group);

	  }


}