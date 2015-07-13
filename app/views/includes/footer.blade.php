<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js') }}
{{ HTML::script('http://code.angularjs.org/1.2.6/angular.js') }}
{{ HTML::script('js/app.js') }}
<script>
    $(function () {
    	$("#loading").hide();
    });
    $( "#loginForm" ).submit(function( event ) {
		var childWindow = window.open("<?=$launchpadurl;?>", "Launch Paid Login","scrollbars=yes,left=500, width=600, height=600");
        return false;
    });
    function getChildValue(status, access_token)  {
        if( status == "0" ) {
            $('.login-submit').before($('<div class="alert alert-error alert-danger">'+access_token+'</div>'));
        } else {
            window.location = "/users/processOauth/"+access_token;
        }
    }
</script>
