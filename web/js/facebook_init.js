( function ( $ ) {
    "use strict";
    
    window.fbAsyncInit = function() {
	FB.init({
            appId      : '494743687272365', // App ID
            channelUrl : 'www.car-people.be/', // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
          });

          
        var facebookLogin = function(){
              FB.api('/me', function(response) {
                  console.log(response);
                  $.ajax({
                      url:location.origin+'/PFE-CodeIgniter/ajax/facebook_login/',
                      type:'POST',
                      dataType: "json",
                      data: { email: response.email, id:response.id },
                      success: function($data){
                          console.log($data);
                          if($data){
                              //location.reload(); 
                          }
                          else{
                              window.location.replace(location.origin+'/PFE-CodeIgniter/inscription');
                          }
                      }
                  });
              },{scope: 'email,user_about_me,user_birthday'});
          }
          
          var facebookRegister = function(){
              FB.api('/me', function(response) {
                  console.log(response);
                  $.ajax({
                      url:location.origin+'/PFE-CodeIgniter/ajax/facebook_register/',
                      type:'POST',
                      dataType: "json",
                      data: { email: response.email, id:response.id ,nom:response.last_name, prenom:response.first_name, sexe:response.gender, naissance:response.birthday},
                      success: function($data){
                          console.log($data);
                          if($data){
                            window.location.replace(location.origin+'/PFE-CodeIgniter/user/profil/'+$data);
                          }
                      }
                  });
              },{scope: 'email,user_about_me,user_birthday'});
          }
          
          $("#facebook_login").on('click',function(){
              FB.getLoginStatus(function(response) {
                  if (response.authResponse) {
                      console.log(response);
                      facebookLogin();                  
                  } else {
                       //---- encore jamais donner autorisation
                       console.log("1er connexion");
                       FB.login(function(response) {
                          if (response.authResponse){
                              //L'utilisateur a autorisé l'application
                              console.log(response);
                              facebookLogin();
                          } else {
                              //L'utilisateur n'a pas autorisé l'application
                              console.log("pas autorisé");
                          };
                      }, {scope: 'email,user_about_me,user_birthday'});
                  }
              });  
          });
          
          $("#facebook_logout").on('click', function(){
              console.log("deco");
              FB.getLoginStatus(function(response) {
                  if (response.authResponse) {
                      $.ajax({
                          url:location.origin+'/PFE-CodeIgniter/ajax/facebook_logout/',
                          type:'POST',
                          success: function($data){
                              console.log($data);
                              if($data){
                                 FB.logout(function(response) {
                                      // user is now logged out
                                      location.reload();
                                  });  
                              }
                          }
                      });
                  }
              });
          });
          
          $(".facebook_register>img").on('click',function(){
              FB.getLoginStatus(function(response) {
                  if (response.authResponse) {
                      console.log("connexion autorisé");
                      facebookRegister();                  
                  } else {
                       //---- encore jamais donner autorisation
                       console.log("1er connexion ou pas connecté");
                       FB.login(function(response) {
                          if (response.authResponse){
                              //L'utilisateur a autorisé l'application
                              console.log("autorisé ou connecté");
                              facebookRegister();
                          } else {
                              //L'utilisateur n'a pas autorisé l'application
                              console.log("pas autorisé");
                          };
                      }, {scope: 'email,user_about_me,user_birthday'});
                  }
              }); 
          });
          
        };
        // Load the SDK asynchronously
        (function(d){
           var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/fr_FR/all.js";
           ref.parentNode.insertBefore(js, ref);
         }(document));
    
}( jQuery ) );