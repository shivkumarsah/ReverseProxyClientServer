<?php
include_once("setting.php");
$postedData=(file_get_contents("php://input"));
$inputs = json_decode($postedData,true);
switch($inputs['submitedtype']){
    case "dbsetting":
        $resArray = array();
        if(empty($inputs['dbhost'])){
            $resArray[]="DB host missing";
        }
        if(empty($inputs['dbport'])){
            $resArray[]="DB port missing";
        }
        if(empty($inputs['dbname'])){
            $resArray[]="DB name missing";
        }
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $settingObj = new setting();
        $dbresponse = $settingObj->installDatabase($inputs);
        if( !$dbresponse['error'] ){
            $configrespons = $settingObj->checkConfig($inputs);
            if( $configrespons ) {
                $settingObj->updateState('form.database');
                $responseArray = array("error"=>false,"responseMessage"=>"success");
            } else {
                $responseArray = array("error"=>true,"responseMessage"=>"fail");
            }
            echo  json_encode($responseArray);exit;
            break;
        }else{
            // update state.ini
            $responseArray = array("error"=>true, "responseMessage"=>$response['responseMessage']);
            echo  json_encode($responseArray);exit;
            break;
        }
        break;
    case "proxysetting":
        $resArray = array();
        if(empty($inputs['baseUrl'])){
            $resArray[]="Base Url missing";
        }
        if(empty($inputs['confPath'])){
            $resArray[]="Configuration path missing";
        }
        if(empty($inputs['nginxPath'])){
            $resArray[]="Nginx service path missing";
        }
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $settingObj = new setting();
        $configresponse = $settingObj->checkConfig($inputs);
        if( $configresponse ) {
            $settingObj->updateState('form.proxy');
            $responseArray = array("error"=>false,"responseMessage"=>"success");
        } else {
            $responseArray = array("error"=>true,"responseMessage"=>"fail");
        }
        echo  json_encode($responseArray);exit;
        break;
    case "adminsetting":
        $resArray = array();   
        if(empty($inputs['domainapikey'])){
            $resArray[]="Please enter api key.";
        }
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $settingObj = new setting();
        $configrespons = $settingObj->checkConfig($inputs);
        if( $configrespons ) {
            $settingObj->updateState('form.admin');
            $responseArray = array("error"=>false,"responseMessage"=>"success");
        } else {
            $responseArray = array("error"=>true,"responseMessage"=>"fail");
        }
        echo  json_encode($responseArray);exit;
        break;
    case "emailsetting":
        $resArray = array();    
        if( !$inputs['smtpskipped'] ) {
            if(empty($inputs['emailhost'])){
                $resArray[]="Email host missing";
            }
            if(empty($inputs['emailport'])){
                $resArray[]="Email port missing";
            }
            if(empty($inputs['emailusername'])){
                $resArray[]="Username missing";
            }
            if(empty($inputs['emailpassword'])){
                $resArray[]="Password missing";
            }
            if(empty($inputs['fromname'])){
                $resArray[]="From name missing";
            }
            if(empty($inputs['fromemail'])){
                $resArray[]="From Email missing";
            }
        }
        
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $settingObj = new setting();
        $configrespons = $settingObj->checkConfig($inputs);
        if( $configrespons ) {
            $settingObj->updateState('form.smpt');
            $responseArray = array("error"=>false,"responseMessage"=>"success");
        } else {
            $responseArray = array("error"=>true,"responseMessage"=>"fail");
        }
        echo  json_encode($responseArray);exit;
        break;
    case "uploadsetting":
        $settingObj = new setting();
        $settingObj->updateState('form.install');
        $configrespons = $settingObj->checkConfig($inputs);
        if( $configrespons ) {
            $responseArray = array("error"=>false,"responseMessage"=>"success");
        } else {
            $responseArray = array("error"=>true,"responseMessage"=>"fail");
        }
        echo  json_encode($responseArray);exit;
        break;
    case "installsetting":
        $settingObj = new setting();
        $responseArray = $settingObj->installProcess();
        $settingObj->installationComplete();
        $settingObj->updateState('');
        echo  json_encode($responseArray);exit;
        break;
    case "checkstate":
        $settingObj = new setting();
        $responseArray = $settingObj->getState();
        $responseArray['url']           = $settingObj->getUrl();
        $responseArray['serverName']    = $_SERVER['HTTP_HOST'];
        $responseArray['urlPort']       = $_SERVER['SERVER_PORT'];
        $responseArray['baseProtocol']  = $settingObj->getProtocol();
        $responseArray['domainApiKey']  = md5(uniqid(mt_rand(), true));
        $responseArray['path'] = $_SERVER['DOCUMENT_ROOT'].'/../configs/';
        $responseArray['confPath'] = realpath($_SERVER['DOCUMENT_ROOT'].'/../configs/');

        //echo "<pre>"; print_r($responseArray); print_r($_SERVER); exit;
        echo  json_encode($responseArray);exit;
        break;
    default :
        $responseArray = array("error"=>true,"responseMessage"=>"No Action Found");
        echo  json_encode($responseArray);exit;
}
?>