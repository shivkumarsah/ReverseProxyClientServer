<?php

use Openroster\Storage\School\SchoolRepository as School;
use Openroster\Storage\Student\StudentRepository as Student;
use Openroster\Storage\Teacher\TeacherRepository as Teacher;
use Openroster\Storage\Course\CourseRepository as Course;
use Openroster\Storage\Csvfiles\CsvfilesRepository as Csvfiles;

/**
 * ImportController Class
 *
 * Implements actions regarding Import management
 */
class ImportController extends BaseController
{

    protected $importPath = null;

    public function __construct(School $school, Student $student, Teacher $teacher, Course $course, Csvfiles $Csvfiles)
    {
        $this->school = $school;
        $this->student = $student;
        $this->course = $course;
        $this->teacher = $teacher;
        $this->Csvfiles = $Csvfiles;
        $this->importPath = Config::get('appvals.file_import_path');
    }

    /**
     * Displays the form for account creation
     *
     * @return  Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('importdata.import');
    }
    
    
    /**
     * Displays the file Import Log
     *
     * @return  Illuminate\Http\Response
     */
    public function getFileLog()
    {
        $fileId = Route::input('file_id');
        $fileData = $this->Csvfiles->find($fileId);
        return View::make('importdata.showlog', array('fileData' => $fileData));
    }

    /**
     * Returns list of Csv Files
     *
     * @return  Illuminate\Http\Response
     */
    public function csvFiles()
    {
        return $this->Csvfiles->getCsvFilesList();
    }

    /**
     * Return Json response for file import status
     *
     * @return  Illuminate\Http\Response
     */
    public function importCsv()
    {
        $return = $this->Csvfiles->processAndUpload(Input::file('file'), Input::all());
        return Response::json($return);
    }

    /**
     * Return Json response for file import status
     *
     * @return  Illuminate\Http\Response
     */
    public function fileImportStatus()
    {
        return View::make('importdata.import');
    }

    /**
     * Import School
     *
     * @return  Illuminate\Http\Response
     */
    public function doSchoolsImport()
    {   
        
        ini_set('max_execution_time', 0);
        $startImport = $this->school->startImport('school');

        if (empty($startImport['status']))
            return $startImport;

        Excel::filter('chunk')->load($this->importPath . 'Schools.csv')->chunk(Config::get('appvals.upload_chunk_size', '100'), function($results) {
                    $impStatus = $this->school->importSchools($results);
                });
        $finishImport = $this->school->finishImport('school');
        // mail csv log status
       
        $fileData = $this->Csvfiles->find(1);
        Mail::send('importdata.mail', array('fileData' => $fileData) , function($message) {
            $userDetail = Confide::user();
            $message->to($userDetail->email, $userDetail->username)->subject('Import Log');
        });
        return $finishImport;
    }

    /**
     * Import Student
     *
     * @return  Illuminate\Http\Response
     */
    public function doStudentsImport()
    {   ini_set('max_execution_time', 0);
        
        $startImport = $this->student->startImport('student');

        if (empty($startImport['status']))
            return $startImport;

        Excel::filter('chunk')->load($this->importPath . 'Students.csv')->chunk(Config::get('appvals.upload_chunk_size', '100'), function($results) {

                    $impStatus = $this->student->importStudents($results);
                });
        $finishImport = $this->student->finishImport('student');
        // mail csv log status
       
        $fileData = $this->Csvfiles->find(2);
        Mail::send('importdata.mail', array('fileData' => $fileData) , function($message) {
            $userDetail = Confide::user();
            $message->to($userDetail->email, $userDetail->username)->subject('Import Log');
        });
        return $finishImport;
    }

    /**
     * Import Teachers
     *
     * @return  Illuminate\Http\Response
     */
    public function doTeachersImport()
    {   ini_set('max_execution_time', 0);
        $startImport = $this->teacher->startImport('teacher');

        if (empty($startImport['status']))
            return $startImport;

        Excel::filter('chunk')->load($this->importPath . 'Teachers.csv')->chunk(Config::get('appvals.upload_chunk_size', '100'), function($results) {

                    $impStatus = $this->teacher->importTeachers($results);
                });
        $finishImport = $this->teacher->finishImport('teacher');
        // mail csv log status
       
        $fileData = $this->Csvfiles->find(3);
        Mail::send('importdata.mail', array('fileData' => $fileData) , function($message) {
            $userDetail = Confide::user();
            $message->to($userDetail->email, $userDetail->username)->subject('Import Log');
        });
        return $finishImport;
    }

    /**
     * Import Courses
     *
     * @return  Illuminate\Http\Response
     */
    public function doCoursesImport()

    {   
        ini_set('max_execution_time', 0);

        //return array('status' => 1, 'msg' => '', 'data' => $this->course->getFileData('course'), 'file_id' => 5);
        $startImport = $this->course->startImport('course');
        if (empty($startImport['status'])){
            return $startImport;
        }
        Excel::filter('chunk')->load($this->importPath . 'Courses.csv')->chunk(Config::get('appvals.upload_chunk_size', '100'), function($results) {
                    $impStatus = $this->course->importCourses($results);
                });
        $finishImport = $this->course->finishImport('course');
        // mail csv log status
       
        $fileData = $this->Csvfiles->find(5);
        Mail::send('importdata.mail', array('fileData' => $fileData) , function($message) {
            $userDetail = Confide::user();
            $message->to($userDetail->email, $userDetail->username)->subject('Import Log');
        });
        return $finishImport;
    }

    /**
     * Import Enrollments
     *
     * @return  Illuminate\Http\Response
     */
    public function doEnrollmentsImport()
    {   ini_set('max_execution_time', 0);
         
        //return array('status' => 1, 'msg' => '', 'data' => $this->course->getFileData('enrollment'), 'file_id' => 6);
        $startImport = $this->student->startImport('enrollments');
      
        if (empty($startImport['status'])){
            return $startImport;
        }
        Excel::filter('chunk')->load($this->importPath . 'Enrollments.csv')->chunk(Config::get('appvals.upload_chunk_size', '100'), function($results) {
                $this->student->importEnrollment($results);
        });
        $finishImport = $this->student->finishImport('enrollments');
        // mail csv log status
       
        $fileData = $this->Csvfiles->find(6);
        Mail::send('importdata.mail', array('fileData' => $fileData) , function($message) {
            $userDetail = Confide::user();
            $message->to($userDetail->email, $userDetail->username)->subject('Import Log');
        });
        return $finishImport;
    }

}
