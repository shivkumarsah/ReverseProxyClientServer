<?php
//namespace Openroster\Resources;
use Swagger\Annotations as SWG;

class ApiSchoolsController extends BaseController
{

    public function __construct()
    {
        
    }

    /**
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/",
 *     basePath="http://openroster.lan/v1/LTI/schools"
 * )
 */

    public function index($param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {
        
        $headers = getallheaders();
        //echo"<pre>";print_r($headers);exit;
        /* if(!empty($headers['Authorization'])){
          $AuthorizationArray = explode(",",$headers['Authorization']);
          foreach($AuthorizationArray as $val){
          if (strpos($val,'oauth_token') !== false) {
          $authTokenArray = explode("=",$val);
          $headers['auth-x'] =  str_replace('"','',urldecode($authTokenArray[1]));
          }
          }
          } */
         
         if(!empty($headers['api-key'])) {
             $headers['Api-Key'] = $headers['api-key'] ;
         }
         if(!empty($headers['secret-key'])) {
             $headers['Secret-Key'] = $headers['secret-key'] ;
         }
        //echo"<pre>";print_r($headers);exit;
        //if(empty($headers['auth-x'])){
        //if (empty($headers['secret-key']) || empty($headers['api-key'])) {
        if (empty($headers['Secret-Key']) || empty($headers['Api-Key'])) { 
            $responseArray = array();
            $responseArray['responseCode'] = 400;
            $responseArray['error'] = true;
            //$responseArray['responseMessage'] = "Invalid or blank auth token";
            $responseArray['responseMessage'] = "Blank api key  or secret key";
            return Response::json($responseArray, $responseArray['responseCode']);
            exit;
        } else { 
            $developer = new Developer();
            //  $result=$developer -> checkIToken($headers['auth-x']);
            $result = $developer->checkIToken($headers);
            
            
            if ($result['status'] === true) {
                $developerId = $result['developerId'];
                $requestUri = $_SERVER['REQUEST_URI'];
//                if(stristr($requestUri, '?api_key=')) {
//                    $requestUri = substr($requestUri,0, strpos($requestUri, '?api_key='));
//                }
                
                $result = $this->getCallingMethod($requestUri);
                if ($result['status'] == false) {
                    $responseArray = array();
                    $responseArray['responseCode'] = $result['responseCode'];
                    $responseArray['error'] = true;
                    $responseArray['responseMessage'] = $result['responseMessage'];
                    return Response::json($responseArray, $responseArray['responseCode']);
                    exit;
                } else {

                    $school = new School();
                    $callingMethod = $result['callingApi'];
                    if (!empty($result['schoolId'])) {
                        $schoolId = $result['schoolId'];
                    } else {
                        $schoolId = NULL;
                    }

                    if (!empty($result['param'])) {
                        $param = $result['param'];
                    } else {
                        $param = NULL;
                    }

                    
                    if($schoolId != NULL){
                        if(preg_match('/^\d+$/',$schoolId)) {
                          // valid input.
                        } else {
                            $resultArray = array();
                            $resultArray['status'] = false;    
                            $resultArray['responseMessage'] = "Unexpected Parameters";
                            $resultArray['responseCode'] = 402;
                            return Response::json($resultArray, $resultArray['responseCode']);exit;
                        }
                       
                    }
                    
                    if($param != NULL){
                        if(preg_match('/^\d+$/',$param)) {
                          // valid input.
                        } else {
                            $resultArray = array();
                            $resultArray['status'] = false;    
                            $resultArray['responseMessage'] = "Unexpected Parameters";
                            $resultArray['responseCode'] = 402;
                            return Response::json($resultArray, $resultArray['responseCode']);exit;
                        }
                    }
                    $result=$school->$callingMethod($schoolId,$param,$developerId);
                    

                    $responseArray = array();
                    $responseArray['responseCode'] = 200;
                    if ($result['status'] === false) {
                        $responseArray['error'] = true;
                        $responseArray['responseMessage'] = $result['responseMessage'];
                        $responseArray['responseCode'] = $result['responseCode'];
                    } else {
                        $responseArray['error'] = false;
                        $responseArray['responseMessage'] = $result['responseMessage'];
                        $responseArray['data'] = $result['requestApiData'];
                    }

                    return Response::json($responseArray, $responseArray['responseCode']);
                    exit;
                }
            } else {
                $responseArray = array();
                $responseArray['responseCode'] = $result['responseCode'];
                $responseArray['error'] = true;
                $responseArray['responseMessage'] = $result['responseMessage'];
                return Response::json($responseArray, $responseArray['responseCode']);
                exit;
            }
        }
    }

    public function getCallingMethod($uri)
    {
        
        $uriArray = explode("/", $uri);
        $requestSegments = array_slice($uriArray, 4);
        $totalUri = count($requestSegments);
        $resultArray = array();
        switch ($totalUri) {
            case 0:
                $resultArray['status'] = true;
                $resultArray['callingApi'] = "getSchools";
                return $resultArray;
                break;

            case 1:
                if (!empty($requestSegments[0])) {
                    $resultArray['status'] = false;
                    $resultArray['responseMessage'] = "Unexpected Parameters";
                    $resultArray['responseCode'] = 402;
                    return $resultArray;
                } else {
                    $resultArray['status'] = true;
                    $resultArray['callingApi'] = "getSchools";
                    return $resultArray;
                }
                break;

            case 2:
                if (!empty($requestSegments[0]) && !empty($requestSegments[1])) {
                    if ($requestSegments[1] == 'courses') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "schoolsCourses";
                        $resultArray['schoolId'] = $requestSegments[0];
                        return $resultArray;
                    } else if ($requestSegments[1] == 'teachers') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "schoolsTeachers";
                        $resultArray['schoolId'] = $requestSegments[0];
                        return $resultArray;
                    } else if ($requestSegments[1] == 'students') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "schoolsStudents";
                        $resultArray['schoolId'] = $requestSegments[0];
                        return $resultArray;
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        return $resultArray;
                    }
                } else {
                    $resultArray['status'] = false;
                    $resultArray['responseMessage'] = "Unexpected Parameters";
                    $resultArray['responseCode'] = 402;
                    return $resultArray;
                }

