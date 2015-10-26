// our controller for the form
// =============================================================================
app.controller('formController', function( $scope, $serverRequest, $state, formSteps, $location ) {
    // we will store all of our form data in this object
    $scope.formData = {};
    $scope.databaseError = false;
    $scope.databaseErrorElement = '';
    $scope.adminError = false;
    $scope.smptError = false;
    $scope.installError = false;
    $scope.formStepSubmitted=false;
    $scope.formData.folderPath = '' ;
    $scope.passwordCheckbox = false;
    $scope.inputType = 'password';
    $scope.errorUserName = false;
    $scope.errorDomainApiKey = false;
    $scope.errorPassword = false;
    $scope.errorEmail = false;
    $scope.errorSmptFromEmail = false;
    $scope.errorSmptFromName = false;
    $scope.errorCSVpath = false;
    $scope.errorInstallation = '';
    $scope.adminurl = 'http://openrosters2.icreondemoserver.com/';
    $scope.dbProcessing = false;

    $scope.keydown = function( type ){
        if ( type === 'password' ){
            $scope.errorPassword = false;
        } else if( type === 'username' ){
            $scope.errorUserName = false;
        } else if( type === 'email') {
            $scope.errorEmail = false;
        } else if ( type === 'csvpath' ) {
            $scope.errorCSVpath = false;
        } else if ( type === 'domainapikey' ) {
            $scope.errorDomainApiKey = false;
        } else if ( type === 'fromname' ) {
            $scope.errorSmptFromName = false;
        } else if ( type === 'fromemail' ) {
            $scope.errorSmptFromEmail = false;
        }
    };

    var nextState=function(currentState) {
        switch (currentState) {
            case 'form.welcome':
                return 'form.database';
                break;
            case 'form.database':
                return 'form.proxy';
                break;
            case 'form.proxy':
                return 'form.admin';
                break;
            case 'form.admin':
                return 'form.install';
                //return 'form.smpt';
                break;
            case 'form.smpt':
                return 'form.install';
                break;
            case 'form.install':
                return 'form.success';
                break;
            default:
                alert('Did not match any switch');
        }

    };

    var updateValidityOfCurrentStep=function(updatedValidity) {
        var currentStateIndex = _.findIndex(formSteps, function(formStep) {
            return formStep.uiSref === $state.current.name;
        });

        formSteps[currentStateIndex].valid = updatedValidity;
    };

    $scope.checkInfo = function ( currentUrl ){
        // Write code here!
    };

    $scope.hideShowPassword = function(){
        if ($scope.inputType == 'password') {
            $scope.inputType = 'text';
        } else {
            $scope.inputType = 'password';
        }
    };

    $scope.checkFieldValidation = function ( json ){
        var validInput = true;
        
        if( typeof json.username !== "undefined") {
            if( json.username.length < 6 ){
                validInput = false;
                $scope.errorUserName = true;
            }
        }
        
        if( typeof json.domainapikey !== "undefined") {
            if( json.domainapikey.length < 8 ){
                validInput = false;
                $scope.errorDomainApiKey = true;
            }
        }
        if( typeof json.password !== "undefined") {
            if( json.password.length < 6 ){
                validInput = false;
                $scope.errorPassword = true;
            }
        }
        
        if( typeof json.email !== "undefined"){
            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            if ( !filter.test( json.email )) {
                validInput = false;
                $scope.errorEmail = true;
            }
        }
		
	if( typeof json.fromemail !== "undefined"){
            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            if ( !filter.test( json.fromemail )) {
                validInput = false;
                $scope.errorSmptFromEmail = true;
            }
        }
		
	if( typeof json.fromname !== "undefined"){
            if( json.fromname.length <= 0 ){
                validInput = false;
                $scope.errorSmptFromName = true;
            }
        }


        return validInput;

    };

    $scope.goToNextSection=function(isSkipped) {

        var currentUrl = $state.current.name ;
        console.log(currentUrl.toString());

        if ( currentUrl.toString() === 'form.database' ){
            var isValid = true;
            document.getElementById('loading').style.display = 'block';
            if( isValid ){
                $scope.dbProcessing = true;
                var jsonObj = {
                    'dbhost' : $scope.formData.dbHost ? $scope.formData.dbHost : '',
                    'dbport' : $scope.formData.dbPort ? $scope.formData.dbPort : '',
                    'dbname' : $scope.formData.dbName ? $scope.formData.dbName : '',
                    'dbusername' : $scope.formData.dbUserName ? $scope.formData.dbUserName : '',
                    'dbpassword' : $scope.formData.dbPassword ? $scope.formData.dbPassword : '',
                    'submitedtype' : 'dbsetting'
                };

                $serverRequest.install.checkCredential( 'installation.php', jsonObj );
                $scope.$on ( 'DB_STATUS', function () {
                    document.getElementById('loading').style.display = 'none';
                    $scope.databaseError = $serverRequest.install.dbStatus;
                    $scope.databaseErrorElement = $serverRequest.install.dbresponse;
                    $scope.responseData = $serverRequest.install.responseData;
                    $scope.callAfterSuccess( !$serverRequest.install.dbStatus );
                });
            }
        } else if ( currentUrl.toString() === 'form.proxy' ){
            var isValid = true;
            document.getElementById('loading').style.display = 'block';
            if( isValid ){
                var jsonObj = {
                    'baseProtocol' : $scope.formData.baseProtocol,
                    'baseUrl' : $scope.formData.baseUrl ? $scope.formData.baseUrl : '',
                    'baseUrlPort' : $scope.formData.baseUrlPort ? $scope.formData.baseUrlPort : '',
                    'certificatePem' : $scope.formData.certificatePem ? $scope.formData.certificatePem : '',
                    'certificateKey' : $scope.formData.certificateKey ? $scope.formData.certificateKey : '',
                    'confPath' : $scope.formData.confPath ? $scope.formData.confPath : '',
                    'nginxPath' : $scope.formData.nginxPath ? $scope.formData.nginxPath : '',
                    'submitedtype' : 'proxysetting'
                };
                $serverRequest.install.checkCredential( 'installation.php', jsonObj );
                $scope.$on ( 'PROXY_STATUS', function () {
                    document.getElementById('loading').style.display = 'none';
                    $scope.proxyError = $serverRequest.install.proxyStatus;
                    $scope.proxyErrorElement = $serverRequest.install.proxyresponse;
                    $scope.responseData = $serverRequest.install.responseData;
                    $scope.callAfterSuccess( !$serverRequest.install.proxyStatus );
                });
            }
        } else if ( currentUrl.toString() === 'form.admin' ) {
            
            var isValid = true;
            var json = {
                'domainapikey' : $scope.formData.domainApiKey ? $scope.formData.domainApiKey : '',
                'domainAddress' : $scope.formData.domainAddress ? $scope.formData.domainAddress : ''
            };

            isValid = $scope.checkFieldValidation( json );
            
            if( isValid ) {
                var jsonObj = {
                    'domainapikey' : $scope.formData.domainApiKey ? $scope.formData.domainApiKey : '',
                    'domainAddress' : $scope.formData.domainAddress ? $scope.formData.domainAddress : '',
                    'submitedtype': 'adminsetting'
                };

                $serverRequest.install.checkCredential('installation.php', jsonObj);
                $scope.$on('ADMIN_STATUS', function () {
                    $scope.adminError = $serverRequest.install.adminStatus;
                    $scope.callAfterSuccess(!$serverRequest.install.adminStatus);
                });
            }

        } else if ( currentUrl.toString() === 'form.smpt' ) {

            var isValid = true;
            var json = {
                'username' : $scope.formData.smptName ? $scope.formData.smptName : '',
                'password' : $scope.formData.smptPassword ? $scope.formData.smptPassword : '',
                'fromname': $scope.formData.smptFromName ? $scope.formData.smptFromName : '',
                'fromemail': $scope.formData.smptFromEmail ? $scope.formData.smptFromEmail : ''
            }
            if(!isSkipped) {
                isValid = $scope.checkFieldValidation( json );
            }
            
            if( isValid ) {
                var jsonObj = {
                    'emailhost': $scope.formData.smptServer ? $scope.formData.smptServer : '',
                    'emailport': $scope.formData.smptPort ? $scope.formData.smptPort : '',
                    'emailusername': $scope.formData.smptName ? $scope.formData.smptName : '',
                    'emailpassword': $scope.formData.smptPassword ? $scope.formData.smptPassword : '',
                    'fromname': $scope.formData.smptFromName ? $scope.formData.smptFromName : '',
                    'fromemail': $scope.formData.smptFromEmail ? $scope.formData.smptFromEmail : '',
                    'smtpskipped':isSkipped ? 'yes' : 'no',
                    'submitedtype': 'emailsetting'
                };

                $serverRequest.install.checkCredential('installation.php', jsonObj);
                $scope.$on('SMPT_STATUS', function () {
                    $scope.smptError = $serverRequest.install.smptStatus;
                    $scope.callAfterSuccess(!$serverRequest.install.smptStatus);

                    //if ( !$serverRequest.install.smptStatus ) {
                    //    $scope.processForm($scope);
                    //}
                });
            }
        } else if ( currentUrl.toString() === 'form.install' ) {

            var jsonObj = {
                'submitedtype' : 'uploadsetting'
            };

            if( jsonObj.submitedtype !== '' ){
                document.getElementById('loading').style.display = 'block';
                $serverRequest.install.checkCredential( 'installation.php', jsonObj );
                $scope.$on ( 'INSTALL_STATUS', function () {
                    $scope.installError = $serverRequest.install.installStatus;
                    if ( !$serverRequest.install.installStatus ) {
                        $scope.processForm($scope);
                    }
                });
            } else {
                $scope.errorCSVpath = true;
            }
        }
    };

    $scope.callAfterSuccess = function ( isFormValid ) {
        $scope.formStepSubmitted = true;
        if( isFormValid ) {
            $scope.passwordCheckbox = false;
            $scope.inputType = 'password';
            $scope.errorUserName = false;
            $scope.errorPassword = false;
            // reset this for next form
            $scope.formStepSubmitted = false;
            // mark the step as valid so we can navigate to it via the links
            updateValidityOfCurrentStep(true /*valid */);

            $state.go(nextState($state.current.name));
        } else {
            // mark the step as valid so we can navigate to it via the links
            updateValidityOfCurrentStep(false /*not valid */);
        }
    };
    
    // function to process the form
    $scope.processForm = function($scope) {
        var jsonObj = {
          'submitedtype' : 'installsetting'
        };
        $serverRequest.install.checkCredential( 'installation.php', jsonObj );
        $scope.$on ( 'INSTALLING_STATUS', function () {
            document.getElementById('loading').style.display = 'none';
            if ( $serverRequest.install.installingStatus.error ) {
                $scope.installError = $serverRequest.install.installingStatus.error;
                $scope.errorInstallation = $serverRequest.install.installingStatus.responseMessage;
            } else {
                updateValidityOfCurrentStep(true);
                $state.go(nextState($state.current.name));
            }
        });
    };
    
    $scope.checkState = function() {
        var jsonObj = {
          'submitedtype' : 'checkstate'
        };
        $serverRequest.install.checkCredential( 'installation.php', jsonObj );
        $scope.$on ( 'CHECK_INSTALLATION_STATE', function () {
            var data = $serverRequest.install.responseData;
            console.log("$serverRequest.install.responseData = ", data);

            $scope.formData.domainAddress   = data.url;
            $scope.formData.domainApiKey    = data.domainApiKey
            $scope.formData.baseUrl         = data.serverName;
            $scope.formData.baseUrlPort     = data.urlPort;
            $scope.formData.baseProtocol    = data.baseProtocol;
            $scope.formData.confPath        = data.confPath;
            $scope.formData.nginxPath       = data.nginxPath; //'/var/www/html/php_root';

            $scope.formData.certificatePem = data.certificatePem;
            $scope.formData.certificateKey = data.certificateKey;

            if( $serverRequest.install.installationState != $state.current.name ) {
                $state.current.name = $serverRequest.install.installationState;
                updateValidityOfCurrentStep(true);
                $state.go(nextState($state.current.name));
            }
        });
    }
    $scope.checkState();
});