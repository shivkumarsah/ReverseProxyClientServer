<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as'=>'home', 'uses'=>'UsersController@login'));
Route::post('/', 'UsersController@doLogin');
//
Route::get('users/login', array('as'=>'login', 'uses'=>'UsersController@login'));
Route::post('users/login', array('as'=>'doLogin', 'uses'=>'UsersController@doLogin'));
Route::any('users/signup', array('as'=>'signup', 'uses'=>'UsersController@signup'));

//OAuth token verification
//Route::get('users/lunchpadtoken/{response_token}', array('as'=>'lunchpadtoken', 'uses'=>'UsersController@lunchpadtoken'));
Route::get('users/lunchpadtoken', array('as'=>'lunchpadtoken', 'uses'=>'UsersController@lunchpadtoken'));

Route::any('api/v1/developers', array('as' => 'ApiDevelopersController', 'uses' => 'ApiDevelopersController@index'));
Route::any('apiaccess', array('as' => 'apiaccessController', 'uses' => 'DevelopersController@apiaccess'));
Route::get('users/processOauth/{token}', array('as'=>'processOauth', 'uses'=>'UsersController@processOauth'));
Route::get('users/autologin', array('as'=>'autoLogin', 'uses'=>'UsersController@autoLogin'));
Route::get('logout/{gwstoken}', array('as'=>'logoutToken', 'uses'=>'UsersController@logoutToken'));

Route::any('users/checkdomain', array('as'=>'checkDomain', 'uses'=>'UsersController@checkDomain'));

Route::get('v2/LTI/schools/{param1?}/{param2?}/{param3?}/{param4?}', array('as' => 'ApiSchoolsController', 'uses' => 'ApiSchoolsController@index'));
Route::any('api/v1/proxy', array('as' => 'ApiDevelopersController', 'uses' => 'ProxyClientController@index'));

