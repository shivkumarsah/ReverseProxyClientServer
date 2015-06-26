<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
{{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js') }}
{{ HTML::script('http://code.angularjs.org/1.2.6/angular.js') }}
{{ HTML::script('js/app.js') }}
<script>
 $(function () {
    $("#loading").hide();
 });
</script>
<script>
    $( "#loginForm" ).submit(function( event ) {
        var requrl = "https://launchpad.classlink.com/oauth2/auth/?clientid=c1435056532565575a2ef69eaf3a8e637bab5b4bea28bd&scopes=profile&redirecturl=http%3A%2F%2Fbetaproxyadmin.oneroster.com%2Fusers%2Flunchpadtoken";
        //var requrl = "https://launchpad.classlink.com/oauth2/auth/?clientid=c1435056532565575a2ef69eaf3a8e637bab5b4bea28bd&scopes=profile&redirecturl=http://betaproxyadmin.oneroster.com/users/lunchpadtoken";
        var childWindow = window.open(requrl, "Launch Paid Login","scrollbars=yes,left=500, width=600, height=600");
        return false;
    });
    function getChildValue(status, access_token)  {
        //console.log(status+"--"+access_token)
        if( status == "0" ) {
            $('.login-submit').before($('<div class="alert alert-error alert-danger">'+access_token+'</div>'));
        } else {
            window.location = "/users/processOauth/"+access_token;
        }
    }
</script>