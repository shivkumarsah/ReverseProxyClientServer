@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" ng-controller="importPanelCtrl">
    <h1 class="page-header">
        {{Form::label('page_header', trans('messages.importdata.page_header'), array('class' => 'normal-weight'))}}
    </h1>
    <div class="bs-callout bs-callout-warning">
        <h4>Need Help?</h4>
        <p style="margin-top: 0px !important;">The files must be uploaded before you can validate and import the data. You can manually upload each file or copy/FTP them to the <a href="#" data-container="body" data-toggle="popover" data-placement="left" data-content="c:\uploads">
                uploads
            </a> folder. Schools.csv is optional. </p>
    </div>

    <button type="button" class="btn btn-success" ng-click="selectFileUpload()">
        {{Form::label('file_upload', trans('messages.importdata.file_upload'), array('class' => 'normal-weight'))}}
    </button>
    <button type="button" class="btn btn-warning" ng-click="selectVerify()">
        {{Form::label('verify', trans('messages.importdata.verify'), array('class' => 'normal-weight'))}}
    </button>
    <button type="button" class="btn btn-default" ng-click="selectDownloadTemplate()">
        {{Form::label('download_templates', trans('messages.importdata.download_templates'), array('class' => 'normal-weight'))}}
    </button>

    <div style="width: 50%; display: inline-block; color: red;" ng-show='showVerifyMsg'>[[showVerifyMsg]]</div>
    <div id="viewPanel" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{Form::label('name', trans('messages.importdata.name'), array('class' => 'table-header'))}}</th>
                    <th>{{Form::label('status', trans('messages.importdata.status'), array('class' => 'table-header'))}}</th>
                    <th>{{Form::label('lastmodified', trans('messages.importdata.lastmodified'), array('class' => 'table-header'))}}</th>
                    <th>{{Form::label('actions', trans('messages.importdata.actions'), array('class' => 'table-header'))}}</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="elementItem in reportItems">
                    <td>[[ elementItem.file_name ]]</td>
                    <td>
                        <span class="label" ng-class="whatClassIsIt( elementItem )">[[ elementItem.btnText ]]</span>
                    </td>
                    <td ng-bind-html="trustedHtml( elementItem )"></td>
                    <td>
                        <button type="button" class="btn btn-default btn-sm" ng-click="showLogs( elementItem )">
                            {{Form::label('logs', trans('messages.importdata.logs'), array('class' => 'normal-weight'))}}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script type="text/ng-template" id="firstDialog">
        <div class="ngdialog-message">
            <div nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter">
               <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <h3>
                            {{Form::label('select_files', trans('messages.importdata.select_files'), array('class' => 'normal-weight'))}}
                        </h3>
                        <!--<div ng-show="uploader.isHTML5">
                            <div class="well my-drop-zone" nv-file-over="" uploader="uploader">
                                {{Form::label('base_drop_zone1', trans('messages.importdata.base_drop_zone1'), array('class' => 'normal-weight'))}}
                            </div>
                            <div nv-file-drop="" uploader="uploader" options="{ url: '/foo' }">
                                <div nv-file-over="" uploader="uploader" over-class="another-file-over-class" class="well my-drop-zone">
                                    {{Form::label('base_drop_zone2', trans('messages.importdata.base_drop_zone2'), array('class' => 'normal-weight'))}}
                                </div>
                            </div>
                        </div>-->
                        {{Form::label('multiple', trans('messages.importdata.multiple'), array('class' => 'normal-weight'))}}
                        <input type="file" nv-file-select="" uploader="uploader" multiple  /><br/>

                        {{Form::label('single', trans('messages.importdata.single'), array('class' => 'normal-weight'))}}
                        <input type="file" style="width: 225px;text-overflow: ellipsis;" nv-file-select="" uploader="uploader" /><br/>

                        <div style="width: 225px;color: red;text-overflow: ellipsis;overflow: hidden;" ng-show="wrongFileMsg">[[wrongFileMsg]]</div>
                    </div>

                    <div class="col-md-7" style="margin-bottom: 40px;">

                        <h3>
                            {{Form::label('upload_queue', trans('messages.importdata.upload_queue'), array('class' => 'normal-weight'))}}
                        </h3>
                        <p style="margin-top: 0px !important;">{{Form::label('queue_length', trans('messages.importdata.queue_length'), array('class' => 'normal-weight'))}}: [[ uploader.queue.length ]]</p>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="50%">
                                        {{Form::label('name', trans('messages.importdata.name'), array('class' => 'table-header'))}}
                                    </th>
                                    <th ng-show="uploader.isHTML5">
                                        {{Form::label('size', trans('messages.importdata.size'), array('class' => 'table-header'))}}
                                    </th>
                                    <th ng-show="uploader.isHTML5">
                                        {{Form::label('progress', trans('messages.importdata.progress'), array('class' => 'table-header'))}}
                                    </th>
                                    <th>
                                        {{Form::label('status', trans('messages.importdata.status'), array('class' => 'table-header'))}}
                                    </th>
                                    <th>
                                        {{Form::label('actions', trans('messages.importdata.actions'), array('class' => 'table-header'))}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in uploader.queue">
                                    <td><strong>[[ item.file.name ]]</strong></td>
                                    <td ng-show="uploader.isHTML5" nowrap>[[ item.file.size/1024/1024|number:2 ]] MB</td>
                                    <td ng-show="uploader.isHTML5">
                                        <div class="progress" style="margin-bottom: 0;">
                                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok ok-color"></i></span>
                                        <span ng-show="item.isError"><i class="glyphicon glyphicon-remove remove-color"></i></span>
                                    </td>
                                    <td nowrap>
                                        <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                            <span class="glyphicon glyphicon-upload"></span>
                                            {{Form::label('upload', trans('messages.importdata.upload'), array('class' => 'normal-weight'))}}
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                            <span class="glyphicon glyphicon-ban-circle"></span>
                                            {{Form::label('cancel', trans('messages.importdata.cancel'), array('class' => 'normal-weight'))}}
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                                            <span class="glyphicon glyphicon-trash"></span>
                                            {{Form::label('remove', trans('messages.importdata.remove'), array('class' => 'normal-weight'))}}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div>
                            <div>
                                {{Form::label('queue_progress', trans('messages.importdata.queue_progress'), array('class' => 'normal-weight'))}}:
                                <div class="progress" style="">
                                    <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
                                <span class="glyphicon glyphicon-upload"></span>
                                {{Form::label('upload_all', trans('messages.importdata.upload_all'), array('class' => 'normal-weight'))}}
                            </button>
                            <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
                                <span class="glyphicon glyphicon-ban-circle"></span>
                                {{Form::label('cancel_all', trans('messages.importdata.cancel_all'), array('class' => 'normal-weight'))}}
                            </button>
                            <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
                                <span class="glyphicon glyphicon-trash"></span>
                                {{Form::label('remove_all', trans('messages.importdata.remove_all'), array('class' => 'normal-weight'))}}
                            </button>
                            <button type="button" class="btn btn-success btn-s" ng-click="closeUploadDialog()">
                                <span class="glyphicon glyphicon-ok"></span>
                                Process Further
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        </div>
    </script>

</div>

@endsection
