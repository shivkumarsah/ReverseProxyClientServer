<?php
error_reporting(0);
// file permissions

//echo "<pre>"; print_r($_SERVER['HTTP_HOST']); exit;

$root_path      = $_SERVER['DOCUMENT_ROOT'];
$host_url       = $_SERVER['HTTP_HOST'];
$mode           = 0777;

$install_folder_path    = $root_path . "/" . "install" . "/";
$config_folder_path     = $root_path . "/" . "../" . "app/config/";
$config_folder_path     = $root_path . "/" . "install" . "/config/";
$querylogs_folder_path  = $root_path . "/" . "../" . "app/storage/logs/query-logs";

$config_file_path       = $install_folder_path . "config.ini";
$state_file_path        = $install_folder_path . "state.txt";
$install_file_path      = $install_folder_path . "install.php";

$database_file_path     = $config_folder_path . "database.php";
$proxy_file_path        = $config_folder_path . "proxy.php";
$mail_file_path         = $config_folder_path . "mail.php";
$domainapikey_file_path = $config_folder_path . "domainapikey.php";
$appval_file_path       = $config_folder_path . "appvals.php";


$isAllWritable  = true;
$errors         = array();

///chmod for all the directory and files 
chmod($config_file_path, $mode);
chmod($state_file_path, $mode);
chmod($install_file_path, $mode);
chmod($database_file_path, $mode);
chmod($mail_file_path, $mode);
chmod($domainapikey_file_path, $mode);
chmod($appval_file_path, $mode);


if (!is_writable($config_file_path)) {
    $errors['config'] = "Please give 0777 permissions to '" . $config_file_path . "' file to continue installation.";
    $isAllWritable = false;
}

if (!is_writable($state_file_path)) {
    $errors['state'] = "Please give 0777 permissions to  '" . $state_file_path . "' file to continue installation.";
    $isAllWritable = false;
}
// create query log directory
if (!is_dir($querylogs_folder_path)) {
    mkdir($querylogs_folder_path, 0777, true);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/darkly/bootstrap.min.css">
    <link rel="stylesheet" href='//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css'>
    <link rel="stylesheet" href="css/style-install.css">
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.3/angular-messages.js"></script>
    <script src="../js/angular-ui-router.min.js"></script>
    <script src="../js/angular-animate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.js"></script>
    <?php if ($isAllWritable) : ?>
        <script src="app/app.js"></script>
        <script src="app/maincontroller.js"></script>
    <?php endif; ?>
</head>
<body ng-app="formApp">
<!-- views will be injected here -->
<div class="container">
    <?php if (!$isAllWritable) : ?>
    <div ui-view="" class="ng-scope">
        <div class="row ng-scope">
            <div id="form-container"
            <div
            ="" class="col-sm-6 col-sm-offset-3">
            <form id="signup-form" class="ng-pristine ng-valid">
                <div ui-view="" id="form-views" class="ng-scope">
                    <div class="form-group ng-scope">
                        <div class="form-group row">
                            <!--<div ng-include="'logo.html'"></div>-->
                            <div class="col-xs-6" align="center" style="width: 100%;">
                                <img src="/img/full-logo-new.png" style="width: 300px;" alt="ClassLink">
                            </div>
                        </div>
                        <div style="text-align: center;padding-bottom: 35px;">
                            <label>Welcome to the ClassLink Proxy installation.</label>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                <div class="isa_error">
                                    <i class="fa fa-times-circle"></i>
                                    <?php echo "Please give permissions to all files and directories described in a manual before starting installation." ?>
                                    <?php foreach($errors as $error ) : ?>
                                        <p><i class="fa fa-times-circle"></i><?php echo $error; ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-xs-offset-3">
                            <a href="" class="btn btn-block btn-info btn-padding-css">
                                Refresh&nbsp;&nbsp;<span class="glyphicon glyphicon-refresh"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php else: ?>
    <div ui-view></div>
<?php endif; ?>
</div>
</body>
</html>