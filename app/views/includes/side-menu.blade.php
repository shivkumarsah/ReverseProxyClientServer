@if ($currentRoute = Route::currentRouteName()) @endif
<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <!-- li @if($currentRoute == 'dashboard') class="active" @endif >
            {{link_to_route('dashboard', trans('messages.links.overview'))}}
        </li-->
        <li @if($currentRoute == 'applications') class="active" @endif >
            {{link_to_route('applications', trans('messages.links.manage_application'))}}
        </li>
        <li @if($currentRoute == 'proxysettings') class="active" @endif >
            {{link_to_route('proxysettings', trans('messages.links.proxy_setting'))}}
        </li>
           
        <!--li ng-click="isCollapsed=!isCollapsed" style="padding-left: 21px;margin-top: 10px;cursor: pointer;">
            <input type="hidden" id="currentRoute" value="<?php echo $currentRoute ?>">
            <span><a href="#">Main menu</a></span>
            &nbsp;<span class="glyphicon" ng-class="isCollapsed ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up'" style="float:right;color:#6498c8;margin-right:10px; font-size: 18px;"></span>
        </li-->
    </ul>

    <!-- ul collapse="isCollapsed" style="list-style-type: none;" class="sub-nav nav-sidebar" style="padding-left: 10px !important;">
        <li style="margin-bottom:5px;" @if($currentRoute == 'login') class="active" @endif >{{link_to_route('login', "Sub menu 1")}}</li>
        <li style="margin-bottom:5px;" @if($currentRoute == 'login') class="active" @endif >{{link_to_route('login', "Sub menu 2")}}</li>
    </ul-->
</div>