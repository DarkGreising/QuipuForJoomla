window.iw = {
    i18n:{},
    urls:{}
}
window.dirtyForm = false;
/*
window.addEvent('domready', function(){
   
    var txts = $$('input.focus');
    if(txts.length > 0){
        try{
            txts[0].focus();
        }catch(x){}        
    }
    else{
        txts = $$('input[type=text]');
        if(txts.length > 0) try{
            txts[0].focus();
        }catch(x){}        
    }
    
    var spinner = new Spinner();
    $$('form.item input').addEvent('change',function(){
        window.dirtyForm = true;
    });
    $$('form.item textarea').addEvent('change',function(){
        window.dirtyForm = true;
    });
    $$('form.item select').addEvent('change',function(){
        window.dirtyForm = true;
    });

});*/
window.onbeforeunload = function(){ 
    if(window.dirtyForm){
        return window.iw.i18n.unsavedchanges;
    }
    
}; 
jQuery(document).ready(function($){   
   
    var quipu_ajxaction_clicked = function(evt){
        evt.preventDefault();
        var self = $(this);       
        var cnf = self.attr('data-confirm');
        if(cnf && !confirm(cnf)){
            return false;
        }
        self.button('loading')
        var url = self.attr('href');                        
        var mthod = self.attr('data-method');
        if(!mthod){
            mthod = 'get';
        }        
        
        $.ajax({
            dataType:'json',
            context:self,
            url: url, 
            type: mthod.toUpperCase(),
            success:quipu_ajxaction_success,
            error:quipu_ajxaction_error
        });
    }
    
    var quipu_ajxaction_error = function(data){
        var self = $(this);
        self.button('reset');
        alert(data);
    }
    
    var quipu_ajxaction_success = function(data){ 
        var self = $(this);
        if(self.attr('data-postreload')){
            window.top.location.reload(true);
        }
        else{
            var cont = $(self.attr('rel'));
            if(data.responseText){
                if(cont.length > 0){
                    cont.html(data.responseText);
                }
                else{
                    alert(data.responseText);
                }
            }                
            self.button('reset');            
        }
    }
    var quipu_setupListeners = function(){
        $('body')
        .on('click','.btn[data-loading-text]',function(evt){
            var self = $(this);            
            self.button('loading');
        })
        .on('click','a.ajx',quipu_ajxaction_clicked);
    }
    var quipu_addpoweredby = function(){
        var s = $('<p>').append('<i>').addClass('icon-wrench').html('&nbsp;' + window.iw.i18n.poweredby);
        var toolbar = $('#status div.btn-toolbar');
        $('<div>').addClass('btn-group').append(s).appendTo(toolbar); 
    }   
    //init ...
    quipu_addpoweredby();
    quipu_setupListeners();    
});