                break;

            case 3:
                if (empty($requestSegments[2])) {
                    if ($requestSegments[1] == 'courses') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "schoolsCourses";
                        $resultArray['schoolId'] = $requestSegments[0];
                        return $resultArray;
                    } else if ($requestSegments[1] == 'teachers') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "schoolsTeachers";
                        $resultArray['schoolId'] = $requestSegments[0];
                        return $resultArray;
                    } else if ($requestSegments[1] == 'students') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "schoolsStudents";
                        $resultArray['schoolId'] = $requestSegments[0];
                        return $resultArray;
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        $resultArray['responseCode'] = 402;
                        return $resultArray;
                    }
                } else {
                    if ($requestSegments[1] == 'courses') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "coursesDetail";
                        $resultArray['schoolId'] = $requestSegments[0];
                        $resultArray['param'] = $requestSegments[2];
                        return $resultArray;
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        $resultArray['responseCode'] = 402;
                        return $resultArray;
                    }
                }
                asd($requestSegments, 2);
                break;

            case 4:
                if (empty($requestSegments[3])) {
                    if ($requestSegments[1] == 'courses') {
                        $resultArray['status'] = true;
                        $resultArray['callingApi'] = "coursesDetail";
                        $resultArray['schoolId'] = $requestSegments[0];
                        $resultArray['param'] = $requestSegments[2];
                        return $resultArray;
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        $resultArray['responseCode'] = 402;
                        return $resultArray;
                    }
                } else {
                    if ($requestSegments[3] == 'students') {
                        if ($requestSegments[1] == 'courses') {
                            $resultArray['status'] = true;
                            $resultArray['callingApi'] = "courseStudent";
                            $resultArray['schoolId'] = $requestSegments[0];
                            $resultArray['param'] = $requestSegments[2];
                            return $resultArray;
                        } else {
                            $resultArray['status'] = false;
                            $resultArray['responseMessage'] = "Unexpected Parameters";
                            $resultArray['responseCode'] = 402;
                            return $resultArray;
                        }
                    } else if ($requestSegments[3] == 'courses') {
                        if ($requestSegments[1] == 'students') {
                            $resultArray['status'] = true;
                            $resultArray['callingApi'] = "studentCourse";
                            $resultArray['schoolId'] = $requestSegments[0];
                            $resultArray['param'] = $requestSegments[2];
                            return $resultArray;
                        } else if ($requestSegments[1] == 'teachers') {
                            $resultArray['status'] = true;
                            $resultArray['callingApi'] = "teacherCourse";
                            $resultArray['schoolId'] = $requestSegments[0];
                            $resultArray['param'] = $requestSegments[2];
                            return $resultArray;
                        } else {
                            $resultArray['status'] = false;
                            $resultArray['responseMessage'] = "Unexpected Parameters";
                            $resultArray['responseCode'] = 402;
                            return $resultArray;
                        }
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        $resultArray['responseCode'] = 402;
                        return $resultArray;
                    }
                }
                break;

            case 5:
                if (empty($requestSegments[3])) {
                    $resultArray['status'] = false;
                    $resultArray['responseMessage'] = "Unexpected Parameters";
                    $resultArray['responseCode'] = 402;
                    return $resultArray;
                } else {
                    if ($requestSegments[3] == 'students') {
                        if ($requestSegments[1] == 'courses') {
                            $resultArray['status'] = true;
                            $resultArray['callingApi'] = "courseStudent";
                            $resultArray['schoolId'] = $requestSegments[0];
                            $resultArray['param'] = $requestSegments[2];
                            return $resultArray;
                        } else {
                            $resultArray['status'] = false;
                            $resultArray['responseMessage'] = "Unexpected Parameters";
                            $resultArray['responseCode'] = 402;
                            return $resultArray;
                        }
                    } else if ($requestSegments[3] == 'courses') {
                        if ($requestSegments[1] == 'students') {
                            $resultArray['status'] = true;
                            $resultArray['callingApi'] = "studentCourse";
                            $resultArray['schoolId'] = $requestSegments[0];
                            $resultArray['param'] = $requestSegments[2];
                            return $resultArray;
                        } else if ($requestSegments[1] == 'teachers') {
                            $resultArray['status'] = true;
                            $resultArray['callingApi'] = "teacherCourse";
                            $resultArray['schoolId'] = $requestSegments[0];
                            $resultArray['param'] = $requestSegments[2];
                            return $resultArray;
                        } else {
                            $resultArray['status'] = false;
                            $resultArray['responseMessage'] = "Unexpected Parameters";
                            $resultArray['responseCode'] = 402;
                            return $resultArray;
                        }
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        $resultArray['responseCode'] = 402;
                        return $resultArray;
                    }
                }
                break;

            default:
                $resultArray['status'] = false;
                $resultArray['responseMessage'] = "Unexpected Parameters";
                $resultArray['responseCode'] = 402;
                return $resultArray;
                break;
        }
    }

}

?>
