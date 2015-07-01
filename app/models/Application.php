<?php

class Application extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applications';

    protected $primaryKey = 'id';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'remember_token'
    );
    
    public function verifyAdminProxy($data)
    {
        $response = array();
        $response['status'] = 1;
        $response['isProxyExist'] = 0;
        if (isset($data['TenantId']) && ! empty($data['TenantId'])) {
            $user = AdminUser::where('tenant_id', '=', $data['TenantId'])->first();
            if (! empty($user)) {
                $response['user'] = $user->toArray();
                if (! empty($user['proxy_status']) && ! empty($user['api_key'])) {
                    $response['isProxyExist'] = 1;
                }
            } else {
                $user = array();
                $user['email'] = $data['Email'];
                $user['name'] = $data['DisplayName'];
                $user['tenant_id'] = $data['TenantId'];
                $user['proxy_status'] = 0;
                $user['created_at'] = date("Y-m-d h:i:s");
                $user['updated_at'] = date("Y-m-d h:i:s");
                try {
                    $id = AdminUser::insertGetId($user);
                    $response['user'] = AdminUser::find($id)->toArray();
                } catch (Exception $ex) {
                    $response['status'] = 0;
                    $response['error'] = "Invalid Response From Launchpad.";
                }
            }
        } else {
            $response['status'] = 0;
            $response['error'] = "Invalid Response From Launchpad.";
        }
        return $response;
    }
    /**
     * Get reverse proxy details for admin.
     *
     * @return  array $user
     */
    public function getProxy()
    {
        $response = array();
        $response['status'] = 0;
        $access_key = Session::get('access_key');
        $user_id = Session::get('user_id');
        if (! empty($access_key) && ! empty($user_id)) {
            try {
                $user = AdminUser::find($user_id);
                if (! empty($user)) {
                    $response['status']         = 1;
                    $response['proxy_status']   = $user->proxy_status;
                    $response['proxy_url']      = $user->proxy_url;
                    $response['api_key']        = $user->api_key;
                    if(empty($user->api_key)) {
                        $response['api_key']    = md5(uniqid(mt_rand(), true));
                        //$response['api_key']    = Hash::make("secret");
                    }
                    
                }
            } catch (Exception $ex) {
                $response['message'] = Lang::get('messages.login.invalid_access');
            }
        } else {
            $response['message'] = Lang::get('messages.login.invalid_access');
        }
        return $response;
    }
    /**
     * Set reverse proxy details for admin.
     *
     * @param  array $input Array containing 'proxy_url' and 'api_key'.
     *
     * @return  boolean Success
     */
    public function setProxy($input)
    {
        $response = array();
        $response['status'] = 0;
        $access_key = Session::get('access_key');
        $user_id = Session::get('user_id');
        if (! empty($access_key) && ! empty($user_id)) {
            try {
                DB::table('admin_users')->where('id', $user_id)->update([
                    'proxy_status'  => 1,
                    'proxy_url'     => $input['proxy_url'],
                    'api_key'       => $input['api_key']
                ]);
                $user = AdminUser::find($user_id);
                if (! empty($user->proxy_url)) {
                    $response['status'] = 1;
                    $response['user'] = $user;
                }
            } catch (Exception $ex) {
                $response['message'] = Lang::get('messages.login.invalid_access');
            }
        } else {
            $response['message'] = Lang::get('messages.login.invalid_access');
        }
        return $response;
    }
}