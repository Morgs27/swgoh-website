<!-- <html lang="en">
  <head>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="622917144422-qfoc6ku55kh1ruavjv7bmaugm33vo22a.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
  <body>
    <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
    <script>
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        console.log("ID: " + profile.getId()); // Don't send this directly to your server!
        console.log('Full Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log("Image URL: " + profile.getImageUrl());
        console.log("Email: " + profile.getEmail());

        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token: " + id_token);

      }
    </script>
   
  </body>
</html> -->

<!DOCTYPE html>
<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
    <script>
      function start() {
        gapi.load('auth2', function() {
          auth2 = gapi.auth2.init({
            client_id: '622917144422-qfoc6ku55kh1ruavjv7bmaugm33vo22a.apps.googleusercontent.com',
            api_key: 'AIzaSyCDoDpnzFsB8WTabHYF7qm3Iy805S_URwU',
            // discovery_docs: ['https://www.googleapis.com/discovery/v1/apis/translate/v2/rest'],
            // Scopes to request in addition to 'profile' and 'email'
            scope: 'https://www.googleapis.com/auth/games',
          });
        });
      }
      function signInCallback(authResult) {
        window.code = authResult['code'];
        if (authResult['code']) {
          $.ajax({
            url: "includes/google_sign_in.php",
            method: "POST",   
            data: {code:authResult['code']},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
          });
        } else {
          console.log('error: failed to obtain authorization code')
        }
      }
    </script>
  </head>
  <body>
    <button id="signinButton">Sign In With Google</button>
    <script>
      $('#signinButton').click(function() {
        // Obtain an authorization code from Google
        auth2.grantOfflineAccess().then(signInCallback);
      });
    </script>
  </body>
</html>
