<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
{{ HTML::script('js/jquery.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/angular.min.js') }}
{{ HTML::script('js/app.js') }}
<script>
    $(function () {
    	$("#loading").hide();
    });
    $( "#loginForm" ).submit(function( event ) {
        <?php if(!empty($launchpadurl)) { ?>
            var childWindow = window.open("<?php echo @$launchpadurl;?>", "Launch Paid Login","scrollbars=yes,left=500, width=600, height=600");
            return false;
        <?php } else { ?>
            window.location = "/users/autologin/";
            return false;
        <?php } ?>
    });
    function getChildValue(status, access_token)  {
        if( status == "0" ) {
            $('.login-submit').before($('<div class="alert alert-error alert-danger">'+access_token+'</div>'));
        } else {
            window.location = "/users/processOauth/"+access_token;
        }
    }
</script>
