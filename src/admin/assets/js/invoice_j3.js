jQuery(document).ready(function($){   
    $('#bt-choose-activities').on('click',function(evt){
        var self = $(this);
        evt.preventDefault();
        var frm = $('#frm-choose-activities');
        var url = frm.attr('action');
        var mthod = 'post';
        var data = frm.serialize();
        self.button('loading');        
        $.ajax({
            url: url + '&' + data, 
            type: mthod,            
            success: function(data){
                if(data.responseText){
                    alert(data.responseText);
                }
                window.top.location.reload(true);
            },
            error: function(data){
                //console.log(data);
                self.button('reset');   
            }            
        });                   
    });
});