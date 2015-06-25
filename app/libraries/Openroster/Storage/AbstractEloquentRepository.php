<?php

namespace Openroster\Storage;

abstract class AbstractEloquentRepository
{

    /**
     * Make a new instance of the entity to query on
     *
     * @param array $with
     */
    public function make($params = array())
    {
        $q = $this->model;

        if (!empty($params['where']) && is_array($params['where'])) {
            foreach ($params['where'] as $fld => $v) {
                $val = is_array($v) ? $v['value'] : $v;
                $op = !empty($v['op']) ? $this->getOperator($v['op']) : '=';
                if ($op == 'like') {
                    $q = $q->where($fld, $op, '%"' . $val . '"%');
                } else {
                    $q = $q->where($fld, $op, $val);
                }
            }
        }
        
        if (!empty($params['with'])) {
            $q->with($params['with']);
        }
        return $q;
    }

    /**
     * Return all users
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getOperator($op = '')
    {
        $opArr = array(
            '=' => '=',
            '>' => '>',
            '>=' => '>=',
            '<' => '<',
            '<=' => '<=',
            'like' => 'like'
        );
        return $ret = !empty($opArr[$op]) ? $opArr[$op] : '=';
    }

    /**
     * Return all users
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find an entity by id
     *
     * @param int $id
     * @param array $with
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id, array $params = array())
    {
        $query = $this->make($params);

        return $query->find($id);
    }

    /**
     * Find a single entity by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getFirstBy($key, $value, array $params = array())
    {
        return $this->make($params)->where($key, '=', $value)->first();
    }

    /**
     * Find many entities by key value
     *
     * @param string $key
     * @param string $value
     * @param array $with
     */
    public function getManyBy($key, $value, array $params = array())
    {
        return $this->make($params)->where($key, '=', $value)->get();
    }

    /**
     * Find many entities by key value
     *
     * @param array $where
     * @param array $with
     */
    public function getMany($params)
    {
        $query = $this->make($params);
        $query = $this->applySorting($query, $params);
        return $query->get()->all();
    }

