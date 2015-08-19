<?php
use Illuminate\Http\Request;


class ProxyClientController extends BaseController
{
    
    public function __construct(AdminUser $admin, Request $request)
    {
        //$request = new Request();
        $this->request = $request;
        $this->admin = $admin;
    
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $headers = getallheaders();

        $responseArray = array();
        $responseArray['status'] = false;

        if(!empty($headers['api-key'])) {
            $headers['Api-Key'] = $headers['api-key'] ;
            //$api_key = $headers['api-key'];
        }
        if (empty($headers['Api-Key'])) {
            $responseArray['message'] = "Invalid or blank auth token";
            //$responseArray['responseMessage'] = "Please enter API key.";
            return Response::json($responseArray, 400);
            exit;
        } else {
            $api_key = Config::get('domainapikey.api_key');
            if(!empty($headers['Api-Key']) && $headers['Api-Key'] == $api_key) {

                if($this->request->isMethod('post')) {
                    $input = Input::all();
                    unset($input['id']);
                    $proxy = $this->proxySetup($input);
                    $responseArray['status']    = $proxy['status'];
                    $responseArray['id']        = $proxy['id'];
                    $responseArray['message']   = $proxy['message'];
                }
                else if($this->request->isMethod('put')) {
                    $input = Input::all();
                    $proxy = $this->proxySetup($input);
                    $responseArray['status']    = $proxy['status'];
                    $responseArray['id']        = $proxy['id'];
                    $responseArray['message']   = $proxy['message'];
                }
                else if($this->request->isMethod('delete')) {
                    $input = Input::all();
                    $proxy = $this->proxyDelete($input);
                    $responseArray['status']    = $proxy['status'];
                    $responseArray['id']        = $proxy['id'];
                    $responseArray['message']   = $proxy['message'];
                }
                else {
                    $input = Input::all();
                    $proxy = $this->proxyList($input);
                    $responseArray['status']    = $proxy['status'];
                    $responseArray['data']      = $proxy['data'];
                    $responseArray['message']   = $proxy['message'];
                }
                return Response::json($responseArray, 200);
                exit;
            } else {
                $responseArray['message'] = "Please enter valid api key.";
                return Response::json($responseArray, 200);
                exit;
            }
        }
	}

    public function proxySetup($input=array()) {
        try{
            if(isset($input['id']) && !empty($input['id'])) {
                $id = $input['id'];

                DB::table('applications')->where('id', $id)->update([
                    'name'          => $input['application_name'],
                    'internal_url'  => $input['internal_url'],
                    'request_uri'   => $input['internal_uri']
                    //'external_url'  => $input['internal_url'],
                ]);
            } else {
                //$application = DB::table('applications')->orderBy('id', 'desc')->first();
                //$next_id = (int)$application->id + 1;

                $application = new Application();

                $application->tenant_id     = $input['tenant_id']; //$tenant_id;
                $application->name          = $input['application_name'];
                $application->internal_url  = $input['internal_url'];
                //$application->external_url  = $input['internal_url'];
                $application->request_uri   = $input['internal_uri'];

                $application->save();
                $id = $application->id;
            }
            //$admin          = $this->admin->getProxy();
            //$admin_api_key  = $admin['api_key'];

            $proxy_base_url     = Config::get('proxy.base_url');
            $proxy_listen_port  = Config::get('proxy.listen_port');
            $proxy_config_path  = Config::get('proxy.config_path');
            $proxy_service_path = Config::get('proxy.nginx_service_path');
            $proxy_auth_url     = Config::get('proxy.auth_url');

            $tenant_id      = $input['tenant_id'];;
            $request_uri    = $input['internal_uri']; //'gwstoken='; //$admin_api_key;
            $request_params = 'gwstoken='; //$admin_api_key;
            $external_ip    = $id.".".$proxy_base_url;

            $external_url   = $id.".".$proxy_base_url.':'.$proxy_listen_port.'/'.$request_uri;
            $concat_operator = strpos($external_url, '?') ? '&':'?';
            $external_url   = $external_url.$concat_operator.$request_params;
            $internal_url   = $input['internal_url'];
            $lua_file_path  = '/etc/nginx/nginx.lua'; //$_SERVER['DOCUMENT_ROOT']

            /*$strnginx="";
            $strnginx.='server {listen      '.$proxy_listen_port.'; server_name  '.$external_ip.';';
            //$strnginx.='include /etc/nginx/default.d/*.conf;';
            $strnginx.='location / {root   /usr/share/nginx/html;index index.php  index.html index.htm;';
            $strnginx.='auth_basic "closed site";';
            $strnginx.='auth_basic_user_file '.$proxy_config_path.'/.htpasswd;';
            $strnginx.='proxy_pass   '.$internal_url.';';
            $strnginx.='}}';*/

            $strnginx="";
            $strnginx.='server {listen '.$proxy_listen_port.'; server_name '.$external_ip.';';
            $strnginx.='location / {';
            $strnginx.='auth_request /check;';
            $strnginx.='auth_request_set $gws $upstream_http_gwstoken;';
            $strnginx.='header_filter_by_lua_file '.$lua_file_path.';';
            $strnginx.='proxy_pass   '.$internal_url.';';
            $strnginx.='}';
            //$strnginx.='error_page 403 = @error403;';
            //$strnginx.='location @error403 { return 404; }';
            $strnginx.='location = /check {';
            $strnginx.='proxy_pass '.$proxy_auth_url.'?tenant_id='.$tenant_id.';';
            $strnginx.='proxy_pass_request_body off;';
            $strnginx.='proxy_set_header Content-length "";';
            $strnginx.='proxy_set_header X-Original-URI $request_uri;';
            $strnginx.='}}';

            $filename=$proxy_config_path.'/'.$external_ip.'.conf';
            $fp = fopen($filename,"w");
            fwrite($fp, $strnginx);
            fclose($fp);

            // Update External URL in application table
            DB::table('applications')->where('id', $id)->update([
                'external_url'  => $external_url,
                'request_uri'   => $request_uri
            ]);
            $output = array( 'status' => 1, 'id' => $id, 'message'=> 'Application updated successfully');

            exec($proxy_service_path);
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return $output;
    }

    public function proxyDelete($input=array()) {
        try {
            if(isset($input['id']) && !empty($input['id'])) {
                $id = $input['id'];
                $application = Application::find($id);
                $status = $application->delete();

                $config_file = $id . "." . Config::get('proxy.base_url') . '.conf';
                $config_file_path = Config::get('proxy.config_path') . '/' . $config_file;
                if (file_exists($config_file_path)) {
                    unlink($config_file_path);
                }
                $output = array( 'status' => $status, 'id' => $id, 'message'=> 'Application deleted successfully');
            } else {
                $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Application id is missing');
            }
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return $output;
    }

    public function proxyList($input=array()) {
        try {
            if(isset($input['tenant_id']) && !empty($input['tenant_id'])) {
                $tenant_id = $input['tenant_id'];
                $results = Application::where('tenant_id', '=', $tenant_id)->get();
                if(count($results)) {
                    $output = array( 'data' => $results, 'status' => 1);
                } else {
                    $output = array( 'data' => array(), 'status' => 1, 'message'=> 'No record found.');
                }
            } else {
                $output = array( 'status' => 0, 'data' => array(), 'message'=> 'Tenant id is missing');
            }
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'data' => array(), 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return $output;
    }

}
