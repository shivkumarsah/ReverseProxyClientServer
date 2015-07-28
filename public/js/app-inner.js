var proxyApp = angular.module('proxyApp', [
    'ui.bootstrap',
    'restangular',
    'ngRoute',
    'angularFileUpload',
    'ngDialog',
    'flash'
]);

proxyApp.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

proxyApp.controller( 'appCtrl', function( $scope, $serverRequest, $clientExportData, ngDialog ) {

    $scope.isCollapsed = true;
    /*$scope.currentRouteVal = document.getElementById('currentRoute').value;
    if( $scope.currentRouteVal === 'schools' ||  $scope.currentRouteVal === 'teachers' ||
        $scope.currentRouteVal === 'students' ||  $scope.currentRouteVal === 'courses' ||
        $scope.currentRouteVal === 'enrollments' ){
        $scope.isCollapsed = false;
    } else {
        $scope.isCollapsed = true;
    }*/
    window.onkeydown = function( event ) {
        if ( event.keyCode === 27 ) {
            ngDialog.closeAll();
        }
    };
});

proxyApp.controller("dashboardCtrl", function( $scope, $serverRequest ) {

    $scope.graphData = [];
    $serverRequest.graphs.pullGraphData();
    $scope.showCount = false;

    $scope.$on ( 'GRAPHS_DATA', function () {

        window.plot_data = [];
        $scope.graphData = $serverRequest.graphs.graphLocalStorage;
        $scope.showCount = true;
        $scope.getMonth = function( val ){
            var arrayMonth =['', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']
            return arrayMonth[val];
        };

        _.each( $scope.graphData.graphApi, function ( eleGraph ) {
            $scope.monthname = $scope.getMonth( eleGraph.month );
            window.plot_data.push( [ $scope.monthname, eleGraph.total ]);
        });

        window.barChartsData = [
            [1,$scope.graphData.studentCount],
            [2,$scope.graphData.teacherCount],
            [3,$scope.graphData.courseCount],
            [4,$scope.graphData.schoolCount]
        ];

        Openroster.init();
        Index.initCharts();
        Index.initBarCharts();

        $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 80 ) );
        $("#viewPanel").niceScroll({
            cursorcolor:"#6498c8",
            autohidemode: false,
            cursorwidth: '8px'
        });

    });


});

proxyApp.controller( 'passwordChangeCtrl', function( $scope, ngDialog ) {

    $scope.clickSetting = function () {
        ngDialog.open({
            template: 'secondDialog',
            controller: 'changePwdController',
            className: 'ngdialog-theme-default ngdialog-theme-custom secondDialog'
        });
    };
});

proxyApp.controller( 'changePwdController', function( $scope, $element, $serverRequest, $location ){

    $scope.message = "";
    $scope.respnseDataMsg = "";
    $scope.user = {
        oldpassword: "",
        newpassword: "",
        confirmPassword: ""
    };

    $scope.field = {
        errorMsg:false,
        oPassword: "",
        nPassword: "",
        cPassword: "",
        comparePassword: ""
    };

    $scope.mgsEmpty = function () {
        $scope.field.errorMsg = false;
        $scope.field.oPassword = "";
        $scope.field.nPassword = "";
        $scope.field.cPassword = "";
        $scope.field.comparePassword = "";
    };

    $scope.changePassword = function() {

        if( $scope.user.oldpassword ) {
            $scope.field.oPassword ="";
        } else {
            $scope.field.oPassword = "Empty field!";
            $scope.field.errorMsg = true;
        }

        if( $scope.user.newpassword ) {
            $scope.field.nPassword ="";
        } else {
            $scope.field.nPassword = "Empty field!";
            $scope.field.errorMsg = true;
        }

        if( $scope.user.confirmPassword ) {
            $scope.field.cPassword ="";
        } else {
            $scope.field.cPassword = "Empty field!";
            $scope.field.errorMsg = true;
        }

        if( $scope.user.newpassword !=="" && $scope.user.confirmPassword !=="" && $scope.user.oldpassword !== "" ){
            if( $scope.user.newpassword === $scope.user.confirmPassword ) {
                $scope.field.comparePassword ="";

                var json ={
                    'oldpassword': $scope.user.oldpassword,
                    'password': $scope.user.newpassword ,
                    'password_confirmation': $scope.user.confirmPassword
                }
                $scope.varProtocol = $location.protocol();
                $scope.varHost = $location.host();
                $serverRequest.passwordChange.pushChangePassword($scope.varProtocol+'://'+ $scope.varHost+ '/users/changepassword', json );

            } else {
                $scope.message = "";
                $scope.field.comparePassword = "Password confirmation doesn't match Password.";
                $scope.field.errorMsg = true;
            }
        }

        $scope.$on ( 'PASSWORD_CHANGED', function () {
            $scope.respnseDataMsg = $serverRequest.passwordChange.responseData.responseMessage;
        });

        //Trigger close button after password change
        if ( !$scope.field.errorMsg ) {
            //$scope.closeThisDialog('Cancel');
        }

        $element.bind("keydown keypress", function( ) {
            $scope.mgsEmpty();

        });

    };

});

proxyApp.controller( 'importPanelCtrl', function( $scope, $serverRequest, $sce, ngDialog, $http, $q, $rootScope, $window, $filter ){

    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 270 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px'
    });

    $scope.reportBtnText = ['missing','waiting to verify', 'in-progress', 'complete'];
    $scope.reportItems = [];

    //Pull request to server for getting the import data status.
    $serverRequest.report.pullPageData();

    //Button click functionality.
    $scope.selectFileUpload = function () {
        $scope.showMsg = false;
        $scope.showVerifyMsg = '';
        ngDialog.open({
            template: 'firstDialog',
            controller: 'FileUploadCtrl',
            className: 'ngdialog-theme-default ngdialog-theme-custom'
        });

    };

    $scope.showLogs = function( element ) {
        var logFileid = element.file_id;
        ngDialog.open({
            template: 'importlog/'+logFileid,
            className: 'ngdialog-theme-default ngdialog-theme-custom ngdialog-import-log'
        });

    };

    $scope.selectVerify = function () {

        $scope.showMsg = false;
        _.each ( $serverRequest.report.uploadData.LocalStorage, function ( element ) {
            if ( element.file_available.toString() === '0' && !$scope.showMsg ){
                $scope.showMsg = true;
            }
        });

        if( $scope.showMsg ){
            $scope.showVerifyMsg = 'Please upload missing file.';
        } else {
            $scope.showVerifyMsg = '';
            $scope.callServerForVerify();
        }

    };
    $scope.updateUploadedDataVerify = function( json ){
        var eleIndex;

        var query = _.find ( $serverRequest.report.uploadData.LocalStorage, function ( element, index ) {
            eleIndex = index;
            return json.data.file_id.toString() === element.file_id.toString();
        });

        if ( query ){
            $serverRequest.report.uploadData.LocalStorage[eleIndex].file_available = json.data.data.file_available;
            $serverRequest.report.uploadData.LocalStorage[eleIndex].updated_at = json.data.data.updated_at;
        }
        $rootScope.$broadcast ( 'UPLOADED_DATA' );
    };

    $scope.callServerForVerify = function () {
        $scope.currentRequest = 0;
        $scope.deferred = $q.defer();
        makeNextServerRequest();

        function makeNextServerRequest() {
            // Do whatever you need with the array item.
            $scope.postData = $serverRequest.report.uploadData.LocalStorage[$scope.currentRequest];
            $scope.localVar = $serverRequest.report.uploadData.LocalStorage[$scope.currentRequest];

            $scope.postUrl = 'import'+$scope.localVar.file_name.substr(0, $scope.localVar.file_name.lastIndexOf(".")).toLowerCase();

            if( $scope.localVar.file_available.toString() === '1' ){

                $serverRequest.report.uploadData.LocalStorage[$scope.currentRequest].file_available = '2';
                $rootScope.$broadcast ( 'UPLOADED_DATA' );

                $http.post( $scope.postUrl, $scope.postData )
                    .then( function ( data ){
                        //Update uploadData array after server response and broadcast.
                        $scope.updateUploadedDataVerify( data );
                        $rootScope.$broadcast ( 'UPLOADED_DATA' );
                        $scope.currentRequest++;
                        // Continue if there are more items.
                        if ($scope.currentRequest < $serverRequest.report.uploadData.LocalStorage.length){
                            makeNextServerRequest();
                        } else {
                            // Resolve the promise otherwise.
                            $scope.deferred.resolve();
                        }
                    });

            } else {
                $scope.currentRequest++;
                if ($scope.currentRequest < $serverRequest.report.uploadData.LocalStorage.length){
                    makeNextServerRequest();
                } else {
                    // Resolve the promise otherwise.
                    $scope.deferred.resolve();
                }
            }
        }
        // return a promise for the completed requests
        return $scope.deferred.promise;

    };

    $scope.selectDownloadTemplate = function () {

        $window.open('csv-format.zip', '_blank', '');
        $scope.showMsg = false;
        $scope.showVerifyMsg = '';
    };

    $scope.$on ( 'UPLOADED_DATA', function () {
        //TODO: Need to be convert for loop too _each loop
        for( var i = 0 ; i < $serverRequest.report.uploadData.LocalStorage.length ; i++ ){
            var btnTextVal = $serverRequest.report.uploadData.LocalStorage[i].file_available;
            $serverRequest.report.uploadData.LocalStorage[i].btnText = $scope.reportBtnText[btnTextVal];

        }
        $scope.reportItems = $serverRequest.report.uploadData.LocalStorage;

    });

    // Bind conditional date in the import data view.
    $scope.trustedHtml = function ( element ) {
        if( element.file_available.toString() === '0' ) {
            return $sce.trustAsHtml('-');
        } else {
            /*var date_time = element.updated_at.split(' ');
            var formatedData = date_time[0].split('-');
            return $sce.trustAsHtml( formatedData[1]+'/'+formatedData[2]+'/'+formatedData[0] );*/
            return $sce.trustAsHtml( element.updated_at );
        }

    };

    // For inject btn class in import panel.
    $scope.whatClassIsIt = function( element ) {

        switch ( element.file_available.toString() ) {
            case '0':   // for missing file.
                if( element.file_is_optional.toString() === '1' ) {
                    return 'label-default'; // for optional missing file.
                }else {
                    return 'label-danger'; //missing file.
                }
            case '1': // waiting to verify.
                return 'label-warning';
            case '2': // verifying In-progress.
                return 'label-primary';
            case '3': // complete.
                return 'label-success';
            default: //missing file.
                return 'label-danger';
        }

    };

});

