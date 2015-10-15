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
        <!--li @if($currentRoute == 'sslsettings') class="active" @endif ng-show="<?php echo (Config::get('launchpad.login_required')==false && Config::get('proxy.nginx_protocol')=='https')?1:0; ?>"-->
        <li @if($currentRoute == 'sslsettings') class="active" @endif ng-hide="<?php echo Config::get('launchpad.login_required'); ?>">
            {{link_to_route('sslsettings', trans('messages.links.ssl_setting'))}}
        </li>
    </ul>
</div>