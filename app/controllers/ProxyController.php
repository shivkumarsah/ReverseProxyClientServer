<?php
use Illuminate\Http\Request;
use Openroster\Storage\Application\ApplicationRepository as Application;

class ProxyController extends BaseController
{
    
    public function __construct(AdminUser $admin, Application $application)
    {
        //$request = new Request();
        //$this->request = $request; 
        $this->admin = $admin;
        $this->application = $application;
    
    }
    
    /**
     * Displays the reverse proxy setting page
     *
     * @return  Illuminate\Http\Response
     */
    public function settings() {
        $proxy = $this->admin->getProxy();
        $login = Config::get('launchpad.login_required');
        $showAction = ($login)? false : true;
        return View::make('proxy.settings')->with('result', $proxy)->with('showAction', $showAction);
    }
    
    /**
     * Save reverse proxy setting
     *
     * @return  Illuminate\Http\Response
     */
    public function settingsSave() {
        $input = Input::all();
        $login = Config::get('launchpad.login_required');
        if($login) {
            $validate = $this->verifyProxyDomain($input);
            if ($validate['status']) {
                $result = $this->admin->setProxy($input);
            } else {
                $result = $validate;
            }
        } else {
            $dir_path = $_SERVER['DOCUMENT_ROOT'].'/../app/config/';
            $apikeycontent = '<?php return array( "api_key" => ' . "'" . $input["api_key"] . "'" . ');';

            $dmr = file_put_contents($dir_path . 'domainapikey.php', $apikeycontent);
            if ($dmr == "" || $dmr == false) {
                throw new Exception("Permissions required to '/app/config/domainapikey.php'");
            }
            $result = $this->admin->setProxy($input);
        }
        return Response::json($result);
    }

    /**
     * Displays the reverse https ssl certificate setting page
     *
     * @return  Illuminate\Http\Response
     */
    public function sslSettings() {
        $proxy = $this->admin->getProxy();
        $login = Config::get('launchpad.login_required');
        $showAction = ($login)? false : true;
        return View::make('proxy.sslsettings')->with('result', $proxy)->with('showAction', $showAction);
    }

    /**
     * Save https ssl certificate setting
     *
     * @return  Illuminate\Http\Response
     */
    public function sslSettingsSave() {
        $input = Input::all();

        $dir_path = $_SERVER['DOCUMENT_ROOT'].'/../app/config/local/';

        Config::set('proxy.certificatePem', 'file fill path');
        Config::set('proxy.certificateKey', 'file fill path');

        $dmr = file_put_contents($dir_path . 'proxy.php', print_r(Config::get('proxy'), true));
        if ($dmr == "" || $dmr == false) {
            throw new Exception("Permissions required to ". $dir_path .'proxy.php');
        }
        $result = $this->admin->setProxy($input);
        return Response::json($result);
    }