// Confide routes
//Route::group(array('before' => 'auth'), function()
Route::group(array('before' => 'admin-auth'), function()
{
    Route::get('users/logout', array('as'=>'logout', 'uses'=>'UsersController@logout'));
    Route::get('users/dashboard', array('as'=>'dashboard', 'uses'=>'UsersController@dashboard'));
    Route::get('users/applications', array('as'=>'applications', 'uses'=>'UsersController@dashboard'));
    Route::get('users/settings', array('as'=>'settings', 'uses'=>'UsersController@dashboard'));
    
    Route::get('proxy/settings', array('as'=>'proxysettings', 'uses'=>'ProxyController@settings'));
    Route::post('proxy/settings/save', array('as'=>'proxysettingssave', 'uses'=>'ProxyController@settingsSave'));
    
    Route::get('proxy/applications', array('as'=>'applications', 'uses'=>'ProxyController@applications'));
    Route::any('proxy/applications/list', array('as'=>'developerslist', 'uses'=>'ProxyController@applicationList'));
    Route::any('proxy/applications/add', array('as'=>'applicationsadd', 'uses'=>'ProxyController@applicationAdd'));
    Route::any('proxy/applications/edit', array('as'=>'applicationsedit', 'uses'=>'ProxyController@applicationEdit'));
    Route::any('proxy/applications/delete', array('as'=>'applicationsdelete', 'uses'=>'ProxyController@applicationDelete'));
    
    
    //Route::get('users/create',  'UsersController@create');
    //Route::post('users', 'UsersController@store');
    
    //Route::get('users/confirm/{code}', 'UsersController@confirm');
    //Route::get('users/forgot_password', 'UsersController@forgotPassword');
    //Route::post('users/forgot_password', 'UsersController@doForgotPassword');
    Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
    Route::post('users/reset_password', 'UsersController@doResetPassword');
    Route::post('users/changepassword', 'UsersController@changepassword');
    Route::get('users/graph', array('as'=>'dashboardgraph', 'uses'=>'UsersController@graph'));
    Route::get('users/settings', array('as'=>'settings', 'uses'=>'UsersController@settings'));
    Route::get('developers', array('as'=>'developers', 'uses'=>'DevelopersController@index'));
    Route::get('developers/schools/{id}', array('as'=>'developerschools', 'uses'=>'DevelopersController@developerSchools'));
    Route::any('developers/assignschool', array('as'=>'developerassignschool', 'uses'=>'DevelopersController@assignSchool'));
    Route::get('developers/create', array('as'=>'addDeveloper', 'uses'=>'DevelopersController@create'));
    Route::any('developers/add', array('as'=>'addDeveloper', 'uses'=>'DevelopersController@addDeveloper'));
    Route::any('developers/edit', array('as'=>'editDeveloper', 'uses'=>'DevelopersController@editDeveloper'));
    Route::any('developers/key', array('as'=>'developerKey', 'uses'=>'DevelopersController@developerKey'));
    Route::any('developers/delete', array('as'=>'deleteDeveloper', 'uses'=>'DevelopersController@deleteDeveloper'));
    Route::any('developers/apitokens/{id}', array('as'=>'apitokens', 'uses'=>'DevelopersController@apitokens'));
    Route::post('developers', array('as'=>'store_developer', 'uses'=>'DevelopersController@store'));
    Route::get('importdata', array('as'=>'importdata', 'uses'=>'ImportController@index'));
    Route::any('importschools', array('as'=>'importschools', 'uses'=>'ImportController@doSchoolsImport'));
    Route::any('importstudents', array('as'=>'importstudents', 'uses'=>'ImportController@doStudentsImport'));
    Route::any('importteachers', array('as'=>'importteachers', 'uses'=>'ImportController@doTeachersImport'));
    Route::any('importcourses', array('as'=>'importcourses', 'uses'=>'ImportController@doCoursesImport'));
    Route::any('importenrollments', array('as'=>'importenrollments', 'uses'=>'ImportController@doEnrollmentsImport'));
    Route::get('schools', array('as'=>'schools', 'uses'=>'PreviewDataController@index'));
    Route::get('teachers', array('as'=>'teachers', 'uses'=>'PreviewDataController@teachers'));
    Route::get('students', array('as'=>'students', 'uses'=>'PreviewDataController@students'));
    Route::get('subjects', array('as'=>'subjects', 'uses'=>'PreviewDataController@subjects'));
    Route::get('courses', array('as'=>'courses', 'uses'=>'PreviewDataController@courses'));
    Route::get('enrollments', array('as'=>'enrollments', 'uses'=>'PreviewDataController@enrollments'));
    Route::get('data/schools', array('as'=>'schoolsData', 'uses'=>'PreviewDataController@schoolsData'));
    Route::get('data/teachers', array('as'=>'teachersData', 'uses'=>'PreviewDataController@teachersData'));
    Route::get('data/students', array('as'=>'studentsData', 'uses'=>'PreviewDataController@studentsData'));
    Route::get('data/enrollments', array('as'=>'enrollmentsData', 'uses'=>'PreviewDataController@enrollmentsData'));
    Route::get('data/courses', array('as'=>'coursesData', 'uses'=>'PreviewDataController@coursesData'));
    Route::get('csvfiles', array('as'=>'csvfiles', 'uses'=>'ImportController@csvFiles'));
    Route::get('importlog/{file_id}', array('as'=>'importlog', 'uses'=>'ImportController@getFileLog'));
    Route::any('importcsv', array('as'=>'importcsv', 'uses'=>'ImportController@importCsv'));
    Route::any('listdevelopers', array('as'=>'listdevelopers', 'uses'=>'DevelopersController@listDevelopers'));
    Route::get('apidocs', array('as'=>'apidocs', 'uses'=>'ApiDocsController@index'));
});

Route::get('api-docs', array('as'=>'api-docs', 'uses'=>'ApiDocsController@apidocs'));
Route::get('v1/LTI/schools/{param1?}/{param2?}/{param3?}/{param4?}', array('as' => 'ApiSchoolsController', 'uses' => 'ApiSchoolsController@index'));
Route::get('api-docs/schoolsApi', array('as'=>'schoolsApi', 'uses'=>'RestApiSchoolsController@index'));
Route::get('v1/LTI/schools/list', array('as'=>'schoolsApiList', 'uses'=>'RestApiSchoolsController@schools'));


include_once('sort-paginate-route.php');


$date_now = (new DateTime)->format('Y-m-d');
$path = storage_path().'/logs/query-logs/query-'.$date_now.'.log';

App::before(function($request) use($path) {
    $start = PHP_EOL.'=| '.$request->method().' '.$request->path().' |='.PHP_EOL;
    
  File::append($path, $start);
});

Event::listen('illuminate.query', function($sql, $bindings, $time) use($path) { 
    // Uncomment this if you want to include bindings to queries
    //$sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
    //$sql = vsprintf($sql, $bindings);
    $time_now = (new DateTime)->format('Y-m-d H:i:s');
    $log = $time_now.' | '.$sql.' | '.$time.'ms'.PHP_EOL;
    //echo"<pre>";print_r($log);
  File::append($path, $log);
});