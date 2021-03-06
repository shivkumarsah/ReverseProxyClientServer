
// create our angular app and inject ngAnimate and ui-router
// =============================================================================
var app = angular.module('formApp', [
    'ngAnimate',
    'ngMessages',
    'ui.router'
]);

// configuring our routes
// =============================================================================
app.config( function ( $stateProvider, $urlRouterProvider ) {
    $stateProvider
        // route to show our basic form (/form)
        .state('form', {
            url: '/form',
            templateUrl: 'form.html',
            controller: 'formController'
        })

        // nested states
        // each of these sections will have their own view
        // url will be nested (/form/database)

        // url will be /form/welcome
        .state('form.welcome', {
            url: '/welcome',
            templateUrl: 'form-welcome.html'
        })

        .state('form.database', {
            url: '/database',
            templateUrl: 'form-database.html'
        })

        .state('form.proxy', {
            url: '/proxy',
            templateUrl: 'form-proxy.html'
        })

        // url will be nested (/form/admin)
        .state('form.admin', {
            url: '/admin',
            templateUrl: 'form-admin.html'
        })
        // url will be nested (/form/smpt)
        .state('form.smpt', {
            url: '/smpt',
            templateUrl: 'form-smpt.html'
        })

        // url will be /form/payment
        .state('form.install', {
            url: '/install',
            templateUrl: 'form-install.html'
        })
        
         // url will be /form/success
        .state('form.success', {
            url: '/success',
            templateUrl: 'form-success.html'
        });

    // catch all route
    // send users to the form page
    $urlRouterProvider.otherwise('/form/welcome');
});

app.value('formSteps', [
    {uiSref: 'form.welcome', valid: false},
    {uiSref: 'form.database', valid: false},
    {uiSref: 'form.proxy', valid: false},
    {uiSref: 'form.admin', valid: false},
    {uiSref: 'form.smpt', valid: true},
    {uiSref: 'form.install', valid: false},
    {uiSref: 'form.success', valid: false}
]);

app.run( ['$rootScope', '$state', 'formSteps', function($rootScope, $state, formSteps) {
    // Register listener to watch route changes
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            var canGoToStep = false;
            // only go to next if previous is valid
            var stateName = '';
            var toStateIndex = _.findIndex(formSteps, function(formStep) {
                return formStep.uiSref === toState.name;

            });

            //console.log('toStateIndex',toStateIndex);
            if(toStateIndex === 0) {
                canGoToStep = true;
            } else {
                canGoToStep = formSteps[toStateIndex - 1].valid;
                if( fromState.name == 'form.success' ) {
                    canGoToStep = false;
                }
            }
            //console.log('canGoToStep', toState.name, canGoToStep);

            // Stop state changing if the previous state is invalid
            if(!canGoToStep && ( fromState.name !== '' && fromState.name !== 'form.welcome' )) {
                // Abort going to step
                event.preventDefault();
            }
        });

    }

]);


app.service ( '$serverRequest', function ( $rootScope, $http ) {

    var $serverRequest = this;
    //For Import Data
    $serverRequest.install = {

        dbStatus: false,
        dbresponse: false,
        proxyStatus: false,
        proxyresponse: false,
        adminStatus: false,
        smptStatus: false,
        installStatus: false,
        installingStatus: false,
        installationState: false,
        responseData: {},

        checkCredential: function ( url, json ) {
            $http ({
                method: 'POST',
                url: url,
                data: json,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                $serverRequest.install.responseData = data;
                if( json.submitedtype === 'dbsetting' ) {
                    $serverRequest.install.dbStatus = data.error;
                    $serverRequest.install.dbresponse = data.responseMessage;
                    $rootScope.$broadcast('DB_STATUS');
                } else if( json.submitedtype === 'proxysetting' ){
                        $serverRequest.install.proxyStatus= data.error ;
                        $serverRequest.install.proxyresponse= data.responseMessage ;
                        $rootScope.$broadcast ( 'PROXY_STATUS' );
                } else if( json.submitedtype === 'adminsetting' ){
                    $serverRequest.install.adminStatus= data.error ;
                    $rootScope.$broadcast ( 'ADMIN_STATUS' );

                }else if( json.submitedtype === 'emailsetting' ){
                    $serverRequest.install.smptStatus= data.error ;
                    $rootScope.$broadcast ( 'SMPT_STATUS' );

                } else if( json.submitedtype === 'uploadsetting' ) {
                    $serverRequest.install.installStatus= data.error ;
                    $rootScope.$broadcast ( 'INSTALL_STATUS' );

                } else if( json.submitedtype === 'installsetting' ) {
                    $serverRequest.install.installingStatus= data ;
                    $rootScope.$broadcast ( 'INSTALLING_STATUS' );
                } else if( json.submitedtype === 'checkstate' ) {
                    $serverRequest.install.installationState= data.state ;
                    $rootScope.$broadcast ( 'CHECK_INSTALLATION_STATE' );
                }

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: post request failed' );
            });

        },

        redirectToLogin: function ( url, json ) {
            $http ({
                method: 'POST',
                url: url,
                data: json,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                $rootScope.$broadcast ( 'REDIRECT_TO_LOGIN' );
            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: post request failed' );
            });

        }

    };

});
