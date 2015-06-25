<?php
namespace Openroster\Storage\Course;
/**
 * Class CourseRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface CourseRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
