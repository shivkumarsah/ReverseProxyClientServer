<?php

error_reporting(0);

class setting {

    public function __construct() {
        
    }

    public function installDatabase($params) {
        try {
            $response['error'] = true;
            $mysqli = new mysqli($params['dbhost'], $params['dbusername'], $params['dbpassword']);
            if (!$mysqli->connect_errno) {
                if (empty($params['dbname'])) {
                    throw new Exception("DB name missing");
                } else {
                    if (!$mysqli->select_db($params['dbname'])) {
                        $sql = "CREATE DATABASE " . $params['dbname'];
                        if ($mysqli->query($sql) === FALSE) {
                            throw new Exception("Error creating database: " . $mysqli->error);
                        }
                        $mysqli->select_db($params['dbname']);
                    }
                    sleep(10);
                    // db creation; 
                    $tables = parse_ini_file("dbtables.ini");
                    foreach ($tables as $key => $value) {
                        if ($mysqli->multi_query($value)) {
                            do {
                                $mysqli->use_result();
                            } while ($mysqli->more_results() && $mysqli->next_result());
                        } else {
                            throw new Exception("Multi query failed: (" . $mysqli->errno . ") " . $mysqli->error);
                        }
                    }
                    $response['error'] = false;
                    $response['responseMessage'] = "Database has been installed successfully.";
                    return $response;
                }
            } else {
                throw new Exception("Connect failed: " . $mysqli->connect_error);
            }
        } catch (Exception $e) {
            $response['responseMessage'] = $e->getMessage();
            return $response;
        }
    }

    public function checkConfig($inputs) {
        try {
            switch ($inputs['submitedtype']) {
                case "dbsetting":
                    $this->updateConfig($inputs['submitedtype'], $inputs);
                    break;
                case "adminsetting":
                    $this->updateConfig($inputs['submitedtype'], $inputs);
                    break;
                case "emailsetting":
                    $this->updateConfig($inputs['submitedtype'], $inputs);
                    break;
                case "uploadsetting":
                    $this->updateConfig($inputs['submitedtype'], $inputs);
                    break;
                default :
                    throw new Exception("No Action Found");
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateConfig($case, $inputs) {
        $configItems = parse_ini_file("config.ini");
        foreach ($inputs as $inputkey => $inputval) {
            if ($inputval != $case) {
                $configItems[$inputkey] = $inputval;
            }
        }
        file_put_contents('config.ini', "");
        $handdler = fopen("config.ini", "a+");
        foreach ($configItems as $configkey => $configval) {
            if ($configval != $case) {
                fwrite($handdler, $configkey . " = '" . $configval . "';\n");
            }
        }
        fclose($handle);
        return true;
    }

    public function updateState($state) {
        try {
            file_put_contents('state.txt', "");
            $handdler = fopen("state.txt", "a+");
            fwrite($handdler, $state);
            fclose($handle);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getState() {
        try {
            $content = file_get_contents('state.txt');
            $state = trim($content);
            if (!empty($state)) {
                return array('state' => $state);
            }
            throw new Exception("State Missing");
        } catch (Exception $e) {
            return array('state' => 'form.welcome');
        }
    }

    public function installationComplete() {
        try {
            $json = array();
            $json['Installation']['status'] = "1";
            $json['Installation']['installation_date'] = date("Y-m-d H:i:s");
            $newJsonString = json_encode($json);
            $icr = file_put_contents('installationStatus.json', $newJsonString);
            if ($icr == "" || $icr == false) {
                throw new Exception("Permissions required to '/public/install/installationStatus.json'");
            }
            $responseArray = array("error" => false, "responseMessage" => "success");
            return $responseArray;
        } catch (Exception $e) {
           $response['error'] = true;
           $response['responseMessage'] = $e->getMessage();
           return $response;
        }
    }

    public function installProcess() {
        try {
            $configItems = parse_ini_file("config.ini");

            // change db settings
            $fileContent = file_get_contents('../../app/config/database.php');
            $fileContent = str_replace("{{mysql-host}}", $configItems['dbhost'], $fileContent);
            $fileContent = str_replace("{{mysql-database}}", $configItems['dbname'], $fileContent);
            $fileContent = str_replace("{{mysql-username}}", $configItems['dbusername'], $fileContent);
            $fileContent = str_replace("{{mysql-password}}", $configItems['dbpassword'], $fileContent);
            $dbr = file_put_contents('../../app/config/database.php', $fileContent);
            if ($dbr == "" || $dbr == false) {
                throw new Exception("Permissions required to  '/app/config/database.php'");
            }
            // db setting  complete
            // school domain api key in the config file 
            $schooldomainapikey = $configItems["schooldomainapikey"];
            $domainapikeycontent = '<?php return array( "domain_api_key" => ' . "'" . $schooldomainapikey . "'" . ');';
            $dmr = file_put_contents('../../app/config/domainapikey.php', $domainapikeycontent);
            if ($dmr == "" || $dmr == false) {
                throw new Exception("Permissions required to '/app/config/domainapikey.php'");
            }
            // school domain api key complete 
            
            // create email settings and upload settings
            if ($configItems['smtpskipped'] == 'no') {
                $emailfileContentSetting = file_get_contents('../../app/config/mail.php');
                $emailfileContentSetting = str_replace("{{emailhost}}", $configItems['emailhost'], $emailfileContentSetting);
                $emailfileContentSetting = str_replace("'{{emailport}}'", $configItems['emailport'], $emailfileContentSetting);
                $emailfileContentSetting = str_replace("{{emailusername}}", $configItems['emailusername'], $emailfileContentSetting);
                $emailfileContentSetting = str_replace("{{emailpassword}}", $configItems['emailpassword'], $emailfileContentSetting);
                $emailfileContentSetting = str_replace("{{fromname}}", $configItems['fromname'], $emailfileContentSetting);
                $emailfileContentSetting = str_replace("{{fromemail}}", $configItems['fromemail'], $emailfileContentSetting);
                $emr = file_put_contents('../../app/config/mail.php', $emailfileContentSetting);
                if ($emr == "" || $emr == false) {
                    throw new Exception("Permissions required to '/app/config/mail.php'");
                }
            }

            // create csv import storage folder
            $importfileContentSetting = file_get_contents('../../app/config/appvals.php');
            $importfileContentSetting = str_replace("{{file_import_path}}", $configItems['uploadpath'] . "/", $importfileContentSetting);
            $importfileContentSetting = str_replace("{{file_import_dir_name}}", basename($configItems['uploadpath']), $importfileContentSetting);
            $amr = file_put_contents('../../app/config/appvals.php', $importfileContentSetting);
            if ($amr == "" || $amr == false) {
                throw new Exception("Permissions required to '/app/config/appvals.php'");
            }
            // create import file
            mkdir("../../app/storage/" . $configItems["uploadpath"], 0777, true);

            // upload and email setting  is complete  
            $responseArray = array("error" => false, "responseMessage" => "success");
            return $responseArray;
        } catch (Exception $e) {
            $response['error'] = true;
            $response['responseMessage'] = $e->getMessage();
            return $response;
        }
    }
}

?>