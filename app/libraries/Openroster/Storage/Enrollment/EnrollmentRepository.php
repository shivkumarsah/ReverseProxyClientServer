<?php
namespace Openroster\Storage\Enrollment;
/**
 * Class CourseRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface EnrollmentRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
