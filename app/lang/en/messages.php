<?php

return array(
    /*
      |--------------------------------------------------------------------------
      | Messages Language Lines
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | as the size rules. Feel free to tweak each of these messages here.
      |
     */

    "logo" => "Logo",
    "classic_url_title" =>"ClassLink, Inc.",
    'project_title' => "Reverse Proxy Management",
    'no_record_found' => "No record found.",
    "links" => array(
        'settings'=>"Settings",
        'logout'=>"Logout",
        'dashboard'=>"Dashboard",
        'overview'=>"Overview",
        'manage_application'=>"Manage Applications",
        'proxy_setting'=>"Reverse Proxy Settings",
    ),
    "settings" => array(
        'page_title'=>"Settings",
        
    ),
    "applications" => array(
        'page_title'=>"Applications",
        'add_application'=> "Create New Application",
        'head_name'=> "Name",
        'head_internal_url'=> "Internal URL",
        'head_external_url'=> "External URL",
        'head_task'=> "Task",
        
    ),
    "login" => array(
        'page_title'=>"Login | Reverse Proxy Management",
        'username' => "Username",
        'password' => "Password",
        'remember_me' => "Remember Me",
        'sign_in' => "Sign In",
        'login' => "Login",
        'account_created' => 'Your account has been successfully created.',
        'instructions_sent'       => 'Please check your email for the instructions on how to confirm your account.',
        'too_many_attempts' => 'Too many attempts. Try again in few minutes.',
        'wrong_credentials' => 'Incorrect username or password.',
        'not_confirmed' => 'Your account may not be confirmed. Check your email for the confirmation link',
        'confirmation' => 'Your account has been confirmed! You may now login.',
        'password_confirmation' => 'The passwords did not match.', 
        'wrong_confirmation' => 'Wrong confirmation code.',
        'password_forgot' => 'The information regarding password reset was sent to your email.',
        'wrong_password_forgot' => 'User not found.',
        'password_reset' => 'Your password has been changed successfully.',
        'wrong_password_reset' => 'Invalid password. Try again',
        'wrong_token' => 'The password reset token is not valid.',
        'duplicated_credentials' => 'The credentials provided have already been used. Try with different credentials.',
        'invalid_access' => 'Invalid login. Try again'
    ),
    "dashboard" => array(
        'page_title'=>"Dashboard",
        
    ),
    "developers" => array(
        'page_title'=>"Developer Management",
        'page_header'=>"Developers",
        'name' => "Name",
        'api' => "Consumer Key",
        'actions' => "Actions",
        'add_developer' => "Add Developer",
        'edit' => "Edit",
        'save' => "Save",
        'delete' => "Delete",
        'generatekey' => "Generate Key",
        
    ),    
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | Here you may specify custom validation messages for attributes using the
      | convention "attribute.rule" to name the lines. This makes it quick to
      | specify a specific custom language line for a given attribute rule.
      |
     */
    'custom' => array(
        'attribute-name' => array(
            'rule-name' => 'custom-message',
        ),
    ),
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
     */
    'attributes' => array(),
);
