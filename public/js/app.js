var validationApp = angular.module('validationApp', []);

// create angular controller
validationApp.controller('loginController', function( $scope, $element, $sce ) {

    $scope.errorMsg = {
        userName : 'The username field is required.',
        password : 'The password field is required.'
    };

    $scope.trustedHtml = function ( errorText ) {
        return $sce.trustAsHtml( errorText );
    };

    window.validateForm = function () {
        var x = document.forms["loginForm"]["username"].value;
        var y = document.forms["loginForm"]["password"].value;
        if ( x === null || x === "" || y === null || y === "" ) {
            return false;
        }
    };

    $('input#inputPassword').attr('type','password');
    $scope.submitted = false
    // function to submit the form after all validation has occurred            
    $scope.submitLoginForm = function( isValid ) {
        $('.alert').remove();
        $scope.submitted = true;
        // check to make sure the form is completely valid
        
        if (isValid) {
            
        }else {

        }

    };
    $element.bind("keydown keypress", function( event ) {
        $scope.submitted = false;
        $('.alert').remove();
    });

});

validationApp.directive('placeholder', function($timeout){
    var i = document.createElement('input');
    if ('placeholder' in i) {
        return {}
    }
    return {
        link: function(scope, elm, attrs){
            $('input#inputPassword').attr('type','text');
            $timeout(function(){
                elm.val(attrs.placeholder);
                elm.bind('focus', function(){
                    if (elm.val() == attrs.placeholder) {
                        elm.val('');
                        if( attrs.id == 'inputPassword'){
                            $('input#inputPassword').attr('type','password');
                        }
                    }
                }).bind('blur', function(){
                    if (elm.val() == '') {
                        elm.val(attrs.placeholder);
                        if( attrs.id == 'inputPassword'){
                            $('input#inputPassword').attr('type','text');
                        }
                    }
                });
            });
        }
    }
});