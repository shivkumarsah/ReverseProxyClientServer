<?php
use Illuminate\Http\Request;

class ProxyController extends BaseController
{
    
    public function __construct(AdminUser $admin)
    {
        //$request = new Request();
        //$this->request = $request; 
        $this->admin = $admin;
    
    }
    
    /**
     * Displays the reverse proxy setting page
     *
     * @return  Illuminate\Http\Response
     */
    public function settings() {
        $proxy = $this->admin->getProxy();
        return View::make('proxy.settings')->with('result', $proxy);
    }
    
    /**
     * Save reverse proxy setting
     *
     * @return  Illuminate\Http\Response
     */
    public function settingsSave() {
        $input = Input::all();
        $result = $this->admin->setProxy($input);
        return Response::json($result);
    }
    
    /**
     * Displays the application list page
     *
     * @return  Illuminate\Http\Response
     */
    public function applications() {
        return View::make('proxy.application');
    }
    
    public function proxySetup($input=array()) {
        try{
            $tenant_id = Session::get('tenant_id');
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
                
                $application->tenant_id     = $tenant_id;
                $application->name          = $input['application_name'];
                $application->internal_url  = $input['internal_url'];
                //$application->external_url  = $input['internal_url'];
                $application->request_uri   = $input['internal_uri'];
                
                $application->save();
                $id = $application->id;
            }
            $admin          = $this->admin->getProxy();
            $admin_api_key  = $admin['api_key'];
            
            $proxy_base_url     = Config::get('proxy.base_url');
            $proxy_listen_port  = Config::get('proxy.listen_port');
            $proxy_config_path  = Config::get('proxy.config_path');
            $proxy_service_path = Config::get('proxy.nginx_service_path');
            $proxy_auth_url     = Config::get('proxy.auth_url');
            
            $request_uri    = $input['internal_uri']; //'gwstoken='; //$admin_api_key;
            $request_params = 'gwstoken='; //$admin_api_key;
            $external_ip    = $id.".".$proxy_base_url;
            
            $external_url   = $id.".".$proxy_base_url.':'.$proxy_listen_port.'/'.$request_uri;
            $contact_operator = strpos($external_url, '?') ? '&':'?';
            $external_url   = $external_url.$contact_operator.$request_params;
            $internal_url   = $input['internal_url'];
            
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
            $strnginx.='root   /usr/share/nginx/html;';
            $strnginx.='index index.php  index.html index.htm;';
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
            $output = array( 'status' => 1, 'id' => $id);
            
            exec($proxy_service_path);
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return $output;
    }
    
    
    public function applicationAdd() {
        try {
            $input = Input::all();
            $output = $this->proxySetup($input);
            /*
            $application = new Application();
            
            $application->tenant_id = Session::get('tenant_id');
            $application->name = $input['application_name'];
            $application->internal_url = $input['internal_url'];
            $application->external_url = $input['internal_url'];
            
            $application->save();
            $id = $application->id;
            $output = array( 'status' => 1, 'id' => $id);*/
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return Response::json($output);
    }
    
    public function applicationEdit() {
         
        try {
            $input = Input::all();
            $output = $this->proxySetup($input);
            /*
            $id = $input['id'];
            DB::table('applications')->where('id', $id)->update([
                'name'          => $input['application_name'],
                'internal_url'  => $input['internal_url'],
                'external_url'  => $input['internal_url']
            ]);
            $output = array( 'status' => 1, 'id' => $id);
            */
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured', 'debug'=> $ex->getMessage());
        }
        return Response::json($output);
    }
    
    public function applicationDelete() {
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
    
    public function applicationList() {
        $tenant_id = Session::get('tenant_id');
        $results = Application::where('tenant_id', '=', $tenant_id)->get();
        return Response::json($results);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
