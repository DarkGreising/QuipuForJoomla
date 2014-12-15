window.iw = {i18n: {}, urls: {}}
window.dirtyForm = false;

window.addEvent('domready', function() {

    var txts = $$('input.focus');
    if (txts.length > 0) {
        try {
            txts[0].focus();
        } catch (x) {
        }
    }
    else {
        txts = $$('input[type=text]');
        if (txts.length > 0)
            try {
                txts[0].focus();
            } catch (x) {
            }
    }

    var spinner = new Spinner();
    $$('form.item input').addEvent('change', function() {
        window.dirtyForm = true;
    });
    $$('form.item textarea').addEvent('change', function() {
        window.dirtyForm = true;
    });
    $$('form.item select').addEvent('change', function() {
        window.dirtyForm = true;
    });

    $$('form.item').each(function(f) {
        f.iwValidator = new Form.Validator.Inline(f);

        f.addEvent('submit', function(e) {
            dirtyForm = false;
            return true;

        });
    });

    document.getElements('a.with-wait-msg').addEvent('click', function(e) {
        this.set('html', window.iw.i18n.pleasewait);
        this.addClass('disabled');
    });

    document.getElements('a.ajx').addEvent('click', function(e) {
        e.stop();
        cnf = this.get('data-confirm');
        if (cnf && !confirm(cnf)) {
            return false;
        }
        var url = this.href;
        var cont = document.id(this.rel);
        var oldHTML = this.get('html');
        var mthod = this.get('data-method');
        if (!mthod) {
            mthod = 'get';
        }
        var self = this;
        this.set('html', window.iw.i18n.pleasewait);
        new Request.JSON({
            url: url,
            method: mthod,
            onComplete: function(data) {
                var response = false;
                if (data && data.responseText) {
                    response = data.responseText;
                }
                else if (this.xhr && this.xhr.statusText) {
                    response = this.xhr.statusText;
                }
                if (response) {
                    if (cont) {
                        cont.set('html', response);
                    }
                    else {
                        alert(response);
                    }

                }
                if (self.get('data-postreload')) {
                    window.top.location.reload(true);
                }
                else {
                    self.set('html', oldHTML);
                }

            }
        }).send();
    });


    try {
        Meio.Mask.createMasks('Regexp', {
            'a6cif': {
                regex: /^([A-Z0-9]+)?$/
            }
        });
        document.getElements('input.mask-date').each(function(input) {
            input.meiomask('fixed.date');
        });
        document.getElements('input.mask-currency').each(function(input) {
            //input.meiomask('Reverse.Euro');
        });

    } catch (x) {
        //console.log(x);
    }

    var c = $('element-box');
    //console.log(c);
    if (c) {
        var n = new Element('p', {
            'class': 'center',
            'html': window.iw.i18n.poweredby
        });
        n.inject(c, 'after');
    }

});
window.onbeforeunload = function() {
    if (window.dirtyForm) {
        return window.iw.i18n.unsavedchanges;
    }

}; 