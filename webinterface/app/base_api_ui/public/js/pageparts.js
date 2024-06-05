$(document).ready(function(){
  $("#pagePartsChangePassword").click();
});
//------------------------------------------------------------------
function pagePartsChangePassword(){
  $.getScript("base_api_ui/js/usrchgpw.js", function(){
    changePassword()
  });
}