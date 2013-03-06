cj.editable.addInputType('checkboxes', {
  
   element : function(settings, original) {
          settings.onblur = 'ignore';
          var myfirst = 0;
          cj.each(settings.data, function(index, value){
            if(!myfirst){
              var input = cj('<input type="checkbox" id="check' + index + '" name="editable-check" value="' + index +  '">');
            }
            else{
              input.append('<input type="checkbox" id="upload" name="editable-check" value="' + index +  '">');
            }
          });
          cj(this).append(input);
          return(input);
      },
      content : function(string, settings, original) {
          /* do nothing */
      },
      plugin : function(settings, original) {
          var form = this;
          form.attr("enctype", "multipart/form-data");
          cj("button:submit", form).bind('click', function() {
              //cj(".message").show();
              cj.ajaxFileUpload({
                  url: settings.target,
                  secureuri:false,
                  fileElementId: 'upload',
                  dataType: 'html',
                  success: function (data, status) {
                      cj(original).html(data);
                      original.editing = false;
                  },
                  error: function (data, status, e) {
                      alert(e);
                  }
              });
              return(false);
          });
      },
    buttons : function(settings, original) { },
    submit  : function(settings, original) { },
    reset   : function(settings, original) { }
});