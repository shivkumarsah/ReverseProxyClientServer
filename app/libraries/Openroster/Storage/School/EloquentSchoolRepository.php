<?php

namespace Openroster\Storage\School;

use Openroster\Storage\AbstractEloquentRepository;

class EloquentSchoolRepository extends AbstractEloquentRepository implements SchoolRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct(\School $model)
    {
        $this->model = $model;
    }

    public function getSchoolList($inputData = array())
    {
        $params = array();
        if (!empty($inputData['pageNumber'])) {
            $params['page'] = $inputData['pageNumber'];
        }
        if (!empty($inputData['itemsPerPage'])) {
            $params['limit'] = $inputData['itemsPerPage'];
        }
        if (!empty($inputData['sortedBy'])) {
            $params['sort_by'] = $inputData['sortedBy'];
        }

        if (!empty($inputData['sortDir'])) {
            $params['sort_order'] = $inputData['sortDir'];
        }

        /*if (!empty($inputData['school_name'])) {
            $params['where']['school_name'] = array('op' => 'like', 'value' => $inputData['school_name']);
        }

        if (!empty($inputData['school_id'])) {
            $params['where']['school_id'] = $inputData['school_id'];
        }*/
        
        //$params['condition']['courses.course_name'] = array('op' => 'like', 'value' => $inputData['course_name']); 
        if (isset($inputData['school_name']) && $inputData['school_name'] != "") {
            $params['condition'][] = array('schools.school_name', 'like', '%'.$inputData['school_name'].'%');
        }
        
        $params['table'] = 'schools';
        return $this->getByPage($params);
    }

    public function importSchools($result)
    {
        $importFiles = \Config::get("appvals.import_file_names");
        $validRecords = $invalidRecords = array();
        if (empty($result)) return false;
            
        try {
            $records = $this->validateSchoolData($result);
            if(count($records['valid_records'])>0){
                $insterArray = array();
                foreach($records['valid_records'] as $key=>$val){
                        $insterArray[$val['school_id']] = $val;
                }
                
                $this->bulkInsert($insterArray);
            }
            
            $logComment['file_import_comment'] = $records['log_message'];
            $logComment['import_successful_records'] = count($records['valid_records']);
            $logComment['import_unsuccessful_records'] = count($records['invlaid_records']);

            $this->updateLogComment($logComment);
        } catch (\Exception $exception) {
            \Log::error($exception);
            $fileData = $this->getFileStatusData('school');
            $fileData['data']['file_available'] = $logFComment['file_available'] = 1;
            $logFComment['file_import_comment'] = $exception->getMessage();
            $this->updateLogComment($logComment);
            $fileData['msg'] = trans('messages.importdata.file_import_restart', array('name'=>$importFiles[$startImport['file_id']]));
            
            return $fileData;
        }
        return true;
    }

    public function validateSchoolData($result)
    {
        $date = new \DateTime;
        $validRecords = array();
        $invalidRecords = array();
        $logMessages = '';

        $validRules = array(
            'school_id' => 'required|Integer|unique:schools',
            'school_name' => 'required'
        );

        foreach ($result as $row) {
            $data = $row->toArray();

            $validator = \Validator::make(
                            $data, $validRules
            );
            if ($validator->fails()) {
                $messages = '<ul>' . implode(" ", $validator->messages()->all('<li>:message</li>')) . '</ul>';
                $invalidRecords[] = array(
                    'id' => $data['school_id'],
                    'data' => $data,
                    'messages' => $messages
                );
                $logMessages .= $this->logComment($data['school_id'], $messages);
            } else {
                $data['updated_at'] = $data['created_at'] = $date;
                $validRecords[] = $data;
            }
        }

        return array('valid_records' => $validRecords, 'invlaid_records' => $invalidRecords, 'log_message' => $logMessages);
    }
    
    public function getDashboardCounts(){
        return $this->dashBoardCounts();
    }
}
