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
    
    public function applicationAdd() {
        $input = Input::all();
        try {
            $application = new Application();
            
            $application->tenant_id = Session::get('tenant_id');
            $application->name = $input['application_name'];
            $application->internal_url = $input['internal_url'];
            $application->external_url = $input['internal_url'];
            
            $application->save();
            $id = $application->id;
            $output = array( 'status' => 1, 'id' => $id);
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> 'Exception occured');
        }
        return Response::json($output);
    }
    
    public function applicationEdit() {
        $input = Input::all();
        $id = $input['id']; 
        try {
            DB::table('applications')->where('id', $id)->update([
                'name'          => $input['application_name'],
                'internal_url'  => $input['internal_url'],
                'external_url'  => $input['internal_url']
            ]);
            $output = array( 'status' => 1, 'id' => $id);
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> $ex->getMessage());
        }
        return Response::json($output);
    }
    
    public function applicationDelete() {
        $input = Input::all();
        $id = $input['id'];
        try {
            $application = Application::find($id);
            $status = $application->delete();
            $output = array( 'status' => $status, 'id' => $id);
        } catch (Exception $ex) {
            $output = array( 'status' => 0, 'id' => 0, 'message'=> $ex->getMessage());
        }
        return Response::json($output);
    }
    
    public function applicationList() {
        //$application = new Application();
        $results = Application::all();
        return Response::json($results);
        //return (array) $application->getApplicationList(Input::all());
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
