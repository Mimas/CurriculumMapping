<?php

/**
 * Class LoginController
 */
class LoginController extends BaseController
{
  /**
   * |--------------------------------------------------------------------------
   * |  Login Controller
   * |--------------------------------------------------------------------------
   * |
   * | @author Petros Diveris
   * |
   */

  /**
   * @var string $lang
   */
  public $lang = 'en';

  /**
   * constructor
   */
  public function __construct()
  {
    $this->lang = Input::get('lang', 'en');
  }

  /**
   * @return mixed
   */
  public function getIndex()
  {
    $view = 'login';

    $output = View::make($view, array('lang' => $this->lang));
    return $output;
  }

  /**
   * Method to request a new password and to send out the change code
   * @version 1.0
   */
  public function actionReset()
  {
    $view = 'reset_request';

    if (Request::getMethod() == 'POST') { // generate code, send email
      // validate input and find user record
      // send reset code by email to user

      try {
        $email = Input::get('email');
        $user = Sentry::findUserByCredentials(array(
          'email' => $email
        ));

        $code = $user->getResetPasswordCode();
        $data = array('code' => $code, 'user' => $user, 'resetUrl' => url('login/change'));

        Mail::send('emails.reset', array('data' => $data), function ($message) use ($user, $code) {

          $from = Config::get('mail.from', array('from'=>'system@diveris.berkleysoft.com', 'name'=>'James' ));

          $fromEmail = $from['address'];
          $fromName = $from['name'];

          $message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Your password reset code ');
          $message->from($fromEmail, $fromName);
        });
        return Redirect::back()->withInput()->with(array('success' => 'Your password reset code has been sent and should be with you shortly'));
      } catch (Exception $e) {
        throw new Exception($e, 501);

      }

    }

    $output = View::make($view, array('lang' => $this->lang));
    return $output;
  }

  /**
   * Method to render and proces the password change form
   * @version 1.0
   * @todo compare password1 and password2
   */
  public function actionChange()
  {
    $view = 'reset_form';
    $output = View::make($view, array('lang' => $this->lang));

    $rules = array('email' => array('required'), 'password' => array('required'), 'password2' => array('required'), '');
    $validator = Validator::make(Input::all(), $rules, array('required' => 'The :attribute is required.'));

    if (Request::getMethod() == 'POST') {

      if ($validator->fails()) {
        $messages = $validator->messages();
        return Redirect::back()->withInput()->withErrors($messages);
      } else {

        try {
          $code = Input::get('code');
          $email = filter_var(Input::get('email'), FILTER_SANITIZE_EMAIL);
          $password = Input::get('password');

          $user = Sentry::findUserByCredentials(array(
            'email' => $email
          ));

          if ($user->checkResetPasswordCode($code)) {
            if ($user->attemptResetPassword($code, $password)) {
              return Redirect::back()->withInput()->with(array('success' => 'Your password reset code has been sent and should bi with you shortly'));
            } else {
              throw new Exception('User password could not be reset.');
            }
          } else {
            throw new Exception('User password could not be reset.');
          }
        } catch (Exception $e) {
          echo $e->getMessage();
          exit;
        }

      }

    }

    return $output;
  }

  /**
   * Function to log user out. responds to post and get requests
   * @author Petros Diveris
   */
  public function actionLogout()
  {
    // Logs the user out
    Sentry::logout();
    Session::clear();
    return Redirect::to('/login');
  }

  /**
   * @return mixed
   * @throws Exception
   */
  public function postIndex()
  {
    $rules = array('email' => array('required'), 'password' => array('required'));
    $validator = Validator::make(Input::all(), $rules, array('required' => 'The :attribute is required.'));

    if ($validator->fails()) {
      $messages = $validator->messages();
      return Redirect::back()->withInput()->withErrors($messages);
    } else {
      try {
        // Set login credentials
        $credentials = array(
          'email' => Input::get('email'),
          'password' => Input::get('password'),
        );

        // Try to authenticate the user
        $user = Sentry::authenticate($credentials, false);
        if ($user)
          return Redirect::to('/');
        else
          throw new \Exception(500, "Big fat error");

      } catch (Cartalyst\Sentry\Users\WrongPasswordException $e) {
        echo 'Wrong password, try again.';
      } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
        $messageBag = new Illuminate\Support\MessageBag;
        $messageBag->add('errors', 'Uknown user');

        return Redirect::back()->withInput()->withErrors($messageBag);
      } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
        echo 'User is not activated.';
      }
    }
  }
}