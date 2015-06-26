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
    'project_title' => "OpenRosters",
    'no_record_found' => "No record found.",
    "links" => array(
        'settings'=>"Settings",
        'logout'=>"Logout",
        'dashboard'=>"Dashboard",
        'overview'=>"Overview",
        'developers'=>"Developers",
        'import_data'=>"Import Data",
        'view_data'=>"View Data",
        'schools'=>"Schools",
        'teachers'=>"Teachers",
        'students'=>"Students",
        'subjects'=>"Subjects",
        'courses'=>"Courses",
        'enrollments'=>"Enrollments",
    ),
    "login" => array(
        'page_title'=>"Login | OpenRosters",
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
    "settings" => array(
        'page_title'=>"Setting Page",
        
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
    "importdata" => array(
        'page_title'=>"Import Data",
        'page_header'=>"Import",
        'name' => "Name",
        'size' => "Size",
        'progress' => "Progress",
        'status' => "Status",
        'actions' => "Actions",
        'lastmodified' => "Last Modified",
        'file_upload' => "File Upload",
        'verify' => "Verify",
        'logs' => "logs",
        'download_templates' => "Download Templates",
        'select_files' => "Select files",
        'base_drop_zone1' => "Base drop zone",
        'base_drop_zone2' => "Another drop zone with its own settings",
        'multiple' => "Multiple",
        'single' => "Single",
        'upload_queue' => "Upload queue",
        'queue_length' => "Queue length",
        'upload' => "Upload",
        'cancel' => "Cancel",
        'remove' => "Remove",
        'upload_all' => "Upload all",
        'cancel_all' => "Cancel all",
        'remove_all' => "Remove all",
        'queue_progress' => "Queue progress",
        'failed_to_import'=>'Record with id :id failed to import due to following reasons: ',
        'file_name_invalid'=>'This is not the valid name of the file we support.',
        'file_format_invalid'=>' We support only CSV format to upload.',
        'file_imported_successfully'=>' File : name imported successfully.',
        'file_import_comment'=>' File :name with size :size bytes imported successfully.',
        'file_unable_import'=>' Sorry unable to import :name .',
        'file_unable_import_missing'=>' Sorry unable to import file :name is missing.',
        'file_unable_import_inprogress'=>'File :name is already in progress.',
        'file_unable_import_imported'=>'File :name has already been imported.',
        'file_import_started'=>'File :name has been started to import.',
        'file_import_restart'=>'File :name has not been imported due to some technical reason. Please re-try after sometime.',
        'file_import_log_title'=>'Log Details of File.',
        'file_last_upload_log'=>'This is the file upload log of last time.',
        'file_last_import_log'=>'This is the log of file when imported last time.',
        
    ),    
    "schools" => array(
        'page_title'=>"Schools Data Preview",
        
    ),    
    "teachers" => array(
        'page_title'=>"Teachers Data Preview",
        
    ),    
    "students" => array(
        'page_title'=>"Students Data Preview",
        
    ),    
    "subjects" => array(
        'page_title'=>"Subjects Data Preview",
        
    ),    
    "courses" => array(
        'page_title'=>"Courses Data Preview",
        
    ),    
    "enrollments" => array(
        'page_title'=>"Students Enrollment Data Preview",
        
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
