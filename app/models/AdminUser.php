<?php
use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;

class AdminUser extends Eloquent implements ConfideUserInterface
{
    
    use ConfideUser;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

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
    
    public function updateAdminUser($data)
    {
        $response = array();
        $response['status'] = 0;
        $access_key = Session::get('access_key');
        $user_id = Session::get('user_id');
        if (! empty($access_key) && ! empty($user_id)) {
            try {
                DB::table('admin_users')->where('user_id', $user_id)->update([
                    'school_domain' => $data['school_domain'],
                    'api_key' => $data['api_key']
                ]);
                $user = AdminUser::find($user_id);
                if (! empty($user->school_domain)) {
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

    public function validateDomain($data)
    {
        $response = array();
        $response['status'] = 1;
        $access_key = Session::get('access_key');
        $user_id = Session::get('user_id');
        $messages = [
            'school_domain.required' => 'The school domain field is required.',
            'school_domain.url' => 'Invalid school URL.',
            'api_key.required' => 'The api key field is required.',
            'school_domain.unique' => 'This school domain has already been taken.',
            'api_key.unique' => 'This api key has already been taken.'
        ];
        
        $rules = array(
            'school_domain' => "required|url|unique:admin_users,school_domain,$user_id,user_id",
            'api_key' => "required|unique:admin_users,api_key,$user_id,user_id"
        );
        if (! empty($access_key) && ! empty($user_id)) {
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                $response['status'] = 0;
                $messages = $validator->messages();
                $errors = "";
                foreach ($messages->all() as $message) {
                    $errors .= $message . "<br>";
                }
                $response['message'] = substr($errors, 0, - 4);
            }
        } else {
            $response['message'] = Lang::get('messages.login.invalid_access');
        }
            return $response;
        }
}