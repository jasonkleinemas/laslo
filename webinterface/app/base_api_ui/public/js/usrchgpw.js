function changePassword(){
//  window.popup = function(){
    w2popup.open({
      width: 320,
      height: 200,
      title: 'Change Your Password',
      focus: 0,
      body: `
        <div class="w2ui-centered" style="line-height: 1.8">
          <div>
  Current Password: <input class="w2ui-input" id="cp" style="margin-bottom: 5px"><br>
  New Password:     <input class="w2ui-input" id="np" style="margin-bottom: 5px"><br>
  Verify Password:  <input class="w2ui-input" id="vp" style="margin-bottom: 5px"><br>
          </div>
      </div>`,
      actions:{
        Ok(){
  //        console.log('keydown', $('#cp').val())
  //       console.log('keydown', $('#np').val())
  //        console.log('keydown', $('#vp').val())
          $.ajax({
            type: "POST",
            url: "index.php?action=base_api_ui.user_xhr.userChangePassword",
            data: {
              'cp':$('#cp').val(),
              'np':$('#np').val(),
              'vp':$('#vp').val(),
            },
            dataType: 'json',
            success: function(data){
              console.log(data)
              if(data.status == 'error'){
                w2popup.message({
                  width  : 200,
                  height : 60,
                  hideOnClick : true, 
                  html   : '<div class="w2ui-centered"><div style="padding: 10px;">'+data.message+'</div></div>'
                });
              }else{
                w2popup.close()
              }
            },
            error: function(request,error){
              console.log(request)
              console.log(error)
              w2popup.message({
                width  : 200,
                height : 60,
                hideOnClick : true, 
                html   : '<div class="w2ui-centered"><div style="padding: 10px;">Communication to Server failed.</div></div>'
              });
            },
          })
        },
        Cancel(){
          w2popup.close()
        }
      },
  //    onKeydown(event) {
  //      console.log('keydown', event)
  //    },
  //    onMove(event) {
  //      console.log('popup moved', event)
  //    }
    });
//  }
}

