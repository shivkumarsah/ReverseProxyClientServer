<?php
namespace Openroster\Storage\Csvfiles;
/**
 * Class CsvfilesRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
interface CsvfilesRepository
{

    public function all();

    public function find($id);

    public function create($input);
}
