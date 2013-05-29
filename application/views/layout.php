<!DOCTYPE html>
<html>
  <head>
    <script src="http://yui.yahooapis.com/3.10.1/build/yui/yui-min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"> </script>
    <script type="text/javascript" src="//releases.flowplayer.org/5.4.1/flowplayer.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//releases.flowplayer.org/5.4.1/skin/minimalist.css">
  </head>
  <body>
    <div id="fb-root"></div>
    <script>
      // Additional JS functions here
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '546208168769753', // App ID
          channelUrl : 'http://www.davos.com/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        });

        FB.Event.subscribe('auth.authResponseChange', function(response) {
          if (response.status === 'connected') {
            $(function () {
              $('#fb_connect').hide();
              $('#fb_logout').show();
              var access_token = $('input#fb_access_token');
              if ( access_token ) {
                access_token.attr('value', response.authResponse.accessToken);
              }
              var signed_request = $('input#fb_signed_request');
              if ( signed_request ) {
                signed_request.attr('value', response.authResponse.signedRequest);
              }
              var user_id = $('input#fb_user_id');
              if ( user_id ) {
                user_id.attr('value', response.authResponse.userID);
              }
              var submit = $('input#submit-button');
              submit.show();
            });
          } else if (response.status === 'not_authorized') {
            FB.login();
          } else {
            FB.login();
          }
        });
      };

      function login() {
        FB.login(function(response){});
      };

      function logout() {
        FB.logout(function(response){});
      }

      function start() {
        var submit = $('input#submit-button');
        submit.hide();
        var message = $('h1#start_message');
        message.show();
      }

      // Load the SDK asynchronously
      (function(d){
         var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         ref.parentNode.insertBefore(js, ref);
       }(document));
    </script>
    <div class="content-wrapper">
      <a href="#" onclick="logout();" id="fb_logout" style="display:none;">Logout</a>
      <h1>D.A.V.O.S.</h1>
      <?php echo $content ?>
    </div>
  <body>
</html>