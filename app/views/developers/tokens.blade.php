@extends('layouts.layout')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" ng-controller="generateKey">
    <h1 class="page-header">
        {{Form::label('page_header', trans('messages.developers.generatekey'), array('class' => 'normal-weight'))}}
    </h1>
    <div id="inner-loading">
		<img style="margin-left: 38%; margin-top: 10%;" height="150px" width="150px" src='/img/ajax-loader.gif' alt='Loading...' />
	</div>
	<button type="button" class="btn btn-large btn-success" id="execute_button" ng-click="redirectToswagger()"">
		<i class="icon-bolt"></i> Swagger
	</button>
	<div id="viewPanel">
    	<div class="content-wrap">
			<form name="developerForm" novalidate>
				<div style="width: 90%;" class="form-group">
					<table>
						<tr>
							<td class="header-css"><h4>Destination:</h4></td>
							<td><h4>[[varProtocol]]://[[varHost]]</h4></td>
							<td>
									&nbsp;<select class="dropdown-css" name="myMethod" ng-model="myMethod" ng-change="changeMethod()" ng-options="methodEle.name for methodEle in getMethodName" required >
										<option value=""> Choose method </option>
									</select>
									<span class="help-inline" ng-show="submitted && developerForm.myMethod.$error.required">Select service!</span>

							</td>
						</tr>
						<tr ng-repeat='ele in myMethod.params' ng-show="myMethod.params.length > 0">
							<td></td>
							<td></td>
							<td style="padding-bottom: 5px;" >
								&nbsp;<input name="eleparam" id='[[ele.id]]' type="text" class="input-box" style="width: 98%;" placeholder=" Enter [[ele.name]]">
							</td>
						</tr>

						<tr>
							<td class="header-css"><h4>Authentication:</h4></td>
							<td>Consumer Key:</td>
							<td>&nbsp;<input size="60%" name="consumer_key" id="consumer_key" class="input-xxlarge input-box" type="text" value="<?php echo $result['api_key'];?>" readonly></td>
						</tr>
						<tr>
							<td></td>
							<td>Consumer Secret:</td>
							<td>&nbsp;<input size="60%" name="consumer_secret" id="consumer_secret" value="<?php echo $result['api_secret'];?>" class="input-xxlarge input-box" type="text" readonly></td>
						</tr>

					<tr>
						<td></td>
						<td style="padding-top:20px;padding-bottom:15px;">
						<button type="button" class="btn btn-large btn-primary" id="execute_button" ng-click="getDataFromServer(developerForm.$valid)">
							<i class="icon-bolt"></i> Launch Request
						</button>
						</td>
						<td></td>
					</tr>
					<tr ng-show="responseErrorData !== '' && responseData === '' && submitted">
						<td></td>
						<td colspan="2"> <span class="help-inline"> [[responseErrorData]] </span> </td>
					</tr>
					<tr ng-show="responseData !== '' && responseErrorData === ''">
						<td colspan="3" > <textarea style="height:200px; width: 100%; overflow:scroll;resize:none">[[responseData]]</textarea > </td>
					</tr>
					</table>
				</div>
			</form>
		</div>
	</div>

</div>
@endsection