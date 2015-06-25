<?php

use Openroster\Storage\Developer\DeveloperRepository as Developer;


/**
 * DevelopersController Class
 *
 * Implements actions regarding user management
 */
class DevelopersController extends BaseController
{
    
    public function __construct(Developer $developer)
    {
        $this->developer = $developer;
        
    }
    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('developers.list');
    }
    
    /**
     * Returns list of Csv Files
     *
     * @return  Illuminate\Http\Response
     */
    public function listDevelopers()
    {
        return (array) $this->developer->getDeveloperList(Input::all());
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
    
    public function apitokens($id){
        $result =  $this->developer->find($id)->toArray();
        return View::make('developers.tokens')->with('result', $result);;
    
    }
    public function developerSchools($id){
        $schoolAssigned=$this->developer->getdeveloperSchools($id);
        echo json_encode($schoolAssigned); exit;
        
    }
    
    public function assignSchool(){
        $input = Input::all();
        $this->developer->assignSchools($input);
        echo json_encode(array("status"=>true)); exit;
    }
    
    public function addDeveloper(){
        $status=$this->developer->addDevelopers(Input::all());
        echo json_encode($status); exit;
    }
    public function editDeveloper(){
       $status= $this->developer->editDevelopers(Input::all());
       echo json_encode($status); exit;
    }
    public function developerKey(){
        $status = $this->developer->refressDeveloprKey(Input::all());
        echo json_encode($status); exit;
    }
    public function deleteDeveloper(){
        $status = $this->developer->deleteDevelopers(Input::all());
        echo json_encode(array("status"=>true)); exit;
    }
    public function apiaccess(){
    
        return View::make('developers.access');
    }
}
