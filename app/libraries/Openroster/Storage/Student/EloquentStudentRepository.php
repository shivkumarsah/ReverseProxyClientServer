<?php

namespace Openroster\Storage\Student;
use Openroster\Storage\AbstractEloquentRepository;

class EloquentStudentRepository extends AbstractEloquentRepository implements StudentRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\Student $model)
    {
        $this->model = $model;
    }

    public function getStudentList($inputData = array())
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
        if (isset($inputData['first_name']) && $inputData['first_name']!='') {
            $params['condition'][] = array('students.first_name', 'like', '%'.$inputData['first_name'].'%');
        }
        if (isset($inputData['last_name']) && $inputData['last_name'] !="") {
            $params['condition'][] = array('students.last_name', 'like', '%'.$inputData['last_name'].'%');
        }
        if (isset($inputData['email']) && $inputData['email'] !="") {
            $params['condition'][] = array('students.email', 'like', '%'.$inputData['email'].'%');
        }
        
        $params['table'] = 'students';
        // param  selected fields
        $params['fields'] = array('students.student_id','students.first_name','students.last_name','students.email','schools.school_name');
        // join array
        $params['join'][] = array('schools','schools.school_id','=','students.school_id','join');
        

        return $this->getByPage($params);
        
        return $this->getByPage($params);
    }

    public function importStudents($result)
    {
       // asd($result,2);
        $importFiles = \Config::get("appvals.import_file_names");
        $validRecords = $invalidRecords = array();
        if (empty($result)) return false;
            
        try {
            
            $records = $this->validateStudentData($result);
            if(count($records['valid_records'])>0){
                $insterArray = array();
                foreach($records['valid_records'] as $key=>$val){
                        $insterArray[$val['student_id']] = $val;
                }
                
                $this->bulkInsert($insterArray);
            }
            
            $logComment['file_import_comment'] = $records['log_message'];
            $logComment['import_successful_records'] = count($records['valid_records']);
            $logComment['import_unsuccessful_records'] = count($records['invlaid_records']);
            
            $this->updateLogComment($logComment, 'student');
            
        } catch (\Exception $exception) {
            \Log::error($exception);
            $fileData = $this->getFileStatusData('student');
            $fileData['data']['file_available'] = $logFComment['file_available'] = 1;
            $logFComment['file_import_comment'] = $exception->getMessage();
            $this->updateLogComment($logComment, 'student');
            $fileData['msg'] = trans('messages.importdata.file_import_restart', array('name'=>$importFiles[$startImport['file_id']]));
            
            return $fileData;
        }
        
        return true;
    }

    public function validateStudentData($result)
    {
        $date = new \DateTime;
        $validRecords = array();
        $invalidRecords = array();
        $logMessages = '';
        
        $validRules = array(
            'student_id' => 'required|Integer|unique:students',
            'school_id' => 'required|exists:schools',
            'first_name' => 'required|alpha_numeric_spaces',
            'last_name' => 'required|alpha_numeric_spaces',
            'email' => 'required|email',
            'adusername' => 'required|alpha_numeric_spaces',
            'grade' => 'required|between:-1,12'
        );

        foreach ($result as $row) {
            $data = $row->toArray();
            
            $validator = \Validator::make(
                            $data, $validRules
            );
            if ($validator->fails()) {
                $messages = '<ul>'.implode(" ",$validator->messages()->all('<li>:message</li>')).'</ul>';
                $invalidRecords[] = array(
                    'id' => $data['student_id'],
                    'data' => $data,
                    'messages' => $messages
                );
                $logMessages .= $this->logComment($data['student_id'], $messages);
            } else {
                $data['updated_at'] = $data['created_at'] = $date;
                $validRecords[] = $data;
            }
        }
        
        return array('valid_records'=>$validRecords, 'invlaid_records'=>$invalidRecords, 'log_message' => $logMessages);
        
    }
    
    public function importEnrollment($result)
    {
        
        $importFiles = \Config::get("appvals.import_file_names");
        $validRecords = $invalidRecords = array();
        if (empty($result)) return false;
            
        try {
            $records = $this->validateEnrollmentData($result);
            
            if(!empty($records['valid_records'])){
                $courseStudentArray = array();
                $courseSchoolArray = array();
                $insterArray = array();
               
                foreach($records['valid_records'] as $key=>$val){
                    // check combination of student and course should be unique in the  course student table
                    if($this->checkUniqueNessOfCourseStudent($val['course_id'],$val['student_id'])){ 
                        $couStu = array("course_id"=> $val['course_id'],"student_id"=>$val['student_id']);
                        \DB::table('course_student')->insert($couStu);
                       /* $couSch = array("course_id"=> $val['course_id'],"school_id"=>$val['school_id']);
                        $courseStudentArray[] = $couStu;
                        $courseSchoolArray[]  = $couSch;*/
                    }
                }
                $this->bulkInsert($courseStudentArray , "course_student");
                //$this->bulkInsert($courseSchoolArray,"course_school");
            }
            
            
            $logComment['file_import_comment'] = $records['log_message'];
            $logComment['import_successful_records'] = count($records['valid_records']);
            $logComment['import_unsuccessful_records'] = count($records['invlaid_records']);
            
            $this->updateLogComment($logComment, 'enrollments');
            
        } catch (\Exception $exception) {
            \Log::error($exception);
            $fileData = $this->getFileStatusData('enrollments');
            $fileData['data']['file_available'] = $logFComment['file_available'] = 1;
            $logFComment['file_import_comment'] = $exception->getMessage();
            $this->updateLogComment($logComment, 'enrollments');
            $fileData['msg'] = trans('messages.importdata.file_import_restart', array('name'=>$importFiles[$startImport['file_id']]));
            
            return $fileData;
        }
        
        return true;
    }
    
    public function validateEnrollmentData($result)
    {
        $date = new \DateTime;
        $validRecords = array();
        $invalidRecords = array();
        $logMessages = '';
        
        $validRules = array(
            'student_id' => 'required|Integer|exists:students',
            'school_id' => 'required|Integer|exists:schools',
            'course_id' => 'required|Integer|exists:courses'
        );

        foreach ($result as $row) {
            $data = $row->toArray();
            
            $validator = \Validator::make(
                            $data, $validRules
            );
            if ($validator->fails()) {
                $messages = '<ul>'.implode(" ",$validator->messages()->all('<li>:message</li>')).'</ul>';
                $invalidRecords[] = array(
                    'id' => $data['student_id'],
                    'data' => $data,
                    'messages' => $messages
                );
                $logMessages .= $this->logComment($data['student_id'], $messages);
            } else {
                $validRecords[] = $data;
            }
        }
        
        return array('valid_records'=>$validRecords, 'invlaid_records'=>$invalidRecords, 'log_message' => $logMessages);
        
    }
    
    function checkUniqueNessOfCourseStudent($courseId, $studentIs){
        
        $res = \DB::table('course_student')
               ->where('course_id', "=" ,$courseId)
               ->where('student_id', "=" ,$studentIs)
               ->count();
       
        if($res > 0){
            return false;
        }else{
            return true;    
        }       
    }
    

}
