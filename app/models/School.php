<?php

class School extends Eloquent {

	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'schools';
	protected $primaryKey = 'school_id';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();
    
     public function getSchools($schoolId = null,$param = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->get();
                $resultArray = array();
                $resultArray['status'] = true;
                $resultArray['responseMessage'] = "Request process successfully";
                $resultArray['requestApi'] = "schools";
                $resultArray['requestApiData'] = $res;
                $this->apiLog($developerId);
                return $resultArray;
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        }
        
        public function schoolsCourses($schoolId,$param = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                   $coureses= DB::table('course_school')
                    ->join('courses', 'courses.course_id', '=', 'course_school.course_id')
                    ->where('course_school.school_id',  '=', $schoolId)
                    ->select(array('courses.course_id as courseId', 'courses.course_name as courseName', 'courses.subjects as subjects','courses.start_date as startDate', 'courses.end_date as endDate'))
                    ->get();
                    $result['schoolName'] =  $res[0];
                    $result['courseList'] =  $coureses;
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "course";
                    $resultArray['requestApiData'] = $result;
                    $this->apiLog($developerId);
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        
        }
        
        public function schoolsTeachers($schoolId,$param = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                   $coureses= DB::table('schools')
                    ->join('teachers', 'teachers.school_id', '=', 'schools.school_id')
                    ->where('schools.school_id',  '=', $schoolId)
                    ->select(array('teachers.first_name as firstName', 'teachers.last_name as lastName', 'teachers.email as email'))
                    ->get();
                    $result['schoolName'] =  $res[0];
                    $result['teachersList'] =  $coureses;
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "teachers";
                    $resultArray['requestApiData'] = $result;
                    $this->apiLog($developerId);
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        
        }
        
        public function schoolsStudents($schoolId,$param = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                   $coureses= DB::table('schools')
                    ->join('students', 'students.school_id', '=', 'schools.school_id')
                    ->where('schools.school_id',  '=', $schoolId)
                    ->select(array('students.first_name as firstName', 'students.last_name as lastName', 'students.email as email'))
                    ->get();
                    $result['schoolName'] =  $res[0];
                    $result['studentsList'] =  $coureses;
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "students";
                    $resultArray['requestApiData'] = $result;
                    $this->apiLog($developerId);
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        }
        
        public function coursesDetail($schoolId = null,$courseId = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                   $coureses= DB::table('courses')->select(array('courses.course_id as courseId', 'courses.course_name as courseName', 'courses.subjects as subjects','courses.start_date as startDate', 'courses.end_date as endDate'))->join('course_school', 'course_school.course_id', '=', 'courses.course_id')->where('course_school.course_id',"=",$courseId)->where('course_school.school_id',"=",$schoolId)->get();
                    if(!$coureses){
                        $resultArray = array();
                        $resultArray['status'] = false;
                        $resultArray['responseCode'] = 404;
                        $resultArray['responseMessage'] = "course does not exist";
                        return $resultArray;
                    }
                    $result['schoolName'] =  $res[0];
                    $result['course'] =  $coureses[0];
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "couseDetail";
                    $resultArray['requestApiData'] = $result;
                    $this->apiLog($developerId);
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        
        }
        
        public function courseStudent($schoolId = null,$courseId = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                    $coures= DB::table('courses')->select(array('courses.course_id as courseId', 'courses.course_name as courseName', 'courses.subjects as subjects','courses.start_date as startDate', 'courses.end_date as endDate')) ->join('course_school', 'course_school.course_id', '=', 'courses.course_id')->where('courses.course_id',"=",$courseId)->where('course_school.school_id',"=",$schoolId)->get();
                    if(!$coures){
                        $resultArray = array();
                        $resultArray['status'] = false;
                        $resultArray['responseCode'] = 412;
                        $resultArray['responseMessage'] = "Course is not associated with this school";
                        return $resultArray;
                    }
                    $students= DB::table('course_student')
                    ->join('students', 'students.student_id', '=', 'course_student.student_id')
                    ->where('course_student.course_id',  '=', $courseId)
                    ->select(array('students.first_name as firstName', 'students.last_name as lastName', 'students.email as email'))
                    ->get();
                    $result['schoolName'] =  $res[0];
                    $result['courseName'] =  $coures[0];
                    $result['studentsList'] =  $students;
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "courseStudents";
                    $resultArray['requestApiData'] = $result;
                    $this->apiLog($developerId);
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        }
        
