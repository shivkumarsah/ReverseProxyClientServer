<?php

namespace Openroster\Storage\Imports;

/**
 * Class SchoolRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
class SchoolImport extends BaseImport
{

    public function getFile()
    {
        return storage_path('imports') . '/Schools.csv';
    }

}
