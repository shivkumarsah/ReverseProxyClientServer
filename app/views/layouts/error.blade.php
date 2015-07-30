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
    
    <!--link rel="stylesheet" type="text/css" href="css/robot_page.css">
    <link rel="stylesheet" type="text/css" href="css/robot_styles.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"/></script>
    <script type="text/javascript" src="js/brokebot.js"/></script>
    <script src="dist/snap.svg-min.js" type="text/javascript"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:700,300' rel='stylesheet' type='text/css'-->
    <script>
      var isHeadless = true;
    </script>
  </head>
  <body  ng-app="proxyApp" ng-controller="appCtrl" >
    @yield('content')
    @include('includes.footer-inner')
  </body>
</html>