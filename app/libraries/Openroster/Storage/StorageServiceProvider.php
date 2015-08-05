<?php

namespace Openroster\Storage;

use Illuminate\Support\ServiceProvider;


/**
 * Class SchoolRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
class StorageServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            'Openroster\Storage\Application\ApplicationRepository', function ($app) {
            return new \Openroster\Storage\Application\EloquentApplicationRepository(new \Application);
        });

        $this->app->bind(
            'Openroster\Storage\School\SchoolRepository', function ($app) {
            return new \Openroster\Storage\School\EloquentSchoolRepository(new \School);
        });

        $this->app->bind(
            'Openroster\Storage\Student\StudentRepository', function ($app) {
            return new \Openroster\Storage\Student\EloquentStudentRepository(new \Student);
        });

        $this->app->bind(
            'Openroster\Storage\Teacher\TeacherRepository', function ($app) {
            return new \Openroster\Storage\Teacher\EloquentTeacherRepository(new \Teacher);
        });

        $this->app->bind(
            'Openroster\Storage\Course\CourseRepository', function ($app) {
            return new \Openroster\Storage\Course\EloquentCourseRepository(new \Course);
        });

        $this->app->bind(
            'Openroster\Storage\Developer\DeveloperRepository', function ($app) {
            return new \Openroster\Storage\Developer\EloquentDeveloperRepository(new \Developer);
        });

        $this->app->bind(
            'Openroster\Storage\Csvfiles\CsvfilesRepository', function ($app) {
            return new \Openroster\Storage\Csvfiles\EloquentCsvfilesRepository(new \Csvfiles);
        });

        $this->app->bind(
            'Openroster\Storage\Enrollment\EnrollmentRepository', function ($app) {
            return new \Openroster\Storage\Enrollment\EloquentEnrollmentRepository(new \Enrollment);
        });
    }

}
