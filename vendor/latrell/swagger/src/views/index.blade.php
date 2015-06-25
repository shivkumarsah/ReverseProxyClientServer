<!DOCTYPE html>
<html>
<head>
  <title>{{ Config::get('swagger::title'); }}</title>
  <!-- <link href='https://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/> -->
  <link href="{{ asset('packages/latrell/swagger/css/fonts.css') }}" rel='stylesheet' type='text/css'/>
  <link href="{{ asset('packages/latrell/swagger/css/reset.css') }}" media='screen' rel='stylesheet' type='text/css'/>
  <link href="{{ asset('packages/latrell/swagger/css/screen.css') }}" media='screen' rel='stylesheet' type='text/css'/>
  <link href="{{ asset('packages/latrell/swagger/css/reset.css') }}" media='print' rel='stylesheet' type='text/css'/>
  <link href="{{ asset('packages/latrell/swagger/css/screen.css') }}" media='print' rel='stylesheet' type='text/css'/>
  <script src="{{ asset('packages/latrell/swagger/lib/shred.bundle.js') }}" type="text/javascript"></script>
  <script src="{{ asset('packages/latrell/swagger/lib/jquery-1.8.0.min.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/jquery.slideto.min.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/jquery.wiggle.min.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/jquery.ba-bbq.min.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/handlebars-1.0.0.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/underscore-min.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/backbone-min.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/swagger.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/swagger-ui.js') }}" type='text/javascript'></script>
  <script src="{{ asset('packages/latrell/swagger/lib/highlight.7.3.pack.js') }}" type='text/javascript'></script>

  <!-- enabling this will enable oauth2 implicit scope support -->
  <script src="{{ asset('packages/latrell/swagger/lib/swagger-oauth.js') }}" type='text/javascript'></script>

  <script type="text/javascript">
    $(function () {
      window.swaggerUi = new SwaggerUi({
      url: "{{ URL::route('swagger_docs') }}",
      dom_id: "swagger-ui-container",
      supportedSubmitMethods: ['get', 'post', 'put', 'delete'],
      onComplete: function(swaggerApi, swaggerUi){
        log("Loaded SwaggerUI");

        if(typeof initOAuth == "function") {
        	/*
            initOAuth({
              clientId: "your-client-id",
              realm: "your-realms",
              appName: "your-app-name"
            });
            */
        }
        $('pre code').each(function(i, e) {
          hljs.highlightBlock(e)
        });
      },
      onFailure: function(data) {
        log("Unable to Load SwaggerUI");
      },
      docExpansion: "none"
    });

    $('#input_apiKey').change(function() {
      var key = $('#input_apiKey')[0].value;
      log("key: " + key);
      if(key && key.trim() != "") {
        log("added key " + key);
        window.authorizations.add("key", new ApiKeyAuthorization("api_key", key, "query"));
      }
    })
    window.swaggerUi.load();
  });
  </script>
</head>

<body class="swagger-section">
<div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="http://swagger.wordnik.com">swagger</a>
    <form id='api_selector'>
      <div class='input icon-btn'>
        <img id="show-pet-store-icon" src="{{ asset('packages/latrell/swagger/images/pet_store_api.png') }}" title="Show Swagger Petstore Example Apis">
      </div>
      <div class='input icon-btn'>
        <img id="show-wordnik-dev-icon" src="{{ asset('packages/latrell/swagger/images/wordnik_api.png') }}" title="Show Wordnik Developer Apis">
      </div>
      <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
      <div class='input'><input placeholder="api_key" id="input_apiKey" name="apiKey" type="text"/></div>
      <div class='input'><a id="explore" href="#">Explore</a></div>
    </form>
  </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
