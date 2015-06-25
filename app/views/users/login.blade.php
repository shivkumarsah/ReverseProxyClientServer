@extends('layouts.home')
@section('content')
{{Form::open(array('route' => 'doLogin', 'name'=>'loginForm', 'id'=>'loginForm', 'class'=>"form-signin", 'novalidate'=>'true'))}}

{{ HTML::image('img/full-logo.png', trans('messages.logo'), array('style' => 'height:auto; width:300px; margin-bottom:25px')) }}
 @include('includes.error-notice')
 @include('includes.success-message')

 
{{Form::submit(trans('messages.login.login'), array('class'=>'btn btn-lg btn-primary btn-block', 'tabindex'=>'4', 'ng-click'=>'submitLoginForm(loginForm.$valid)' ))}}
{{ Form::close() }}

<div class="login-footer centered"></div>
@endsection