proxyApp.controller('FileUploadCtrl', function ( $scope, ngDialog, FileUploader, $rootScope, $serverRequest ) {

    $scope.validUploadFile = ['schools.csv', 'students.csv', 'teachers.csv', 'courses.csv', 'enrollments.csv'];
    var uploader = $scope.uploader = new FileUploader({
        url: 'importcsv'
    });

    $scope.closeUploadDialog = function (){
        ngDialog.close();
    };

    // FILTERS
    uploader.filters.push({
        name: 'customFilter',
        fn: function(item /*{File|FileLikeObject}*/, options) {
            return this.queue.length < 10;
        }
    });

// CALLBACKS
    uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
        console.info('onWhenAddingFileFailed', item, filter, options);
    };

    uploader.onAfterAddingFile = function(fileItem) {
        $scope.wrongFileMsg = '';
        var index;
        var query = _.find ( $scope.validUploadFile, function ( element, index ) {
            index = index;
            return fileItem.file.name.toLowerCase() === element.toLowerCase();
        });
        if ( !query ){
            uploader.queue = _.filter( uploader.queue, function ( element ) {
                return element.file.name.toLowerCase() !== fileItem.file.name.toLowerCase();
            });
            $scope.wrongFileMsg = 'Invalid file "' + fileItem.file.name +'".'
        } else {
            $scope.wrongFileMsg = '';
        }
        console.info('onAfterAddingFile', fileItem);
    };

    uploader.onAfterAddingAll = function(addedFileItems) {
        console.info('onAfterAddingAll', addedFileItems);
    };

    uploader.onBeforeUploadItem = function(item) {
        console.info('onBeforeUploadItem', item);
    };

    uploader.onProgressItem = function(fileItem, progress) {
        console.info('onProgressItem', fileItem, progress);
    };

    uploader.onProgressAll = function(progress) {
        console.info('onProgressAll', progress);
    };

    uploader.onSuccessItem = function(fileItem, response, status, headers) {
        fileItem.isSuccess = response.status.toString() === '1';
        fileItem.isError = response.status.toString() === '0';
        console.info('onSuccessItem', fileItem, response, status, headers);
    };

    uploader.onErrorItem = function(fileItem, response, status, headers) {
        console.info('onErrorItem', fileItem, response, status, headers);
    };

    uploader.onCancelItem = function(fileItem, response, status, headers) {
        console.info('onCancelItem', fileItem, response, status, headers);
    };

    uploader.onCompleteItem = function(fileItem, response, status, headers) {
        if( response.status.toString() === '1' ){
            $scope.updateUploadedData( response );
        }
        console.info('onCompleteItem', fileItem, response, status, headers);
    };

    uploader.onCompleteAll = function() {
        console.info('onCompleteAll');
    };

    console.info('uploader', uploader);

    $scope.updateUploadedData = function( json ){
        var eleIndex;
        var query = _.find ( $serverRequest.report.uploadData.LocalStorage, function ( element, index ) {
            eleIndex = index;
            return json.data.file_id.toString() === element.file_id.toString();
        });

        if ( query ){
            $serverRequest.report.uploadData.LocalStorage[eleIndex].file_available = json.status.toString();
            $serverRequest.report.uploadData.LocalStorage[eleIndex].updated_at = json.data.updated_at;
        }
        $rootScope.$broadcast ( 'UPLOADED_DATA' );
    }

});

proxyApp.config(function($routeProvider, RestangularProvider) {

    RestangularProvider.setBaseUrl('data');
    RestangularProvider.setRequestInterceptor(function(elem, operation, what) {

        if (operation === 'put') {
            elem._id = undefined;
            return elem;
        }
        return elem;
    });

});


//------------ Directive --------------------//
proxyApp.directive('sortBy', function() {
    return {
        templateUrl: 'sort-by-html',
        restrict: 'E',
        transclude: true,
        replace: true,
        scope: {
            sortdir: '=',
            sortedby: '=',
            sortvalue: '@',
            onsort: '='
        },
        link: function(scope, element, attrs) {
            scope.sort = function() {
                if (scope.sortedby == scope.sortvalue)
                    scope.sortdir = scope.sortdir == 'asc' ? 'desc' : 'asc';
                else {
                    scope.sortedby = scope.sortvalue;
                    scope.sortdir = 'asc';
                }
                scope.onsort(scope.sortedby, scope.sortdir);
            }
        }
    };
});

proxyApp.directive('onBlurChange', function($parse) {
    return function(scope, element, attr) {
        var fn = $parse(attr['onBlurChange']);
        var hasChanged = false;
        element.on('change', function(event) {
            hasChanged = true;
        });

        element.on('blur', function(event) {
            if (hasChanged) {
                scope.$apply(function() {
                    fn(scope, {$event: event});
                });
                hasChanged = false;
            }
        });
    };
});

proxyApp.directive('onEnterBlur', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if (event.which === 13) {
                element.blur();
                event.preventDefault();
            }
        });
    };
});
//------------ Factories --------------------//
proxyApp.factory( 'api', function ( Restangular ) {

    //prepend /api before making any request with restangular
    // RestangularProvider.setBaseUrl('/data');
    return {
        schools: {
            search: function ( query ) {
                return Restangular.all( 'schools' ).getList( query );
            }
        },
        teachers: {
            search: function ( query ) {
                return Restangular.all( 'teachers' ).getList( query );
            }
        },
        students: {
            search: function ( query ) {
                return Restangular.all( 'students' ).getList( query );
            }
        },
        courses: {
            search: function ( query ) {
                return Restangular.all( 'courses' ).getList( query );
            }
        },
        enrollments: {
            search: function ( query ) {
                return Restangular.all( 'enrollments' ).getList( query );
            }
        }
    };
});

