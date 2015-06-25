<?php
namespace Openroster\Storage\School;
/**
 * Class SchoolRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface SchoolRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
