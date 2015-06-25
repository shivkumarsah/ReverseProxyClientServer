<?php

namespace Openroster\Storage\Enrollment;

use Openroster\Storage\AbstractEloquentRepository;

class EloquentEnrollmentRepository extends AbstractEloquentRepository implements EnrollmentRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\Enrollment $model)
    {   
        $this->model = $model;
    }

    public function getEnrollmentList($inputData = array())
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
        //$params['condition']['courses.course_name'] = array('op' => 'like', 'value' => $inputData['course_name']); 
        if (isset($inputData['course_name']) && $inputData['course_name'] !="") {
            $params['condition'][] = array('courses.course_name', 'like', '%'.$inputData['course_name'].'%');
        }
        if (isset($inputData['first_name']) &&  $inputData['first_name'] != "") {
            $params['condition'][] = array('students.first_name', 'like', '%'.$inputData['first_name'].'%');
        }
        if (isset($inputData['last_name']) && $inputData['first_name']!="") {
            $params['condition'][] = array('students.last_name', 'like', '%'.$inputData['last_name'].'%');
        }
        
        $params['table'] = 'course_student';
        
        
        // param  selected fields
        $params['fields'] = array('course_student.course_id','course_student.student_id','courses.course_name','students.first_name','students.last_name');
        
        // join array
        $params['join'][] = array('courses','courses.course_id','=','course_student.course_id','join');
        $params['join'][] = array('students', 'students.student_id', '=', 'course_student.student_id','join');
        
        return $this->getByPage($params);
    }
}