// School preview data controller.
proxyApp.controller('SchoolCtrl', function( $scope, api, $element, $clientExportData ) {

    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 160 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px'
    });
    $scope.selectedPage = 1;
    $scope.totalPages = 0;
    $scope.schoolsCount = 0;
    $scope.itemsPerPage = 10;
    $scope.changePageIndex = 1;
    $scope.inValidPage = false;
    $scope.showRecord = 'record';
    $scope.disabledNavigation = false;
    $scope.headers =
        [
            { title: 'School Id', value: 'school_id' },
            { title: 'School Name', value: 'school_name' }
        ];
    $scope.myAction = '';
    $scope.listAction = [
        {id:'1', name:'Export to Excel'},
        {id:'2', name:'Export to CSV'},
        {id:'3', name:'Export to XML'}
    ];
    $scope.listNoRecord = [ '10', '20', '50', '100', '150', 'All'  ];

    //default criteria that will be sent to the server
    $scope.filterCriteria = {
        pageNumber: 1,
        sortDir: 'asc',
        sortedBy: 'school_id',
        itemsPerPage: '10'
    };

    $element.bind("keydown keypress", function(event) {
        $scope.inValidPage = false;
    });

    $scope.getNumberOfRecord = function() {
        if( $scope.filterCriteria.itemsPerPage.toString()  === 'All'){
            $scope.disabledNavigation = true;
        } else {
            $scope.disabledNavigation = false;
        }
        $scope.inValidPage = false;
        $scope.changePageIndex = 1;
        $scope.fetchResult();
    };

    $scope.sortingClass = function( element ){
        if( $scope.filterCriteria.sortedBy === element.value ){
            if($scope.filterCriteria.sortDir === 'asc'){
                return 'sorting_asc';
            }else{
                return 'sorting_desc';
            }
        } else {
            return 'sorting';
        }
    };

    $scope.previousPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) - 1;
    };

    $scope.nextPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) + 1;
    };

    //watch change index value. if condition satisfied then trigger the function "selectPage".
    $scope.$watch( function() {
        return $scope.changePageIndex;
    }, function( newVal, oldVal ) {

        if ( newVal !== oldVal) {
            if ( newVal > $scope.totalPages  ||  newVal.toString() < '1'){
                $scope.itemsPerPage = '10';
                $scope.changePageIndex = 1;
                $scope.inValidPage = true;
            } else {
                if ( oldVal > $scope.totalPages  ||  oldVal.toString() < '1'){

                } else {
                    $scope.inValidPage = false;
                }
                $scope.selectPage( newVal );
            }
        }

    });

    //The function that is responsible of fetching the result from the server and setting the grid to the new result
    $scope.fetchResult = function() {
        return api.schools.search($scope.filterCriteria).then(function( data ) {
            $scope.schools = data.items;
            $scope.totalPages = data.totalPages;
            $scope.schoolsCount = data.totalItems;
            if( $scope.schoolsCount.toString() > '1' ){
                $scope.showRecord = 'records';
            } else {
                $scope.showRecord = 'record';
            }
        }, function() {
            $scope.schools = [];
            $scope.totalPages = 0;
            $scope.schoolsCount = 0;
        });
    };

    $scope.$watch( function(){
        return $scope.totalPages;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            $('#viewPanel').getNiceScroll().onResize();
        }
    });

    $("#viewPanel").mouseover(function() {
        $('#viewPanel').getNiceScroll().onResize();
    });

    //called when navigate to another page in the pagination
    $scope.selectPage = function( page ) {
        $scope.filterCriteria.pageNumber = page;
        $scope.fetchResult();
    };


    //Will be called when filtering the grid, will reset the page number to one
    $scope.filterResult = function() {
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    //call back function that we passed to our custom directive sortBy, will be called when clicking on any field to sort
    $scope.onSort = function(sortedBy, sortDir) {
        $scope.inValidPage = false;
        $scope.filterCriteria.sortDir = sortDir;
        $scope.filterCriteria.sortedBy = sortedBy;
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    $scope.changeAction = function() {
        //console.log( $scope.myAction );
    };

    $scope.exportData = function () {

        var fileName ='';
        if( $scope.myAction.id.toString() === '1' ) {
            fileName = "Report.xls";
            $scope.exportToExcel( fileName );
        } else if( $scope.myAction.id.toString() === '2' ) {
            fileName = "Report.csv";
            $scope.exportToCSV( fileName );
        } else {
            fileName = "Report.xml";
            $scope.exportToXML( fileName );
        }

    };

    $scope.exportToCSV = function ( fileName ) {
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.CSV.appendToHeader( $scope.headers );

        _.each( $scope.schools, function( element ){
            tempDataStorage = tempDataStorage + element.school_id + ',' + element.school_name + '\r\n';
        });
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToExcel = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.EXCEL.appendToHeader( $scope.headers );

        _.each( $scope.schools, function( element ){
            tempDataStorage = tempDataStorage + '<tr>' +
            '<td>' + element.school_id + '</td>' +
            '<td>' + element.school_name + '</td>' +
            '</tr>';
        });
        tempDataStorage += '</table>';
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToXML = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.XML.appendToHeader( $scope.headers );

        _.each( $scope.schools, function( element, index ){
            tempDataStorage += '<row id="'+index+'">';
            tempDataStorage += '<column id="'+element.school_id+'">'+ element.school_id +'</column>' +
            '<column id="'+element.school_id+'n">'+ element.school_name.replace("&","and") +'</column>';
            tempDataStorage += '</row>';
        });
        tempDataStorage += '</data></tabledata>';

        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    //manually select a page to trigger an ajax request to populate the grid on page load
    $scope.selectPage(1);

});

//Teacher preview data controller.
proxyApp.controller('TeacherCtrl', function( $scope, api, $element, $clientExportData ) {

    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 160 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px'
    });
    $scope.selectedPage = 1;
    $scope.totalPages = 0;
    $scope.TeachersCount = 0;
    $scope.itemsPerPage = 10;
    $scope.changePageIndex = 1;
    $scope.inValidPage = false;
    $scope.showRecord = 'record';
    $scope.disabledNavigation = false;
    $scope.headers =
        [
            { title: 'Teacher Id', value: 'teacher_id' },
            { title: 'First Name', value: 'first_name' },
            { title: 'Last Name', value: 'last_name' },
            { title : 'Email', value: 'email'}
        ];
    $scope.myAction = '';
    $scope.listAction = [
        {id:'1', name:'Export to Excel'},
        {id:'2', name:'Export to CSV'},
        {id:'3', name:'Export to XML'}
    ];
    $scope.listNoRecord = [ '10', '20', '50', '100', '150', 'All'  ];

    //default criteria that will be sent to the server
    $scope.filterCriteria = {
        pageNumber: 1,
        sortDir: 'asc',
        sortedBy: 'teacher_id',
        itemsPerPage: '10'
    };

    $element.bind("keydown keypress", function(event) {
        $scope.inValidPage = false;
    });

    $scope.getNumberOfRecord = function() {
        if( $scope.filterCriteria.itemsPerPage.toString()  === 'All'){
            $scope.disabledNavigation = true;
        } else {
            $scope.disabledNavigation = false;
        }
        $scope.inValidPage = false;
        $scope.changePageIndex = 1;
        $scope.fetchResult();
    };

    $scope.sortingClass = function( element ){
        if( $scope.filterCriteria.sortedBy === element.value ){
            if($scope.filterCriteria.sortDir === 'asc'){
                return 'sorting_asc';
            }else{
                return 'sorting_desc';
            }
        } else {
            return 'sorting';
        }
    };

    $scope.previousPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) - 1;
    };

    $scope.nextPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) + 1;
    };

    //watch change index value. if condition satisfied then trigger the function "selectPage".
    $scope.$watch( function() {
        return $scope.changePageIndex;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            if ( newVal > $scope.totalPages  ||  newVal.toString() < '1'){
                $scope.itemsPerPage = '10';
                $scope.changePageIndex = 1;
                $scope.inValidPage = true;
            } else {
                if ( oldVal > $scope.totalPages  ||  oldVal.toString() < '1'){

                } else {
                    $scope.inValidPage = false;
                }
                $scope.selectPage( newVal );
            }
        }

    });

    //The function that is responsible of fetching the result from the server and setting the grid to the new result
    $scope.fetchResult = function() {
        return api.teachers.search($scope.filterCriteria).then(function(data) {
            $scope.teachers = data.items;
            $scope.totalPages = data.totalPages;
            $scope.teachersCount = data.totalItems;
            if( $scope.teachersCount.toString() > '1' ){
                $scope.showRecord = 'records';
            } else {
                $scope.showRecord = 'record';
            }
        }, function() {
            $scope.teachers = [];
            $scope.totalPages = 0;
            $scope.teachersCount = 0;
        });
    };

    $scope.$watch( function(){
        return $scope.totalPages;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            $('#viewPanel').getNiceScroll().onResize();
        }
    });

    $("#viewPanel").mouseover(function() {
        $('#viewPanel').getNiceScroll().onResize();
    });

    //called when navigate to another page in the pagination
    $scope.selectPage = function( page ) {
        $scope.filterCriteria.pageNumber = page;
        $scope.fetchResult();
    };


    //Will be called when filtering the grid, will reset the page number to one
    $scope.filterResult = function() {
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    //call back function that we passed to our custom directive sortBy, will be called when clicking on any field to sort
    $scope.onSort = function(sortedBy, sortDir) {
        $scope.inValidPage = false;
        $scope.filterCriteria.sortDir = sortDir;
        $scope.filterCriteria.sortedBy = sortedBy;
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    $scope.changeAction = function() {
        //console.log( $scope.myAction );
    };

    $scope.exportData = function () {

        var fileName ='';
        if( $scope.myAction.id.toString() === '1' ) {
            fileName = "Report.xls";
            $scope.exportToExcel( fileName );
        } else if( $scope.myAction.id.toString() === '2' ) {
            fileName = "Report.csv";
            $scope.exportToCSV( fileName );
        } else {
            fileName = "Report.xml";
            $scope.exportToXML( fileName );
        }

    };

    $scope.exportToCSV = function ( fileName ) {
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.CSV.appendToHeader( $scope.headers );

        _.each( $scope.teachers, function( element ){
            tempDataStorage = tempDataStorage  + element.teacher_id + ', ' + element.first_name + ', ' + element.last_name + ', ' + element.email + '\r\n';
        });
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToExcel = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.EXCEL.appendToHeader( $scope.headers );

        _.each( $scope.teachers, function( element ){
            tempDataStorage = tempDataStorage + '<tr>' +
            '<td>' + element.teacher_id + '</td>' +
            '<td>' + element.first_name + '</td>' +
            '<td>' + element.last_name + '</td>' +
            '<td>' + element.email + '</td>' +
            '</tr>';
        });
        tempDataStorage += '</table>';
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToXML = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.XML.appendToHeader( $scope.headers );

        _.each( $scope.teachers, function( element, index ){
            tempDataStorage += '<row id="'+index+'">';
            tempDataStorage += '<column id="'+element.teacher_id+'">'+ element.teacher_id +'</column>' +
            '<column id="'+element.teacher_id+'f">'+ element.first_name +'</column>' +
            '<column id="'+element.teacher_id+'l">'+ element.last_name +'</column>' +
            '<column id="'+element.teacher_id+'e">'+ element.email +'</column>';
            tempDataStorage += '</row>';
        });
        tempDataStorage += '</data></tabledata>';
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    //manually select a page to trigger an ajax request to populate the grid on page load
    $scope.selectPage(1);

});

//Student preview data controller.
proxyApp.controller('StudentCtrl', function( $scope, api, $element, $clientExportData ) {

    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 160 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px'
    });
    $scope.selectedPage = 1;
    $scope.totalPages = 0;
    $scope.StudentsCount = 0;
    $scope.itemsPerPage = 10;
    $scope.changePageIndex = 1;
    $scope.inValidPage = false;
    $scope.showRecord = 'record';
    $scope.disabledNavigation = false;
    $scope.headers =
        [
            { title: 'Student Id', value: 'student_id' },
            { title: 'First Name', value: 'first_name' },
            { title: 'Last Name', value: 'last_name' },
            { title : 'Email', value: 'email'}
        ];
    $scope.myAction = '';
    $scope.listAction = [
        {id:'1', name:'Export to Excel'},
        {id:'2', name:'Export to CSV'},
        {id:'3', name:'Export to XML'}
    ];
    $scope.listNoRecord = [ '10', '20', '50', '100', '150', 'All'  ];

    //default criteria that will be sent to the server
    $scope.filterCriteria = {
        pageNumber: 1,
        sortDir: 'asc',
        sortedBy: 'student_id',
        itemsPerPage: '10'
    };

    $element.bind("keydown keypress", function(event) {
        $scope.inValidPage = false;
    });

    $scope.getNumberOfRecord = function() {
        if( $scope.filterCriteria.itemsPerPage.toString()  === 'All'){
            $scope.disabledNavigation = true;
        } else {
            $scope.disabledNavigation = false;
        }
        $scope.inValidPage = false;
        $scope.changePageIndex = 1;
        $scope.fetchResult();
    };

    $scope.sortingClass = function( element ){
        if( $scope.filterCriteria.sortedBy === element.value ){
            if($scope.filterCriteria.sortDir === 'asc'){
                return 'sorting_asc';
            }else{
                return 'sorting_desc';
            }
        } else {
            return 'sorting';
        }
    };

    $scope.previousPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) - 1;
    };

    $scope.nextPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) + 1;
    };

    //watch change index value. if condition satisfied then trigger the function "selectPage".
    $scope.$watch( function() {
        return $scope.changePageIndex;
    }, function( newVal, oldVal ) {

        if ( newVal !== oldVal) {
            if ( newVal > $scope.totalPages  ||  newVal.toString() < '1'){
                $scope.itemsPerPage = '10';
                $scope.changePageIndex = 1;
                $scope.inValidPage = true;
            } else {
                if ( oldVal > $scope.totalPages  ||  oldVal.toString() < '1'){

                } else {
                    $scope.inValidPage = false;
                }
                $scope.selectPage( newVal );
            }
        }

    });

    //The function that is responsible of fetching the result from the server and setting the grid to the new result
    $scope.fetchResult = function() {
        return api.students.search( $scope.filterCriteria ).then( function( data ) {
            $scope.students = data.items;
            $scope.totalPages = data.totalPages;
            $scope.studentsCount = data.totalItems;
            if( $scope.studentsCount.toString() > '1' ){
                $scope.showRecord = 'records';
            } else {
                $scope.showRecord = 'record';
            }
        }, function() {
            $scope.students = [];
            $scope.totalPages = 0;
            $scope.studentsCount = 0;
        });
    };

    $scope.$watch( function(){
        return $scope.totalPages;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            $('#viewPanel').getNiceScroll().onResize();
        }
    });

    $("#viewPanel").mouseover(function() {
        $('#viewPanel').getNiceScroll().onResize();
    });

    //called when navigate to another page in the pagination
    $scope.selectPage = function( page ) {
        $scope.filterCriteria.pageNumber = page;
        $scope.fetchResult();
    };


    //Will be called when filtering the grid, will reset the page number to one
    $scope.filterResult = function() {
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    //call back function that we passed to our custom directive sortBy, will be called when clicking on any field to sort
    $scope.onSort = function(sortedBy, sortDir) {
        $scope.inValidPage = false;
        $scope.filterCriteria.sortDir = sortDir;
        $scope.filterCriteria.sortedBy = sortedBy;
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    $scope.changeAction = function() {
        //console.log( $scope.myAction );
    };

    $scope.exportData = function () {

        var fileName ='';
        if( $scope.myAction.id.toString() === '1' ) {
            fileName = "Report.xls";
            $scope.exportToExcel( fileName );
        } else if( $scope.myAction.id.toString() === '2' ) {
            fileName = "Report.csv";
            $scope.exportToCSV( fileName );
        } else {
            fileName = "Report.xml";
            $scope.exportToXML( fileName );
        }

    };

    $scope.exportToCSV = function ( fileName ) {
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.CSV.appendToHeader( $scope.headers );
        _.each( $scope.students, function( element ){
            tempDataStorage = tempDataStorage + element.student_id + ', ' + element.first_name + ', ' + element.last_name + ', ' + element.email + '\r\n';
        });

        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToExcel = function ( fileName ){

        var tempDataStorage = '';
        tempDataStorage = $clientExportData.EXCEL.appendToHeader( $scope.headers );

        _.each( $scope.students, function( element ){
            tempDataStorage = tempDataStorage + '<tr>' +
            '<td>' + element.student_id + '</td>' +
            '<td>' + element.first_name + '</td>' +
            '<td>' + element.last_name + '</td>' +
            '<td>' + element.email + '</td></tr>';
        });
        tempDataStorage += '</table>';

        $clientExportData.Export.saveAs( tempDataStorage, fileName );

    };

    $scope.exportToXML = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.XML.appendToHeader( $scope.headers );

        _.each( $scope.students, function( element, index ){
            tempDataStorage += '<row id="'+index+'">';
            tempDataStorage += '<column id="'+element.student_id+'">'+ element.student_id +'</column>' +
            '<column id="'+element.student_id+'f">'+ element.first_name +'</column>' +
            '<column id="'+element.student_id+'l">'+ element.last_name +'</column>' +
            '<column id="'+element.student_id+'e">'+ element.email +'</column>';
            tempDataStorage += '</row>';
        });
        tempDataStorage += '</data></tabledata>';

        $clientExportData.Export.saveAs( tempDataStorage, fileName );

    };

    //manually select a page to trigger an ajax request to populate the grid on page load
    $scope.selectPage(1);

});

