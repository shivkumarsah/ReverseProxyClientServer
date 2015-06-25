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
            $resArray[]="DB prot missing";
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
        $status=$settingObj ->checkDatabase($inputs);
        if($status){
            file_put_contents('config.ini',"");
            $handdler=fopen("config.ini","a+");
            fwrite($handdler,"dbhost = '".$inputs['dbhost']."';\n");
            fwrite($handdler,"dbport = '".$inputs['dbport']."';\n");
            fwrite($handdler,"dbusername = '".$inputs['dbusername']."';\n");
            fwrite($handdler,"dbpassword = '".$inputs['dbpassword']."';\n");
            fwrite($handdler,"dbname = '".$inputs['dbname']."';\n");
            $configItems = parse_ini_file("config.ini");
            $responseArray = array("error"=>false,"responseMessage"=>"success");
            echo  json_encode($responseArray);exit;
            break;
        }else{
            $responseArray = array("error"=>true,"responseMessage"=>"DB connection error");
            echo  json_encode($responseArray);exit;
            break;
        }
        break;
    case "adminsetting":
        $resArray = array();    
        if(empty($inputs['adminusername'])){
            $resArray[]="Admin user name missing";
        }
        if(empty($inputs['adminpassword'])){
            $resArray[]="Admin password missing";
        }
        if(empty($inputs['adminemail'])){
            $resArray[]="Admin email missing";
        }
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $handdler=fopen("config.ini","a+");
        fwrite($handdler,"adminusername = '".$inputs['adminusername']."';\n");
        fwrite($handdler,"adminpassword = '".$inputs['adminpassword']."';\n");
        fwrite($handdler,"adminemail = '".$inputs['adminemail']."';\n");
        $configItems = parse_ini_file("config.ini");
        $responseArray = array("error"=>false,"responseMessage"=>"success");
        echo  json_encode($responseArray);exit;
        break;
    case "emailsetting":
        $resArray = array();    
        if(empty($inputs['emailhost'])){
            $resArray[]="Email host missing";
        }
        if(empty($inputs['emailport'])){
            $resArray[]="Email port missing";
        }
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $handdler=fopen("config.ini","a+");
        fwrite($handdler,"emailhost = '".$inputs['emailhost']."';\n");
        fwrite($handdler,"emailport = '".$inputs['emailport']."';\n");
        fwrite($handdler,"emailusername = '".$inputs['emailusername']."';\n");
        fwrite($handdler,"emailpassword = '".$inputs['emailpassword']."';\n");
        $configItems = parse_ini_file("config.ini");
        $responseArray = array("error"=>false,"responseMessage"=>"success");
        echo  json_encode($responseArray);exit;
        break;
    case "uploadsetting":
        $resArray = array();    
        if(empty($inputs['uploadpath'])){
            $resArray[]="upload missing";
        }
        if(!empty($resArray)){
            $responseArray = array("error"=>true,"responseMessage"=>"Missing fields","filedlist"=>$resArray);
            echo  json_encode($responseArray);exit;
            break;
        }
        $handdler=fopen("config.ini","a+");
        fwrite($handdler,"uploadpath = '".$inputs['uploadpath']."';\n");
        $configItems = parse_ini_file("config.ini");
        $responseArray = array("error"=>false,"responseMessage"=>"success");
        echo  json_encode($responseArray);exit;
        break;
    case "installsetting":
        $settingObj = new setting();
        $responseArray = $status=$settingObj ->installProcess($inputs);
        echo  json_encode($responseArray);exit;
        break;
    default :
        $responseArray = array("error"=>true,"responseMessage"=>"No Action Found");
        echo  json_encode($responseArray);exit;
}
?>