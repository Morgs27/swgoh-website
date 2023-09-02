<?php include 'header.php';
echo Date('F j, Y, g:i a',1667828510);
echo 5182383/ (60*60*24);
?>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '5463163363765318',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v14.0'
    });
    
    FB.getLoginStatus(function(response) {
        console.log(response);
      if (response.status === 'connected') {
        var accessToken = response.authResponse.accessToken;
      } 
    } );
    
    pageAccess_token = 'EABNot9elrEYBAIcfywkDAOvn9ig5tVg9cvFmE2D5IndQyb7i4CVQZAk26jyPv7VTKgyiyd9ZAKllDejTe8BrDycl0mkKnvhi3nxQdNZCQNzxH1ugdrFqGVU84SYXhGTZCyJLlwfiaLP6feTqxv3lpfbtYpGZC2trReWdxXIamZCI5hkZAQWYBdvu8OeLPyMF6gZD'
    
    FB.api(
        '/2218963458222249/feed',
        'GET',
        {access_token: pageAccess_token},
      function(response) {
          console.log(response);
      }
    );

  };
  

</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

<Button style = 'width: 100%; height: 200px;' onclick = "getToken()">
    Get Token
</Button>