    public function makeJoins($query,$params){
        if(!empty($params['join'])){
            foreach($params['join'] as $val){
                $query->join($val[0],$val[1],$val[2],$val[3]);
            }    
        }
    }
    /**
     * Get Results by Page
     * @param int $page
     * @param int $limit
     * @param array $with
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function getByPage($params = array())
    {
        if (empty($params['page']))
            $params['page'] = 1;
        if (empty($params['limit']))
            $params['limit'] = 10;
        
       
        $result = new \StdClass;
        $result->page = $params['page'];
        $result->totalPages = $params['page'];
        $result->limit = $params['limit'];
        $result->totalItems = 0;
        $result->items = array();

        $query = $this->make($params);
        $countObj = clone $query;
        //$this->makeJoins($query,$params);
        //$totalItems = $result->totalItems = $query->count();
        $totalItems = $this->totalItems($params);
        $result->totalItems = $totalItems;
        if($params['limit'] == 'All'){
            $result->totalPages = 1;
        }else{
            $result->totalPages = ceil($totalItems / $params['limit']);
        }
        $result->items = $this->getPageSortedData($query, $params);
        return $result;
    }

    public function applySorting($query, $params)
    {

        if (!empty($params['sort_by'])) {
            $order = empty($params['sort_order']) ? 'asc' : $params['sort_order'];
            $query = $query->orderBy($params['sort_by'], $order);
        }

        return $query;
    }
    
    public function totalItems($params){
        $model=\DB:: table($params['table']);
        if (!empty($params['condition']) && is_array($params['condition'])) {
            foreach ($params['condition'] as $fld => $v) {
                $model->where($v[0], $v[1], $v[2]);
            }
        }
        
        if($params['table']!="developers"){
            if(!empty($params['join'])){
                foreach($params['join'] as $val){
                    $model->$val[4]($val[0],$val[1],$val[2],$val[3]);
                }    
            }
        }
       
        $totalRecords = $model->count();
        return $totalRecords;
    }

    public function getPageSortedData($query, $params)
    {   
        if($params['limit'] != 'All'){
            $model = $query->skip($params['limit'] * ($params['page'] - 1))
                ->take($params['limit']);
        }else{
            $model = $query;
        }
        if (!empty($params['sort_by'])) {
            $order = empty($params['sort_order']) ? 'asc' : $params['sort_order'];
            $model = $model->orderBy($params['sort_by'], $order);
        }
        if(!empty($params['fields'])){
           $model->select($params['fields']);
        }
        if (!empty($params['condition']) && is_array($params['condition'])) {
            foreach ($params['condition'] as $fld => $v) {
                    $model->where($v[0], $v[1], $v[2]);
            }
        }
        
        if(!empty($params['join'])){
            foreach($params['join'] as $val){
                $model->$val[4]($val[0],$val[1],$val[2],$val[3]);
            }    
        }
        if(!empty($params['groupby'])){
           $model->groupBy($params['groupby']);
        }
        return $model->get()->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($input)
    {
        return $this->model->create($input);
    }

    public function getChunkIteration($totalRow = 0)
    {
        $chunkArr = array('totalRow' => $totalRow);
        $chunkArr['chunkSize'] = $chunkSize = \Config::get('appvals.upload_chunk_size', '100');
        $chunkArr['iterationCount'] = $iteration = ceil($totalRow / $chunkSize);

        for ($i = 1, $j = 1; $i <= $iteration; $i++) {
            $chunkArr['iteration'][] = array('strat' => $j);
        }
    }

    public function startImport($import = 'school')
    {   
        $return = array('status' => 0, 'msg' => '', 'data' => array(), 'file_id' => 0);
        $date = new \DateTime;
        
        if (!\Config::has("appvals.import_details.$import")) {
            $return['msg'] .= trans('messages.importdata.file_unable_import', array('name' => $import));
            return $return;
        }
        
        $impDetails = \Config::get("appvals.import_details.$import");
        $impMasterDetails = \Config::get("appvals.import_details.import_master");
        $importFiles = \Config::get("appvals.import_file_names");
            
        $fileId = $return['file_id'] = $impDetails['file_id'];

        $fileData = \DB::table($impMasterDetails['table'])
                ->where($impMasterDetails['id'], $impDetails['file_id'])
                ->first();
        
        $return['data'] = (array) $fileData;
           
        if (empty($fileData->file_available)) {
            $return['msg'] .= trans('messages.importdata.file_unable_import_missing', array('name' => $importFiles[$fileId]));
            return $return;
        } else if ($fileData->file_available == 2) {
            $return['msg'] .= trans('messages.importdata.file_unable_import_inprogress', array('name' => $importFiles[$fileId]));
            return $return;
        } else if ($fileData->file_available == 3) {
            $return['msg'] .= trans('messages.importdata.file_unable_import_imported', array('name' => $importFiles[$fileId]));
            return $return;
        }

        
        $updateData = array(
            'file_import_status' => 1,
            'file_available' => 1,
            'file_import_comment' => '',
            'import_successful_records' => 0,
            'import_unsuccessful_records' => 0,
            'file_last_import_started_at' => $date,
            'file_last_imported_at' => $date
        );
        
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        /*\DB::table($impDetails['table'])->truncate();
        if(!empty($impDetails['assoc_trunc_tables']) && is_array($impDetails['assoc_trunc_tables'])) {
            foreach($impDetails['assoc_trunc_tables'] as $tbl) {
                \DB::table($tbl)->truncate();
            }
        }*/
        
        \DB::table($impMasterDetails['table'])
                ->where($impMasterDetails['id'], $impDetails['file_id'])
                ->update($updateData);

        $return['data'] = array_merge($return['data'], $updateData);

        $return['status'] = 1;

        $return['msg'] .= trans('messages.importdata.file_import_started', array('name' => $importFiles[$fileId]));

