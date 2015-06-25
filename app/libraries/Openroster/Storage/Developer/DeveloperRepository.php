<?php
namespace Openroster\Storage\Developer;
/**
 * Class StudentRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface DeveloperRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
