<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('SchoolTableSeeder');
        $this->command->info('School table seeded!');

        $this->call('StudentTableSeeder');
        $this->command->info('Student table seeded!');

        $this->call('TeaherTableSeeder');
        $this->command->info('Teaher table seeded!');

        $this->call('SubjectTableSeeder');
        $this->command->info('Subject table seeded!');

        $this->call('CourseTableSeeder');
        $this->command->info('Course table seeded!');

        $this->call('CourseSubjectTableSeeder');
        $this->command->info('courses_subjects table seeded!');

        $this->call('SchoolCourseTableSeeder');
        $this->command->info('schools_courses table seeded!');


        $this->call('TeacherSubjectTableSeeder');
        $this->command->info('teachers_subjects table seeded!');

        $this->call('StudentCourseTableSeeder');
        $this->command->info('students_courses  table seeded!');
        
        $this->call('DeveloperTableSeeder');
        $this->command->info('developers  table seeded!');
    }

}

class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert(array(
            'email' => 'prashant.kumar@icreon.com',
            'username' => 'openrosteradmin',
            'password' => Hash::make('oroster'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => true
        ));
    }

}

class SchoolTableSeeder extends Seeder
{

    public function run()
    {
        $date = new \DateTime;
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('schools')->truncate();

        DB::table('schools')->insert(array(
            array('school_name' => 'Test School 1', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 2', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 3', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 4', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 5', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 6', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 7', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 8', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 9', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 10', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 11', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 12', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 13', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 14', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 15', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 16', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 17', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 18', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 19', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 20', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 21', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 22', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 23', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 24', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 25', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 26', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 27', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 28', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 29', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 30', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 31', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 32', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 33', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 34', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 35', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 36', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 37', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 38', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 39', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 40', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 41', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 42', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 43', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 44', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 45', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 46', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 47', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 48', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 49', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 50', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 51', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 52', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 53', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 54', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 55', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 56', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 57', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 58', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 59', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 60', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 61', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 62', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 63', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 64', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 65', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 66', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 67', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 68', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 69', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 70', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 71', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 72', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 73', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 74', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 75', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 76', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 77', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 78', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 79', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 80', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 81', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 82', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 83', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 84', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 86', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 87', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 88', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 89', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 90', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 91', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 92', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 93', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 94', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 95', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 96', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 97', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 98', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 99', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 100', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 101', 'created_at' => $date, 'updated_at' => $date),
            array('school_name' => 'Test School 102', 'created_at' => $date, 'updated_at' => $date),
        ));

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class StudentTableSeeder extends Seeder
{

    public function run()
    {
        $date = new \DateTime;
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('students')->truncate();
        $schCount = DB::table('schools')->count();

        $stuDataArr = array();
        for ($schId = 1, $j = $schCount; $schId <= $schCount; $schId++, $j--) {

            if ($j < ($schCount / 2))
                $j = $schCount;

            for ($k = 1, $grd = -1; $k <= $j; $k++, $grd++) {

                if ($grd > 12)
                    $grd = -1;

                $stuDataArr[] = array('school_id' => $schId, 'first_name' => "School $schId Student $k", 'last_name' => "School $schId Student last name $k", 'email' => "school{$schId}student{$k}@yopmail.com", 'adusername' => "school{$schId}student{$k}", 'grade' => "$grd", 'created_at' => $date, 'updated_at' => $date);
            }
        }

        DB::table('students')->insert($stuDataArr);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class TeaherTableSeeder extends Seeder
{

    public function run()
    {
        $date = new \DateTime;
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('teachers')->truncate();
        $schCount = DB::table('schools')->count();

        $teachersDataArr = array();
        for ($schId = 1, $j = 20; $schId <= $schCount; $schId++, $j--) {

            if ($j < 10)
                $j = 20;

            for ($k = 1; $k <= $j; $k++) {


                $teachersDataArr[] = array('school_id' => $schId, 'first_name' => "School $schId Teacher $k", 'last_name' => "School $schId Teacher last name $k", 'email' => "school{$schId}teacher{$k}@yopmail.com", 'adusername' => "school{$schId}teacher{$k}", 'created_at' => $date, 'updated_at' => $date);
            }
        }

        DB::table('teachers')->insert($teachersDataArr);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class SubjectTableSeeder extends Seeder
{

    public function run()
    {
        $date = new \DateTime;
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('subjects')->truncate();

        DB::table('subjects')->insert(array(
            array('name' => 'Accounting', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Administration', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Ancient History', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Anthropology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Applied Business', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Arabic', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Archaeology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Architectural Technolog', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Art and Design', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Bengali', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Biblical Hebrew', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Biology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Business Management', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Cantonese', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Care', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Chemistry', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Childcare and Development', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Chinese', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Citizenship Studies ', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Classical Civilisation', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Classical Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Communication Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Computer Science', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Construction', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Critical Thinking', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Dance', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Design and Manufacture', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Drama and Theatre Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Dutch', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Early Education and Childcare', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Economics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Economics & Business ', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Electrical Engineering', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Electronics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Engineering', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Engineering Science', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'English', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'English Language', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'English Literature', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'English for Speakers of Other Languages (ESOL)', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Environmental Science', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Environmental Technology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Fabrication and Welding Engineering', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Fashion and Textile Technology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Film Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'French', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Gaelic', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'General Studies Geograph Geolog German', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Politics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Graphic(al) Communication', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Classical Greek', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Gujarati', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Health and Food Technology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Health and Social Care', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Hebrew (Modern)', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Histor History of Art', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Home Economics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Home Economics: Lifestyle and Consumer Technolog', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Humanities', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Information Communication Technology (ICT)', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Information Systems', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Irish', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Italian', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Japanese', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Latin', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Law', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Managing Environmental Resources', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Mandarin (Simplified)', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Mathematics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Mechatronics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Media Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Modern Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Music', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Music Technology', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Panjabi', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Performance Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Persian', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Philosoph Physical Education', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Physics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Polish', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Politics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Portuguese', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Product Design', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Psycholog Religious Studies', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Russian', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Science', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Science in Society', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Sociolog Software Systems and Development', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Spanish', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Statistics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Turkish', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Urdu', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Use of Mathematics', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'Welsh (First Language)', 'created_at' => $date, 'updated_at' => $date),
            array('name' => 'World Development', 'created_at' => $date, 'updated_at' => $date),
        ));

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class CourseTableSeeder extends Seeder
{

    public function run()
    {
        $date = new \DateTime;
        $end1Date = $nextyear = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1));
        $end2Date = $nextyear = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 2));
        $end3Date = $nextyear = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 3));
        $end4Date = $nextyear = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 4));
        $end5Date = $nextyear = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 5));

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('courses')->truncate();