        return $return;
    }
    
    public function getFileData($file = 'school')
    {
        $fileData = array();
        $date = new \DateTime;

        if (!\Config::has("appvals.import_details.$file")) {
            return $fileData;
        }

        $impDetails = \Config::get("appvals.import_details.$file");
        $impMasterDetails = \Config::get("appvals.import_details.import_master");
        
        
        $fileData = \DB::table($impMasterDetails['table'])
                ->where($impMasterDetails['id'], $impDetails['file_id'])
                ->first();

        $fileData = (array) $fileData;

        return $fileData;
    }
    
    public function getFileStatusData($file = 'school')
    {
        $fileData = $this->getFileData($file);
        return array('status' => 0, 'msg' => '', 'data' => $fileData, 'file_id' => $fileData['file_id']);               
       
    }

    public function finishImport($import = 'school')
    {
        $return = array('status' => 0, 'msg' => '', 'data' => array(), 'file_id' => 0);
        $date = new \DateTime;
        $updateData = array(
            'file_import_status' => 2,
            'file_available' => 3,
            'file_last_imported_at' => $date,
            'updated_at' => $date
        );
        if (!\Config::has("appvals.import_details.$import")) {
            $return['msg'] .= trans('messages.importdata.file_unable_import', array('name' => $import));
            return $return;
        }

        $impDetails = \Config::get("appvals.import_details.$import");
        $impMasterDetails = \Config::get("appvals.import_details.import_master");
        $importFiles = \Config::get("appvals.import_file_names");

        $fileId = $return['file_id'] = $impDetails['file_id'];


        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        \DB::table($impMasterDetails['table'])
                ->where($impMasterDetails['id'], $impDetails['file_id'])
                ->update($updateData);

        $fileData = \DB::table($impMasterDetails['table'])
                ->where($impMasterDetails['id'], $impDetails['file_id'])
                ->first();

        $return['data'] = (array) $fileData;
        $return['status'] = 1;
        $return['msg'] .= trans('messages.importdata.file_imported_successfully', array('name' => $importFiles[$fileId]));

        return $return;
    }

    public function updateLogComment($logComment, $import = 'school')
    {

        $date = new \DateTime;
        $updateData = array();
        if (!empty($logComment['file_import_comment'])) {

            $updateData['file_import_comment'] = \DB::raw("CONCAT_WS(file_import_comment, '$logComment[file_import_comment]')");
        }

        if (!empty($logComment['import_successful_records'])) {

            $updateData['import_successful_records'] = \DB::raw('import_successful_records + ' . $logComment['import_successful_records']);
        }

        if (!empty($logComment['import_unsuccessful_records'])) {

            $updateData['import_unsuccessful_records'] = \DB::raw('import_unsuccessful_records + ' . $logComment['import_unsuccessful_records']);
        }

        if (!empty($logComment['file_available'])) {

            $updateData['file_available'] = $logComment['file_available'];
        }

        if (!\Config::has("appvals.import_details.$import") || empty($updateData))
            return false;

        $impDetails = \Config::get("appvals.import_details.$import");
        $impMasterDetails = \Config::get("appvals.import_details.import_master");

        \DB::table($impMasterDetails['table'])
                ->where($impMasterDetails['id'], $impDetails['file_id'])
                ->update($updateData);
    }

    public function bulkInsert($data = array(), $table = null)
    {
        if (empty($table)) {
            $table = $this->model->getTable();
        }
        if (empty($data)) {
            return false;
        }
        \DB::table($table)
                ->insert($data);
    }

    public function logComment($id = '', $messages = '', $logType = 0)
    {
        $comment = '';

        switch ($logType) {
            case 0:
                $comment = trans('messages.importdata.failed_to_import', array('id' => $id)) . $messages;
                break;
        }
        return $comment;
    }
    
    public function dashBoardCounts(){
        $countArray = array();
        // school count
        $countArray['schoolCount'] =  \DB::table('schools')->count();
        // student count
        $countArray['studentCount'] =  \DB::table('students')->count();
        // teacher count
        $countArray['teacherCount'] =  \DB::table('teachers')->count();
        // course count
        $countArray['courseCount'] =  \DB::table('courses')->count();
        // developer count
        $countArray['developerCount'] =  \DB::table('developers')->count();
        // api log count
        $countArray['apiLogCount'] =  \DB::table('developers_api_call_logs')->count();
        $date = date('Y-m-d');
        $countArray['apiLogCountToday'] =  \DB::table('developers_api_call_logs')->where(\DB::raw('date(added_on)'),"=",$date)->count();
           
        $countArray['graphApi'] = \DB::select('select  count(id) as total ,  month(added_on) as month from developers_api_call_logs group by  (month(added_on))');
            
        return $countArray;    
    }
    
    

}