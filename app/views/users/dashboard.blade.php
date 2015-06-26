@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  ng-controller="dashboardCtrl">
    <h1 class="page-header">{{trans('messages.dashboard.page_title')}}</h1>
    <div id="viewPanel">

    </div>
</div>
@endsection
