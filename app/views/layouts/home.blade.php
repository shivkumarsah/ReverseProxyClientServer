<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{trans('messages.login.page_title')}}</title>
        @include('includes.header')

    </head>
    <body ng-app="validationApp" ng-controller="loginController">
        <div id="loading">
            <img style="margin-left: 38%; margin-top: 10%;" height="250px" width="250px" src='/img/ajax-loader.gif' alt='Loading...' />
        </div>
        <div class="container">
             @yield('content')
            <div class="cl-footer centered"> 
                {{link_to(Config::get('appvals.classic_url' , 'http://www.classlink.com/'), trans('messages.classic_url_title'), array('target'=>'_blank'))}}
            </div>
        </div>

        @include('includes.footer')

    </body>
</html>