        public function studentCourse($schoolId = null,$studentId = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                    $student= DB::table('students')->select(array('students.first_name as firstName', 'students.last_name as lastName', 'students.email as email'))->join('schools', 'schools.school_id', '=', 'students.school_id')->where('student_id',"=",$studentId)->where('students.school_id',"=",$schoolId)->get();
                    if(!$student){
                        $resultArray = array();
                        $resultArray['status'] = false;
                        $resultArray['responseCode'] = 412;
                        $resultArray['responseMessage'] = "Student is not associated with this school";
                        return $resultArray;
                    }
                    
                    $course= DB::table('course_student')
                    ->join('courses', 'courses.course_id', '=', 'course_student.course_id')
                    ->join('course_school', 'courses.course_id', '=', 'courses.course_id')
                    ->where('course_student.student_id',  '=', $studentId)
                    ->where('course_school.school_id',  '=', $schoolId)
                    ->select(array('courses.course_id as courseId', 'courses.course_name as courseName', 'courses.subjects as subjects','courses.start_date as startDate', 'courses.end_date as endDate'))
                    ->groupBy('courses.course_id')
                    ->get();
                    if(!$course){
                        $resultArray = array();
                        $resultArray['status'] = false;
                        $resultArray['responseCode'] = 412;
                        $resultArray['responseMessage'] = "No course is associated with this student";
                        return $resultArray;
                    }
                    $result['schoolName'] =  $res[0];
                    $result['studentName'] = $student[0];
                    $result['courseList'] =  $course;
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "studentsCourse";
                    $resultArray['requestApiData'] = $result;
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        
        }
        
        public function teacherCourse($schoolId = null,$teacherId = null,$developerId){
            Config::set('database.fetch', PDO::FETCH_ASSOC);
            try{
                $res = DB::table('schools')->select(array('school_id as Id','school_name as name'))->where('school_id',"=",$schoolId)->get();
                if(!empty($res)){
                    $teacher= DB::table('teachers')->select(array('teachers.first_name as firstName', 'teachers.last_name as lastName', 'teachers.email as email'))->join('schools', 'schools.school_id', '=', 'teachers.school_id')->where('teacher_id',"=",$teacherId)->get();
                    if(!$teacher){
                        $resultArray = array();
                        $resultArray['status'] = false;
                        $resultArray['responseCode'] = 412;
                        $resultArray['responseMessage'] = "Teacher is not associated with this school";
                        return $resultArray;
                    }
                    $course= DB::table('course_teacher')
                    ->join('courses', 'courses.course_id', '=', 'course_teacher.course_id')
                    ->where('course_teacher.teacher_id',  '=', $teacherId)
                    ->select(array('courses.course_id as courseId', 'courses.course_name as courseName', 'courses.subjects as subjects','courses.start_date as startDate', 'courses.end_date as endDate'))
                    ->get();
                    $result['schoolName'] =  $res[0];
                    $result['teacherName'] = $teacher[0];
                    $result['courseList'] =  $course;
                    $resultArray = array();
                    $resultArray['status'] = true;
                    $resultArray['responseMessage'] = "Request process successfully";
                    $resultArray['requestApi'] = "teacherCourses";
                    $resultArray['requestApiData'] = $result;
                    $this->apiLog($developerId);
                    return $resultArray;
                }else{
                    $resultArray = array();
                    $resultArray['status'] = false;
                    $resultArray['responseCode'] = 404;
                    $resultArray['responseMessage'] = "School does not exist";
                    return $resultArray;
                }
            }catch(execption $e){
                $resultArray = array();
                $resultArray['status'] = false;
                $resultArray['responseCode'] = 403;
                $resultArray['responseMessage'] = $e->getMessage();
                return $resultArray;
            }
        }
        public function apiLog($id){
            $developerArray = DB::table('developers')->where('developer_id','=',$id)->get();
            $data = array();
            $data['developer_id'] = $developerArray[0]->developer_id;
            $data['called_api_key'] = $developerArray[0]->api_key;
            DB::table('developers_api_call_logs')->insert($data);
        }
        
}
