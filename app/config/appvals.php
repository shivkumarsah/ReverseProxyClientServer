<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application Values
    |--------------------------------------------------------------------------
    |
    | When you create new packages via the Artisan "workbench" command your
    | name is needed to generate the composer.json file for your package.
    | You may specify it now so it is used for all of your workbenches.
    | 'subjects','subject_teacher','course_subject',
    */

    'classic_url' => 'http://www.classlink.com/',
    'file_import_dir_name' => 'imports',
    'file_import_path' => 'app/storage/imports/',
    'upload_chunk_size' => 50,
    'import_details' => array(
        'import_master' => array('table' => 'csvfiles', 'id' => 'file_id'),
        'school' => array('table' => 'schools', 'file_id' => 1, 'id' => 'school_id'),
        'teacher' => array('table' => 'teachers', 'file_id' => 3, 'id' => 'teacher_id'),
        'student' => array('table' => 'students', 'file_id' => 2, 'id' => 'student_id'),
        'subject' => array('table' => 'subjects', 'file_id' => 4, 'id' => 'subject_id'),
        'course' => array('table' => 'courses', 'file_id' => 5, 'id' => 'course_id', 'assoc_trunc_tables' => array('course_school', 'course_teacher')),
        'enrollments' => array('table' => 'course_student', 'file_id' => 6, 'id' => 'student_id'),
    ),
    'import_file_names' => array(
        1 => 'schools.csv',
        2 => 'students.csv',
        3 => 'teachers.csv',
        4 => 'subjects.csv',
        5 => 'courses.csv',
        6 => 'enrollments.csv'
    ),
    'import_file_formats' => array(
        1 => 'text/comma-separated-values',
        2 => 'application/octet-stream'
    ),


);
