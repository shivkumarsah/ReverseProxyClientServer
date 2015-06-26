<?php

class AdminHistory extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_login_history';

    protected $primaryKey = 'id';

    /**
     * create a database log when admin pass a valid login.
     *
     * @data array
     * @access_token string
     */
    public function logHistory($data, $access_token)
    {
        if (isset($data['TenantId']) && ! empty($data['TenantId'])) {
            $adminHistory = new AdminHistory();
            $adminHistory->tenent_id    = $data['TenantId'];
            $adminHistory->email        = $data['Email'];
            $adminHistory->name         = $data['DisplayName'];
            $adminHistory->access_token = $access_token;
            $adminHistory->response_data  = json_encode($data);
            $adminHistory->created_at   = date("Y-m-d h:i:s");
            try {
                $adminHistory->save();
                $id = $adminHistory->id;
            } catch (Exception $ex) {
                $id = 0;
            }
        } else {
            $id = 0;
        }
        return $id;
    }
}