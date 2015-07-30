<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if ($currentRoute = Route::currentRouteName()) @endif
    <title>
        @if (isset($page_title)) 
            {{$page_title}}
        @elseif(Lang::has("messages.$currentRoute.page_title"))
            {{Lang::get("messages.$currentRoute.page_title")}}
        @else
            {{trans('messages.project_title')}}
        @endif - Error
       
    </title>
    {{ HTML::style('css/robot_page.css') }}
    {{ HTML::style('css/robot_styles.css') }}
    {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js') }}
    {{ HTML::script('js/brokebot.js') }}
    {{ HTML::script('dist/snap.svg-min.js') }}
    {{ HTML::style('http://fonts.googleapis.com/css?family=Open+Sans:700,300') }}
    <script>
      var isHeadless = true;
    </script>
    <style>
    .login-footer {
      background: url("../img/classlink-logo.png") no-repeat scroll 50% 50% transparent;
      display: block;
      height: 41px;
      margin-top: 100px;
      width: 68px;
    }
    .centered {
      display: block;
      float: none;
      margin-left: auto;
      margin-right: auto;
    }
    .cl-footer{
      color:#000000;
    }
    #robot_holder {
      padding: 0px;
      margin: 0px;
      height: 370px;
    }
    </style>
  </head>
  <body ng-app="proxyApp" ng-controller="appCtrl" >
    @yield('content')
  </body>
</html>