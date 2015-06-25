<?php

namespace Openroster\Storage\Csvfiles;
use Openroster\Storage\AbstractEloquentRepository;

class EloquentCsvfilesRepository extends AbstractEloquentRepository implements CsvfilesRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\Csvfiles $model)
    {
        $this->model = $model;
    }
    
    
    public function getCsvFilesList($inputData = array())
    {
        $params = array(
            'where' => array('status'=>1),
            'sort_by' => 'file_order',
        );
        

        return $this->getMany($params);
    }
    
    
    public function processAndUpload($file, $data)
    {
        
        $return = array('status'=>0, 'msg'=>'', 'data'=>array(), 'file_id'=>0);
        // dd($file->getClientMimeType());
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientMimeType();
        $fileSize = $file->getClientSize();
        
        if(!$file_id = array_search(strtolower($fileName), \Config::get('appvals.import_file_names' ))) {
            $return['msg'] .= trans('messages.importdata.file_name_invalid');
        }
        
        if(!in_array(strtolower($fileType), \Config::get('appvals.import_file_formats' ))) {
            $return['msg'] .= trans('messages.importdata.file_format_invalid');
        }
       // if(!empty($return['msg'])) return $return;
        
        $return['file_id'] = $file_id;
        
        $fileNameToMove = ucfirst(\Config::get("appvals.import_file_names.$file_id" ));
        $destPath = storage_path().'/'.\Config::get('appvals.file_import_dir_name' );
         try {
                $fileRet = $file->move($destPath, $fileNameToMove);
                $return['msg'] .= trans('messages.importdata.file_imported_successfully' , array('name'=>$fileName));
                $fileData = $this->model->find($file_id);
                if(empty($fileData->file_available)) {
                    $fileData->created_at = new \DateTime;
                }
                
                $fileData->file_available = 1;
                
                $fileData->file_upload_comment = trans('messages.importdata.file_import_comment' , array('name'=>$fileName, 'size'=>$fileSize));;
                $fileData->save();
                $return['data'] = $fileData->toArray();
                $return['status'] = 1;
                return $return;
            } catch (\Exception $exception) {
            \Log::error($exception);
            $return['msg'] .= $exception->getMessage();
             return $return;
        }
        
        
        

        return $this->getMany($params);
    }
}
