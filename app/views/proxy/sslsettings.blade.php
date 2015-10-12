@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" ng-controller="sslCtrl">
	<h1 class="page-header">{{Form::label('page_header',
		trans('messages.sslsettings.page_title'), array('class' =>
		'normal-weight'))}}</h1>
	<div id="inner-loading">
		<img style="margin-left: 38%; margin-top: 10%;" height="150px"
			width="150px" src='/img/ajax-loader.gif' alt='Loading...' />
	</div>
	<div flash-message="5000" ></div>
	<div class="table-responsive" id="viewPanel">
		<form name="settingForm" novalidate>
			<table class="table table-striped">
				<tbody>
					<tr>
						<td>Certificate File Path</td>
						<td><input size="60%" name="certificate_pem" id="certificate_pem" type="text"
								   class="input-xxlarge input-box" data-ng-model="settingData.certificate_pem"
								   ng-init="settingData.certificate_pem='<?php echo @$result['certificate_pem'];?>'"
								   placeholder="Please enter ssl certificate path" ng-required="1" />
							<div ng-show="settingForm.certificate_pem.$error.required && showErrorMsg" style="font-size: 12px; ! important; float: left; color: red;">Please enter certificate path.</div>
                        </td>
					</tr>
					<tr>
						<td>Certificate Key File Path</td>
						<td><input size="60%" name="certificate_key" id="certificate_key" type="text"
								   data-ng-model="settingData.certificate_key" class="input-xxlarge input-box"
								   ng-init="settingData.certificate_key='<?php echo @$result['certificate_key'];?>'"
								   placeholder="Please enter ssl certificate key path" />
							<div ng-show="settingForm.certificate_key.$error.required && showErrorMsg" style="font-size: 12px; ! important; float: left; color: red;">Please enter certificate path.</div>
						</td>
					</tr>
					<tr>
						<td></td>
						<td style="padding-top: 20px; padding-bottom: 15px;">
							<button type="button" class="btn btn-large btn-primary" id="save_settings" ng-click="saveSettings(settingForm)">
								<i class="icon-bolt"></i> Update Settings
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
@endsection
