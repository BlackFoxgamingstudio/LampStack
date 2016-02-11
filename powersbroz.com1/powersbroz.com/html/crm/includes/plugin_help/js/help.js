ucm = ucm || {};
ucm.help = {
    current_pages: '',
    current_modules: '',
    url_extras: '',
    url_help: 'https://ultimateclientmanager.com/api/help.php?',
    lang: {
        'loading': 'Loading',
        'help': 'Help'
    },
    init: function(){
        var t = this;
        $('body').append('<div id="help_popup" title="' + t.lang.help + '"> <div class="modal_inner" style="height:100%;">' + t.lang.loading + '</div> </div>');
        $("#help_popup").dialog({
            autoOpen: false,
            width: '70%',
            height: $(window).height() - 100,
            modal: true,
            buttons: {
                Close: function() {
                    $(this).dialog('close');
                }
            },
            open: function(){
                // todo? pull in our json from the url and format it locally.

                $('.modal_inner',this).html('<iframe src="' + t.url_help + 'pages=' + t.current_pages + '&modules=' + t.current_modules + t.url_extras + '" id="ghelp_iframe" frameborder="0" style="width:100%; height:100%;" ></iframe>');
            },
            close: function() {
                $('.modal_inner',this).html('');
            }
        });
        $('#header_help').click(function(){
            $('#help_popup').dialog('open');
            return false;
        });
    }
};
ucm.open_help = function(help_id){
    var options = {
        autoOpen: true,
        height: 260,
        width: 300,
        modal: true,
        buttons: {}
    };
    options.buttons[ucm.lang.ok] = function() {
        $(this).dialog('close');
    };
    $("#help_"+help_id).dialog(options);
};

$(function(){ucm.help.init();});