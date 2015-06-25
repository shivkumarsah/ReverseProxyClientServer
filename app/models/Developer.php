<?php

class Developer extends Eloquent {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'developers';
	protected $primaryKey = 'developer_id';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
     
	protected $hidden = array();
     
    function __construct(){
        parent :: __construct();
    }  
    public function checkIdentity($params){
            $params=$params->all();
            $resultArray = array();
            $res = DB::table('developers')
                       //->where('developer_name', "=" ,$params['name'])
                       ->where('api_key', "=" ,$params['apiKey'])
                       ->count();
            if($res == 1){
                $token=$this->createtoken($params);
                $resultArray['status'] = false;    
                $resultArray['responseMessage'] = "Request process successfully";
                $resultArray['token'] = $token;
            }else{
                $resultArray['status'] = true;    
                $resultArray['responseMessage'] = "Invalid api key";
            } 
            return $resultArray;
        }
        
        protected function createtoken($params){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            if (function_exists('com_create_guid')){
				$token = 	base64_encode(com_create_guid());
            }else{
                   mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
                   $charid = strtoupper(md5(uniqid(rand(), true)));
                   $hyphen = chr(45);// "-"
                   $uuid = chr(123)// "{"
                           .substr($charid, 0, 8).$hyphen
                           .substr($charid, 8, 4).$hyphen
                           .substr($charid,12, 4).$hyphen
                           .substr($charid,16, 4).$hyphen
                           .substr($charid,20,12)
                           .chr(125);// "}"
                    $token = 	base64_encode($uuid);
                  
            }
            $res = DB::table('developers')
                       //->where('developer_name', "=" ,$params['name'])
                       ->where('api_key', "=" ,$params['apiKey'])
                       ->get();
            $res = (array) $res;
            $res = $res[0];
           
            $count = DB::table('api_token')
                       ->where('developerId', "=" ,$res->developer_id)
                       ->count();           
                       
            if($count > 0){
                DB::table('api_token')->where('developerId', '=', $res->developer_id)->delete();    
            }
            DB::table('api_token')->insert(
                                            array('tokenId' => $token,
                                              'developerId' => $res->developer_id,
                                              'name' => $res->developer_name,
                                              'lastupdatedTime' => date('Y-m-d H:i:s'),
                                              'createdTime' => date('Y-m-d H:i:s')
                                            )
                                    );
            
            return $token;
        }
        
        public function checkIToken($token){
                
            $res = DB::table('developers')
                       ->where('api_key', "=" ,$token['Api-Key'])
                       ->where('api_secret', "=" ,$token['Secret-Key'])
                       ->count();
            if($res == 1){
                //$this->updateLastActionTime($token);        
                $result = DB::table('developers')
                       ->where('api_key', "=" ,$token['Api-Key'])
                       ->where('api_secret', "=" ,$token['Secret-Key'])
                       ->get();
                
                $resultArray['status'] = true;
                $resultArray['developerId'] = $result[0]->developer_id;
            }else{
                $resultArray['status'] = false;    
                $resultArray['responseCode'] = 401;    
                $resultArray['responseMessage'] = "Invalid api key or secret key";
            }
            
            
            /*$resultArray = array();
            $res = DB::table('api_token')
                       ->where('tokenId', "=" ,$token)
                       ->count();
            if($res == 1){
                $this->updateLastActionTime($token);        
                $result = DB::table('api_token')
                       ->where('tokenId', "=" ,$token)
                       ->get();
                
                $resultArray['status'] = true;
                $resultArray['developerId'] = $result[0]->developerId;
            }else{
                $resultArray['status'] = false;    
                $resultArray['responseMessage'] = "Invalid auth token";
            }
            */
            return $resultArray;
        }
        
        protected function updateLastActionTime($token){
            DB::table('api_token')
                ->where("tokenId","=",$token)
                ->update(
                    array(
                        'lastupdatedTime' => date('Y-m-d H:i:s')
                    )
                );
        }
        
        public function getDeveloperDetail($id){
            $result = \DB::table('developers')->where('developer_id','!=', $id)->get();
            
            return (array) $result[0];
        }
}
