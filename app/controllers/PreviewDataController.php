<?php

use Openroster\Storage\School\SchoolRepository as School;
use Openroster\Storage\Student\StudentRepository as Student;
use Openroster\Storage\Teacher\TeacherRepository as Teacher;
use Openroster\Storage\Course\CourseRepository as Course;
use Openroster\Storage\Enrollment\EnrollmentRepository as Enrollment;
/**
 * PreviewDataController Class
 *
 * Implements actions regarding data preview
 */
class PreviewDataController extends BaseController
{

    public function __construct(School $school, Student $student, Teacher $teacher, Course $course,Enrollment $enrollment)
    {
        $this->school = $school;
        $this->student = $student;
        $this->course = $course;
        $this->teacher = $teacher;
        $this->enrollment = $enrollment;
    }

    /**
     * Displays the preview data of schools
     *
     * @return  Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('previewdata.schools');
    }

    /**
     * Returns data of schools
     *
     * @return  Illuminate\Http\Response
     */
    public function schoolsData()
    {
        return (array) $this->school->getSchoolList(Input::all());
    }

    /**
     * Displays the preview data of teachers
     *
     * @return  Illuminate\Http\Response
     */
    public function teachers()
    {
        return View::make('previewdata.teachers');
    }
    
    /**
     * Returns data of schools
     *
     * @return  Illuminate\Http\Response
     */
    public function teachersData()
    {
        return (array) $this->teacher->getTeacherList(Input::all());
    }

    /**
     * Displays the preview data of students
     *
     * @return  Illuminate\Http\Response
     */
    public function students()
    {
        return View::make('previewdata.students');
    }
    
    /**
     * Returns data of schools
     *
     * @return  Illuminate\Http\Response
     */
    public function studentsData()
    {
        
        return (array) $this->student->getStudentList(Input::all());
    }

    /**
     * Displays the preview data of subjects
     *
     * @return  Illuminate\Http\Response
     */
    public function subjects()
    {
        return View::make('previewdata.subjects');
    }

    /**
     * Displays the preview data of courses
     *
     * @return  Illuminate\Http\Response
     */
    public function courses()
    {
        return View::make('previewdata.courses');
    }
    
    /**
     * Returns data of schools
     *
     * @return  Illuminate\Http\Response
     */
    public function coursesData()
    {   
        return (array) $this->course->getCourseList(Input::all());
    }

    /**
     * Displays the preview data of enrollments
     *
     * @return  Illuminate\Http\Response
     */
    public function enrollments()
    {
        return View::make('previewdata.enrollments');
    }
    
    public function enrollmentsData()
    {   
        return (array) $this->enrollment->getEnrollmentList(Input::all());
    }

}
