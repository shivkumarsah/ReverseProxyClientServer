@if ($currentRoute = Route::currentRouteName()) @endif
<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li @if($currentRoute == 'dashboard') class="active" @endif >{{link_to_route('dashboard', trans('messages.links.overview'))}}</li>
        <li @if($currentRoute == 'developers' || $currentRoute == 'apitokens') class="active" @endif >{{link_to_route('developers', trans('messages.links.developers'))}}</li>
        <li @if($currentRoute == 'importdata') class="active" @endif >{{link_to_route('importdata', trans('messages.links.import_data'))}}</li>
           
        <li ng-click="isCollapsed=!isCollapsed" style="padding-left: 21px;margin-top: 10px;cursor: pointer;">
            <input type="hidden" id="currentRoute" value="<?php echo $currentRoute ?>">
            <span><a href="#">{{trans('messages.links.view_data')}}</a></span>
            &nbsp;<span class="glyphicon" ng-class="isCollapsed ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up'" style="float:right;color:#6498c8;margin-right:10px; font-size: 18px;"></span>
        </li>
    </ul>

    <ul collapse="isCollapsed" style="list-style-type: none;" class="sub-nav nav-sidebar" style="padding-left: 10px !important;">
        <li style="margin-bottom:5px;" @if($currentRoute == 'schools') class="active" @endif >{{link_to_route('schools', trans('messages.links.schools'))}}</li>
        <li style="margin-bottom:5px;" @if($currentRoute == 'teachers') class="active" @endif >{{link_to_route('teachers', trans('messages.links.teachers'))}}</li>
        <li style="margin-bottom:5px;" @if($currentRoute == 'students') class="active" @endif >{{link_to_route('students', trans('messages.links.students'))}}</li>
        <!--<li @if($currentRoute == 'subjects') class="active" @endif >{{link_to_route('subjects', trans('messages.links.subjects'))}}</li>-->
        <li style="margin-bottom:5px;" @if($currentRoute == 'courses') class="active" @endif >{{link_to_route('courses', trans('messages.links.courses'))}}</li>
        <li  style="margin-bottom:5px;"@if($currentRoute == 'enrollments') class="active" @endif >{{link_to_route('enrollments', trans('messages.links.enrollments'))}}</li>
    </ul>
</div>