    /**
     * Validate Proxy domain and api-key using cURL
     *
     * @return  Illuminate\Http\Response
     */
    public function verifyProxyDomain($input) {
        $user_id = Session::get('user_id');
        $user = AdminUser::find($user_id);
        $response['status'] = 0;
        $response['message'] = "";
        if (!empty($user)) {
            $user = $user->toArray();
            $user['proxy_url'] = $input['proxy_url'];
            $user['api_key'] = $input['api_key'];

            $headers        = array();
            $headers[]      = 'ADMIN_API_KEY: ' . $user['api_key'];
            $request_url    = $user['proxy_url'] . "/users/checkdomain";
            $fields_string  = "";
            foreach ($user as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, count($user));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $data = json_decode($output, true);
            if (!empty($info) && $info['http_code'] == "200") {
                if (!empty($data['status']) && $data['status']) {
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
                $response['message'] = "Please check Proxy Domain is configured properly.";
            }
        } else {
            $response['message'] = "Error in data.";
        }
        return $response;
    }

    /**
     * Displays the application list page
     *
     * @return  Illuminate\Http\Response
     */
    public function applications() {
        $login = Config::get('launchpad.login_required');
        return View::make('proxy.application')->with('showAction', $login);
    }

    /**
     * Application create on proxy server
     *
     * @return  Illuminate\Http\Response
     */
    public function applicationAdd() {
        try {
            //$input = Input::all();
            //$output = $this->proxySetup($input);

            $user_id = Session::get('user_id');
            $user = AdminUser::find($user_id);
            $response['status'] = 0;
            $response['message'] = "";
            if (!empty($user)) {
                $input          = Input::all();
                $user           = $user->toArray();
                $request_data   = array_merge($user, $input);

                $headers        = array();
                $headers[]      = 'api-key: ' . $user['api_key'];
                $request_url    = $user['proxy_url'] . "/api/v1/proxy";
                $fields_string  = "";
                foreach ($request_data as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_URL, $request_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, count($user));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                $data = json_decode($output, true);
                //asd($data,0);
                //asd($info);

                if (!empty($info) && $info['http_code'] == "200") {
                    $response = $data;
                } else if (!empty($info) && (int)$info['http_code'] >= 500) {
                    $response['message'] = "Please check proxy server configuration";
                } else {
                    $response['message'] = "Please check Proxy Domain is configured properly.";
                }
            } else {
                $response['message'] = "Error in data.";
            }

        } catch (Exception $ex) {
            $response = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return Response::json($response);
    }

    /**
     * Edit application on proxy server
     *
     * @return  Illuminate\Http\Response
     */
    public function applicationEdit() {
        try {
            $user_id = Session::get('user_id');
            $user = AdminUser::find($user_id);
            $response['status'] = 0;
            $response['message'] = "";
            if (!empty($user)) {
                $input          = Input::all();
                $user           = $user->toArray();
                $request_data   = array_merge($user, $input);

                $headers        = array();
                $headers[]      = 'api-key: ' . $user['api_key'];
                $request_url    = $user['proxy_url'] . "/api/v1/proxy";
                $fields_string  = "";
                foreach ($request_data as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_URL, $request_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, count($user));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                $data = json_decode($output, true);
                if (!empty($info) && $info['http_code'] == "200") {
                    $response = $data;
                } else if (!empty($info) && (int)$info['http_code'] >= 500) {
                    $response['message'] = "Please check proxy server configuration";
                } else {
                    $response['message'] = "Please check Proxy Domain is configured properly.";
                }
            } else {
                $response['message'] = "Error in data.";
            }
        } catch (Exception $ex) {
            $response = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return Response::json($response);
    }

    /**
     * Displays the application from proxy server
     *
     * @return  Illuminate\Http\Response
     */
    public function applicationDelete() {
        try {
            $user_id = Session::get('user_id');
            $user = AdminUser::find($user_id);
            $response['status'] = 0;
            $response['message'] = "";
            if (!empty($user)) {
                $input          = Input::all();
                $user           = $user->toArray();
                $request_data   = array_merge($user, $input);

                $headers        = array();
                $headers[]      = 'api-key: ' . $user['api_key'];
                $request_url    = $user['proxy_url'] . "/api/v1/proxy";
                $fields_string  = "";
                foreach ($request_data as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_URL, $request_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, count($user));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                $data = json_decode($output, true);

                if (!empty($info) && $info['http_code'] == "200") {
                    $response = $data;
                } else if (!empty($info) && (int)$info['http_code'] >= 500) {
                    $response['message'] = "Please check proxy server configuration";
                } else {
                    $response['message'] = "Please check Proxy Domain is configured properly.";
                }
            } else {
                $response['message'] = "Error in data.";
            }
        } catch (Exception $ex) {
            $response = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return Response::json($response);
    }

    /**
     * Delete the application from application
     *
     * @return  Illuminate\Http\Response
     */
    public function applicationDeleteOLD() {
        $input = Input::all();
        $id = $input['id'];
        try {
            $application = Application::find($id);
            $status = $application->delete();
            
            $config_file        = $id.".".Config::get('proxy.base_url').'.conf';
            $config_file_path   = Config::get('proxy.config_path').'/'.$config_file;
            
            unlink($config_file_path);
            
            $output = array( 'status' => $status, 'id' => $id);
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return Response::json($output);
    }

    /**
     * Displays the application list page
     *
     * @return  Illuminate\Http\Response
     */
    public function applicationList() {
        $login = Config::get('launchpad.login_required');
        if($login) {
            // Show application list to proxy server
            //-------------------------//
            $user_id = Session::get('user_id');
            $user = AdminUser::find($user_id);
            $response['status'] = 0;
            $response['message'] = "";
            if (!empty($user)) {
                $input          = Input::all();
                $user           = $user->toArray();
                $request_data   = array_merge($user, $input);

                $headers        = array();
                $headers[]      = 'api-key: ' . $user['api_key'];
                $request_url    = $user['proxy_url'] . "/api/v1/proxy";
                $fields_string  = "";
                foreach ($request_data as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');
                $request_url = $request_url.'?tenant_id='.$user['tenant_id'];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                $data = json_decode($output, true);
                if (!empty($info) && $info['http_code'] == "200") {
                    if(isset($data['data']) && !empty($data['data'])) {
                        $response = $data['data'];
                    } else {
                        $response['message'] = ($data['message']) ? $data['message'] : "Please check proxy server configuration";
                    }
                } else if (!empty($info) && (int)$info['http_code'] >= 500) {
                    $response['message'] = "Please check proxy server configuration";
                } else {
                    $response['message'] = "Please check Proxy Domain is configured properly.";
                }
            } else {
                $response['message'] = "Error in data.";
            }
            return Response::json($response);
        } else {
            // Show application list to proxy client
            /*$tenant_id = Session::get('tenant_id');
            //$results = Application::where('tenant_id', '=', $tenant_id)->get();
            $results = Application::all();
            return Response::json($results);*/
            return (array) $this->application->getApplicationList(Input::all());
        }
    }
}
