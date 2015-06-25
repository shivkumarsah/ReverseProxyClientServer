<?php

use Swagger\Swagger;

class ApiDocsController extends BaseController
{

    public function index()
    {

        $swagger = new Swagger('/var/www/openroster', array('/var/www/openroster/vendor'));
        header('Content-Type: application/json');
        echo $swagger->getResource('/v1/LTI/schools', array('output' => 'json'));
    }

    /**
     * @SWG\Info(
     * title="Swagger Sample App",
     * description="This is a sample server Petstore server. You can find out more about Swagger
      at <a href=""http://swagger.wordnik.com"">http://swagger.wordnik.com</a> or on irc.freenode.net, #swagger. For this sample,
      you can use the api key ""special-key"" to test the authorization filters",
     * termsOfServiceUrl="http://helloreverb.com/terms/",
     * contact="apiteam@wordnik.com",
     * license="Apache 2.0",
     * licenseUrl="http://www.apache.org/licenses/LICENSE-2.0.html"
     * )
     *
     * @SWG\Authorization(
     * type="oauth2",
     * @SWG\Scope(scope="write:pets", description="Modify pets in your account"),
     * @SWG\Scope(scope="read:pets", description="Read your pets"),
     * grantTypes={
     * "implicit": {
     * "loginEndpoint": { "url": "http://petstore.swagger.wordnik.com/api/oauth/dialog" },
     * "tokenName": "access_token"
     * },
     * "authorization_code": {
     * "tokenRequestEndpoint": {
     * "url": "http://petstore.swagger.wordnik.com/api/oauth/requestToken",
     * "clientIdName": "client_id",
     * "clientSecretName": "client_secret"
     * },
     * "tokenEndpoint": {
     * "url": "http://petstore.swagger.wordnik.com/api/oauth/token",
     * "tokenName": "auth_code"
     * }
     * }
     * }
     * )
     */
    public function apidocs()
    {
        $result = new \StdClass;
        $result->apiVersion = "1.0.0";
        $result->swaggerVersion = "1.2";
        
        $schoolapi = new \StdClass;
        $schoolapi->path = "/schoolsApi";
        $schoolapi->description = "Schools Api operations";
        
        $result->apis[] = $schoolapi;
        
        $result->authorizations = new \StdClass;
        $result->authorizations->oauth2 = new \StdClass;
        $result->authorizations->oauth2->type = "oauth2";
        
        $oauthReadScope = new \StdClass;
        $oauthReadScope->scope = "read:schools";
        $oauthReadScope->description = "Reads Schools API data";
        
        $result->authorizations->oauth2->scopes[] = $oauthReadScope;
        
        $grantTypes = new \StdClass;
        
        $implicit = new \StdClass;
        $implicit->loginEndpoint =  new \StdClass;
        $implicit->loginEndpoint->url = url()."/oauth-login";
        $implicit->tokenName = "access_token";
        
        $grantTypes->implicit = $implicit;
        
        
        $auth = new \StdClass;
        $auth->tokenRequestEndpoint =  new \StdClass;
        $auth->tokenRequestEndpoint->url = url()."/oauth-request-token";
        $auth->tokenRequestEndpoint->clientIdName = "client_id";
        $auth->tokenRequestEndpoint->clientSecretName = "client_secret";
        
        $auth->tokenEndpoint =  new \StdClass;
        $auth->tokenEndpoint->url = url()."/oauth-token";
        $auth->tokenEndpoint->tokenName = "auth_code";
        
        $grantTypes->authorization_code = $auth;
        
        $result->authorizations->oauth2->grantTypes = $grantTypes;
        
        $info = new \StdClass;
        $info->title = "OpenRosters REST API to fetch Data";
        $info->description = "OpenRosters REST API to fetch Data";
        //$info->termsOfServiceUrl = url();
        $info->termsOfServiceUrl = "javascript:void(0)";
        //$info->contact = "prashantprive@gmail.com";
        $info->license = "Apache 2.0";
        $info->licenseUrl = "http://www.apache.org/licenses/LICENSE-2.0.html";
        $result->info = $info;
        
        
        
        //echo"<pre>";print_r(json_decode('{"apiVersion":"1.0.0","swaggerVersion":"1.2","apis":[{"path":"/pet","description":"Operations about pets"},{"path":"/user","description":"Operations about user"},{"path":"/store","description":"Operations about store"}],"authorizations":{"oauth2":{"type":"oauth2","scopes":[{"scope":"write:pets","description":"Modify pets in your account"},{"scope":"read:pets","description":"Read your pets"}],"grantTypes":{"implicit":{"loginEndpoint":{"url":"http://petstore.swagger.wordnik.com/api/oauth/dialog"},"tokenName":"access_token"},"authorization_code":{"tokenRequestEndpoint":{"url":"http://petstore.swagger.wordnik.com/api/oauth/requestToken","clientIdName":"client_id","clientSecretName":"client_secret"},"tokenEndpoint":{"url":"http://petstore.swagger.wordnik.com/api/oauth/token","tokenName":"auth_code"}}}}},"info":{"title":"Swagger Sample App","description":"This is a sample server Petstore server.  You can find out more about Swagger \n    at <a href=\"http://swagger.wordnik.com\">http://swagger.wordnik.com</a> or on irc.freenode.net, #swagger.  For this sample,\n    you can use the api key \"special-key\" to test the authorization filters","termsOfServiceUrl":"http://helloreverb.com/terms/","contact":"apiteam@wordnik.com","license":"Apache 2.0","licenseUrl":"http://www.apache.org/licenses/LICENSE-2.0.html"}}'));exit;
        return Response::json($result);
        //return {"apiVersion":"1.0.0","swaggerVersion":"1.2","apis":[{"path":"/pet","description":"Operations about pets"},{"path":"/user","description":"Operations about user"},{"path":"/store","description":"Operations about store"}],"authorizations":{"oauth2":{"type":"oauth2","scopes":[{"scope":"write:pets","description":"Modify pets in your account"},{"scope":"read:pets","description":"Read your pets"}],"grantTypes":{"implicit":{"loginEndpoint":{"url":"http://petstore.swagger.wordnik.com/api/oauth/dialog"},"tokenName":"access_token"},"authorization_code":{"tokenRequestEndpoint":{"url":"http://petstore.swagger.wordnik.com/api/oauth/requestToken","clientIdName":"client_id","clientSecretName":"client_secret"},"tokenEndpoint":{"url":"http://petstore.swagger.wordnik.com/api/oauth/token","tokenName":"auth_code"}}}}},"info":{"title":"Swagger Sample App","description":"This is a sample server Petstore server.  You can find out more about Swagger \n    at <a href=\"http://swagger.wordnik.com\">http://swagger.wordnik.com</a> or on irc.freenode.net, #swagger.  For this sample,\n    you can use the api key \"special-key\" to test the authorization filters","termsOfServiceUrl":"http://helloreverb.com/terms/","contact":"apiteam@wordnik.com","license":"Apache 2.0","licenseUrl":"http://www.apache.org/licenses/LICENSE-2.0.html"}};
    }

}

?>
