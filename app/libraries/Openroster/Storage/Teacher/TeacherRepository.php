<?php
namespace Openroster\Storage\Teacher;
/**
 * Class TeacherRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface TeacherRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
