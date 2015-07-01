@if ($currentRoute = Route::currentRouteName()) @endif
<div id="loading">
    <img style="margin-left: 38%; margin-top: 10%;" height="250px" width="250px" src='/img/ajax-loader.gif' alt='Loading...' />
</div>
<div ng-controller="passwordChangeCtrl">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" >
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {{ html_entity_decode( HTML::link("/", HTML::image("img/logo.png", trans('messages.logo'), array('style'=>'margin-top: -5px; height:30px;')) , array('class'=>'navbar-brand') )) }}
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- li ng-click="-clickSetting();">
                        {{Form::label('settings', trans('messages.links.settings') , array('class' => 'setting-css'))}}
                    </li -->
                    <li>{{link_to_route('logout', trans('messages.links.logout'))}}</li>
                </ul>
            </div>
        </div>
    </nav>
    <script type="text/ng-template" id="secondDialog">
        <div style=" color: red;text-align: center; "> [[ respnseDataMsg ]] </div>
        <div class="ngdialog-message">
            <span class="help-inline" style="color: #4cae4c !important;" ng-show=message>[[ message ]]</span>
            <form name="pwdChangeForm" novalidate>
                   <div class="form-group">
                       <label>Old password</label>
                       <input type="text" name="oldpassword" class="form-control" ng-model="user.oldpassword" required />
                       <span class="help-inline" ng-show=field.oPassword>[[ field.oPassword ]]</span>
                   </div>
                   <div class="form-group">
                       <label>New password</label>
                       <input type="password" name="newpassword" class="form-control" ng-model="user.newpassword" required />
                       <span class="help-inline" ng-show=field.nPassword>[[ field.nPassword ]]</span>
                   </div>
                   <div class="form-group">
                       <label>Confirm Password</label>
                       <input type="password" name="confirmPassword" class="form-control"ng-model="user.confirmPassword" />
                       <span class="help-inline" ng-show=field.cPassword>[[ field.cPassword ]]</span>
                   </div>
                   <div class="form-group">
                       <span class="help-inline" style="display: -webkit-box;" ng-show=field.comparePassword>[[ field.comparePassword ]]</span>
                       <button type="text" ng-click="changePassword()" class="btn btn-primary">Save!</button>
                   </div>
            </form>
        </div>
    </script>
</div>