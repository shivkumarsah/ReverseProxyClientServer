@extends('layouts.api')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" ng-controller="generateKey">
    <h1 class="page-header">
      API Public Access
    </h1>
    <div id="inner-loading">
		<img style="margin-left: 38%; margin-top: 10%;" height="150px" width="150px" src='/img/ajax-loader.gif' alt='Loading...' />
	</div>
	<div id="viewPanel">
    	<div class="content-wrap">
			<form name="developerForm" novalidate>
				<div style="width: 90%;" class="form-group">
					<table>
						<tr>
							<td class="header-css"><h4>Destination:</h4></td>
							<td><h4>[[varProtocol]]://[[varHost]]</h4></td>
							<td>
									<select class="dropdown-css" name="myMethod" ng-model="myMethod" ng-change="changeMethod()" ng-options="methodEle.name for methodEle in getMethodName" required >
										<option value=""> Choose method </option>
									</select>
									<span class="help-inline" ng-show="submitted && developerForm.myMethod.$error.required">Select service!</span>

							</td>
						</tr>
						<tr ng-repeat='ele in myMethod.params' ng-show="myMethod.params.length > 0">
							<td></td>
							<td></td>
							<td style="padding-bottom: 5px;" >
								<input name="eleparam" id='[[ele.id]]' type="text" class="input-box" style="width: 99%;" placeholder=" Enter [[ele.name]]">
							</td>
						</tr>

						<tr>
							<td class="header-css"><h4>Authentication:</h4></td>
							<td>Consumer Key:</td>
							<td><input size="60%" name="consumer_key" id="consumer_key" class="input-xxlarge input-box" type="text" value="" ></td>
						</tr>
						<tr>
							<td></td>
							<td>Consumer Secret:</td>
							<td><input size="60%" name="consumer_secret" id="consumer_secret" value="" class="input-xxlarge input-box" type="text" ></td>
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
					<tr ng-show="responseErrorData !== '' && responseData === ''">
						<td></td>
						<td class="help-inline" colspan="2" > [[responseErrorData]] </td>
					</tr>
					<tr ng-show="responseData !== '' && responseErrorData === ''">
						<td colspan="3" > <textarea style="height:200px; width:800px; overflow:scroll;resize:none">[[responseData]]</textarea > </td>
					</tr>
					</table>
				</div>
			</form>
		</div>
	</div>

</div>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="http://openroster.lan/js/jquery.min-1.11.1.js"></script>

<script src="http://openroster.lan/js/jquery.nicescroll.min.js"></script>

<script src="http://openroster.lan/js/bootstrap.min-3.3.0.js"></script>

<script src="http://code.angularjs.org/1.2.6/angular.js"></script>

<script src="http://code.angularjs.org/1.2.4/angular-route.js"></script>

<script src="http://openroster.lan/js/ui-bootstrap-tpls-0.12.0.js"></script>

<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>

<script src="http://openroster.lan/js/restangular.js"></script>

<script src="http://openroster.lan/js/app-inner.js"></script>

<script src="http://openroster.lan/js/angular-file-upload.min.js"></script>

<script src="http://openroster.lan/js/ngDialog.min.js"></script>

<script src="http://openroster.lan/js/jquery.flot.min.js"></script>

<script src="http://openroster.lan/js/jquery.flot.resize.min.js"></script>

<script src="http://openroster.lan/js/jquery.flot.categories.min.js"></script>

<script src="http://openroster.lan/js/FileSaver.js"></script>

<script src="http://openroster.lan/js/openroster.js"></script>

<script src="http://openroster.lan/js/index.js"></script>

@endsection