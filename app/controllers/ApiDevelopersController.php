<?php
class ApiDevelopersController extends BaseController {

	

	public function __construct(){
        
        
    }
    public function index(){
        $inputs = Input::json();
        $inputArray = $inputs->all();
        $name   = trim(@$inputArray['tokenSecret']);
        $apiKey = trim(@$inputArray['apiKey']);
        if(empty($name)){
            $responseArray = array();
            $responseArray['responseCode'] =200; 
            $responseArray['error'] =true; 
            $responseArray['responseMessage'] ="Please send token Secret"; 
            return Response::json($responseArray);
        }
        if(empty($apiKey)){
            $responseArray = array();
            $responseArray['responseCode'] =200; 
            $responseArray['error'] =true; 
            $responseArray['responseMessage'] ="Please send valid api Key"; 
            return Response::json($responseArray);
        }
        $developer = new Developer();
        $result=$developer->checkIdentity(Input::json());
        $responseArray = array();
        $responseArray['responseCode'] =200; 
        $responseArray['error'] = $result['status']; 
        $responseArray['responseMessage'] = $result['responseMessage']; 
        if(!empty($result['token'])){
            $responseArray['token'] = $result['token'];
        }
        return Response::json($responseArray);
	}
}

?>
