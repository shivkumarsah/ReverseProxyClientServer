<?php
//namespace Openroster\Resources;
/**
* @license http://www.apache.org/licenses/LICENSE-2.0
* Copyright [2014] [Robert Allen]
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*
* @package
* @category
* @subpackage
*/
use Swagger\Annotations as SWG;

/**
* @package
* @category
* @subpackage
*
* @SWG\Resource(
* apiVersion="1.0.0",
* swaggerVersion="1.2",
* resourcePath="/schoolsApi",
* description="Operations about schools",
* produces="['application/json','application/xml','text/plain','text/html']"
* )
*/

class RestApiSchoolsController extends BaseController
{

    public function __construct()
    {
        
    }
    
    public function index()
    {
        //echo"<pre>";print_r(json_decode('{"apiVersion":"1.0.0","swaggerVersion":"1.2","basePath":"http://petstore.swagger.wordnik.com/api","resourcePath":"/store","produces":["application/json"],"apis":[{"path":"/store/order/{orderId}","operations":[{"method":"GET","summary":"Find purchase order by ID","notes":"For valid response try integer IDs with value <= 5. Anything above 5 or nonintegers will generate API errors","type":"Order","nickname":"getOrderById","authorizations":{},"parameters":[{"name":"orderId","description":"ID of pet that needs to be fetched","required":true,"type":"string","paramType":"path","allowMultiple":false}],"responseMessages":[{"code":400,"message":"Invalid ID supplied"},{"code":404,"message":"Order not found"}]},{"method":"DELETE","summary":"Delete purchase order by ID","notes":"For valid response try integer IDs with value < 1000.  Anything above 1000 or nonintegers will generate API errors","type":"void","nickname":"deleteOrder","authorizations":{"oauth2":[{"scope":"write:pets","description":"write to your pets"}]},"parameters":[{"name":"orderId","description":"ID of the order that needs to be deleted","required":true,"type":"string","paramType":"path","allowMultiple":false}],"responseMessages":[{"code":400,"message":"Invalid ID supplied"},{"code":404,"message":"Order not found"}]}]},{"path":"/store/order","operations":[{"method":"POST","summary":"Place an order for a pet","notes":"","type":"void","nickname":"placeOrder","authorizations":{"oauth2":[{"scope":"write:pets","description":"write to your pets"}]},"parameters":[{"name":"body","description":"order placed for purchasing the pet","required":true,"type":"Order","paramType":"body","allowMultiple":false}],"responseMessages":[{"code":400,"message":"Invalid order"}]}]}],"models":{"Order":{"id":"Order","properties":{"id":{"type":"integer","format":"int64"},"petId":{"type":"integer","format":"int64"},"quantity":{"type":"integer","format":"int32"},"status":{"type":"string","description":"Order Status","enum":["placed"," approved"," delivered"]},"shipDate":{"type":"string","format":"date-time"}}}}}'));exit;
        
        $response400 = new \StdClass;
        $response400->code = 400;
        $response400->message = "Blank api key  or secret key";
        
        $response401 = new \StdClass;
        $response401->code = 401;
        $response401->message = "Invalid api key or secret key";
        
        $response402 = new \StdClass;
        $response402->code = 402;
        $response402->message = "Unexpected Parameters Values";
        
        $response403 = new \StdClass;
        $response403->code = 403;
        $response403->message = "Application Error";
        
        $response404 = new \StdClass;
        $response404->code = 404;
        $response404->message = "Requested School or entity does not exist";
        
        $response405 = new \StdClass;
        $response405->code = 405;
        $response405->message = "Association between school id and corresponding entity id is not correct";
        
        $response412 = new \StdClass;
        $response412->code = 412;
        $response412->message = "Association between school id and corresponding entity id is not correct";
        
        $response200 = new \StdClass;
        $response200->code = 200;
        $response200->message = "API returned result with data";
        
        
        $result = new \StdClass;
        $result->apiVersion = "1.0.0";
        $result->swaggerVersion = "1.2";
        $result->basePath = url()."/v1/LTI";
        $result->resourcePath = "/schools";
        $result->produces = array("application/json", "application/xml", "text/plain", "text/html");
        
        $schoolListapi = new \StdClass;
        $schoolListapi->path = "/schools";
        $schoolListOp = new \StdClass;
        $schoolListOp->method = "GET";
        $schoolListOp->summary = "List Schools";
        $schoolListOp->notes = "Can be done by Valid API Key";
        $schoolListOp->type = "void";
        $schoolListOp->nickname = "ListSchools";
        $schoolListOp->authorizations = new \StdClass;
        $schoolListOp->parameters = array();
        $schoolListOp->responseMessages = array($response400, $response401, $response402, $response403, $response200);
        $schoolListapi->operations = array($schoolListOp);
        
        $courseListapi = new \StdClass;
        $courseListapi->path = "/schools/{school_ID}/courses";
        $courseListOp = new \StdClass;
        $courseListOp->method = "GET";
        $courseListOp->summary = "List Courses Of a School";
        $courseListOp->notes = "Can be done by Valid API Key";
        $courseListOp->type = "void";
        $courseListOp->nickname = "ListSchoolCourses";
        $courseListOp->authorizations = new \StdClass;
        $courseListParam1 = new \StdClass;
        $courseListParam1->name = "school_ID";
        $courseListParam1->description = "schoolId of the school for which courses has to be fetched";
        $courseListParam1->required = true;
        $courseListParam1->type = "integer";
        $courseListParam1->format = "int64";
        $courseListParam1->paramType = "path";
        $courseListParam1->allowMultiple = false;
        $courseListParam1->minimum = "1.0";
        $courseListOp->parameters = array($courseListParam1);
        $courseListOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response200);
        $courseListapi->operations = array($courseListOp);
        
