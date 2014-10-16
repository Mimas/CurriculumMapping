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
   *
   * Handle POST requests for setting User permissions
   *
   * @param $id
   * @return \Illuminate\View\View
   * @throws Exception
   */
  public function postPermissions($id = -1) {


    $allPermissions = Permission::where('id','>',1)->get();
    if (!$allPermissions || count($allPermissions)<1 ) {
      throw new Exception('No settable permissions found.');
    }

    $user = Sentry::findUserById($id);

    if (!$user) {
      App::abort(404, 'Cannot find the user.');
    }

    $userPermissions = array();

    foreach($allPermissions as $permission) {
      $label = $permission->class.'_'.$permission->action;   // e.g. user.create

      if (array_key_exists($label,Input::all())) {
        $userPermissions[$permission->class.'.'.$permission->action] = 1;
      } else {
        $userPermissions[$permission->class.'.'.$permission->action] = 0;
      }
    }

    $user->permissions = $userPermissions;

    if (!$user->save()) {
      throw new Exception('User could not be updated.');
    } else {
      $status = array('close'=>true,);
      return  View::make('edituserpermissions',
        array('lang'=>$this->lang,
          'user'=>$user,
          'status'=>$status,
          'classes'=>array(),
          'permissions'=>array()));

    }

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


    // $userSubjects =

    $subjectAreas = SubjectareaView::where('id','>',0)
                                   ->orderBy('subject')
                                   ->get();

    $userSubjects = self::getUserSubjectAreas($id);

    $output = View::make('edituser', array('lang'=>$this->lang,
                                            'user'=>$user,
                                            'subjectAreas'=>$subjectAreas,
                                            'userSubjects'=>$userSubjects,
                                           ));

    return $output;
  }

  /**
   * Get user's subject areas like
   * @param int $id | null
   * @return array
   */
  public static function getUserSubjectAreas($id=0) {
    $userSubjectsRecs = UserSubjectareas::where('users_id','=',intval($id))->get();

    $userSubjects = array();

    foreach ($userSubjectsRecs as $us) {
      $userSubjects[] = $us->subjectareas_id;
    }
    return $userSubjects;
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

      $areas = Input::get('area',array());

      // expand pls
      DB::transaction(function() use ($user, $areas)
      {
        DB::table('users_subjectareas')->where('users_id', $user->getId())->delete();

        foreach ($areas as $i=>$areaId) {
          $id = DB::table('users_subjectareas')->insertGetId(
            array('users_id' =>$user->id, 'subjectareas_id' => $areaId));
        }
      });
    }


    return Redirect::to('admin/user/'.$user->getId().'/edit');

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

  /**
   * The Dashboard. Might move to routes as it's app specific...
   * @return mixed
   */
  public function getDashboard() {
    $view = 'dashboard';

    $output = View::make($view, array('lang'=>$this->lang) );
    return $output;
  }



	public function missingMethod($parameters = array())
	{
		echo 'catch all method';
		return;
	}


  /**
   * The method below is just a bootstrap for creating a user
   */
   public function getSeed() {
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