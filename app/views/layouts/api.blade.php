<!DOCTYPE html>
<html lang="en">
  <head>
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
        @endif
       
    </title>
    @include('includes.header-inner')
    
  </head>
  <body  ng-app="openrosterApp" ng-controller="openrosterCtrl" >
   <div id="loading">
        <img style="margin-left: 38%; margin-top: 10%;" height="250px" width="250px" src='/img/ajax-loader.gif' alt='Loading...' />
    </div>
    <div class="container-fluid">
      <div class="row">
         @yield('content')
      </div>
    </div>
 
   @include('includes.footer-inner')

  </body>
</html>