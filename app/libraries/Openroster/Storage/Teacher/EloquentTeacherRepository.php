<?php

namespace Openroster\Storage\Teacher;
use Openroster\Storage\AbstractEloquentRepository;

class EloquentTeacherRepository extends AbstractEloquentRepository implements TeacherRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\Teacher $model)
    {
        $this->model = $model;
    }

    public function getTeacherList($inputData = array())
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
        }

        if (!empty($inputData['sortDir'])) {
            $params['sort_order'] = $inputData['sortDir'];
        }

        /*if (!empty($inputData['first_name'])) {
            $params['where']['first_name'] = array('op' => 'like', 'value' => $inputData['first_name']);
        }

        if (!empty($inputData['school_id'])) {
            $params['where']['school_id'] = $inputData['school_id'];
        }*/
        //$params['condition']['courses.course_name'] = array('op' => 'like', 'value' => $inputData['course_name']); 
        if (isset($inputData['first_name']) && $inputData['first_name']!="") {
            $params['condition'][] = array('teachers.first_name', 'like', '%'.$inputData['first_name'].'%');
        }
        if (isset($inputData['last_name']) && $inputData['last_name'] !="") {
            $params['condition'][] = array('teachers.last_name', 'like', '%'.$inputData['last_name'].'%');
        }
        if (isset($inputData['email']) && $inputData['email'] !="") {
            $params['condition'][] = array('teachers.email', 'like', '%'.$inputData['email'].'%');
        }
        
        $params['table'] = 'teachers';
        return $this->getByPage($params);
    }

    public function importTeachers($result)
    {
        
        $importFiles = \Config::get("appvals.import_file_names");
        $validRecords = $invalidRecords = array();
        if (empty($result)) return false;
            
        try {
            $records = $this->validateTeacherData($result);
            if(count($records['valid_records'])>0){
                $insterArray = array();
                foreach($records['valid_records'] as $key=>$val){
                        $insterArray[$val['teacher_id']] = $val;
                }
                
                $this->bulkInsert($insterArray);
            }
            $logComment['file_import_comment'] = $records['log_message'];
            $logComment['import_successful_records'] = count($records['valid_records']);
            $logComment['import_unsuccessful_records'] = count($records['invlaid_records']);
            
            $this->updateLogComment($logComment, 'teacher');
            
        } catch (\Exception $exception) {
            \Log::error($exception);
            $fileData = $this->getFileStatusData('teacher');
            $fileData['data']['file_available'] = $logFComment['file_available'] = 1;
            $logFComment['file_import_comment'] = $exception->getMessage();
            $this->updateLogComment($logComment, 'teacher');
            $fileData['msg'] = trans('messages.importdata.file_import_restart', array('name'=>$importFiles[$startImport['file_id']]));
            
            return $fileData;
        }
        
        return true;
    }

    public function validateTeacherData($result)
    {
        $date = new \DateTime;
        $validRecords = array();
        $invalidRecords = array();
        $logMessages = '';
        
        $validRules = array(
            'teacher_id' => 'required|Integer|unique:teachers',
            'school_id' => 'required|exists:schools',
            'first_name' => 'required|alpha_numeric_spaces',
            'last_name' => 'required|alpha_numeric_spaces',
            'email' => 'required|email',
            'adusername' => 'required|alpha_numeric_spaces'
        );

        foreach ($result as $row) {
            $data = $row->toArray();
            
            $validator = \Validator::make(
                            $data, $validRules
            );
            if ($validator->fails()) {
                $messages = '<ul>'.implode(" ",$validator->messages()->all('<li>:message</li>')).'</ul>';
                $invalidRecords[] = array(
                    'id' => $data['teacher_id'],
                    'data' => $data,
                    'messages' => $messages
                );
                $logMessages .= $this->logComment($data['teacher_id'], $messages);
            } else {
                $data['updated_at'] = $data['created_at'] = $date;
                $validRecords[] = $data;
            }
        }
        
        return array('valid_records'=>$validRecords, 'invlaid_records'=>$invalidRecords, 'log_message' => $logMessages);
        
    }
    
   
    
    

}
