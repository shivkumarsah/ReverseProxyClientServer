@extends('layouts.popup')
@section('content')
<h1 class="page-header" style="margin:0px 0px 0px;">
    {{trans('messages.importdata.file_import_log_title')}}
</h1>
<div style="height:400px; overflow:auto;">
    <div class="bs-callout bs-callout-warning">
        <h4>{{trans('messages.importdata.file_last_upload_log')}}</h4>
        <p>{{$fileData->file_upload_comment}} </p>
    </div>

    <div class="bs-callout bs-callout-warning">
        <h4>{{trans('messages.importdata.file_last_import_log')}}</h4>
        <p>{{$fileData->file_import_comment}} </p>
    </div>
<div>
@endsection
