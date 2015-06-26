// our controller for the form
// =============================================================================
app.controller('formController', function( $scope, $serverRequest, $state, formSteps, $location ) {

    // we will store all of our form data in this object
    $scope.formData = {};
    $scope.databaseError = false;
    $scope.adminError = false;
    $scope.smptError = false;
    $scope.installError = false;
    $scope.formStepSubmitted=false;
    $scope.formData.folderPath = '' ;
    $scope.passwordCheckbox = false;
    $scope.inputType = 'password';
    $scope.errorUserName = false;
    $scope.errorPassword = false;
    $scope.errorEmail = false;
    $scope.errorCSVpath = false;

    $scope.keydown = function( type ){

        if ( type === 'password' ){
            $scope.errorPassword = false;
        } else if( type === 'username' ){
            $scope.errorUserName = false;
        } else if( type === 'email') {
            $scope.errorEmail = false;
        } else if ( type === 'csvpath' ) {
            $scope.errorCSVpath = false;
        }

    };

    var nextState=function(currentState) {
        switch (currentState) {
            case 'form.welcome':
                return 'form.database';
                break;
            case 'form.database':
                return 'form.admin';
                break;
            case 'form.admin':
                return 'form.smpt';
                break;
            case 'form.smpt':
                return 'form.install';
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
        if ($scope.inputType == 'password')
            $scope.inputType = 'text';
        else
            $scope.inputType = 'password';
    };

    $scope.checkFieldValidation = function ( json ){
        var validInput = true;

        if( json.username.length < 6 ){
            validInput = false;
            $scope.errorUserName = true;
        }

        if( json.password.length < 6 ){
            validInput = false;
            $scope.errorPassword = true;
        }

        if( typeof json.email !== "undefined"){
            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            if ( !filter.test( json.email )) {
                validInput = false;
                $scope.errorEmail = true;
            }
        }

        return validInput;

    };

    $scope.goToNextSection=function() {

        var currentUrl = $state.current.name ;

        if ( currentUrl.toString() === 'form.database' ){
            var isValid = true;
            /*var json = {
                'username' : $scope.formData.dbUserName ? $scope.formData.dbUserName : '',
                'password' : $scope.formData.dbPassword ? $scope.formData.dbPassword : ''
            };
            isValid = $scope.checkFieldValidation( json );*/

            if( isValid ){
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
                    $scope.databaseError = $serverRequest.install.dbStatus;
                    $scope.callAfterSuccess( !$serverRequest.install.dbStatus );
                });
            }

        } else if ( currentUrl.toString() === 'form.admin' ) {

            var isValid = true;
            var json = {
                'username' : $scope.formData.adminName ? $scope.formData.adminName : '',
                'password' : $scope.formData.adminPassword ? $scope.formData.adminPassword : '',
                'email' : $scope.formData.adminEmail ? $scope.formData.adminEmail : ''
            };

            isValid = $scope.checkFieldValidation( json );

            if( isValid ) {
                var jsonObj = {
                    'adminusername': $scope.formData.adminName ? $scope.formData.adminName : '',
                    'adminpassword': $scope.formData.adminPassword ? $scope.formData.adminPassword : '',
                    'adminemail': $scope.formData.adminEmail ? $scope.formData.adminEmail : '',
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
                'password' : $scope.formData.smptPassword ? $scope.formData.smptPassword : ''
            }

            isValid = $scope.checkFieldValidation( json );

            if( isValid ) {
                var jsonObj = {
                    'emailhost': $scope.formData.smptServer ? $scope.formData.smptServer : '',
                    'emailport': $scope.formData.smptPort ? $scope.formData.smptPort : '',
                    'emailusername': $scope.formData.smptName ? $scope.formData.smptName : '',
                    'emailpassword': $scope.formData.smptPassword ? $scope.formData.smptPassword : '',
                    'submitedtype': 'emailsetting'
                };

                $serverRequest.install.checkCredential('installation.php', jsonObj);
                $scope.$on('SMPT_STATUS', function () {
                    $scope.smptError = $serverRequest.install.smptStatus;
                    $scope.callAfterSuccess(!$serverRequest.install.smptStatus);
                });
            }
        } else if ( currentUrl.toString() === 'form.install' ) {

            var jsonObj = {
                'uploadpath' : $scope.formData.folderPath ? $scope.formData.folderPath : '',
                'submitedtype' : 'uploadsetting'
            };

            if( jsonObj.uploadpath !== '' ){
                document.getElementById('loading').style.display = 'block';
                $serverRequest.install.checkCredential( 'installation.php', jsonObj );
                $scope.$on ( 'INSTALL_STATUS', function () {
                    $scope.installError = $serverRequest.install.installStatus;
                    if ( !$serverRequest.install.installStatus ) {
                        $scope.processForm( );
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
    $scope.processForm = function() {
        var jsonObj = {
          'submitedtype' : 'installsetting'
        };

        $serverRequest.install.checkCredential( 'installation.php', jsonObj );
        $scope.$on ( 'INSTALLING_STATUS', function () {
            if ( $serverRequest.install.installingStatus.error ) {
                alert('Error on installation!');
            } else {
                $scope.varProtocol = $location.protocol();
                $scope.varHost = $location.host();
                var rData = $serverRequest.install.installingStatus;
                var jsonObj = {
                    'email' : rData.email,
                    'password_confirmation' : rData.password_confirmation,
                    'username' : rData.username,
                    'password' : rData.password
                };

                var redirectUrl = $scope.varProtocol+'://'+$scope.varHost+'/users/signup';
                $serverRequest.install.redirectToLogin( redirectUrl, jsonObj );

            }

        });
    };

    $scope.$on ( 'REDIRECT_TO_LOGIN', function () {
        $scope.vProtocol = $location.protocol();
        $scope.vHost = $location.host();
        window.location.href = $scope.vProtocol+'://'+$scope.vHost;
    });

});