<?php 
class setting{

    public function __construct(){
    
    
    }
    
    public function checkDatabase($params){
        // check connection
        try{
            @$conn=mysql_connect($params['dbhost'],$params['dbusername'],$params['dbpassword']);
            if(!empty($conn)){
                if(empty($params['dbname'])){
                     return false;
                }else{ 
                    $res=mysql_select_db($params['dbname'],$conn);
                    if(!$res){
                        $sql = "CREATE DATABASE ".$params['dbname'];
                        $queres = mysql_query($sql);
                        if (!$queres) {
                            return false;
                        }
                        mysql_select_db($params['dbname'],$conn)or die(mysql_error());
                    }
                    return true;
                }

            }else{
                return false;
            }
        
        }catch(Exception $e)
        {
            return false;
        }
    }
    public function installProcess(){
        $configItems = parse_ini_file("config.ini");
        
        // connect db
        $conn=mysql_connect($configItems['dbhost'],$configItems['dbusername'],$configItems['dbpassword']);
        if($conn){
            $res=mysql_select_db($configItems['dbname'],$conn);
            if(!$res){
                $sql = "CREATE DATABASE ".$configItems['dbname'];
                mysql_query($sql);
                mysql_select_db($configItems['dbname'],$conn);
            }
            
            sleep(10);
            $tables = parse_ini_file("tables.ini");
            foreach($tables as $key=>$value){
                if($key != 'csvfilesdata'){
                    $dropSql = 'DROP TABLE IF EXISTS '.$key.';';    
                    mysql_query($dropSql) or die(mysql_error());
                    mysql_query($value) or die(mysql_error());
                    mysql_query('TRUNCATE TABLE '.$key.';');
                }else{
                    mysql_query($value) or die(mysql_error());
                }
            }
            // db creation done;
            
            // change db settings
            
            $fileContent = file_get_contents('../../app/config/database.php');
            $fileContent = str_replace("{{host}}",$configItems['dbhost'],$fileContent);
            $fileContent = str_replace("{{database}}",$configItems['dbname'],$fileContent);
            $fileContent = str_replace("{{username}}",$configItems['dbusername'],$fileContent);
            $fileContent = str_replace("{{password}}",$configItems['dbpassword'],$fileContent);
            file_put_contents('../../app/config/database.php',$fileContent);
            
            // db setting  complete
            
            // create email settings and upload settings
            $fileContentSetting = file_get_contents('../../app/config/installation.php');
            $emailArray = "'emailhost' =>'".$configItems['emailhost']."','emailport' =>'".$configItems['emailport']."','emailusername'=>'".$configItems['emailusername']."','emailpassword'=>'".$configItems['dbhost']."'";
           
            $fileContentSetting = str_replace("'{{emailsetting}}'",$emailArray,$fileContentSetting);
            $fileContentSetting = str_replace("{{uploadpathsetting}}",$configItems['uploadpath'],$fileContentSetting);
            file_put_contents('../../app/config/installation.php',$fileContentSetting);
            
            // upload and email setting  is completed  
            
            $responseArray = array("error"=>false,"responseMessage"=>"success",'username'=>$configItems['adminusername'],'email'=>$configItems['adminemail'],'password'=>$configItems['adminpassword'],"password_confirmation"=>$configItems['adminpassword']);
            return $responseArray;
            
        }else{
            return false;
        }
    }
    
}


?>