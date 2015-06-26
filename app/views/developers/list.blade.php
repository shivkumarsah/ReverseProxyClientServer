@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" ng-controller="DeveloperCtrl">
    <h1 class="page-header">
        {{Form::label('page_header', trans('messages.developers.page_header'), array('class' => 'normal-weight'))}}
    </h1>



    <div class="row">
            <div class="col-md-4 col-sm-6" style="margin-bottom: 10px;">
                <div>
                    <button type="button" class="btn btn-success" ng-click="addNewData()">
                        {{Form::label('add_developer', trans('messages.developers.add_developer'), array('class' => 'normal-weight'))}}
                    </button>
                </div>
                <div style="font-size: 12px;!important;float: left;color:red;margin-top:2px;" ng-show="inValidPage">Please enter valid page number.</div>
            </div>
            <div class="col-md-8 col-sm-18" style="text-align: left;margin-bottom: 10px;">

                <span style="font-size: 12px;!important;float: right;margin-top:7px;" ng-show="items.length !== 0">
                   &nbsp;records | Found total [[developersCount]] [[showRecord]]
                </span>
                <span style="font-size: 12px;!important;float: right;margin-top:0px;" ng-show="items.length !== 0">
                    <select tabindex="4" class="form-control input-xsmall input-sm input-inline page-records-drop-down" ng-change="getNumberOfRecord()" name="itemsPerPage" ng-model="itemsPerPage" ng-options="recordEle for recordEle in listNoRecord" >
                    </select>
                </span>

                 <span style="font-size: 12px;!important;float: right;margin-top:7px;" ng-show="items.length !== 0">
                    &nbsp;of [[totalPages]] | View &nbsp;
                </span>

                <span style="font-size: 12px;!important;float: right;" ng-show="items.length !== 0">
                        Page
                        <a tabindex="1" href="#" class="btn btn-sm default prev" ng-disabled = "changePageIndex.toString() === '1' || disabledNavigation" style="height:28px;margin-top: -3px;" title="Prev" ng-click=previousPage()>
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <input tabindex="2" ng-disabled = "disabledNavigation" ng-model='changePageIndex' class="pagination-panel-input form-control input-mini" maxlength="5" style="text-align:center; margin-left:5px; padding:2px;" type="text">
                        <a tabindex="3" href="#" class="btn btn-sm default next" ng-disabled = "changePageIndex >= totalPages || disabledNavigation" style="height:28px;margin-top: -3px;" title="Next" ng-click=nextPage()>
                            <i class="fa fa-angle-right"></i>
                        </a>
                </span>
            </div>

        </div>

    <div id="viewPanel" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:20%;">{{Form::label('name', trans('messages.developers.name'), array('class' => 'table-header'))}}/Email</th>
                    <th style="width:30%;">{{Form::label('api', trans('messages.developers.api'), array('class' => 'table-header'))}}</th>
                    <th style="width:20%;">Consumer Secret</th>
                    <th style="width:30%;">{{Form::label('actions', trans('messages.developers.actions'), array('class' => 'table-header'))}}</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="item in items">
                    <td class="wrapword">
                        <span style="cursor:pointer;text-decoration: underline;" ng-click="apiTokens( item )">[[item.developer_name]]</span><br>
                        <span style="font-style: italic">[[item.email]]</span>
                    </td>
                    <td>
                        <div class="wrapword" style="width: 80%;float: left;overflow-wrap: white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap; word-wrap: break-word;">
                            [[item.api_key]]
                        </div>
                        <div style="width: 18%;float: right;" >
                            <button type="button" class="btn btn-default btn-sm" ng-click="updateConsumerKey( $index )"><i class="fa fa-refresh"></i></button>
                        </div>
                    </td>
                    <td class="wrapword">
                        <span style="display: -webkit-box;">[[item.api_secret]]</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-default btn-sm" ng-click="editMode = true; editItem( item, $index )" >
                            {{Form::label('edit', trans('messages.developers.edit'), array('class' => 'normal-weight'))}}
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" ng-click="removeItem( item, $index )">
                            {{Form::label('delete', trans('messages.developers.delete'), array('class' => 'normal-weight'))}}
                        </button>
                        <button type="button" class="btn btn-success btn-sm" ng-click="assignSchool( item )">
                            Assign School
                        </button>
                    </td>
                </tr>
                 <tr ng-show="items.length === 0" >
                    <td colspan="3"><h3>{{trans('messages.no_record_found')}}</h3></td>
                 </tr>
            </tbody>
        </table>
    </div>

    <!-- Delete confirmation popup : Code Start -->
    <script type="text/ng-template" id="modalDialogId">
        <div class="ngdialog-message">
            <span style="text-align:center;"><h4>Would you like to delete developer?</h4></span>
            <span style="text-align:center;"class="wrapword"><h5>"[[developerName]]"</h5></span>
        </div>
        <div class="ngdialog-buttons" style="padding-right: 138px;">
                <button tabindex="5" type="button" class="btn btn-default btn-sm" ng-click="closeThisDialog()" style="float: right;margin-right: 5px;" >Cancel</button>
                <button tabindex="6" type="button" class="btn btn-danger btn-sm" ng-click="confirm(confirmValue)" style="float: right;margin-right: 5px;">Delete</button>
        </div>
    </script>
    <!-- Delete confirmation popup : Code End-->
    <!-- Add/Edit Developer popup : Code Start-->
    <script type="text/ng-template" id="modalDialogAddEdit">
        <div class="ngdialog-message">
            <h4>[[ eventMode ]] </h4>
            <div>
                <span style="display: -webkit-box;">
                    <label for="name">Name: </label>
                </span>
                <span style="display: -webkit-box;">
                    <input tabindex="7" type="text" show-focus=true ng-model="developer_name" class="add-developer-input" ng-change="keydown('name')"/>
                </span>
                <span ng-show="errorName" class="error error-color" style="display: -webkit-box;">[[errorMsgName]]</span>
            </div>
            <div>
                <span style="display: -webkit-box;">
                    <label for="email">Email: </label>
                </span>
                <span style="display: -webkit-box;">
                    <input tabindex="7" type="text" ng-model="email" class="add-developer-input" ng-change="keydown('email')"/>
                </span>
                <span ng-show="errorEmail" class="error error-color" style="display: -webkit-box;">[[errorMsgEmail]]</span>
            </div>
            <div ng-show="showSecret">
                <span style="display: -webkit-box;">
                    <label for="name">Consumer Secret: </label>
                </span>
                <span style="display: -webkit-box;">
                    <input tabindex="9" type="text" ng-model="consumerSecret" class="add-developer-input" ng-change="keydown('secret')"/>
                </span>
                <span ng-show="errorSecret" class="error error-color" style="display: -webkit-box;">[[errorMsgSecret]]</span>
            </div>


        </div>
        <div class="ngdialog-buttons">
            <button  tabindex="12" type="button" class="btn btn-default btn-sm" ng-click="closeThisDialog()" style="float: right;margin-right: 5px;" >Cancel</button>
            <button tabindex="11" ng-hide="showUpdateBtn" type="button" class="btn btn-success btn-sm" ng-click="update()" style="float: right;margin-right: 5px;">Update</button>
            <button tabindex="10" ng-hide="!showUpdateBtn" type="button" class="btn btn-success btn-sm" ng-click="save()" style="float: right;margin-right: 5px;">Save</button>
        </div>
    </script>
    <!-- Add/Edit Developer popup : Code End-->
    <!-- Assigns schools to developer popup : Code Start-->
    <script type="text/ng-template" id="assignSchoolDialog">
            <h3 style="margin-top:0px;">
                Developer: [[developerName]]
            </h3>
            <div class="ngdialog-message table-responsive" style="height:250px; overflow:auto;">
               <table class="table table-striped">
                   <thead>
                       <tr>
                           <th style="width:12%; vertical-align: top;">Select All<input style="margin-left: 28%!important;" type="checkbox" class="checkBoxCss" ng-model="school.allItemsSelected" ng-change="selectAll()"></th>
                           <th style="width:20%; vertical-align: top;">School Id</th>
                           <th style="width:68%;vertical-align: top;">School Name</th>
                       </tr>
                   </thead>
                   <tbody>
                       <tr ng-repeat="entity in school.entities" ng-class="activeClass( entity )">
                           <td><input style="margin-left: 28%!important;" type="checkbox" class="checkBoxCss" ng-model="entity.isChecked" ng-change="selectEntity()"></td>
                           <td>[[entity.school_id]]</td>
                           <td>[[entity.school_name]]</td>
                       </tr>
                   </tbody>
               </table>
            </div>
            <div class="ngdialog-buttons">
                  <span style="float: right;margin-right: 5px;">
                      <button type="button" class="btn btn-default btn-sm" ng-click="closeDialogBox()">Cancel</button>
                  </span>
                  <span style="float: right;margin-right: 5px;">
                      <button type="button" class="btn btn-success btn-sm" ng-disabled="!btnAssignStatus" ng-click='assignSchool()'>Assign</button>
                  </span>
            </div>
        </script>
    <!-- Assigns schools to developer popup : Code End-->
</div>
@endsection