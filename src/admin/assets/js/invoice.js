window.addEvent('domready', function(){
    document.id('bt-choose-activities').addEvent('click',function(e){
        e.stop();
        var frm = document.id('frm-choose-activities');
        var url = frm.get('action');
        var mthod = 'post';
        var data = frm.toQueryString();        
                    
        this.set('value', window.iw.i18n.pleasewait);        
        new Request.JSON({
            url: url + '&' + data, 
            method: mthod,
            onSuccess: function(data){
                if(data.responseText){
                    alert(data.responseText);
                }
                window.top.location.reload(true);
            }
        }).send(data);                    
    });
});