//Course preview data controller.
proxyApp.controller('CourseCtrl', function( $scope, api, $filter, $element, $clientExportData ) {

    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 160 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px'
    });

    $scope.datepicker1 ={};
    $scope.datepicker2 ={};
    $scope.selectedPage = 1;
    $scope.totalPages = 0;
    $scope.CoursesCount = 0;
    $scope.changePageIndex = 1;
    $scope.inValidPage = false;
    $scope.showRecord = 'record';
    $scope.disabledNavigation = false;

    $scope.headers =
        [
            { title: 'Course Id', value: 'course_id' },
            { title: 'Course Name', value: 'course_name' },
            { title: 'Subject', value: 'subjects' },
            { title: 'Start Date', value: 'start_date'},
            { title: 'End Date', value: 'end_date'},
            { title: 'School Name', value: 'school_name' },
            { title: 'Teacher First Name', value: 'first_name' },
            { title: 'Teacher Last Name', value: 'last_name' },
        ];
    $scope.myAction = '';
    $scope.listAction = [
        {id:'1', name:'Export to Excel'},
        {id:'2', name:'Export to CSV'},
        {id:'3', name:'Export to XML'}
    ];

    $scope.listNoRecord = [ '10', '20', '50', '100', '150', 'All'  ];

    //default criteria that will be sent to the server
    $scope.filterCriteria = {
        pageNumber: 1,
        sortDir: 'asc',
        sortedBy: 'course_id',
        itemsPerPage: '10'
    };

    $element.bind("keydown keypress", function(event) {
        $scope.inValidPage = false;
    });

    $scope.getNumberOfRecord = function() {
        if( $scope.filterCriteria.itemsPerPage.toString()  === 'All'){
            $scope.disabledNavigation = true;
        } else {
            $scope.disabledNavigation = false;
        }
        $scope.inValidPage = false;
        $scope.changePageIndex = 1;
        $scope.fetchResult();
    };

    $scope.$watch( function() {
        return $scope.datepicker1.isOpen;
    }, function(newVal, oldVal) {
        if( !newVal && typeof newVal !== "undefined" ){
            $scope.filterCriteria.start_date = $filter('date')($scope.filterCriteria.start_date, "yyyy-MM-dd");
            $scope.fetchResult();

        }

    });
    $scope.$watch( function() {
        return $scope.datepicker2.isOpen;
    }, function(newVal, oldVal) {
        if( !newVal && typeof newVal !== "undefined" ){
            $scope.filterCriteria.end_date = $filter('date')($scope.filterCriteria.end_date, "yyyy-MM-dd");
            $scope.fetchResult();

        }

    });

    $scope.sortingClass = function( element ){
        if( $scope.filterCriteria.sortedBy === element.value ){
            if($scope.filterCriteria.sortDir === 'asc'){
                return 'sorting_asc';
            }else{
                return 'sorting_desc';
            }
        } else {
            return 'sorting';
        }
    };

    $scope.previousPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) - 1;
    };

    $scope.nextPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) + 1;
    };

    //watch change index value. if condition satisfied then trigger the function "selectPage".
    $scope.$watch( function() {
        return $scope.changePageIndex;
    }, function( newVal, oldVal ) {

        if ( newVal !== oldVal) {
            if ( newVal > $scope.totalPages  ||  newVal.toString() < '1'){
                $scope.itemsPerPage = '10';
                $scope.changePageIndex = 1;
                $scope.inValidPage = true;
            } else {
                if ( oldVal > $scope.totalPages  ||  oldVal.toString() < '1'){

                } else {
                    $scope.inValidPage = false;
                }
                $scope.selectPage( newVal );
            }
        }

    });
    //The function that is responsible of fetching the result from the server and setting the grid to the new result
    $scope.fetchResult = function() {
        return api.courses.search( $scope.filterCriteria ).then( function( data ) {
            $scope.courses = data.items;
            $scope.totalPages = data.totalPages;
            $scope.coursesCount = data.totalItems;

            if( $scope.coursesCount.toString() > '1' ){
                $scope.showRecord = 'records';
            } else {
                $scope.showRecord = 'record';
            }
        }, function() {
            $scope.courses = [];
            $scope.totalPages = 0;
            $scope.coursesCount = 0;
        });
    };

    $scope.$watch( function(){
        return $scope.totalPages;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            $('#viewPanel').getNiceScroll().onResize();
        }
    });

    $("#viewPanel").mouseover(function() {
        $('#viewPanel').getNiceScroll().onResize();
    });

    //called when navigate to another page in the pagination
    $scope.selectPage = function( page ) {
        $scope.filterCriteria.pageNumber = page;
        $scope.fetchResult();
    };


    //Will be called when filtering the grid, will reset the page number to one
    $scope.filterResult = function() {
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    //call back function that we passed to our custom directive sortBy, will be called when clicking on any field to sort
    $scope.onSort = function(sortedBy, sortDir) {
        $scope.inValidPage = false;
        $scope.filterCriteria.sortDir = sortDir;
        $scope.filterCriteria.sortedBy = sortedBy;
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    $scope.changeAction = function() {
        //console.log( $scope.myAction );
    };

    $scope.exportData = function () {

        var fileName ='';
        if( $scope.myAction.id.toString() === '1' ) {
            fileName = "Report.xls";
            $scope.exportToExcel( fileName );
        } else if( $scope.myAction.id.toString() === '2' ) {
            fileName = "Report.csv";
            $scope.exportToCSV( fileName );
        } else {
            fileName = "Report.xml";
            $scope.exportToXML( fileName );
        }

    };

    $scope.exportToCSV = function ( fileName ) {
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.CSV.appendToHeader( $scope.headers );
        _.each( $scope.courses, function( element ){
            tempDataStorage = tempDataStorage + element.course_id + ', ' + element.course_name + ', ' + element.subjects + ', ' + element.start_date + ', ' + element.end_date + ', ' + element.school_name + ', ' + element.first_name + ', ' + element.last_name + '\r\n';
        });
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToExcel = function ( fileName ){

        var tempDataStorage = '';
        tempDataStorage = $clientExportData.EXCEL.appendToHeader( $scope.headers );

        _.each( $scope.courses, function( element ){
            tempDataStorage = tempDataStorage + '<tr>' +
            '<td>' + element.course_id + '</td>' +
            '<td>' + element.course_name + '</td>' +
            '<td>' + element.subjects + '</td>' +
            '<td>' + element.start_date + '</td>' +
            '<td>' + element.end_date + '</td>' +
            '<td>' + element.school_name + '</td>' +
            '<td>' + element.first_name + '</td>' +
            '<td>' + element.last_name + '</td>' +
            '</tr>';
        });
        tempDataStorage += '</table>';

        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToXML = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.XML.appendToHeader( $scope.headers );

        _.each( $scope.courses, function( element, index ){
            tempDataStorage += '<row id="'+index+'">';
            tempDataStorage += '<column id="'+element.course_id+'">'+ element.course_id +'</column>' +
            '<column id="'+element.course_id+'cn">'+ element.course_name.replace("&","and") +'</column>' +
            '<column id="'+element.course_id+'su">'+ element.subjects.replace("&","and") +'</column>' +
            '<column id="'+element.course_id+'sd">'+ element.start_date +'</column>' +
            '<column id="'+element.course_id+'ed">'+ element.end_date +'</column>' +
            '<column id="'+element.course_id+'sn">'+ element.school_name.replace("&","and") +'</column>' +
            '<column id="'+element.course_id+'fn">'+ element.first_name +'</column>' +
            '<column id="'+element.course_id+'ln">'+ element.last_name +'</column>';
            tempDataStorage += '</row>';
        });
        tempDataStorage += '</data></tabledata>';

        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    //manually select a page to trigger an ajax request to populate the grid on page load
    $scope.selectPage(1);

});

// School preview data controller.
proxyApp.controller('EnrollmentsCtrl', function( $scope, api, $element, $clientExportData ) {

    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 160 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px'
    });
    $scope.selectedPage = 1;
    $scope.totalPages = 0;
    $scope.enrollmentsCount = 0;
    $scope.itemsPerPage = 10;
    $scope.changePageIndex = 1;
    $scope.inValidPage = false;
    $scope.showRecord = 'record';
    $scope.disabledNavigation = false;
    $scope.headers =
        [
            { title: 'Course Id', value: 'course_id' },
            { title: 'Course Name', value: 'course_name' },
            { title: 'Student First Name', value: 'first_name' },
            { title: 'Student Last Name', value: 'last_name' }
        ];
    $scope.myAction = '';
    $scope.listAction = [
        {id:'1', name:'Export to Excel'},
        {id:'2', name:'Export to CSV'},
        {id:'3', name:'Export to XML'}
    ];

    $scope.listNoRecord = [ '10', '20', '50', '100', '150', 'All'  ];
    //default criteria that will be sent to the server
    $scope.filterCriteria = {
        pageNumber: 1,
        sortDir: 'asc',
        sortedBy: 'course_id',
        itemsPerPage: '10'
    };

    $element.bind("keydown keypress", function(event) {
        $scope.inValidPage = false;
    });

    $scope.getNumberOfRecord = function() {
        if( $scope.filterCriteria.itemsPerPage.toString()  === 'All'){
            $scope.disabledNavigation = true;
        } else {
            $scope.disabledNavigation = false;
        }
        $scope.inValidPage = false;
        $scope.changePageIndex = 1;
        $scope.fetchResult();
    };

    $scope.sortingClass = function( element ){
        if( $scope.filterCriteria.sortedBy === element.value ){
            if($scope.filterCriteria.sortDir === 'asc'){
                return 'sorting_asc';
            }else{
                return 'sorting_desc';
            }
        } else {
            return 'sorting';
        }
    };

    $scope.previousPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) - 1;
    };

    $scope.nextPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) + 1;
    };

    //watch change index value. if condition satisfied then trigger the function "selectPage".
    $scope.$watch( function() {
        return $scope.changePageIndex;
    }, function( newVal, oldVal ) {

        if ( newVal !== oldVal) {
            if ( newVal > $scope.totalPages  ||  newVal.toString() < '1'){
                $scope.itemsPerPage = '10';
                $scope.changePageIndex = 1;
                $scope.inValidPage = true;
            } else {
                if ( oldVal > $scope.totalPages  ||  oldVal.toString() < '1'){

                } else {
                    $scope.inValidPage = false;
                }
                $scope.selectPage( newVal );
            }
        }

    });

    $scope.$watch( function(){
        return $scope.totalPages;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            $('#viewPanel').getNiceScroll().onResize();
        }
    });

    $("#viewPanel").mouseover(function() {
        $('#viewPanel').getNiceScroll().onResize();
    });

    //The function that is responsible of fetching the result from the server and setting the grid to the new result
    $scope.fetchResult = function() {
        return api.enrollments.search($scope.filterCriteria).then(function( data ) {
            $scope.enrollments = data.items;
            $scope.totalPages = data.totalPages;
            $scope.enrollmentsCount = data.totalItems;
            if( $scope.enrollmentsCount.toString() > '1' ){
                $scope.showRecord = 'records';
            } else {
                $scope.showRecord = 'record';
            }
        }, function() {
            $scope.enrollments = [];
            $scope.totalPages = 0;
            $scope.enrollmentsCount = 0;
        });
    };

    //called when navigate to another page in the pagination
    $scope.selectPage = function( page ) {
        $scope.filterCriteria.pageNumber = page;
        $scope.fetchResult();
    };


    //Will be called when filtering the grid, will reset the page number to one
    $scope.filterResult = function() {
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    //call back function that we passed to our custom directive sortBy, will be called when clicking on any field to sort
    $scope.onSort = function(sortedBy, sortDir) {
        $scope.inValidPage = false;
        $scope.filterCriteria.sortDir = sortDir;
        $scope.filterCriteria.sortedBy = sortedBy;
        $scope.filterCriteria.pageNumber = 1;
        $scope.fetchResult().then(function() {
            //The request fires correctly but sometimes the ui doesn't update, that's a fix
            $scope.filterCriteria.pageNumber = 1;
        });
    };

    $scope.changeAction = function() {
        //console.log( $scope.myAction );
    };

    $scope.exportData = function () {

        var fileName ='';
        if( $scope.myAction.id.toString() === '1' ) {
            fileName = "Report.xls";
            $scope.exportToExcel( fileName );
        } else if( $scope.myAction.id.toString() === '2' ) {
            fileName = "Report.csv";
            $scope.exportToCSV( fileName );
        } else {
            fileName = "Report.xml";
            $scope.exportToXML( fileName );
        }

    };

    $scope.exportToCSV = function ( fileName ) {
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.CSV.appendToHeader( $scope.headers );
        _.each( $scope.enrollments, function( element ){
            tempDataStorage = tempDataStorage + element.course_id + ', ' + element.course_name + ', ' + element.first_name + ', ' + element.last_name + '\r\n';
        });
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToExcel = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.EXCEL.appendToHeader( $scope.headers );
        _.each( $scope.enrollments, function( element ){
            tempDataStorage = tempDataStorage + '<tr>' +
            '<td>' + element.course_id + '</td>' +
            '<td>' + element.course_name + '</td>' +
            '<td>' + element.first_name + '</td>' +
            '<td>' + element.last_name + '</td>' +
            '</tr>';
        });
        tempDataStorage += '</table>';

        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    $scope.exportToXML = function ( fileName ){
        var tempDataStorage = '';
        tempDataStorage = $clientExportData.XML.appendToHeader( $scope.headers );
        _.each( $scope.enrollments, function( element, index ){
            tempDataStorage += '<row id="'+index+'">';
            tempDataStorage += '<column id="'+element.course_id+'">'+ element.course_id +'</column>' +
            '<column id="'+element.course_id+'cn">'+ element.course_name.replace("&","and") +'</column>' +
            '<column id="'+element.course_id+'fn">'+ element.first_name +'</column>' +
            '<column id="'+element.course_id+'ln">'+ element.last_name +'</column>';
            tempDataStorage += '</row>';
        });
        tempDataStorage += '</data></tabledata>';
        $clientExportData.Export.saveAs( tempDataStorage, fileName );
    };

    //manually select a page to trigger an ajax request to populate the grid on page load
    $scope.selectPage(1);

});

