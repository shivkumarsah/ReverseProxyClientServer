<?php

use Openroster\Storage\School\SchoolRepository as School;
use Openroster\Storage\Developer\DeveloperRepository as Developer;

/**
 * UsersController Class
 *
 * Implements actions regarding user management
 */
class UsersController extends BaseController {

    public function __construct(School $school, Developer $developer) {
        $this->school = $school;
        $this->developer = $developer;
    }

    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function dashboard() {
        return View::make('users.dashboard');
    }

    public function graph() {
        $reqParams = $this->makeCurlParams(Input::all());
        $result = $this->executeRequest($reqParams);
        echo $result;
        exit;
    }

    /**
     * Displays the change password form
     *
     * @return  Illuminate\Http\Response
     */
    public function settings() {
        return View::make('users.change_password');
    }

    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function create() {
        return View::make(Config::get('confide::signup_form'));
    }

    /**
     * Stores new account
     *
     * @return  Illuminate\Http\Response
     */
    public function store() {
        $repo = App::make('UserRepository');
        $user = $repo->signup(Input::all());

        if ($user->id) {
            if (Config::get('confide::signup_email')) {
                Mail::queueOn(
                        Config::get('confide::email_queue'), Config::get('confide::email_account_confirmation'), compact('user'), function ($message) use ($user) {
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
    public function login() {
        $repo = App::make('UserRepository');
        //Session::flush();
        $headerinfo = Request::header();
        //Confide::user()
        if (isset($headerinfo['access-token']) &&
                Session::has("access_token")
        ) {
            return Redirect::route('dashboard');
        } else {
            $islocal = Config::get('launchpad.ignore_oauth2');
            if ($islocal) {
                return View::make(Config::get('confide::login_form'));
            } else {
                $launcpad = array();
                $launchpadurl = "";
                $launcpad['clientid'] = Config::get('launchpad.client_id');
                $launcpad['scopes'] = Config::get('launchpad.scopes');
                $launcpad['redirecturl'] = Config::get('launchpad.redirecturl');
                $launchpadurl .= Config::get('launchpad.launcpad_url');
                $query_string = http_build_query($launcpad);
                $launchpadurl .= $query_string;
                return View::make(Config::get('confide::login_form'))->with('launchpadurl', $launchpadurl);
            }
        }
    }

    /**
     * Displays the launchpad redirect url child window
     *
     * @return  Illuminate\Http\Response
     */
    public function lunchpadtoken() {
        //die("ok here on lounchpad tocken");
        $response_token = Input::get("response_token");
        if (!empty($response_token)) {
            $parmas = array();
            $params['response_token'] = urlencode($response_token);
            $client_secret = Config::get('launchpad.client_secret');
            $params['client_secret'] = urlencode($client_secret);
            $fields_string = "";
            foreach ($params as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Config::get('launchpad.access_token_url'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            $output = curl_exec($ch);
            /*echo "<pre>";
            print_r($output);
            exit;*/
            if (!empty($output)) {
                curl_close($ch);
                $outputarr = json_decode($output, true);
                $status = $outputarr['status'];
                if ($status == "0") {
                    if (isset($outputarr['error_code']) && $outputarr['error_code'] == "invalid_response_token") {
                        $response = Lang::get('messages.login.invalid_response_token');
                    }
                } else {
                    $response = $outputarr['access_token'];
                }
                return View::make('users.lunchpadtoken')
                                ->with('response', $response)
                                ->with('status', $status);
            } else {
                curl_close($ch);
                $response = Lang::get('messages.login.invalid_response');
                return View::make('users.lunchpadtoken')
                                ->with('response', $response)
                                ->with('status', "0");
            }
        } else {
            $response = Lang::get('messages.login.invalid_access');
            return View::make('users.lunchpadtoken')
                            ->with('response', $response)
                            ->with('status', "0");
        }
    }

    /**
     * Intermediate state to verify access token
     *
     * @return  Illuminate\Http\Response
     */
    public function processOauth($token) {
        if (!empty($token)) {
            $apiurl = "https://nodeapi.classlink.com/my/info";
            $header = array("access_token: $token");
            //echo $token; exit;
            $ch = curl_init();
            $optArray = array(
                CURLOPT_URL => $apiurl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $header
            );
            curl_setopt_array($ch, $optArray);
            $output = curl_exec($ch);
            if (!empty($output)) {
                curl_close($ch);
                $outputArr = json_decode($output, true);
                // check user is tenant administrator
                if (isset($outputArr['Role']) && $outputArr['Role'] == "Tenant Administrator") {
                    $adminhistory = new AdminHistory();
                    $adminhistory->logHistory($outputArr, $token);
                    $adminuser = new AdminUser();
                    $response = $adminuser->verifyAdminProxy($outputArr, true);
                    //asd($response);
                    if (!empty($response['status']) && !empty($response['user'])) {
                        $user = $response['user'];
                        // wirte in access_token
                        Session::put('isProxyExist', $response['isProxyExist']);
                        Session::put('access_key', $token);
                        Session::put('access_token', $token);
                        Session::put('user_id', $user['id']);
                        Session::put('tenant_id', $user['tenant_id']);
                        Session::put('api_key', $user['api_key']);
                        Session::put(Session::getId(), $token);
                        
                        if (!empty($response['isProxyExist'])) {
                            return Redirect::intended(route('dashboard'));
                        } else {
                            return Redirect::intended(route('domain'));
                        }
                    } else {
                        $err_msg = Lang::get('messages.login.invalid_access');
                        return Redirect::action('UsersController@login')->with('error', $err_msg);
                    }
                } else {
                    $err_msg = Lang::get('messages.login.invalid_access');
                    return Redirect::action('UsersController@login')->with('error', $err_msg);
                }
            } else {
                curl_close($ch);
                $err_msg = Lang::get('messages.login.invalid_response');
                return Redirect::action('UsersController@login')->with('error', $err_msg);
            }
        } else {
            $err_msg = Lang::get('messages.login.invalid_access');
            return Redirect::action('UsersController@login')->with('error', $err_msg);
        }
    }

    public function verifySchoolDomain($input) {
        $user_id = Session::get('user_id');
        $user = AdminUser::find($user_id);
        $response['status'] = 0;
        $response['message'] = "";
        if (!empty($user)) {
            $user = $user->toArray();
            $user['school_domain'] = $input['school_domain'];
            $user['api_key'] = $input['api_key'];

            $headers = array();
            $headers[] = 'ADMIN_API_KEY: ' . $user['api_key'];
            $verificationurl = $user['school_domain'] . "/users/checkdomain";
            $fields_string = "";
            foreach ($user as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $verificationurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, count($user));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $data = json_decode($output, true);
            if (!empty($info) && $info['http_code'] == "200") {
                if (!empty($data['status']) && !empty($data['user'])) {
                    $response['status'] = 1;
                    $response['message'] = $data['message'];
                } else {
                    if (!empty($data['message'])) {
                        $response['message'] = $data['message'];
                        if (!empty($data['error'])) {
                            $response['message'] .= " \n --" . $data['error'];
                        }
                    }
                }
            } else {
                $response['message'] = "Please check School Domain is configured properly.";
            }
        } else {
            $response['message'] = "Error in data.";
        }
        return $response;
    }

    /**
     * Intermediate state to get school domain
     *
     * @return  Illuminate\Http\Response
     */
    public function domain() {
        $access_key = Session::get('access_key');
        $user_id = Session::get('user_id');
        $isSchoolDomainExist = Session::get('isSchoolDomainExist');
        if (!empty($access_key) && !empty($access_key)) {
            $user = AdminUser::find($user_id);
            return View::make('users.process_oauth')
                            ->with('isSchoolDomainExist', $isSchoolDomainExist)
                            ->with('schooldomain', $user->school_domain)
                            ->with('apikey', $user->api_key);
        } else {
            $err_msg = Lang::get('messages.login.invalid_access');
            return Redirect::action('UsersController@login')
                            ->with('error', $err_msg);
        }
    }

    public function updateDomain() {
        $rawinput = Input::all();
        $input = array_map('trim', $rawinput);
        // make validation cehck 
        $adminuser = new AdminUser();
        $validateResponse = $adminuser->validateDomain($input);
        if (!empty($validateResponse['status'])) {
            $schoolresponse = $this->verifySchoolDomain($input);
            if (!empty($schoolresponse['status'])) {
                // update in the database 
                $response = $adminuser->updateAdminUser($input);
                if (!empty($response['status'])) {
                    $user = $response['user']->toArray();
                    $repo = App::make('UserRepository');
                    $info['username'] = $user['username'];
                    $info['password'] = Config::get('launchpad.user_password');
                    if ($repo->login($info)) {
                        $token = Session::get('access_key');
                        Session::put('access_token', $token);
                        Session::put('tenant_id', $user['tenant_id']);
                        Session::put('school_domain', $user['school_domain']);
                        Session::put('api_key', $user['api_key']);
                        Session::put(Session::getId(), $token);
                        return array('status' => 1, 'redirect' => "/users/dashboard");
                    } else {
                        if ($repo->isThrottled($info)) {
                            $err_msg = Lang::get('messages.login.too_many_attempts');
                        } elseif ($repo->existsButNotConfirmed($info)) {
                            $err_msg = Lang::get('messages.login.not_confirmed');
                        } else {
                            $err_msg = Lang::get('messages.login.wrong_credentials');
                        }
                        return array('status' => 0, 'message' => $err_msg);
                    }
                } else {
                    return $response;
                }
            } else {
                return $schoolresponse;
            }
        } else {
            return $validateResponse;
        }
    }

    /**
     * Intermediate state to update school domain & login
     *
     * @return  Illuminate\Http\Response
     */
    public function checkDomain() {
        $rawinput = Input::all();
        $input = array_map('trim', $rawinput);
        $adminuser = new AdminUser();
        $response = $adminuser->updateAdminUser($input);
        if ($response['status'] == 1) {
            $user = $response['user']->toArray();
            $repo = App::make('UserRepository');
            $info['username'] = $user['username'];
            $info['password'] = Config::get('launchpad.user_password');
            if ($repo->login($info)) {
                $token = Session::get('access_key');
                Session::put('access_token', $token);
                Session::put('tenant_id', $user['tenant_id']);
                Session::put('school_domain', $user['school_domain']);
                Session::put('api_key', $user['api_key']);
                Session::put(Session::getId(), $token);
                return Redirect::intended(route('dashboard'));
            } else {
                if ($repo->isThrottled($info)) {
                    echo $err_msg = Lang::get('messages.login.too_many_attempts');
                } elseif ($repo->existsButNotConfirmed($info)) {
                    echo $err_msg = Lang::get('messages.login.not_confirmed');
                } else {
                    echo $err_msg = Lang::get('messages.login.wrong_credentials');
                }
                return Redirect::action('UsersController@login')->with('error', $err_msg);
            }
        } else {
            return Redirect::action('UsersController@domain')
                            ->with('error', $response['errors'])
                            ->withInput();
        }
    }

    /**
     * Intermediate state to login on localhost
     *
     * @return  Illuminate\Http\Response
     */
    public function processLocal() {
        // wirte in access_token
        $data['TenantId'] = Config::get('local.TenantId');
        $adminuser = new AdminUser();
        $response = $adminuser->verifyAdminUser($data);
        if ($response['status'] == 1 && !empty($response['user'])) {
            $user = $response['user'];
            $repo = App::make('UserRepository');
            $info['username'] = $user['username'];
            $info['password'] = Config::get('local.user_password');
            if ($repo->login($info)) {
                $token = Config::get('local.access_token');
                Session::put('access_token', $token);
                Session::put('tenant_id', $user['tenant_id']);
                Session::put('school_domain', $user['school_domain']);
                Session::put('api_key', $user['api_key']);
                Session::put(Session::getId(), $token);
                return Redirect::intended(route('dashboard'));
            } else {
                if ($repo->isThrottled($info)) {
                    echo $err_msg = Lang::get('messages.login.too_many_attempts');
                } elseif ($repo->existsButNotConfirmed($info)) {
                    echo $err_msg = Lang::get('messages.login.not_confirmed');
                } else {
                    echo $err_msg = Lang::get('messages.login.wrong_credentials');
                }
            }
        } else {
            $err_msg = Lang::get('messages.login.invalid_access');
        }
        return Redirect::action('UsersController@login')->with('error', $err_msg);
    }

    /**
     * Attempt to do login
     *
     * @return  Illuminate\Http\Response
     */
    public function doLogin() {
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
    public function confirm($code) {
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
    public function forgotPassword() {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     * @return  Illuminate\Http\Response
     */
    public function doForgotPassword() {
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
    public function resetPassword($token) {
        return View::make(Config::get('confide::reset_password_form'))
                        ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    public function doResetPassword() {
        $repo = App::make('UserRepository');
        $input = array(
            'token' => Input::get('token'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if ($repo->resetPassword($input)) {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return Redirect::action('UsersController@login')
                            ->with('notice', $notice_msg);
        } else {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
            return Redirect::action('UsersController@resetPassword', array('token' => $input['token']))
                            ->withInput()
                            ->with('error', $error_msg);
        }
    }

    /**
     * Attempt change password of the user
     *
     * @return  Illuminate\Http\Response
     */
    public function changepassword() {
        $repo = App::make('UserRepository');
        $input = array(
            'oldpassword' => Input::get('oldpassword'),
            'password' => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        );
        $userDetail = Confide::user();
        $input['email'] = $userDetail->email;
        $input['pass'] = $userDetail->password;
        $input['id'] = $userDetail->user_id;
        $resultArray = $repo->changepassword($input);
        //Set new password to user
        if ($resultArray['status'] == true) {
            $userDetail->password = Input::get('password');
            $userDetail->password_confirmation = Input::get('password_confirmation');

            $userDetail->touch();
            $save = $userDetail->save();
        }
        echo json_encode($resultArray);
        exit;
    }

    /**
     * Log the user out of the application.
     *
     * @return  Illuminate\Http\Response
     */
    public function logout() {
        Session::flush();
        Confide::logout();
        return Redirect::to('/');
    }

    public function signup() {
        $repo = App::make('UserRepository');
        $input = Input::all();
        /* $postedData=json_decode((file_get_contents("php://input")),true);
          echo "<pre>"; print_r($input); die; */
        if ($repo->signup($input)) {
            $response = array("status" => true);
            echo json_encode($response);
            exit;
        }
    }

}
