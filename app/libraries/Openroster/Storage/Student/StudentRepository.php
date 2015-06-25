<?php
namespace Openroster\Storage\Student;
/**
 * Class StudentRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface StudentRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