/**
 * Create service for appending the header and export functionality.
 * @param {Object} $rootScope
 * @param {Object} $http
 */
proxyApp.service( '$clientExportData', function(){

    var $clientExportData = this;

    $clientExportData.CSV = {

        appendToHeader : function( header ){
            var tempDataStorage= '';
            _.each( header, function( element, index ){
                tempDataStorage += tempDataStorage ? ','+element.title : element.title;
            });
            tempDataStorage +='\r\n';
            return tempDataStorage;
        }

    };

    $clientExportData.EXCEL = {

        appendToHeader : function( header ){
            var tempDataStorage = '<table><tr>';
            _.each( header, function( element, index ){
                tempDataStorage += '<th>'+element.title+'</th>';
            });
            tempDataStorage +='</tr>';
            return tempDataStorage;
        }

    };

    $clientExportData.XML = {

        appendToHeader : function( header ){
            var tempDataStorage = '<?xml version="1.0" encoding="utf-8"?><tabledata><fields>';
            _.each( header, function( element, index ){
                tempDataStorage +='<field>'+ element.title +'</field>';
            });
            tempDataStorage +='</fields><data>';
            return tempDataStorage;
        }

    };


    $clientExportData.Export = {
        saveAs: function (exportData, fileName){
                var blob = new Blob([exportData], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                });
                saveAs(blob, fileName);
        }
    }

});
/**
 * Create service for pulling data.
 * @param {Object} $rootScope
 * @param {Object} $http
 */
