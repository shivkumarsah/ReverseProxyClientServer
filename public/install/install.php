<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.1.1/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="css/style-install.css">

    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.3/angular-messages.js"></script>
    <script src="../js/angular-ui-router.min.js"></script>
    <script src="../js/angular-animate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.js"></script>
    <script src="app/app.js"></script>
    <script src="app/maincontroller.js"></script>
    
</head>
<body ng-app="formApp">

<!-- views will be injected here -->
<div class="container">
    <div ui-view></div>
</div>

</body>
</html>