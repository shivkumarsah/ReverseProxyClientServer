@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"
	ng-controller="settingsCtrl">
	<h1 class="page-header">{{Form::label('page_header',
		trans('messages.settings.page_title'), array('class' =>
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
						<td>Reverse Proxy Address</td>
						<td><input size="60%" name="proxy_url" id="proxy_url"
							class="input-xxlarge input-box" type="text" data-ng-model="settingData.proxy_url"
							ng-init="settingData.proxy_url='<?php echo @$result['proxy_url'];?>'" placeholder="Please enter reverse proxy address" ng-required="1" />
							<div ng-show="settingForm.proxy_url.$error.required && showErrorMsg" style="font-size: 12px; ! important; float: left; color: red;">Please enter reverse proxy address.</div>
                        </td>
					</tr>
					<tr>
						<td>API Key</td>
						<td><input size="60%" name="api_key" id="api_key" type="text"  
							data-ng-model="settingData.api_key" ng-init="settingData.api_key='<?php echo @$result['api_key'];?>'" placeholder="Please enter API key" 
							class="input-xxlarge input-box" />
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