proxyApp.service( '$serverRequest', function ( $rootScope, $http, $location ) {

    var $serverRequest = this;
    //For Import Data

    $serverRequest.passwordChange = {

        responseData:[],

        pushChangePassword: function ( url, json ){
            $http ({
                method: 'POST',
                url: url,
                data: json,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                $serverRequest.passwordChange.responseData = data;
                $rootScope.$broadcast ( 'PASSWORD_CHANGED' );
            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: push request failed' );
            });
        }
    };


    $serverRequest.report = {

        uploadData: {
            LocalStorage: []
        },

        pullPageData: function ( ) {

            var serviceURL = '/csvfiles';
            $http.get ( serviceURL )
                .success ( function ( data, status, headers, config ) {
                $serverRequest.report.uploadData.LocalStorage = data;
                $rootScope.$broadcast ( 'UPLOADED_DATA' );

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: pull request failed' );
            });

        }

    };

    $serverRequest.graphs = {
        graphLocalStorage:[],

        pullGraphData: function () {
            var serviceURL = 'graph';
            $http.get ( serviceURL )
                .success ( function ( data, status, headers, config ) {
                $serverRequest.graphs.graphLocalStorage = data;
                $rootScope.$broadcast ( 'GRAPHS_DATA' );

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: pull request failed' );
            });
        }
    };

    //For Developers Data
    $serverRequest.developers = {

        developerList: {
            LocalStorage: []
        },
        developerSchoolList: [],

        pullDeveloperData: function ( url ) {

            var serviceURL = url;
            $http.get ( serviceURL )
                .success ( function ( data, status, headers, config ) {
                $serverRequest.developers.developerList.LocalStorage = data;
                $rootScope.$broadcast ( 'DEVELOPER_DATA' );

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: pull request failed' );
            });

        },

        pullSchoolList: function ( url ){
            var serviceURL = url;
            $http.get ( serviceURL )
                .success ( function ( data, status, headers, config ) {
                $serverRequest.developers.developerSchoolList = data;
                $rootScope.$broadcast ( 'DEVELOPER_SCHOOL_DATA' );

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: pull request failed' );
            });
        },

        pushAssignSchool: function ( url, json ){
            $http ({
                method: 'POST',
                url: url,
                data: json,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                $rootScope.$broadcast ( 'ASSIGN_SCHOOL_PUSH' );
            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: push request failed' );
            });
        },

        pushDeveloperData: function( url, json ) {
            $http ({
                method:'POST',
                url:url,
                data:json.data,
                headers:{
                    'Content-Type' : 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {

                if( json.type === 'remove'){
                    $rootScope.$broadcast ( 'DEVELOPER_DATA_UPDATED' );

                } else if ( json.type === 'save' ){
                    $rootScope.$broadcast ( 'DEVELOPER_DATA_UPDATED', data );

                } else if ( json.type === 'update' ){
                    if( data.status === true ){
                        $serverRequest.developers.developerList.LocalStorage.items[json.index].developer_name = json.data.developer_name;
                        $serverRequest.developers.developerList.LocalStorage.items[json.index].email = json.data.email;
                        $serverRequest.developers.developerList.LocalStorage.items[json.index].api_secret = json.data.api_secret;
                    }
                    $rootScope.$broadcast ( 'DEVELOPER_DATA', data );
                } else if ( json.type === 'updatekey' ){
                    $serverRequest.developers.developerList.LocalStorage.items[json.index].api_key = data.api_key;
                    $rootScope.$broadcast ( 'DEVELOPER_DATA' );
                }


            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: push request failed' );
            });

        }      

    };

    //For Applications  Data
    $serverRequest.application = {

        applicationList: {
            LocalStorage: []
        },

        pullApplicatinData: function ( url ) {

            var serviceURL = url;
            $http.get ( serviceURL )
                .success ( function ( data, status, headers, config ) {
                $serverRequest.application.applicationList.LocalStorage = data;
                $rootScope.$broadcast ( 'DEVELOPER_DATA' );

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: pull request failed' );
            });

        },

        saveApplicationData: function( url, json ) {
            $http ({
                method:'POST',
                url:url,
                data:json.data,
                headers:{
                    'Content-Type' : 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {

                if( json.type === 'remove'){
                    $rootScope.$broadcast ( 'APPLICATION_DATA_UPDATED' );

                } else if ( json.type === 'save' ){
                    $rootScope.$broadcast ( 'APPLICATION_DATA_UPDATED', data );

                } else if ( json.type === 'update' ){
                    if( data.status === true ){
                        $serverRequest.developers.developerList.LocalStorage.items[json.index].developer_name = json.data.developer_name;
                        $serverRequest.developers.developerList.LocalStorage.items[json.index].email = json.data.email;
                        $serverRequest.developers.developerList.LocalStorage.items[json.index].api_secret = json.data.api_secret;
                    }
                    $rootScope.$broadcast ( 'APPLICATION_DATA_UPDATED', data );
                } else if ( json.type === 'updatekey' ){
                    $serverRequest.developers.developerList.LocalStorage.items[json.index].api_key = data.api_key;
                    $rootScope.$broadcast ( 'APPLICATION_DATA' );
                }
            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: push request failed' );
            });

        }

    };

    //Generate key services.
    $serverRequest.data = {

        responseData:'',
        generatedKey:[],
        generatedRequestMsg:'',

        pullPageData: function ( url, json ) {
            $http ({
                method: 'GET',
                url: url,
                headers: {
                    'Secret-Key': json.secretkey,
                    'Api-Key': json.apikey
                }
            }).success ( function ( data, status, headers, config ) {

                $serverRequest.data.responseData= data;
                $rootScope.$broadcast ( 'ShowResponseData' );

            }).error ( function ( data, status, headers, config ) {
                $serverRequest.data.responseData= data;
                $rootScope.$broadcast ( 'ShowResponseData' );
                
                console.error ( 'error:: pull request failed' );
            });

        },

        postPageData: function ( url, json ) {

            $http ({
                method: 'POST',
                url: url,
                data: json,
                headers: {
                    'Content-Type': 'application/json'
                }
            }).success ( function ( data, status, headers, config ) {
                if( data.error ){
                    $serverRequest.data.generatedRequestMsg = data.responseMessage;

                } else {
                    $serverRequest.data.generatedRequestMsg = data.responseMessage;

                }
                $rootScope.$broadcast ( 'UpdateKeyField' );

            }).error ( function ( data, status, headers, config ) {
                console.error ( 'error:: pull request failed' );
            });

        }

    };

});



proxyApp.controller( "deleteApplicationCtrl", function( $scope ) {
    $scope.applicationName = $scope.$parent.ngDialogData.name;
});

proxyApp.controller( "assignSchoolCtrl", function( $scope, ngDialog, $serverRequest ) {

    $scope.developerName = $scope.$parent.ngDialogData.developer_name;
    //console.log( $scope, $scope.$parent, $scope.$parent.ngDialogData.developer_id );
    $scope.pullUrl = 'developers/schools/'+$scope.$parent.ngDialogData.developer_id;
    $serverRequest.developers.pullSchoolList( $scope.pullUrl );

    $scope.school = {};

    // This property is bound to the checkbox in the table header
    $scope.school.allItemsSelected = true;
    $scope.btnAssignStatus = false;

    // Initialize the entity list
    $scope.school.entities = [];

    $scope.$on ( 'DEVELOPER_SCHOOL_DATA', function () {
        $scope.school.entities = $serverRequest.developers.developerSchoolList;
        _.each( $scope.school.entities, function( element, index ){
            if( $scope.school.entities[index].isChecked && $scope.school.allItemsSelected ){
                $scope.school.allItemsSelected = true;
                $scope.btnAssignStatus = true;
            } else {
                $scope.school.allItemsSelected = false;
            }

        });

    });

    // Fired when an entity in the table is checked
    $scope.selectEntity = function () {
        var varifyCheckedBox=false;

        for (var i = 0; i < $scope.school.entities.length; i++) {

            if( $scope.school.entities[i].isChecked && varifyCheckedBox === false ){
                varifyCheckedBox = true;
            }
        }

        // ... otherwise check the "allItemsSelected" checkbox
        if( !varifyCheckedBox ){
            $scope.btnAssignStatus = false;
        } else {
            $scope.btnAssignStatus = true;
        }



        // If any entity is not checked, then uncheck the "allItemsSelected" checkbox
        for (var i = 0; i < $scope.school.entities.length; i++) {
            if (!$scope.school.entities[i].isChecked) {

                $scope.school.allItemsSelected = false;
                return;
            }
        }

        // ... otherwise check the "allItemsSelected" checkbox
        $scope.school.allItemsSelected = true;
    };

    // Fired when the checkbox in the table header is checked
    $scope.selectAll = function () {
        // Loop through all the entities and set their isChecked property
        for (var i = 0; i < $scope.school.entities.length; i++) {
            $scope.school.entities[i].isChecked = $scope.school.allItemsSelected;
        }
        if( $scope.school.allItemsSelected ){
            $scope.btnAssignStatus = true;
        } else {
            $scope.btnAssignStatus = false;
        }
    };

    //Push data from server to store.
    $scope.assignSchool = function(){

        var assignJson = [];
        for (var i = 0; i < $scope.school.entities.length; i++) {
            if( $scope.school.entities[i].isChecked ){
                assignJson.push( {
                    'school_id': $scope.school.entities[i].school_id,
                    'developer_id':$scope.$parent.ngDialogData.developer_id
                }  );
            }
        };

        $serverRequest.developers.pushAssignSchool( 'developers/assignschool', assignJson );

        $scope.$on ( 'ASSIGN_SCHOOL_PUSH', function () {
            ngDialog.close();
        });

    };

    //Close ngDilaog box onclick button.
    $scope.closeDialogBox = function(){
        ngDialog.close();
    };

    //Return active class.
    $scope.activeClass = function( ele ){
        if( ele.isChecked ){
            return 'selected'
        }
    };

});

proxyApp.controller("settingsCtrl", function( $scope, $http, Flash) {
    $scope.showErrorMsg = false;

    console.log("settingsCtrl called");
    $scope.settingData = {};

    $scope.saveSettings = function (formObj) {
        console.log("formObj = ", formObj);
        if (formObj.$valid) {
            var responseObj = $http({
                url: '/proxy/settings/save',
                method: 'post',
                data: {
                    proxy_url: $scope.settingData.proxy_url, 
                    api_key: $scope.settingData.api_key
                },
            });
            responseObj.success(function (data, status, headers, config) {
                console.log(data);
                if (data.status) {
                    var message = 'Reverse proxy details saved successfully.';
                    Flash.create('success', message, 'custom-class');
                }
                else {
                    Flash.create('danger', data.message, 'custom-class');
                    $scope.showHeaderErrorMsg = true;
                }
            });
            responseObj.error(function (data, status, headers, config) {
                alert(data.error);
            });
        } else {
            $scope.showErrorMsg = true;
        }
    };

});

proxyApp.controller("applicationCtrl", function($scope, $http, Flash, $serverRequest, $element, ngDialog) {
    $('#viewPanel').height( $( window ).height() - ( $('#navbar').height() + 125 ) );
    $("#viewPanel").niceScroll({
        cursorcolor:"#6498c8",
        autohidemode: false,
        cursorwidth: '8px',
        bouncescroll: true,
        touchbehavior:true,
        grabcursorenabled: true,
        cursordragontouch: true
    });
    $scope.editing = false;
    $scope.addnew = false;
    $scope.totalPages = 0;
    $scope.developersCount = 0;
    $scope.itemsPerPage = '10';
    $scope.changePageIndex = 1;
    $scope.listNoRecord = [ '10', '20', '50', '100', '150', 'All'  ];
    $scope.inValidPage = false;
    $scope.showRecord = 'records';
    $scope.disabledNavigation = false;


    //Pull request to server for getting the developer data.
    $scope.getDeveloperData = function(){
        $serverRequest.application.pullApplicatinData( 'applications/list?itemsPerPage='+$scope.itemsPerPage+'&pageNumber='+$scope.changePageIndex );
    };

    $scope.getDeveloperData();

    $scope.$on ( 'DEVELOPER_DATA', function () {
        // $scope.items            = $serverRequest.application.applicationList.LocalStorage.items;
        // $scope.developersCount  = $serverRequest.application.applicationList.LocalStorage.totalItems;
        // $scope.totalPages       = $serverRequest.application.applicationList.LocalStorage.totalPages;

        console.log("$serverRequest.application.applicationList.LocalStorage");
        console.log($serverRequest.application.applicationList.LocalStorage);

        $scope.items            = $serverRequest.application.applicationList.LocalStorage;
        $scope.developersCount  = 100;
        $scope.totalPages       = 10;
        if( $scope.developersCount.toString() > '1' ){
            $scope.showRecord = 'records';
        } else {
            $scope.showRecord = 'record';
        }
    });

    $scope.$watch( function(){
        return $scope.totalPages;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            $('#viewPanel').getNiceScroll().onResize();
        }
    });

    $("#viewPanel").mouseover(function() {
        $('#viewPanel').getNiceScroll().onResize();
    });

    $scope.getNumberOfRecord = function() {
        if( $scope.itemsPerPage.toString()  === 'All'){
            $scope.disabledNavigation = true;
        } else {
            $scope.disabledNavigation = false;
        }
        $scope.inValidPage = false;
        $scope.changePageIndex = 1;
        $scope.getDeveloperData();
    };

    $scope.addNewData = function () {
        var item = {
            action: 'save'
        };
        ngDialog.openConfirm({
            template: 'modalDialogAddEdit',
            className: 'ngdialog-theme-default',
            controller: 'addEditApplicationCtrl',
            data: JSON.stringify( item )
        }).then( function ( ) {

        }, function () {
            console.log('Modal promise rejected.');
        });
    };

    $element.bind("keydown keypress", function( ) {
        $scope.inValidPage = false;
        $scope.errorMsgDev = '';
    });

    $scope.previousPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) - 1;
    };

    $scope.nextPage = function(){
        $scope.changePageIndex = parseInt($scope.changePageIndex) + 1;
    };

    $scope.$watch( function() {
        return $scope.changePageIndex;
    }, function( newVal, oldVal ) {
        if ( newVal !== oldVal) {
            if ( newVal > $scope.totalPages  ||  newVal.toString() < '1'){
                $scope.itemsPerPage = '10';
                $scope.changePageIndex = 1;
                $scope.inValidPage = true;

            } else {
                $scope.changePageIndex = newVal;
                $scope.getDeveloperData();
            }
        }

    });

    $scope.removeItem = function( item, index ){

        $scope.applicationName = $scope.items[index].name;
        ngDialog.openConfirm({
            template: 'modalDialogId',
            className: 'ngdialog-theme-default',
            controller: 'deleteApplicationCtrl',
            data: JSON.stringify( item )
        }).then( function ( ) {
            var json = {
                type: 'remove',
                data: {
                    id: $scope.items[index].id
                },
                index: index
            };
            $serverRequest.application.saveApplicationData( 'applications/delete', json );
            $scope.addnew = false;
        }, function () {
            console.log('Modal promise rejected.');
        });
    };

    $scope.editItem = function( item, index ){

        item.index = index;
        item.action = 'update';
        ngDialog.openConfirm({
            template: 'modalDialogAddEdit',
            className: 'ngdialog-theme-default',
            controller: 'addEditApplicationCtrl',
            data: JSON.stringify( item )
        }).then( function ( ) {

        }, function () {
            console.log('Modal promise rejected.');
        });

    };

    $scope.updateConsumerKey = function ( index ) {

        var json = {
            type: 'updatekey',
            data: {
                developer_id: $scope.items[index].developer_id
            },
            index: index
        };
        $serverRequest.developers.pushDeveloperData( 'developers/key', json );

    };

    $scope.$on ( 'APPLICATION_DATA_UPDATED', function ( ) {
        console.log("APPLICATION_DATA_UPDATED called - line 2330");
        $scope.itemsPerPage = '10';
        $scope.changePageIndex = 1;
        $scope.getDeveloperData();
    });
});

proxyApp.controller( "addEditApplicationCtrl", function( $scope, ngDialog, $serverRequest, Flash) {

    $scope.element = $scope.$parent.ngDialogData;
    $scope.eventMode = '';
    $scope.showSecret = true;
    $scope.errorSecret = false;
    $scope.errorEmail = false;
    $scope.errorName = false;
    $scope.errorMsgEmail = '';
    $scope.consumerSecret = '';
    $scope.errorMsgName = '';
    $scope.errorMsgSecret = '';
    $scope.showUpdateBtn = true;

    if( $scope.element.action === 'update'){
        $scope.eventMode = 'Edit Application';
        $scope.application_name = $scope.element.name;
        $scope.internal_url = $scope.element.internal_url;
        $scope.internal_uri = $scope.element.internal_uri;
        $scope.showUpdateBtn = false;
    } else if( $scope.element.action === 'save' ){
        $scope.eventMode = 'Create New Application';
        $scope.showUpdateBtn = true;
    }

    $scope.update = function (){
        var isValid = true;
        var json = {
            type: 'update',
            data: {
                id: $scope.element.id,
                application_name: $scope.application_name ? $scope.application_name : '',
                internal_url: $scope.internal_url ? $scope.internal_url : '',
                internal_uri: $scope.internal_uri ? $scope.internal_uri : ''
            },
            index: $scope.element.index
        };

        isValid = $scope.checkFieldValidation( json );
        if( isValid ){
            $serverRequest.application.saveApplicationData( 'applications/edit', json );
            $scope.$on ( 'APPLICATION_DATA_UPDATED', function ( event, data ) {
                console.log("APPLICATION_DATA_UPDATED called - line 2408");
                console.log("data = ", data);
                if( data.status ){
                    ngDialog.close();
                    var message = 'Application updated successfully.';
                    Flash.create('success', message, 'custom-class');
                } else {
                    Flash.create('danger', data.message, 'custom-class');
                }

            });
        }
    };

    $scope.save = function (){
        var isValid = true;
        var json = {
            type: 'save',
            data: {
                application_name: $scope.application_name ? $scope.application_name : '',
                internal_url: $scope.internal_url ? $scope.internal_url : '',
                internal_uri: $scope.internal_uri ? $scope.internal_uri : ''
            }
        };

        isValid = $scope.checkFieldValidation( json );
        if( isValid ){
            $serverRequest.application.saveApplicationData( 'applications/add', json );
            $scope.$on ( 'APPLICATION_DATA_UPDATED', function ( event, data  ) {
                console.log("APPLICATION_DATA_UPDATED called - line 2435");
                if( data.status ){
                    ngDialog.close();
                    var message = 'Application created successfully.';
                    Flash.create('success', message, 'custom-class');
                } else {
                    Flash.create('danger', data.message, 'custom-class');
                }
            });
        }

    };

    $scope.keydown = function( type ){
        if ( type === 'application_name' ){
            $scope.errorName = false;
            $scope.errorMsgName = '';
        } else if( type === 'internal_url' ){
            $scope.errorURL = false;
            $scope.errorMsgURL = '';
        } else if( type === 'internal_uri' ){
            $scope.errorURI = false;
            $scope.errorMsgURI = '';
        }
    };

    $scope.checkFieldValidation = function ( json ){
        var validInput = true;

        if( typeof json.data.application_name !== "undefined") {
            if (json.data.application_name.length < 1) {
                validInput = false;
                $scope.errorName = true;
                $scope.errorMsgName = 'Please enter application name.'; //'Name should contain at least 6 characters.';
            } else {
                var filter = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 ";
                for ( var i = 0; i < json.data.application_name.length; i++) {
                    var strChar = json.data.application_name.charAt(i);
                    if (filter.indexOf(strChar) === -1) {
                        validInput = false;
                        $scope.errorName = true;
                        $scope.errorMsgName = 'Please enter valid application name.';
                    }
                }
            }
        }
        if( typeof json.data.internal_url !== "undefined"){
            var filter = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
            if ( !filter.test( json.data.internal_url )) {
                validInput = false;
                $scope.errorURL = true;
                $scope.errorMsgURL = 'Please enter valid internal URL.';
            }
        }
        if( typeof json.data.internal_uri !== "undefined") {
            if (json.data.internal_uri.length < 1) {
                validInput = false;
                $scope.errorURI = true;
                $scope.errorMsgURI = 'Please enter internal server URL.'; //'Name should contain at least 6 characters.';
            }
        }
        return validInput;
    };

});

proxyApp.directive('placeholder', function($timeout){
    var i = document.createElement('input');
    if ('placeholder' in i) {
        return {}
    }
    return {
        link: function(scope, elm, attrs){
            $timeout(function(){
                elm.val(attrs.placeholder);
                elm.bind('focus', function(){
                    if (elm.val() == attrs.placeholder) {
                        elm.val('');

                    }
                }).bind('blur', function(){
                    if (elm.val() == '') {
                        elm.val(attrs.placeholder);

                    }
                });
            });
        }
    }
});

proxyApp.directive('showFocus', function($timeout) {
    return function(scope, element, attrs) {
        scope.$watch(attrs.showFocus,
            function (newValue) {
                $timeout(function() {
                    newValue && element.focus();
                });
            },true);
    };
});