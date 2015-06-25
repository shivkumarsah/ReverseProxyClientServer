@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  ng-controller="CourseCtrl">

    <h1 class="page-header">{{trans('messages.courses.page_title')}}</h1>
    <div class="row">
        <div class="col-md-8 col-sm-12" style="text-align: left;margin-bottom: 10px;">
            <span style="font-size: 12px;!important;float: left;" ng-show="courses.length !== 0">
                    Page
                    <a tabindex="1" href="#" class="btn btn-sm default prev" ng-disabled = "changePageIndex.toString() === '1' || disabledNavigation" style="height:28px;margin-top: -3px;" title="Prev" ng-click=previousPage()>
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <input tabindex="2" ng-disabled = "disabledNavigation" ng-model='changePageIndex' class="pagination-panel-input form-control input-mini" maxlength="5" style="text-align:center;margin-left:5px; padding:2px;" type="text">
                    <a tabindex="3" href="#" class="btn btn-sm default next" ng-disabled = "changePageIndex >= totalPages || disabledNavigation" style="height:28px;margin-top: -3px;" title="Next" ng-click=nextPage()>
                        <i class="fa fa-angle-right"></i>
                    </a>
            </span>
            <span style="font-size: 12px;!important;float: left;margin-top:7px;" ng-show="courses.length !== 0">
                &nbsp;of [[totalPages]] | View &nbsp;
            </span>
            <span style="font-size: 12px;!important;float: left;margin-top:0px;" ng-show="courses.length !== 0">
                <select tabindex="4" class="form-control input-xsmall input-sm input-inline page-records-drop-down" ng-change="getNumberOfRecord()" name="filterCriteria.itemsPerPage" ng-model="filterCriteria.itemsPerPage" ng-options="recordEle for recordEle in listNoRecord" >
                </select>
            </span>
            <span style="font-size: 12px;!important;float: left;margin-top:7px;" ng-show="courses.length !== 0">
                &nbsp;records | Found total [[coursesCount]] [[ showRecord ]]
             <span>
        </div>
        <div class="col-md-4 col-sm-12" style="margin-bottom: 10px;">

            <span style="font-size: 12px;!important;float: right;margin-top:0px;">
                &nbsp;
                <button tabindex="6" type="button" class="btn btn-success btn-sm" ng-disabled='!myAction || courses.length === 0' style="padding: 4px 10px;" ng-click="exportData( )">
                    <label for="save" class="normal-weight">Export</label>
                </button>
                &nbsp;
            </span>

            <span style="font-size: 12px;!important;float: right;margin-top:0px;" >
                <select ng-disabled='courses.length === 0' tabindex="5" class="dropdown-css page-records-drop-down" ng-change="changeAction()" name="myAction" ng-model="myAction" ng-options="actionEle.name for actionEle in listAction" >
                    <option value=""> Choose action </option>
                </select>
            </span>

        </div>
    </div>
    <div id="viewPanel">
        <div class="panel panel-default" id="exportable">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th ng-repeat="header in headers" class="header-background">
                            <sort-by onsort="onSort" sortdir="filterCriteria.sortDir" sortedby="filterCriteria.sortedBy" sortvalue="[[ header.value ]]">
                                [[ header.title ]]<div style="display: -webkit-inline-box;float: right;"><div ng-class="sortingClass( header )"></div></div>
                            </sort-by>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <!--<input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.course_id" type="text" placeholder="Course Id"/>-->
                        </td>
                        <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.course_name" type="text" placeholder="Course Name"/>
                        </td>
                        <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.subjects" type="text" placeholder="Subject"/>
                        </td>
                         <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.start_date" type="text" placeholder="Start Date"
                            datepicker-popup="d MMM yyyy" datepicker-append-to-body="true" is-open="datepicker1.isOpen" ng-click="datepicker1.isOpen = true"/>
                        </td>
                        <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.end_date" type="text" placeholder="End Date"
                            datepicker-popup="d MMM yyyy" datepicker-append-to-body="true" is-open="datepicker2.isOpen" ng-click="datepicker2.isOpen = true"/>
                        </td>
                        <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.school_name" type="text" placeholder="School Name"/>
                        </td>
                        <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.first_name" type="text" placeholder="Teacher First Name"/>
                        </td>
                        <td>
                            <input class="input-control-css" on-enter-blur on-blur-change="filterResult()" ng-model="filterCriteria.last_name" type="text" placeholder="Teacher Last Name"/>
                        </td>

                    </tr>
                    <tr ng-repeat="course in courses ">
                        <td>[[course.course_id]]</td>
                        <td class="wrapword">[[course.course_name]]</td>
                        <td class="wrapword">[[course.subjects]]</td>
                        <td>[[course.start_date | date:'d MMM yyyy']]</td>
                        <td>[[course.end_date | date:'d MMM yyyy']]</td>
                        <td class="wrapword">[[course.school_name]]</td>
                        <td class="wrapword">[[course.first_name]]</td>
                        <td class="wrapword">[[course.last_name]]</td>
                    </tr>
                    <tr ng-show="courses.length === 0" >
                        <td colspan="8"><h3>{{trans('messages.no_record_found')}}</h3></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
