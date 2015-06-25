<?php

namespace Openroster\Storage\Course;

use Openroster\Storage\AbstractEloquentRepository;

class EloquentCourseRepository extends AbstractEloquentRepository implements CourseRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\Course $model)
    {
        $this->model = $model;
    }

    public function getCourseList($inputData = array())
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

        /*if (!empty($inputData['course_name'])) {
            $params['where']['course_name'] = array('op' => 'like', 'value' => $inputData['course_name']);
        }

        if (!empty($inputData['school_id'])) {
            $params['where']['school_id'] = $inputData['school_id'];
        }*/
        
        if (isset($inputData['course_name']) && $inputData['course_name'] !="") {
            $params['condition'][] = array('courses.course_name', 'like', '%'.$inputData['course_name'].'%');
        }
        if (isset($inputData['subjects']) && $inputData['subjects'] !="" ) {
            $params['condition'][] = array('courses.subjects', 'like', '%'.$inputData['subjects'].'%');
        }
        if (isset($inputData['school_name']) && $inputData['school_name'] !="") {
            $params['condition'][] = array('schools.school_name', 'like', '%'.$inputData['school_name'].'%');
        }
        if (isset($inputData['first_name']) && $inputData['first_name']!="") {
            $params['condition'][] = array('teachers.first_name', 'like', '%'.$inputData['first_name'].'%');
        }
        if (isset($inputData['last_name']) && $inputData['last_name']!="") {
            $params['condition'][] = array('teachers.last_name', 'like', '%'.$inputData['last_name'].'%');
        }
        if (!empty($inputData['start_date']) && empty($inputData['end_date'])) {
            $params['condition'][] = array('courses.start_date', '=', $inputData['start_date']);
        }
        if (empty($inputData['start_date']) && !empty($inputData['end_date'])) {
            $params['condition'][] = array('courses.end_date', '=', $inputData['end_date']);
        }
        if (!empty($inputData['start_date']) && !empty($inputData['end_date'])) {
            $params['condition'][] = array('courses.start_date', '=', $inputData['start_date']);
            $params['condition'][] = array('courses.end_date', '=', $inputData['end_date']);
        }
        
        
        $params['table'] = 'courses';
        
        // param  selected fields
        $params['fields'] = array('courses.course_id','courses.course_name','courses.subjects','courses.start_date','courses.end_date','teachers.first_name','teachers.last_name','schools.school_name');
        // join array
        $params['join'][] = array('schools','schools.school_id','=','courses.school_id','join');
        $params['join'][] = array('teachers', 'teachers.teacher_id', '=', 'courses.teacher_id','join');

        return $this->getByPage($params);
    }

    public function importCourses($result)
    {   
        
        $importFiles = \Config::get("appvals.import_file_names");
        $validRecords = $invalidRecords = array();
        if (empty($result))
            return false;

        try {
            $records = $this->validateCourseData($result);
            

            
            $courseTeacherArray = array();
            $courseSchoolArray = array();
            $insterArray = array();
            if(count($records['valid_records'])>0){
                foreach($records['valid_records'] as $key=>$val){
                    $insterArray[$val['course_id']] = $val;
                }
                foreach($insterArray as $ke=>$va){
                    $couTea = array("course_id"=> $va['course_id'],"teacher_id"=>$va['teacher_id']);
                    $couSch = array("course_id"=> $va['course_id'],"school_id"=>$va['school_id']);
                    $courseTeacherArray[] = $couTea;
                    $courseSchoolArray[]  = $couSch;
                }
               
                $this->bulkInsert($insterArray);
                $this->bulkInsert($courseTeacherArray , "course_teacher");
                $this->bulkInsert($courseSchoolArray , "course_school");
            
            }
            
            
            $logComment['file_import_comment'] = $records['log_message'];
            $logComment['import_successful_records'] = count($records['valid_records']);
            $logComment['import_unsuccessful_records'] = count($records['invlaid_records']);

            $this->updateLogComment($logComment, 'course');
        } catch (\Exception $exception) {
            \Log::error($exception);
            $fileData = $this->getFileStatusData('course');
            $fileData['data']['file_available'] = $logFComment['file_available'] = 1;
            $logFComment['file_import_comment'] = $exception->getMessage();
            $this->updateLogComment($logComment, 'course');
            $fileData['msg'] = trans('messages.importdata.file_import_restart', array('name' => $importFiles[$startImport['file_id']]));

            return $fileData;
        }

        return true;
    }

    public function validateCourseData($result)
    {
        $date = new \DateTime;
        $validRecords = array();
        $invalidRecords = array();
        $logMessages = '';



        foreach ($result as $row) {
            $data = $row->toArray();

            $validRules = array(
                'course_id' => "required|Integer|unique:courses,course_id,NULL,id,school_id,$data[school_id]",
                'school_id' => 'required|exists:schools',
                'teacher_id' => "required|exists:teachers,teacher_id,school_id,$data[school_id]",
                'course_name' => 'required|alpha_numeric_spaces',
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d',
                'subjects' => 'required|alpha_numeric_spaces_comma'
            );

            $validator = \Validator::make(
                            $data, $validRules
            );
            if ($validator->fails()) {
                $messages = '<ul>' . implode(" ", $validator->messages()->all('<li>:message</li>')) . '</ul>';
                $invalidRecords[] = array(
                    'id' => $data['course_id'],
                    'data' => $data,
                    'messages' => $messages
                );
                $logMessages .= $this->logComment($data['course_id'], $messages);
            } else {
                $data['updated_at'] = $data['created_at'] = $date;
                $validRecords[] = $data;
            }
        }

        return array('valid_records' => $validRecords, 'invlaid_records' => $invalidRecords, 'log_message' => $logMessages);
    }

}