        $schCount = DB::table('schools')->count();

        for ($schId = 1, $courseId = 1; $schId <= $schCount; $schId++) {


            DB::table('courses')->insert(array(
                array('course_name' => 'Accounting', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'MBA', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'MCA', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'B.Ed', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end1Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Animation', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end1Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Advertising', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end1Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Arts', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Commerce', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Fashion Design', 'school_id' => $schId, 'course_id' => ++$courseId, 'school_id' => $schId, 'start_date' => $date, 'end_date' => $end4Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Hotel Management', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Journalism', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Law', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Acting/Drama', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Medical', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end5Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Science', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Pharmacy', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'SoftwareCourse', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'HardwareCourse', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end1Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Aviation', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end4Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'BBA', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'BCA', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end3Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'PGDM', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'M. Tech', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
                array('course_name' => 'Distance MBA', 'school_id' => $schId, 'course_id' => ++$courseId, 'start_date' => $date, 'end_date' => $end2Date, 'created_at' => $date, 'updated_at' => $date),
            ));
        }


        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class CourseSubjectTableSeeder extends Seeder
{

    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('course_subject')->truncate();

        DB::table('course_subject')->insert(array(
            array('course_id' => 1, 'subject_id' => 1),
            array('course_id' => 2, 'subject_id' => 1),
            array('course_id' => 2, 'subject_id' => 2),
            array('course_id' => 2, 'subject_id' => 5),
            array('course_id' => 3, 'subject_id' => 1),
            array('course_id' => 3, 'subject_id' => 23),
            array('course_id' => 4, 'subject_id' => 53),
            array('course_id' => 4, 'subject_id' => 54),
            array('course_id' => 4, 'subject_id' => 59),
            array('course_id' => 4, 'subject_id' => 78),
            array('course_id' => 4, 'subject_id' => 17),
            array('course_id' => 5, 'subject_id' => 25),
            array('course_id' => 5, 'subject_id' => 45),
            array('course_id' => 5, 'subject_id' => 23),
            array('course_id' => 6, 'subject_id' => 70),
            array('course_id' => 6, 'subject_id' => 71),
            array('course_id' => 6, 'subject_id' => 72),
            array('course_id' => 7, 'subject_id' => 9),
            array('course_id' => 7, 'subject_id' => 49),
            array('course_id' => 7, 'subject_id' => 56),
            array('course_id' => 7, 'subject_id' => 95),
            array('course_id' => 7, 'subject_id' => 3),
            array('course_id' => 8, 'subject_id' => 1),
            array('course_id' => 8, 'subject_id' => 31),
            array('course_id' => 8, 'subject_id' => 32),
            array('course_id' => 8, 'subject_id' => 69),
            array('course_id' => 8, 'subject_id' => 90),
            array('course_id' => 8, 'subject_id' => 93),
            array('course_id' => 9, 'subject_id' => 43),
            array('course_id' => 9, 'subject_id' => 44),
            array('course_id' => 9, 'subject_id' => 95),
            array('course_id' => 9, 'subject_id' => 25),
            array('course_id' => 9, 'subject_id' => 27),
            array('course_id' => 10, 'subject_id' => 1),
            array('course_id' => 10, 'subject_id' => 2),
            array('course_id' => 10, 'subject_id' => 13),
            array('course_id' => 10, 'subject_id' => 53),
            array('course_id' => 10, 'subject_id' => 54),
            array('course_id' => 11, 'subject_id' => 67),
            array('course_id' => 11, 'subject_id' => 71),
            array('course_id' => 11, 'subject_id' => 72),
            array('course_id' => 11, 'subject_id' => 95),
            array('course_id' => 12, 'subject_id' => 25),
            array('course_id' => 12, 'subject_id' => 49),
            array('course_id' => 12, 'subject_id' => 61),
            array('course_id' => 12, 'subject_id' => 66),
            array('course_id' => 13, 'subject_id' => 25),
            array('course_id' => 13, 'subject_id' => 26),
            array('course_id' => 13, 'subject_id' => 27),
            array('course_id' => 13, 'subject_id' => 28),
            array('course_id' => 14, 'subject_id' => 4),
            array('course_id' => 14, 'subject_id' => 12),
            array('course_id' => 14, 'subject_id' => 16),
            array('course_id' => 14, 'subject_id' => 17),
            array('course_id' => 14, 'subject_id' => 25),
            array('course_id' => 14, 'subject_id' => 41),
            array('course_id' => 14, 'subject_id' => 53),
            array('course_id' => 14, 'subject_id' => 54),
            array('course_id' => 14, 'subject_id' => 69),
            array('course_id' => 14, 'subject_id' => 79),
            array('course_id' => 14, 'subject_id' => 86),
            array('course_id' => 15, 'subject_id' => 4),
            array('course_id' => 15, 'subject_id' => 12),
            array('course_id' => 15, 'subject_id' => 16),
            array('course_id' => 15, 'subject_id' => 17),
            array('course_id' => 15, 'subject_id' => 25),
            array('course_id' => 15, 'subject_id' => 41),
            array('course_id' => 15, 'subject_id' => 53),
            array('course_id' => 15, 'subject_id' => 54),
            array('course_id' => 15, 'subject_id' => 69),
            array('course_id' => 16, 'subject_id' => 4),
            array('course_id' => 16, 'subject_id' => 12),
            array('course_id' => 16, 'subject_id' => 16),
            array('course_id' => 16, 'subject_id' => 17),
            array('course_id' => 16, 'subject_id' => 41),
            array('course_id' => 16, 'subject_id' => 53),
            array('course_id' => 16, 'subject_id' => 54),
            array('course_id' => 16, 'subject_id' => 69),
            array('course_id' => 17, 'subject_id' => 23),
            array('course_id' => 17, 'subject_id' => 60),
            array('course_id' => 17, 'subject_id' => 61),
            array('course_id' => 17, 'subject_id' => 83),
            array('course_id' => 18, 'subject_id' => 23),
            array('course_id' => 18, 'subject_id' => 34),
            array('course_id' => 18, 'subject_id' => 35),
            array('course_id' => 19, 'subject_id' => 7),
            array('course_id' => 19, 'subject_id' => 8),
            array('course_id' => 20, 'subject_id' => 1),
            array('course_id' => 20, 'subject_id' => 2),
            array('course_id' => 20, 'subject_id' => 69),
            array('course_id' => 21, 'subject_id' => 22),
            array('course_id' => 21, 'subject_id' => 23),
            array('course_id' => 21, 'subject_id' => 61),
            array('course_id' => 21, 'subject_id' => 69),
            array('course_id' => 22, 'subject_id' => 1),
            array('course_id' => 22, 'subject_id' => 2),
            array('course_id' => 22, 'subject_id' => 13),
            array('course_id' => 22, 'subject_id' => 31),
            array('course_id' => 22, 'subject_id' => 69),
            array('course_id' => 23, 'subject_id' => 33),
            array('course_id' => 23, 'subject_id' => 34),
            array('course_id' => 23, 'subject_id' => 35),
            array('course_id' => 23, 'subject_id' => 36),
            array('course_id' => 24, 'subject_id' => 1),
            array('course_id' => 24, 'subject_id' => 2),
            array('course_id' => 24, 'subject_id' => 5),
            array('course_id' => 24, 'subject_id' => 13),
            array('course_id' => 24, 'subject_id' => 69),
        ));

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class SchoolCourseTableSeeder extends Seeder
{

    public function run()
    {
//        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
//        DB::table('course_school')->truncate();
//
//        $schoolsCoursesDataArr = array();
//        $schCount = DB::table('schools')->count();
//        $courseCount = DB::table('courses')->count();
//        for ($schId = 1; $schId <= $schCount; $schId++) {
//
//
//            for ($k = 1; $k <= $courseCount; $k++) {
//
//                $schoolsCoursesDataArr[] = array('school_id' => $schId, 'course_id' => $k);
//            }
//        }
//
//        //DB::table('course_school')->insert($schoolsCoursesDataArr);
//
//        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class StudentCourseTableSeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('course_student')->truncate();

        $studentCoursesDataArr = array();
        $stuCount = DB::table('students')->count();
        $courseCount = DB::table('courses')->count();

        for ($stuId = 1; $stuId <= $stuCount; $stuId++) {

            $studentCoursesDataArr[] = array('student_id' => $stuId, 'course_id' => rand(1, $courseCount));
        }

        DB::table('course_student')->insert($studentCoursesDataArr);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class TeacherSubjectTableSeeder extends Seeder
{

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('subject_teacher')->truncate();

        $teachersSubjectsDataArr = array();
        $teacherCount = DB::table('teachers')->count();
        $subjectCount = DB::table('subjects')->count();
        for ($teachId = 1, $subId = 1; $teachId <= $teacherCount; $teachId++, $subId++) {
            if ($subId > $subjectCount)
                $subId = 1;
            $teachersSubjectsDataArr[] = array('teacher_id' => $teachId, 'subject_id' => $subId);
        }

        DB::table('subject_teacher')->insert($teachersSubjectsDataArr);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}


class DeveloperTableSeeder extends Seeder
{

    public function run()
    {
        $date = new \DateTime;
        $devArr = array();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('developers')->truncate();

        for ($cnt = 1; $cnt <= 50; $cnt++) {
            $devArr[] = array('developer_id' => $cnt, 'developer_name' => "Developer  $cnt", 'api_key'=>str_shuffle("AfSDFGJHGJVKDJLkljh234325435lkjasdglihasdasdxcsdhnlksD"));
        }
        
        DB::table('developers')->insert($devArr);


        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}