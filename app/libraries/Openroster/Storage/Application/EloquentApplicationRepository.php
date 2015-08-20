<?php

namespace Openroster\Storage\Application;
use Openroster\Storage\AbstractEloquentRepository;

class EloquentApplicationRepository extends AbstractEloquentRepository implements ApplicationRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\Application $model)
    {
        $this->model = $model;
    }

    public function getApplicationList($inputData = array())
    {
        $params = array();
        if (!empty($inputData['pageNumber'])) {
            $params['page'] = $inputData['pageNumber'];
        }
        if (!empty($inputData['itemsPerPage'])) {
            $params['limit'] = $inputData['itemsPerPage'];
        }
        if (!empty($inputData['sortedBy'])) {
            $params['sort_by'] = $inputData['sortedBy'];
        }else{
            $params['sort_by'] = 'applications.id';
        }

        if (!empty($inputData['sortDir'])) {
            $params['sort_order'] = $inputData['sortDir'];
        }else{
            $params['sort_order'] = 'DESC';
        }
        $params['groupby'] = 'applications.id';

        $params['table'] = 'applications';
        
        $params['fields'] = array('applications.id','applications.tenant_id','applications.name','applications.internal_url','applications.external_url','applications.request_uri','applications.created_at','applications.updated_at');
        // join array
        //$params['join'][] = array('developer_school','developer_school.developer_id','=','developers.developer_id','leftJoin');
        return $this->getByPage($params);
    }
    
    public function getdeveloperSchools($id){
        /*$assignedSchools = \DB::table('schools') ->select(array('schools.school_id','schools.school_name', \DB::raw('if(developer_school.developer_id = '.$id.',true,false) as isChecked')))->leftjoin('developer_school', 'developer_school.school_id', '=', 'schools.school_id')->groupBy('schools.school_id')->get();*/
        
        
        $assignedSchools = \DB::table('developer_school')->where('developer_id', '=', $id)
                    ->select('school_id')
                    ->get();
        
        $schoolIds = array();
        foreach($assignedSchools as $key=>$val){
                $schoolIds[]  = $val->school_id;
        }            
        
        
        $result = \DB::table('schools')
                ->select(array('school_id', 'school_name'))
                ->get();
                
        foreach($result as $k=>$v){
            if(in_array($v->school_id,$schoolIds)){
                $result[$k]->isChecked = true; 
            }else{
                $result[$k]->isChecked = false; 
            }
        }
        return $result;
    }
    
    public function assignSchools($input){
        $developerId = $input[0]['developer_id'];
        \DB::table('developer_school')->where('developer_id', $developerId)->delete();
        $this->bulkInsert($input,'developer_school');
    }
    
    public function addDevelopers($input){
            $count=$this->checkDeveloperDuplicate($input['email']);
            if($count>0){
                return array('status'=>false,"statusMessage"=>'Email already exist'); 
            }
            $data = array();
            $data['developer_name'] = $input['developer_name'];
            $data['email'] = $input['email'];
            $data['api_secret'] = $input['api_secret'];
            $data['api_key'] = hash_hmac('sha1', md5(uniqid(mt_rand(), true)), $input['api_secret']);
            $data['created_at'] = date("Y-m-d H:i:s");
            $insterId=\DB::table('developers')
                ->insertGetId($data);
            $data['developer_id'] = $insterId;
            $data['status'] = true;
            return $data;
    }
    public function editDevelopers($input){
            $count=$this->checkDeveloperDuplicate($input['email'],$input['developer_id']);
            if($count>0){
                return array('status'=>false,"statusMessage"=>'Email already exist'); 
            }
            $data = array();
            if(!empty($input['developer_name'])){
                $data['developer_name'] = $input['developer_name'];
            }
            if(!empty($input['email'])){
                $data['email'] = $input['email'];
            }
            if(!empty($input['api_secret'])){
                $data['api_secret'] = $input['api_secret'];
                $data['api_key'] = hash_hmac('sha1', md5(uniqid(mt_rand(), true)), $input['api_secret']);
            }
            
            $data['updated_at'] = date("Y-m-d H:i:s");
            \DB::table('developers')
            ->where('developer_id', $input['developer_id'])
            ->update($data);
            return array('developer_name'=>$input['developer_name'],'email'=>$input['email'],'api_secret'=>$input['api_secret'], 'status'=>true);
    }
    public function refressDeveloprKey($input){
            $data = array();
            $apiKey = md5(uniqid(mt_rand(), true));
            $data['api_key'] = $apiKey;
            \DB::table('developers')
            ->where('developer_id', $input['developer_id'])
            ->update($data);
           return array('api_key'=>$apiKey, 'status'=>true); 
    }
    
    public function deleteDevelopers($input){
            \DB::table('developers')->where('developer_id', $input['developer_id'])->delete();
    }
    
    public function checkDeveloperDuplicate($email, $id = null){
        $model = \DB::table('developers')->where('email','=',$email);
        if(!empty($id)){
            $model->where('developer_id','!=', $id);
        }
        $count=$model->count();
        return $count;
    }
    
}
