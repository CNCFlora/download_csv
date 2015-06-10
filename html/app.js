window.onload = function() {

//  var logged =false;
//
//  $("#logout-bt").hide();
//  $("#app").hide();
//
//  Connect({
//      onlogin: function(user) {
//        for(var i =0;i<user.roles.length;i++) {
//          var context = user.roles[i];
//          for(var j=0;j<context.roles.length;j++) {
//            var role = context.roles[j];
//            var role_name = role.role;
//            if(role_name == 'admin') {
//              logged=true;
//            }
//          }
//        }
//        if(logged) {
//          $("#login-bt").hide();
//          $("#logout-bt").show();
//          $("#app").show();
//        }
//      },
//      onlogout: function() {
//        if(logged) {
//          $("#login-bt").show();
//          $("#logout-bt").hide();
//          $("#app").hide();
//        }
//      }
//  });
//
//  $("#login").submit(function(){
//      return false;
//  });
//  $("#logout-bt").click(function(){
//      Connect.logout();
//  });
//  $("#login-bt").click(function(){
//      Connect.login();
//  });

    $("#login-bt").hide();
    $("#logout-bt").show();
    $("#app").show();

    $.getJSON("complete.php", function (data) {
        for(var d=0;d<data.length;d++) {
            console.log(data)
            $("#src").append("<option value='"+data[d]._id+"'>"+data[d].title.toUpperCase().replace("_"," ")+"</option>");
        }}).fail(function(jqxhr, textStatus, error) {
            var err = textStatus + ", " + error;
            console.log( "Request Failed: " + err );
        });

};
