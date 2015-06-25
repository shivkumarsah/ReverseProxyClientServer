<?php
namespace Openroster\Storage\Imports;
/**
 * Class SchoolRepository
 *
 * This service abstracts some interactions that occurs between Controller and
 * the Database.
 */
abstract class BaseImport extends \Maatwebsite\Excel\Files\ExcelFile {
    protected $fileName = '';
    protected $delimiter  = ',';
    protected $enclosure  = '"';
    protected $lineEnding = '\r\n';

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }
}
