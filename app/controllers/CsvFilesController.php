<?php

use Openroster\Storage\Csvfiles\CsvfilesRepository as Csvfiles;

/**
 * PreviewDataController Class
 *
 * Implements actions regarding data preview
 */
class CsvFilesController extends BaseController
{

    public function __construct(Csvfiles $Csvfiles)
    {
        $this->Csvfiles = $Csvfiles;
    }

    

    /**
     * Returns list of Csv Files
     *
     * @return  Illuminate\Http\Response
     */
    public function csvFiles()
    {
        return $this->Csvfiles->getCsvFilesList();
    }


}
