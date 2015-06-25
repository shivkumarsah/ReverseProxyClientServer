<?php


use Openroster\Storage\School\SchoolRepository as School;
use Openroster\Storage\Developer\DeveloperRepository as Developer;
/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */
class UsersController extends BaseController
{
    
    public function __construct(School $school, Developer $developer)
    {
        $this->school = $school;
        $this->developer = $developer;
        
    }
    
    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function dashboard()
    { 
        return View::make('users.dashboard');
    }
    
    public function graph(){
        $countArray = $this->school->getDashboardCounts();
        echo json_encode($countArray); exit;
    }
    /**
     * Displays the change password form
     *
     * @return  Illuminate\Http\Response
     */
    public function settings()
    {
        return View::make('users.change_password');
    }

    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function create()
    {
        return View::make(Config::get('confide::signup_form'));
    }

    /**
     * Stores new account
     *
     * @return  Illuminate\Http\Response
     */
    public function store()
    {
        $repo = App::make('UserRepository');
        $user = $repo->signup(Input::all());

        if ($user->id) {
            if (Config::get('confide::signup_email')) {
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }

            return Redirect::action('UsersController@login')
                ->with('notice', Lang::get('confide::confide.alerts.account_created'));
        } else {
            $error = $user->errors()->all(':message');

            return Redirect::action('UsersController@create')
                ->withInput(Input::except('password'))
                ->with('error', $error);
        }
    }

    /**
     * Displays the login form
     *
     * @return  Illuminate\Http\Response
     */
    public function login()
    {
        //die("login page");
        if (Confide::user()) {
            return Redirect::route('dashboard');
        } else {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     * @return  Illuminate\Http\Response
     */
    public function doLogin()
    {
        $repo = App::make('UserRepository');
        $input = Input::all();
        
        if ($repo->login($input)) {
           
            return Redirect::intended(route('dashboard'));
        } else {
            if ($repo->isThrottled($input)) {
                $err_msg = Lang::get('messages.login.too_many_attempts');
            } elseif ($repo->existsButNotConfirmed($input)) {
                $err_msg = Lang::get('messages.login.not_confirmed');
            } else {
                $err_msg = Lang::get('messages.login.wrong_credentials');
            }

            return Redirect::action('UsersController@login')
                ->withInput(Input::except('password'))
                ->with('error', $err_msg);
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string $code
     *
     * @return  Illuminate\Http\Response
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
            return Redirect::action('UsersController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return Redirect::action('UsersController@login')
                ->with('error', $error_msg);
        }
    }

    /**
     * Displays the forgot password form
     *
     * @return  Illuminate\Http\Response
     */
    public function forgotPassword()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     * @return  Illuminate\Http\Response
     */
    public function doForgotPassword()
    {
        if (Confide::forgotPassword(Input::get('email'))) {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
            return Redirect::action('UsersController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
            return Redirect::action('UsersController@doForgotPassword')
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Shows the change password form with the given token
     *
     * @param  string $token
     *
     * @return  Illuminate\Http\Response
     */
    public function resetPassword($token)
    {
        return View::make(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    public function doResetPassword()
    {
        $repo = App::make('UserRepository');
        $input = array(
            'token'                 =>Input::get('token'),
            'password'              =>Input::get('password'),
            'password_confirmation' =>Input::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if ($repo->resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::action('UsersController@login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('UsersController@resetPassword', array('token'=>$input['token']))
                ->withInput()
                ->with('error', $error_msg);
        }
    }
    
     /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    public function changepassword()
    {
        
        /*$rules = array(
        'oldpassword'                  => 'required',
        'password'                  => 'required|confirmed|different:oldpassword',
        'password_confirmation'     => 'required|different:oldpassword|same:password'
        );

        $user = User::find(Auth::user()->id);
        $validator = Validator::make(Input::all(), $rules);

        //Is the input valid? new_password confirmed and meets requirements
        if ($validator->fails()) {
            Session::flash('validationErrors', $validator->messages());
            return Redirect::back()->withInput();
        }

        //Is the old password correct?
        if(!Hash::check(Input::get('oldpassword'), $user->password)){
            return Redirect::back()->withInput()->withError('Password is not correct.');
        }

        //Set new password to user
        $user->password = Input::get('password');
        $user->password_confirmation = Input::get('password_confirmation');

        $user->touch();
        $save = $user->save();

        return Redirect::to('logout')->withMessage('Password has been changed.');*/
        
        
        
        
        
        
        
        
        
        
        
        $repo = App::make('UserRepository');
        $input = array(
            'oldpassword'              =>Input::get('oldpassword'),
            'password'              =>Input::get('password'),
            'password_confirmation' =>Input::get('password_confirmation'),
        );
        $userDetail = Confide::user();
        $input['email'] =   $userDetail->email;
        $input['pass'] =   $userDetail->password;
        $input['id'] =   $userDetail->user_id;
        $resultArray = $repo->changepassword($input);
        //Set new password to user
        if($resultArray['status'] == true){
            $userDetail->password = Input::get('password');
            $userDetail->password_confirmation = Input::get('password_confirmation');

            $userDetail->touch();
            $save = $userDetail->save();
        }
        echo json_encode($resultArray); exit;
        
        
    }

    /**
     * Log the user out of the application.
     *
     * @return  Illuminate\Http\Response
     */
    public function logout()
    {
        Confide::logout();

        return Redirect::to('/');
    }
    
    public function signup()
    {   
        $repo = App::make('UserRepository');
        $input = Input::all();
        /*$postedData=json_decode((file_get_contents("php://input")),true);
        echo "<pre>"; print_r($input); die;*/
        if ($repo->signup($input)) {
            $response  = array("status"=>true);
            echo json_encode($response);exit;
        }
    }
}