        $teacherListapi = new \StdClass;
        $teacherListapi->path = "/schools/{school_ID}/teachers";
        $teacherListOp = new \StdClass;
        $teacherListOp->method = "GET";
        $teacherListOp->summary = "List Teachers Of a School";
        $teacherListOp->notes = "Can be done by Valid API Key";
        $teacherListOp->type = "void";
        $teacherListOp->nickname = "ListSchoolTeachers";
        $teacherListOp->authorizations = new \StdClass;
        $teacherListParam1 = new \StdClass;
        $teacherListParam1->name = "school_ID";
        $teacherListParam1->description = "schoolId of the school for which teachers has to be fetched";
        $teacherListParam1->required = true;
        $teacherListParam1->type = "integer";
        $teacherListParam1->format = "int64";
        $teacherListParam1->paramType = "path";
        $teacherListParam1->allowMultiple = false;
        $teacherListParam1->minimum = "1.0";
        $teacherListOp->parameters = array($teacherListParam1);
        $teacherListOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response200);
        $teacherListapi->operations = array($teacherListOp);
        
        $studentListapi = new \StdClass;
        $studentListapi->path = "/schools/{school_ID}/students";
        $studentListOp = new \StdClass;
        $studentListOp->method = "GET";
        $studentListOp->summary = "List Students Of a School";
        $studentListOp->notes = "Can be done by Valid API Key";
        $studentListOp->type = "void";
        $studentListOp->nickname = "ListSchoolStudents";
        $studentListOp->authorizations = new \StdClass;
        $studentListParam1 = new \StdClass;
        $studentListParam1->name = "school_ID";
        $studentListParam1->description = "schoolId of the school for which students has to be fetched";
        $studentListParam1->required = true;
        $studentListParam1->type = "integer";
        $studentListParam1->format = "int64";
        $studentListParam1->paramType = "path";
        $studentListParam1->allowMultiple = false;
        $studentListParam1->minimum = "1.0";
        $studentListOp->parameters = array($studentListParam1);
        $studentListOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response200);
        $studentListapi->operations = array($studentListOp);
        
        $courseDetailapi = new \StdClass;
        $courseDetailapi->path = "/schools/{school_ID}/courses/{course_ID}";
        $courseDetailOp = new \StdClass;
        $courseDetailOp->method = "GET";
        $courseDetailOp->summary = "Course Detail of a Course identified by Id";
        $courseDetailOp->notes = "Can be done by Valid API Key";
        $courseDetailOp->type = "void";
        $courseDetailOp->nickname = "courseDetail";
        $courseDetailOp->authorizations = new \StdClass;
        $courseDetailParam1 = new \StdClass;
        $courseDetailParam1->name = "school_ID";
        $courseDetailParam1->description = "schoolId of the school for which course detail has to be fetched";
        $courseDetailParam1->required = true;
        $courseDetailParam1->type = "integer";
        $courseDetailParam1->format = "int64";
        $courseDetailParam1->paramType = "path";
        $courseDetailParam1->allowMultiple = false;
        $courseDetailParam1->minimum = "1.0";
        $courseDetailParam2 = new \StdClass;
        $courseDetailParam2->name = "course_ID";
        $courseDetailParam2->description = "course_ID of the course for which detail has to be fetched";
        $courseDetailParam2->required = true;
        $courseDetailParam2->type = "integer";
        $courseDetailParam2->format = "int64";
        $courseDetailParam2->paramType = "path";
        $courseDetailParam2->allowMultiple = false;
        $courseDetailParam2->minimum = "1.0";
        $courseDetailOp->parameters = array($courseDetailParam1, $courseDetailParam2);
        $courseDetailOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response412, $response200);
        $courseDetailapi->operations = array($courseDetailOp);
        
        $courseStudentapi = new \StdClass;
        $courseStudentapi->path = "/schools/{school_ID}/courses/{course_ID}/students";
        $courseStudentOp = new \StdClass;
        $courseStudentOp->method = "GET";
        $courseStudentOp->summary = "List of students for a course";
        $courseStudentOp->notes = "Can be done by Valid API Key";
        $courseStudentOp->type = "void";
        $courseStudentOp->nickname = "courseStudentList";
        $courseStudentOp->authorizations = new \StdClass;
        $courseStudentParam1 = new \StdClass;
        $courseStudentParam1->name = "school_ID";
        $courseStudentParam1->description = "schoolId of the school for which students for a course has to be fetched";
        $courseStudentParam1->required = true;
        $courseStudentParam1->type = "integer";
        $courseStudentParam1->format = "int64";
        $courseStudentParam1->paramType = "path";
        $courseStudentParam1->allowMultiple = false;
        $courseStudentParam1->minimum = "1.0";
        $courseStudentParam2 = new \StdClass;
        $courseStudentParam2->name = "course_ID";
        $courseStudentParam2->description = "course_ID of the course for which students has to be fetched";
        $courseStudentParam2->required = true;
        $courseStudentParam2->type = "integer";
        $courseStudentParam2->format = "int64";
        $courseStudentParam2->paramType = "path";
        $courseStudentParam2->allowMultiple = false;
        $courseStudentParam2->minimum = "1.0";
        $courseStudentOp->parameters = array($courseStudentParam1, $courseStudentParam2);
        $courseStudentOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response412, $response200);
        $courseStudentapi->operations = array($courseStudentOp);
        
        $studentCourseapi = new \StdClass;
        $studentCourseapi->path = "/schools/{school_ID}/students/{student_ID}/courses";
        $studentCourseOp = new \StdClass;
        $studentCourseOp->method = "GET";
        $studentCourseOp->summary = "List of courses for a student";
        $studentCourseOp->notes = "Can be done by Valid API Key";
        $studentCourseOp->type = "void";
        $studentCourseOp->nickname = "studentCourseList";
        $studentCourseOp->authorizations = new \StdClass;
        $studentCourseParam1 = new \StdClass;
        $studentCourseParam1->name = "school_ID";
        $studentCourseParam1->description = "schoolId of the school for which students for a course has to be fetched";
        $studentCourseParam1->required = true;
        $studentCourseParam1->type = "integer";
        $studentCourseParam1->format = "int64";
        $studentCourseParam1->paramType = "path";
        $studentCourseParam1->allowMultiple = false;
        $studentCourseParam1->minimum = "1.0";
        $studentCourseParam2 = new \StdClass;
        $studentCourseParam2->name = "student_ID";
        $studentCourseParam2->description = "student_ID of the student for which courses has to be fetched";
        $studentCourseParam2->required = true;
        $studentCourseParam2->type = "integer";
        $studentCourseParam2->format = "int64";
        $studentCourseParam2->paramType = "path";
        $studentCourseParam2->allowMultiple = false;
        $studentCourseParam2->minimum = "1.0";
        $studentCourseOp->parameters = array($studentCourseParam1, $studentCourseParam2);
        $studentCourseOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response412, $response200);
        $studentCourseapi->operations = array($studentCourseOp);
        
        $teacherCourseapi = new \StdClass;
        $teacherCourseapi->path = "/schools/{school_ID}/teachers/{teacher_ID}/courses";
        $teacherCourseOp = new \StdClass;
        $teacherCourseOp->method = "GET";
        $teacherCourseOp->summary = "List of courses for a teacher";
        $teacherCourseOp->notes = "Can be done by Valid API Key";
        $teacherCourseOp->type = "void";
        $teacherCourseOp->nickname = "studentCourseList";
        $teacherCourseOp->authorizations = new \StdClass;
        $teacherCourseParam1 = new \StdClass;
        $teacherCourseParam1->name = "school_ID";
        $teacherCourseParam1->description = "schoolId of the school for which courses for a teacher has to be fetched";
        $teacherCourseParam1->required = true;
        $teacherCourseParam1->type = "integer";
        $teacherCourseParam1->format = "int64";
        $teacherCourseParam1->paramType = "path";
        $teacherCourseParam1->allowMultiple = false;
        $teacherCourseParam1->minimum = "1.0";
        $teacherCourseParam2 = new \StdClass;
        $teacherCourseParam2->name = "teacher_ID";
        $teacherCourseParam2->description = "teacher_ID of the teacher for which courses has to be fetched";
        $teacherCourseParam2->required = true;
        $teacherCourseParam2->type = "integer";
        $teacherCourseParam2->format = "int64";
        $teacherCourseParam2->paramType = "path";
        $teacherCourseParam2->allowMultiple = false;
        $teacherCourseParam2->minimum = "1.0";
        $teacherCourseOp->parameters = array($teacherCourseParam1, $teacherCourseParam2);
        $teacherCourseOp->responseMessages = array($response400, $response401, $response402, $response403, $response404, $response412, $response200);
        $teacherCourseapi->operations = array($teacherCourseOp);
        
        
        
        $result->apis = array(
            $schoolListapi, 
            $courseListapi, 
            $teacherListapi, 
            $studentListapi, 
            $courseDetailapi, 
            $courseStudentapi,
            $studentCourseapi,
            $teacherCourseapi
        );
               
            
        return Response::json($result);
        
        
    }

     /**
* @SWG\Api(path="/schoolsApi/list",
* @SWG\Operation(
* method="GET",
* summary="Lists Schools",
* notes="",
* type="void",
* nickname="SchoolsList",
* authorizations={},
* )
* )
*/

    public function schools()
    {
        
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
                            return $resultArray;
                        }
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        return $resultArray;
                    }
                }
                break;

            case 5:
                if (empty($requestSegments[3])) {
                    $resultArray['status'] = false;
                    $resultArray['responseMessage'] = "Unexpected Parameters";
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
                            return $resultArray;
                        }
                    } else {
                        $resultArray['status'] = false;
                        $resultArray['responseMessage'] = "Unexpected Parameters";
                        return $resultArray;
                    }
                }
                break;

            default:
                $resultArray['status'] = false;
                $resultArray['responseMessage'] = "Unexpected Parameters";
                return $resultArray;
                break;
        }
    }

}

?>
