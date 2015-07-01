<?php
namespace Openroster\Storage\Application;
/**
 * Class ApplicationRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface ApplicationRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
