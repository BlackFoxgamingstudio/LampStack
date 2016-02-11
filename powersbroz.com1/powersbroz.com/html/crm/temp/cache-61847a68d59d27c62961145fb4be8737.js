
 // includes/plugin_social/js/social.js

ucm.social = {

    modal_url: '',
    init: function(){
        var p = $('#social_modal_popup');
        p.dialog({
            autoOpen: false,
            width: 700,
            height: 600,
            modal: true,
            buttons: {
                Close: function() {
                    $(this).dialog('close');
                }
            },
            open: function(){
                var t = this;
                $.ajax({
                    type: "GET",
                    url: ucm.social.modal_url+(ucm.social.modal_url.match(/\?/) ? '&' : '?')+'display_mode=ajax',
                    dataType: "html",
                    success: function(d){
                        $('.modal_inner',t).html(d);
                        $('input[name=_redirect]',t).val(window.location.href);
                        init_interface();
                        $('.modal_inner iframe.autosize',t).height($('.modal_inner',t).height()-41); // for firefox
                    }
                });
            },
            close: function() {
                $('.modal_inner',this).html('');
            }
        });
        $('body').delegate('.social_modal','click',function(){
            ucm.social.open_modal($(this).attr('href'), $(this).data('modal-title'));
            return false;
        });
    },
    close_modal: function(){
        var p = $('#social_modal_popup');
        p.dialog('close');
    },
    open_modal: function(url, title){
        var p = $('#social_modal_popup');
        p.dialog('close');
        ucm.social.modal_url = url;
        p.dialog('option', 'title', title);
        p.dialog('open');
    }

};

 // includes/plugin_social_facebook/js/social_facebook.js

ucm.social.facebook = {
    api_url: '',
    init: function(){

        $('body').delegate('.facebook_reply_button','click',function(){
            var f = $(this).parents('.facebook_comment').first().next('.facebook_comment_replies').find('.facebook_comment_reply_box');
            f.show();
            f.find('textarea')[0].focus();
        }).delegate('.facebook_comment_reply textarea','keyup',function(){
            var a = this;
            if (!$(a).prop('scrollTop')) {
                do {
                    var b = $(a).prop('scrollHeight');
                    var h = $(a).height();
                    $(a).height(h - 5);
                }
                while (b && (b != $(a).prop('scrollHeight')));
            }
            $(a).height($(a).prop('scrollHeight') + 10);
        }).delegate('.facebook_comment_reply button','click',function(){
            // send a message!
            var p = $(this).parent();
            var txt = $(p).find('textarea');
            var message = txt.val();
            if(message.length > 0){
                //txt[0].disabled = true;
                // show a loading message in place of the box..
                $.ajax({
                    url: ucm.social.facebook.api_url,
                    type: 'POST',
                    data: {
                        action: 'send-message-reply',
                        id: $(this).data('id'),
                        facebook_id: $(this).data('facebook-id'),
                        message: message,
                        debug: $(p).find('.reply-debug')[0].checked ? 1 : 0,
                        form_auth_key: ucm.form_auth_key
                    },
                    dataType: 'json',
                    success: function(r){
                        if(r && typeof r.redirect != 'undefined'){
                            window.location = r.redirect;
                        }else if(r && typeof r.message != 'undefined'){
                            p.html("Info: "+ r.message);
                        }else{
                            p.html("Unknown error, please try reconnecting to Facebook in settings. "+r);
                        }
                    }
                });
                p.html('Sending...');
            }
            return false;
        }).delegate('.socialfacebook_message_action','click',ucm.social.facebook.message_action);
        $('.facebook_message_summary a').click(function(){
            var p = $(this).parents('tr').first().find('.socialfacebook_message_open').click();
            return false;
        });
        /*$('.pagination_links a').click(function(){
            $(this).parents('.ui-tabs-panel').first().load($(this).attr('href'));
            return false;
        });*/
    },
    message_action: function(link){
        $.ajax({
            url: ucm.social.facebook.api_url,
            type: 'POST',
            data: {
                action: $(this).data('action'),
                id: $(this).data('id'),
                form_auth_key: ucm.form_auth_key
            },
            dataType: 'script',
            success: function(r){
                ucm.social.close_modal();
            }
        });
        return false;
    }
};

 // includes/plugin_social_twitter/js/social_twitter.js

ucm.social.twitter = {
    api_url: '',
    init: function(){

        $('body').delegate('.twitter_reply_button','click',function(){
            var f = $(this).parents('.twitter_comment').first().next('.twitter_comment_replies').find('.twitter_comment_reply_box');
            f.show();
            f.find('textarea')[0].focus();
        }).delegate('.twitter_comment_reply textarea','keyup',function(){
            var a = this;
            if (!$(a).prop('scrollTop')) {
                do {
                    var b = $(a).prop('scrollHeight');
                    var h = $(a).height();
                    $(a).height(h - 5);
                }
                while (b && (b != $(a).prop('scrollHeight')));
            }
            $(a).height($(a).prop('scrollHeight') + 10);
        }).delegate('.twitter_comment_reply button','click',function(){
            // send a message!
            var p = $(this).parent();
            var txt = $(p).find('textarea');
            if(txt.length > 0){
            var message = txt.val();
            if(message.length > 0){
                //txt[0].disabled = true;
                // show a loading message in place of the box..
                $.ajax({
                    url: ucm.social.twitter.api_url,
                    type: 'POST',
                    data: {
                        action: 'send-message-reply',
                        social_twitter_message_id: $(this).data('id'),
                        social_twitter_id: $(this).data('account-id'),
                        message: message,
                        debug: $(p).find('.reply-debug')[0].checked ? 1 : 0,
                        form_auth_key: ucm.form_auth_key
                    },
                    dataType: 'json',
                    success: function(r){
                        if(r && typeof r.redirect != 'undefined'){
                            window.location = r.redirect;
                        }else if(r && typeof r.message != 'undefined'){
                            p.html("Info: "+ r.message);
                        }else{
                            p.html("Unknown error, please try reconnecting to Twitter in settings. "+r);
                        }
                    }
                });
                    p.html('Sending... Please wait...');
            }
            }
            return false;
        }).delegate('.socialtwitter_message_action','click',
            ucm.social.twitter.message_action
        ).delegate('.twitter_comment_clickable','click',function(){
            ucm.social.open_modal($(this).data('link'), $(this).data('title'));
        });
        $('.twitter_message_summary a').click(function(){
            var p = $(this).parents('tr').first().find('.socialtwitter_message_open').click();
            return false;
        });
        /*$('.pagination_links a').click(function(){
            $(this).parents('.ui-tabs-panel').first().load($(this).attr('href'));
            return false;
        });*/


        jQuery('.twitter_compose_message').change(this.twitter_txt_change).keyup(this.twitter_txt_change).change();
        this.twitter_change_post_type();
        jQuery('[name=twitter_post_type]').change(this.twitter_change_post_type);

    },
    message_action: function(link){
        $.ajax({
            url: ucm.social.twitter.api_url,
            type: 'POST',
            data: {
                action: $(this).data('action'),
                social_twitter_message_id: jQuery(this).data('id'),
                social_twitter_id: $(this).data('social_twitter_id'),
                form_auth_key: ucm.form_auth_key
            },
            dataType: 'script',
            success: function(r){
                ucm.social.close_modal();
            }
        });
        return false;
    },
    twitter_limit: 140,
    twitter_set_limit: function(limit){
        ucm.social.twitter.twitter_limit = limit;
        jQuery('.twitter_compose_message').change();
    },
    charactersleft: function(tweet, limit) {
        var url, i, lenUrlArr;
        var virtualTweet = tweet;
        var filler = "0123456789012345678912";
        var extractedUrls = twttr.txt.extractUrlsWithIndices(tweet);
        var remaining = limit;
        lenUrlArr = extractedUrls.length;
        if ( lenUrlArr > 0 ) {
            for (i = 0; i < lenUrlArr; i++) {
                url = extractedUrls[i].url;
                virtualTweet = virtualTweet.replace(url,filler);
            }
        }
        remaining = remaining - virtualTweet.length;
        return remaining;
    },
    twitter_txt_change: function(){
        var remaining = ucm.social.twitter.charactersleft(jQuery(this).val(), ucm.social.twitter.twitter_limit);
        jQuery(this).parent().find('.twitter_characters_remain').first().find('span').text(remaining);
    },
    twitter_change_post_type: function(){
        var currenttype = jQuery('[name=twitter_post_type]:checked').val();
        jQuery('.twitter-type-option').each(function(){
            jQuery(this).parents('tr').first().hide();
        });
        jQuery('.twitter-type-'+currenttype).each(function(){
            jQuery(this).parents('tr').first().show();
        });
        if(currenttype == 'picture'){
            ucm.social.twitter.twitter_set_limit(118);
        }else{
            ucm.social.twitter.twitter_set_limit(140);
        }
    }
};

 // includes/plugin_quote/js/quote.js

ucm.quote = {

    ajax_task_url: '',
    create_invoice_popup_url: '',
    create_invoice_url: '',

    // init called from the quote edit page
    init: function(){
        var t = this;
        t.update_quote_tax();
    },
    toggle_task_complete: function(task_id){

    },
    update_quote_tax: function(){
        if($('#quote_tax_holder .dynamic_block').length > 1)$('.quote_tax_increment').show(); else $('.quote_tax_increment').hide();
    },
    generate_invoice_done: false,
    generate_invoice: function(title){
        var t = this;

        $('#create_invoice_options_inner').load(t.create_invoice_popup_url,function(){
            $('#create_invoice_options').dialog({
                autoOpen: true,
                height: 560,
                width: 350,
                modal: true,
                title: title,
                buttons: {
                    Create: function() {
                        var url = t.create_invoice_url;
                        var items = $('.invoice_create_task:checked');
                        if(items.length>0){
                            items.each(function(){
                                url += '&task_id[]=' + $(this).data('taskid');
                            });
                            window.location.href=url;
                        }else{
                            $(this).dialog('close');
                        }
                    }
                }
            });
        });
    }
};

$(function(){
    ucm.quote.init();
});

 // includes/plugin_product/js/product.js

ucm.product = {

    product_name_search: '',
    text_box: false,
    task_id: '',
    selected_product_id: 0,

    init: function(){
        var t = this;
        $('body').delegate('.edit_task_description','change',function(){
            t.text_change(this);
        }).delegate('.edit_task_description','keyup',function(){
            t.text_change(this);
        });
    },
    text_change: function(txtbox){
        // look up a product based on this value
        this.text_box = txtbox;
        if(txtbox.value.length > 2 && txtbox.value != this.product_name_search){
            // grep the id out of this text box.
            this.task_id = $(this.text_box).data('id') ? $(this.text_box).data('id') : $(this.text_box).attr('id').replace(/task_desc_/,'');
            //console.log(this.task_id);
            // search!

            // TODO: don't keep hitting ajax if the previous subset string search didn't return any results.

            this.product_name_search = txtbox.value;
            // search for results via ajax, if any are found we show them.
            try{ this.ajax_job.abort();}catch(err){}
            this.ajax_job = $.ajax({
                type: "POST",
                url: window.location.href,
                data: {
                    'product_name': this.product_name_search,
                    '_products_ajax': 'products_ajax_search'
                },
                success: function(result){
                    result = $.trim(result);
                    if(result != ''){
                        //$(ucm.product.text_box).attr('autocomplete','off');
                        ucm.product.show_dropdown(result);
                    }else{
                        ucm.product.hide_dropdown();
                    }
                }
            });
        }
    },
    /* called when the dropdown button is clicked */
    do_dropdown: function(task_id,btn){
        this.task_id = task_id;
        if($('.product_select_dropdown').length>=1){
            this.hide_dropdown();
            return false;
        }
        this.text_box = $(btn).parent().parent().find('.edit_task_description');
        this.product_name_search = ''; // so we show everyting
        this.show_dropdown();
        return false;
    },
    show_dropdown: function(products){
        var t = this;
        if($('.product_select_dropdown').length>=1){
            t.hide_dropdown();
        }
        if(t.text_box){
            $(t.text_box).before('<div class="product_select_dropdown">Loading...</div>');
            if(typeof products != 'undefined'){
                $(ucm.product.text_box).attr('autocomplete','off');
                $('.product_select_dropdown').html(products);

                $('.product_category_parent').bind('click',function(e){
                    $(this).next('ul').toggle();
//                    e.stopImmediatePropagation();
//                    e.stopPropagation();
//                    e.preventDefault();
                    return false;
                });
            }else{
                // todo - clean this ajax up into a single external call
                try{ this.ajax_job.abort();}catch(err){}
                this.ajax_job = $.ajax({
                    type: "POST",
                    url: window.location.href,
                    data: {
                        'product_name': '',
                        '_products_ajax': 'products_ajax_search'
                    },
                    success: function(result){
                        result = $.trim(result);
                        if(result != ''){
                            //$(ucm.product.text_box).attr('autocomplete','off');
//                            $('.product_select_dropdown').html(result);
                            ucm.product.show_dropdown(result);

                        }else{
                            ucm.product.hide_dropdown();
                        }
                    }
                });
            }
            if(!t.clickbound){
                t.clickbound=true;
                setTimeout(function(){$('body').bind('click', t.hide_dropdown);},150);
            }
        }
    },
    clickbound: false,
    hide_dropdown: function(){
        if($('.product_select_dropdown').length>=1){
            $('.product_select_dropdown').remove();
        }
        $('body').unbind('click', this.hide_dropdown);
        ucm.product.clickbound=false;
    },
    select_product: function(product_id){
        $.ajax({
            type: "POST",
            url: window.location.href,
            data: {
                'product_id': product_id,
                '_products_ajax': 'products_ajax_get'
            },
            dataType: 'json',
            success: function(product_data){
                //console.debug(product_data);
                /*amount: "1.00"
                currency_id: "1"
                date_created: "2013-01-28"
                date_updated: "2013-01-28"
                description: "asdfasdf"
                name: "test product"
                product_category_id: "0"
                product_id: "1"
                quantity: "4.00"*/
                if(product_data && product_data.product_id){
                    // hack for invoice support as well...

                    var product_type = 'job';
                    if($('#invoice_product_id_'+ucm.product.task_id).length > 0)product_type = 'invoice';

                    $('#task_product_id_'+ucm.product.task_id).val(product_data.product_id);
                    $('#invoice_product_id_'+ucm.product.task_id).val(product_data.product_id);
                    //etc...
                    if(typeof product_data.name != 'undefined'){
                        var n = product_data.name;
                        if(typeof product_data.product_category_id != 'undefined' && product_data.product_category_id > 0 && typeof product_data.product_category_name != 'undefined'){
                            n = product_data.product_category_name + " > " + n;
                        }
                        $('#task_desc_'+ucm.product.task_id).val(n);
                        $('#invoice_item_desc_'+ucm.product.task_id).val(n);
                    }
                    if(typeof product_data.quantity != 'undefined' && parseFloat(product_data.quantity)>0){
                        $('#task_hours_'+ucm.product.task_id).val(product_data.quantity);
                        $('#'+ucm.product.task_id+'invoice_itemqty').val(product_data.quantity);
                    }else{
                        $('#task_hours_'+ucm.product.task_id).val('');
                        $('#'+ucm.product.task_id+'invoice_itemqty').val('');
                    }
                    if(typeof product_data.amount != 'undefined' && parseFloat(product_data.amount)>0){
                        $('#'+ucm.product.task_id+'taskamount').val(product_data.amount);
                        $('#'+ucm.product.task_id+'invoice_itemrate').val(product_data.amount);
                    }else{
                        $('#'+ucm.product.task_id+'taskamount').val('');
                        $('#'+ucm.product.task_id+'invoice_itemrate').val('');
                    }
                    if(typeof product_data.default_task_type != 'undefined'){
                        $('#manual_task_type_' + ucm.product.task_id).val( parseInt(product_data.default_task_type) );
                    }
                    if(typeof product_data.taxable != 'undefined'){
                        $('#invoice_taxable_item_' + ucm.product.task_id).val( parseInt(product_data.taxable) >= 1 ? 1 : 0).prop('checked',parseInt(product_data.taxable) >= 1 );
                        $('#taxable_t_' + ucm.product.task_id).val( parseInt(product_data.taxable) >= 1 ? 1 : 0 ).prop('checked',parseInt(product_data.taxable) >= 1 );
                    }
                    if(typeof product_data.billable != 'undefined'){
                        // no billable option on invoices.
                        $('#billable_t_' + ucm.product.task_id).val( parseInt(product_data.billable) >= 1 ? 1 : 0 ).prop('checked',parseInt(product_data.billable) >= 1);
                    }
                    if(typeof product_data.description != 'undefined'){
                        $('#task_long_desc_'+ucm.product.task_id).val(product_data.description);
                        $('#'+ucm.product.task_id+'_task_long_description').val(product_data.description);
                        if(product_data.description.length > 0){
                            $(ucm.product.text_box).parent().find('.task_long_description').slideDown();
                        }
                    }else{
                        $('#task_long_desc_'+ucm.product.task_id).val('');
                        $('#'+ucm.product.task_id+'_task_long_description').val('');
                    }
                }
            }
        });
        return false;
    }
};

$(function(){
    ucm.product.init();
});

 // includes/plugin_subscription/js/subscription.js

ucm.subscription = {
    init: function(){
        $('.next_due_date_change').click(function(){
            $(this).after('<input type="text" name="subscription_next_due_date_change['+$(this).data('id')+']" value="'+$(this).text()+'" class="date_field">');
            $(this).hide();
            ucm.load_calendars();
        });
    }
};

jQuery(function(){
    ucm.subscription.init();
});

 // includes/plugin_ticket/js/tickets.js



ucm.ticket = {
    ticket_message_text_is_html: false,
    ticket_url: '',
    init_main: function(){
        // for the main page listing
        $('#bulk_operation_all').change(function(){
            $('.ticket_bulk_check').prop('checked', $(this).is(":checked") );
        });
    },
    init: function(){
        $("#ticket_container").attr({ scrollTop: $("#ticket_container").attr("scrollHeight") });
        //$("#ticket_container").animate({ scrollTop: $("#ticket_container").attr("scrollHeight") },1500);
        $('#show_previous_button').click(function(){
            $('#show_previous_box').html('Loading...');
            $.post( ucm.ticket.ticket_url, {show_only_hidden: 1}, function( data ) {
                $('#show_previous_box').html('');
              $( "#show_previous_box" ).after( data );
            });
            return false;
        });
        $('#save_saved').click(function () {
            $.ajax({
                url: ucm.ticket.ticket_url,
                type: 'POST',
                data: '_process=save_saved_response&saved_response_id=' + $('#canned_response_id').val() + '&value=' + escape($('#new_ticket_message').val()),
                dataType: 'json',
                success: function (r) {
                    alert('Saved successfully');
                }
            });
        });
        $('#insert_saved').click(function () {
            $.ajax({
                url: ucm.ticket.ticket_url,
                data: '_process=insert_saved_response&saved_response_id=' + $('#canned_response_id').val(),
                dataType: 'json',
                success: function (r) {
                    ucm.ticket.add_to_message(r.value);
                }
            });
        });
        $('#private_message').change(function(){
            if(this.checked){
                $(this).parents('.ticket_message').first().addClass('ticket_message_private');
                $('#change_status_id').val(5);
            }else{
                $(this).parents('.ticket_message').first().removeClass('ticket_message_private');
                $('#change_status_id').val($('#data_change_status_id').data('status'));
            }
        }).change();
        $('#change_to_me').click(function(){
            $(this).parent().hide();
            $('#change_assigned_user_id').val($(this).data('user-id'));
            return false;
        });
    },
    add_to_message: function(content){
        if(ucm.ticket.ticket_message_text_is_html) {
            content = content.replace(/\n/g, "<br/>\n");
            tinyMCE.activeEditor.execCommand('mceInsertContent', false, content);
        }else {
            $('#new_ticket_message').val(
                $('#new_ticket_message').val() + content
            );
        }
        return false;
    }
};

$(function(){
    ucm.ticket.init_main();

});

 // includes/plugin_config/js/upgrade.js

ucm.upgrade = {
    upgrade_url: '',
    complete_callback: false,
    upgrade_plugins: [],
    upgrade_post_data: {
        install_upgrade: 'yes',
        via_ajax: 'yes'
    },
    lang:{
        processing:'Processing, please wait...',
        completed:'Completed, click here to continue'
    },
    init: function(){
        $('#upgrade_start').click(ucm.upgrade.start_selected_upgrades);
    },
    start_selected_upgrades: function(){
        ucm.upgrade.upgrade_plugins = [];
        $('.update_checkbox input').each(function(){
            var tr = $(this).parents('tr').first();
            if($(this)[0].checked) {
                $('.update_progress',tr).slideDown();
                ucm.upgrade.upgrade_plugins.push({
                    plugin_name: tr.data('plugin'),
                    html: tr.find('.update_progress')
                });
            }
            $(this).attr('disabled','disabled');
        });
        if(ucm.upgrade.upgrade_plugins.length > 0){
            $('#upgrade_start').val(ucm.upgrade.lang.processing);
            ucm.upgrade.run_next();
        }else{
            alert('Please select at least one update.');
        }
    },
    check_for_updates: function(){
        // todo
        ucm.upgrade.upgrade_plugins = [];
        // check each of the plugins for updates via ajax and report back.
        $('tr[data-type="ajax_plugin"]').each(function(){
            ucm.upgrade.upgrade_plugins.push({
                plugin_name: $(this).data('plugin'),
                check: 1,
                html: $(this).find('.checking_progress')
            });
        });
        if(ucm.upgrade.upgrade_plugins.length > 0){
            ucm.upgrade.run_next();
        }else{
            alert('Failed, please report error to support.');
            return;
        }
        // check for any newly available plugins and add those to the list.

    },
    current_upgrade:0,
    all_done:false,
    run_next: function(){
        //if(ucm.upgrade.current_upgrade>0)return;
        if(typeof ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade] == 'undefined') {
            // completed all upgrades!
            if(typeof ucm.upgrade.complete_callback ==  'function'){
                ucm.upgrade.complete_callback();
                return;
            }
            $('#upgrade_start').val(ucm.upgrade.lang.completed);
            if(ucm.upgrade.all_done){
                // if they click the complete button at the bottom again.. redirect.
                window.location.href=window.location.href + (window.location.href.search(/\?/) ? '&' : '?') + 'done';
            }
            ucm.upgrade.all_done = true;
        }else {
            var post_data = {};
            for (var i in ucm.upgrade.upgrade_post_data) {
                if (ucm.upgrade.upgrade_post_data.hasOwnProperty(i)) {
                    post_data[i] = ucm.upgrade.upgrade_post_data[i];
                }
            }
            post_data.plugin_name = ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].plugin_name;
            //post_data.doupdate = [ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].plugin_name];
            $.ajax({
                url: ucm.upgrade.upgrade_url,
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success: function (d) {
                    // did it work? update the status..
                    if (typeof d.success != 'undefined') {
                        $(ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].html).html(d.message).addClass('success');
                    } else {
                        $(ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].html).html(d.message).addClass('error');
                    }
                    // do the processing...
                    //process_upgrade
                    if(d.plugin_name) {
                        $.ajax({
                            url: ucm.upgrade.upgrade_url,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                process_upgrade: 'yes',
                                plugin_name: d.plugin_name,
                                via_ajax: 1
                            },
                            success: function (d) {
                                // did it work? update the status..
                                if (typeof d.success != 'undefined') {
                                    $(ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].html).append(d.message).addClass('success');
                                } else {
                                    $(ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].html).append(d.message).addClass('error');
                                }
                                // do the processing...
                                //process_upgrade

                                ucm.upgrade.current_upgrade++;
                                ucm.upgrade.run_next();
                            },
                            error: function (d) {
                                alert('Failed to process ' + ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].plugin_name + '. Please try again.');
                                $(ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].html).append('Failed Final Processing').addClass('error');
                                ucm.upgrade.current_upgrade++;
                                ucm.upgrade.run_next();
                            }
                        });
                    }else{
                        ucm.upgrade.current_upgrade++;
                        ucm.upgrade.run_next();
                    }
                },
                error: function (d) {
                    alert('Failed to upgrade ' + ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].plugin_name + '. Please try again.');
                    $(ucm.upgrade.upgrade_plugins[ucm.upgrade.current_upgrade].html).html('Failed').addClass('error');
                    ucm.upgrade.current_upgrade++;
                    ucm.upgrade.run_next();
                }
            });
        }
    }
};

 // includes/plugin_timer/js/timer.js

ucm.timer = {
    mode: 1, // 1 = one timer at a time (new version), 2 = multiple active timers at a time (original version)
    timers: [], // array of active timers, stored in cookies
    chunk_split: '||$|$||',
    split_chunk: '$$|$|$$',
    timer_index: {
        name: 0,
        url: 1,
        task_id: 2,
        start_time: 3,
        pause_time: 4,
        job_id: 5
    },
    init: function(){

        $(document).on('click','.timer_task',function(){
            ucm.timer.timer_click($(this).attr('data-jobid'),$(this).attr('data-taskid'));
            return false;
        });
        $(document).on('mouseenter','.timer_counter',function(){
            ucm.timer.timer_hover(this);
            return false;
        });

        // load any active timers from cookies.
        var cookietimers = this.tread();
        if(typeof cookietimers == 'string'){
            var bits = cookietimers.split(this.chunk_split);
            for (var i in bits){
                if (bits.hasOwnProperty(i)) {
                    var a = bits[i].split(this.split_chunk);
                    var this_timer={};
                    for(var t in this.timer_index){
                        if (this.timer_index.hasOwnProperty(t)) {
                            this_timer[t] = a[this.timer_index[t]];
                        }
                    }
                    this_timer['job_id'] = parseInt(this_timer['job_id']);
                    this_timer['task_id'] = parseInt(this_timer['task_id']);
                    if(!this_timer['job_id'] || !this_timer['task_id'])continue;
                    this_timer['start_time'] = parseInt(this_timer['start_time']);
                    this_timer['pause_time'] = parseInt(this_timer['pause_time']);
                    this.timers.push(this_timer);
                }
            }
        }
        // add a bit of pretty up in the header.
        // the theme must be able to style this so we leave it pretty generic

        $('#timer_menu_button').hover(function(){
            $('#timer_menu_options').show();
        },function(){
            $('#timer_menu_options').hide();
        });
        // start our ticker counter
        this.tick(true);
    },
    tread: function(){
        // todo - do this via ajax? timer per user or something? save in db rather than cookie?
        return Get_Cookie('ucm_timers');
    },
    twrite: function(data){
        // tood - save this back to db via ajax
        Set_Cookie('ucm_timers',data,null,'/');
    },
    save_timers: function(){
        var timer_encoded=[];
        for(var i in this.timers){
            if (this.timers.hasOwnProperty(i)) {
                var arr = [];
                for(var t in this.timer_index){
                    if (this.timer_index.hasOwnProperty(t)) {
                        arr[this.timer_index[t]] = this.timers[i][t];
                    }
                }
                timer_encoded.push(arr.join(this.split_chunk));
            }
        }
        this.twrite(timer_encoded.join(this.chunk_split));
    },
    // runs every second and updates any actively running timers:
    tick: function(recur){
        var timer_count=0;
        for(var i in this.timers){
            if (this.timers.hasOwnProperty(i)) {

                timer_count++;
                // update the timer with current time
                var timer_elapsed = this.now() - this.timers[i]['start_time'];
                if(this.timers[i]['pause_time']){
                    // paused, work out the difference.
                    timer_elapsed -= (this.now() - this.timers[i]['pause_time']);
                }

                var hours = Math.floor(timer_elapsed/3600);
                var mins = Math.floor((timer_elapsed - (hours * 3600 ))/60);
                var secs = Math.floor((timer_elapsed - (hours * 3600 ) - (mins * 60)));

                this.timers[i]['hours'] = hours;
                this.timers[i]['mins'] = mins;
                this.timers[i]['secs'] = secs;

                if (mins == 0){
                    mins = '00';
                }else if (mins < 10){
                    mins = '0' + mins;
                }

                if (secs < 10){
                    secs = '0' + secs;
                }
                secs = ':' + secs;

                if (hours == 0){
                    hours = '';
                }else{
                    hours = hours + ":";
                }

                this.timers[i]['timer_text'] = hours + mins + secs;


                // search the page for a timer icon that matches this job/task_id
                // wack a little <span> timer next to the icon that counts upwards.

                var tl = typeof this.timers[i]['timer_link'] != 'undefined' && jQuery.contains(document.documentElement, this.timers[i]['timer_link'][0]) ? this.timers[i]['timer_link'] : $('a.timer_task[data-jobid="'+this.timers[i]['job_id']+'"][data-taskid="'+this.timers[i]['task_id']+'"]');
                this.timers[i]['timer_link'] = tl;


                if(tl.length>0){
                    // if we're on the job page that is showing this task.
                    tl.show(); // incase completed task, need to re-show active timer buttons so there's a way to stop the thingey.
                    // found a timer icon in the task list!
                    // check if there's a timer span added next to the timer button.
                    var ts = typeof this.timers[i]['span_link'] != 'undefined' && jQuery.contains(document.documentElement, this.timers[i]['span_link'][0]) ? this.timers[i]['span_link'] : tl.parent().find('span.timer_counter');

                    if(ts.length==0){
                        // create spam
                        tl.after('<span class="timer_counter"><span class="timer_number">0:00</span></span>');
                        ts = tl.parent().find('span.timer_counter');
                    }
                    ts.data('timer',this.timers[i]); // so we can do a hover over timer_counter and get access to this timer object asap
                    this.timers[i]['button_link'] = tl;
                    this.timers[i]['span_link'] = ts;
                    if(this.timers[i]['pause_time']){
                        // paused, work out the difference.
                        ts.addClass('timer_paused');
                        tl.addClass('ui-state-default');
                        tl.removeClass('ui-state-active');
                    }else{
                        ts.removeClass('timer_paused');
                        tl.removeClass('ui-state-default');
                        tl.addClass('ui-state-active');
                    }

                    ts.find('.timer_number').html(this.timers[i]['timer_text']);
                }


                // setup this header item so it matches the abovetask item in functionality.
                var header_list_item = typeof this.timers[i]['header_list_item'] != 'undefined' ? this.timers[i]['header_list_item'] : null;
                if(!header_list_item){
                    header_list_item = $('<li><a href="#" class="timer_task ui-state-default ui-corner-all ui-icon ui-icon-clock" data-jobid="'+this.timers[i]['job_id']+'" data-taskid="'+this.timers[i]['task_id']+'">Timer</a><span class="timer_counter timer_header"><span class="timer_number">0:00</span></span></li>');
                    header_list_item.prepend('<a href="'+this.timers[i]['url']+'" class="timer_job_link">'+this.timers[i]['name']+'</a>');
                    $('#active_timer_list').append(header_list_item);
                }
                this.timers[i]['header_list_item'] = header_list_item;
                var header_counter = typeof this.timers[i]['header_counter'] != 'undefined'  ? this.timers[i]['header_counter'] : header_list_item.find('span.timer_counter');
                this.timers[i]['header_counter'] = header_counter;
                if(this.timers[i]['pause_time']){
                    // paused, work out the difference.
                    header_counter.addClass('timer_paused');
                    header_list_item.find('.timer_task').addClass('ui-state-default').removeClass('ui-state-active');
                }else{
                    header_counter.removeClass('timer_paused');
                    header_list_item.find('.timer_task').removeClass('ui-state-default').addClass('ui-state-active');
                }

                header_counter.data('timer',this.timers[i]);
                header_counter.find('.timer_number').html(this.timers[i]['timer_text']);


            }
        }
        // header menu bits:
        if(timer_count>0){
            $('#timer_menu_button').show();
            $('#current_timer_count').html(timer_count);
        }else{
            $('#timer_menu_button').hide();
        }

        if(recur)setTimeout(function(){ucm.timer.tick(true);},500);
        ucm.timer.save_timers();// do this every second? sure!
    },
    now: function(){
        return Math.round(new Date().getTime() / 1000);
    },
    timer_click: function(job_id, task_id){
        // does this timer exist already?
        var exists=false;
        var starting_a_timer=false;
        for(var i in this.timers){
            if (this.timers.hasOwnProperty(i)) {
                if(this.timers[i]['job_id'] == job_id && this.timers[i]['task_id']==task_id){
                    exists=true;
                    // found a duplicate!
                    // button is clicked, what do we do?
                    // options are: pause started timer, start paused timer, complete timer.
                    if(this.timers[i]['pause_time']==0){
                        // not paused yet, pause timer!
                        this.timers[i]['pause_time'] = this.now();
                    }else{
                        // timer is paused, unpause.
                        // increase the start time by the duration this timer was paused for
                        this.timers[i]['start_time'] = this.timers[i]['start_time'] + (this.now() - this.timers[i]['pause_time']);
                        this.timers[i]['pause_time'] = 0; // clear pause state.
                        starting_a_timer = this.timers[i];
                    }
                }
            }
        }
        if(!exists){
            // adding a new one
            var name = $('.task_row_'+task_id).find('a').first().html();
            if(name.length<=0){
                name = 'Timer Task';
            }
            var newtimer = {
                name: name,
                url: window.location.href,
                start_time: this.now(), // in seconds
                pause_time: 0,
                job_id: job_id,
                task_id: task_id
            };
            this.timers.push(newtimer);
            starting_a_timer = newtimer;
        }
        // clear other started timers if we're in the mode 1
        if(this.mode==1 && starting_a_timer){
            for(var x in this.timers){
                if (this.timers.hasOwnProperty(x)) {
                    if(this.timers[x]['pause_time']==0 && this.timers[x] != starting_a_timer){
                        // this timer is running, we should stop it
                        this.timers[x]['pause_time'] = this.now();
                    }
                }
            }
        }

        this.tick(false);// tick will pick this up and clean it up and start displaying it.
    },
    timer_delete: function(timer_object){
        timer_object['button_link'].removeClass('ui-state-active');
        timer_object['button_link'].addClass('ui-state-default');
        timer_object['span_link'].remove();
        timer_object['header_list_item'].remove();
        for(var i in this.timers){
            if (this.timers.hasOwnProperty(i)) {
                if(this.timers[i] == timer_object){ // comparing objects? sure!
                    delete(this.timers[i]);
                }
            }
        }

    },
    timer_finish: function(timer_object){
        var job_id = timer_object['job_id'];
        var task_id = timer_object['task_id'];
        // if timer isn't finished, pause it now.
        if(!timer_object['pause_time']){
            timer_object['pause_time'] = this.now();
        }
        /*$(document).ajaxComplete(function(event, xhr, settings) {
            console.debug(settings);
              if ( settings.url.match(new RegExp('task_id='+task_id)) ) {
                  $(event.currentTarget).unbind('ajaxComplete');
                  if(typeof $('#complete_t_'+task_id)[0] != 'undefined'){
                      // calculate how many hours this is in decimal
                      var time_decimal = timer_object['hours']; // eg: 0, 1, 2
                      if(timer_object['mins']>0){
                          // 60 mins in an hour.
                          time_decimal += Math.floor((timer_object['mins']/60)*100) / 100; // (eg: 0.25 for 15 mins)
                      }
                      // ignore seconds.
                      alert(time_decimal);
                      $('#complete_'+task_id).val(time_decimal);
                      $('#complete_t_'+task_id)[0].checked=true;
                  }else{
                      alert('Failed to mark task as completed. Please try again.');
                  }
              }
        });*/
        edittask(task_id, null, function(){
            if(typeof $('#complete_t_'+task_id)[0] != 'undefined'){
                  // calculate how many hours this is in decimal
                  var time_decimal = timer_object['hours']; // eg: 0, 1, 2
                  if(timer_object['mins']>0){
                      // 60 mins in an hour.
                      time_decimal += Math.floor((timer_object['mins']/60)*100) / 100; // (eg: 0.25 for 15 mins)
                  }
                  // ignore seconds.
                  $('#complete_'+task_id).val(time_decimal);
                  $('#complete_t_'+task_id)[0].checked=true;
              }else{
                  alert('Failed to mark task as completed. Please try again.');
              }
        });
        this.timer_delete(timer_object);
    },
    timer_hover: function(timerspan){
        var timer_object = $(timerspan).data('timer');
        $(timerspan).prepend('<div class="timer_hover">' +
            '<span class="timer_title">Timer</span>' +
            '<ul>' +
            '<li class="timer_number">'+ timer_object['timer_text'] +'</li>' +
            '</ul>' +
            '</div>');
        // add some buttons
        /*
            '<li class="timer_status">Paused</li>' +
            '<li class="timer_action"><a href="#">Delete</a></li>' +
            '<li class="timer_action"><a href="#">Pause</a></li>' +
            '<li class="timer_action"><a href="#">Resume</a></li>' +
            '<li class="timer_action"><a href="#">Stop &amp; Save</a></li>' +*/
        $(timerspan).find('.timer_hover ul').append($('<li class="timer_action" />').append($('<a href="#" class="timer_click">' + (timer_object['pause_time']>0 ? 'Resume' : 'Pause') + '</a>').click(function(){
            ucm.timer.timer_click(timer_object['job_id'],timer_object['task_id']);
            //ucm.timer.timer_hover(timerspan);
            $(timerspan).find('.timer_hover').remove();
            return false;
        })));
        // if we're in the header area then we don't show a 'record' button, just a view job button
        if($(timerspan).hasClass('timer_header')){
            //$(timerspan).find('.timer_hover ul').append($('<li class="timer_action" />').append($('<a href="'+timer_object['url']+'" class="timer_view">View Job</a>')));
        }else{
            $(timerspan).find('.timer_hover ul').append($('<li class="timer_action" />').append($('<a href="#" class="timer_finish">Finish</a>').click(function(){
                ucm.timer.timer_finish(timer_object);
                $(timerspan).find('.timer_hover').remove();
                return false;
            })));
        }
        $(timerspan).find('.timer_hover ul').append($('<li class="timer_action" />').append($('<a href="#" class="timer_cancel">Delete</a>').click(function(){
            if(confirm('Really delete this timer?')){
                ucm.timer.timer_delete(timer_object);
                $(timerspan).find('.timer_hover').remove();
            }
            return false;
        })));
        $(timerspan).on('mouseleave',function(){ $(timerspan).find('.timer_hover').remove(); });
    }
};


$(function(){
    ucm.timer.init();
});

 // includes/plugin_pin/js/pin.js

$(function(){
    $('#top_menu_pin').hover(function(){
        $('#top_menu_pin_options').show();
    },function(){
        $('#top_menu_pin_options').hide();
    });
    $('#pin_current_page').click(function(){
        $('#pin_action').val('add');
        $('#pin_current_title').val(document.title);
        $('#pin_action_form')[0].submit();
        return false;
    });
    $('.top_menu_pin_delete').click(function(){
        $('#pin_action').val('delete');
        $('#pin_id').val($(this).parent().parent().attr('rel'));
        $('#pin_action_form')[0].submit();
        return false;
    });
    $('.top_menu_pin_edit').click(function(){
        var newtitle = prompt('New title:',$(this).parent().parent().find('.top_menu_pin_item').text());
        if(newtitle){
            $('#pin_action').val('modify');
            $('#pin_id').val($(this).parent().parent().attr('rel'));
            $('#pin_current_title').val(newtitle);
            $('#pin_action_form')[0].submit();
        }
        return false;
    });
});

 // includes/plugin_job_discussion/js/job_discussion.js

$(function(){
    $('body').delegate('.task_job_discussion','click',function(){
        var holder = $(this).parents('td:first').find('.task_job_discussion_holder');
        holder.load($(this).attr('href'),function(){
            holder.toggle();
        });
        return false;
    });
    $('body').delegate('.task_job_discussion_add','click',function(){

        var job_id = $(this).data('jobid');
        var task_id = $(this).data('taskid');
        var holder = $(this).parents('td:first').find('.task_job_discussion_holder');
        var sendemail_customer = [];
        var sendemail_staff = [];
        if(typeof $(this).parent('div').find('.sendemail_customer')[0] != 'undefined'){
            $(this).parent('div').find('.sendemail_customer').each(function(){
                if($(this)[0].checked){
                    sendemail_customer.push($(this).val());
                }
            });
        }
        if(typeof $(this).parent('div').find('.sendemail_staff')[0] != 'undefined'){
            $(this).parent('div').find('.sendemail_staff').each(function(){
                if($(this)[0].checked){
                    sendemail_staff.push($(this).val());
                }
            });
        }
        $.ajax({
            type: 'POST',
            url: window.location.href, //$(this).attr('post_url'),
            data: {
                'note': $(this).parent('div').find('textarea').val(),
                'job_discussion_add_job_id': job_id,
                'job_discussion_add_task_id': task_id,
                'sendemail_customer': sendemail_customer,
                'sendemail_staff': sendemail_staff
            },
            dataType: 'json',
            success: function(h){
               var btn = $(holder).parents('td:first').find('.task_job_discussion');
                if(btn.length>0){
                    btn.click();
                    /*var count = parseInt(btn.html());
                    if(!count)count = 0;
                    count = count + 1;*/
                    btn.find('span').html(h.count);
                }
            },
            fail: function(){
                alert('Something went wrong, try again');
            }
        });
        holder.html('Loading...');
        return false;
    });
});

 // includes/plugin_encrypt/js/encrypt.js

$(function(){
    $('span.encrypt_create').each(function(){
        var r = $(this);
        r.hide();
        r.parent('td').first().hover(function(){r.show();},function(){r.hide();});
        r.parent('.form-control').first().hover(function(){r.show();},function(){r.hide();});
    });
    $('span.encrypt_popup').each(function(){
        var r = $(this);
        r.hide();
        r.parent('td').first().hover(function(){r.show();},function(){r.hide();});
        r.parent('.form-control').first().hover(function(){r.show();},function(){r.hide();});
    });
});

 // includes/plugin_extra/js/extra.js

function extra_process_url(){
    var u = $(this).val().match(/https?:\/\/.*$/);
    if(u){
        $(this).parent().find('.extra_link_click').remove();
        $(this).after(' <a href="'+u+'" target="_blank" class="extra_link_click">open &raquo;</a>');
    }else{

    }
}
function extra_show_fields(e){
    e.preventDefault();
    $('.extra_fields_show_more').hide();
    $('.extra_field_row_hidden').show();
    return false;
}
$(function(){
    $(document).on('click','.extra_fields_show_button',extra_show_fields);
    $(document).on('change','.extra_value_input',extra_process_url);
    $('.extra_value_input').each(extra_process_url);
});

 // includes/plugin_encrypt/js/sjcl.js

"use strict";var sjcl={cipher:{},hash:{},keyexchange:{},mode:{},misc:{},codec:{},exception:{corrupt:function(a){this.toString=function(){return"CORRUPT: "+this.message};this.message=a},invalid:function(a){this.toString=function(){return"INVALID: "+this.message};this.message=a},bug:function(a){this.toString=function(){return"BUG: "+this.message};this.message=a},notReady:function(a){this.toString=function(){return"NOT READY: "+this.message};this.message=a}}};
if(typeof module!="undefined"&&module.exports)module.exports=sjcl;
sjcl.cipher.aes=function(a){this.h[0][0][0]||this.w();var b,c,d,e,f=this.h[0][4],g=this.h[1];b=a.length;var h=1;if(b!==4&&b!==6&&b!==8)throw new sjcl.exception.invalid("invalid aes key size");this.a=[d=a.slice(0),e=[]];for(a=b;a<4*b+28;a++){c=d[a-1];if(a%b===0||b===8&&a%b===4){c=f[c>>>24]<<24^f[c>>16&255]<<16^f[c>>8&255]<<8^f[c&255];if(a%b===0){c=c<<8^c>>>24^h<<24;h=h<<1^(h>>7)*283}}d[a]=d[a-b]^c}for(b=0;a;b++,a--){c=d[b&3?a:a-4];e[b]=a<=4||b<4?c:g[0][f[c>>>24]]^g[1][f[c>>16&255]]^g[2][f[c>>8&255]]^
g[3][f[c&255]]}};
sjcl.cipher.aes.prototype={encrypt:function(a){return this.H(a,0)},decrypt:function(a){return this.H(a,1)},h:[[[],[],[],[],[]],[[],[],[],[],[]]],w:function(){var a=this.h[0],b=this.h[1],c=a[4],d=b[4],e,f,g,h=[],i=[],k,j,l,m;for(e=0;e<0x100;e++)i[(h[e]=e<<1^(e>>7)*283)^e]=e;for(f=g=0;!c[f];f^=k||1,g=i[g]||1){l=g^g<<1^g<<2^g<<3^g<<4;l=l>>8^l&255^99;c[f]=l;d[l]=f;j=h[e=h[k=h[f]]];m=j*0x1010101^e*0x10001^k*0x101^f*0x1010100;j=h[l]*0x101^l*0x1010100;for(e=0;e<4;e++){a[e][f]=j=j<<24^j>>>8;b[e][l]=m=m<<24^m>>>8}}for(e=
0;e<5;e++){a[e]=a[e].slice(0);b[e]=b[e].slice(0)}},H:function(a,b){if(a.length!==4)throw new sjcl.exception.invalid("invalid aes block size");var c=this.a[b],d=a[0]^c[0],e=a[b?3:1]^c[1],f=a[2]^c[2];a=a[b?1:3]^c[3];var g,h,i,k=c.length/4-2,j,l=4,m=[0,0,0,0];g=this.h[b];var n=g[0],o=g[1],p=g[2],q=g[3],r=g[4];for(j=0;j<k;j++){g=n[d>>>24]^o[e>>16&255]^p[f>>8&255]^q[a&255]^c[l];h=n[e>>>24]^o[f>>16&255]^p[a>>8&255]^q[d&255]^c[l+1];i=n[f>>>24]^o[a>>16&255]^p[d>>8&255]^q[e&255]^c[l+2];a=n[a>>>24]^o[d>>16&
255]^p[e>>8&255]^q[f&255]^c[l+3];l+=4;d=g;e=h;f=i}for(j=0;j<4;j++){m[b?3&-j:j]=r[d>>>24]<<24^r[e>>16&255]<<16^r[f>>8&255]<<8^r[a&255]^c[l++];g=d;d=e;e=f;f=a;a=g}return m}};
sjcl.bitArray={bitSlice:function(a,b,c){a=sjcl.bitArray.P(a.slice(b/32),32-(b&31)).slice(1);return c===undefined?a:sjcl.bitArray.clamp(a,c-b)},extract:function(a,b,c){var d=Math.floor(-b-c&31);return((b+c-1^b)&-32?a[b/32|0]<<32-d^a[b/32+1|0]>>>d:a[b/32|0]>>>d)&(1<<c)-1},concat:function(a,b){if(a.length===0||b.length===0)return a.concat(b);var c=a[a.length-1],d=sjcl.bitArray.getPartial(c);return d===32?a.concat(b):sjcl.bitArray.P(b,d,c|0,a.slice(0,a.length-1))},bitLength:function(a){var b=a.length;
if(b===0)return 0;return(b-1)*32+sjcl.bitArray.getPartial(a[b-1])},clamp:function(a,b){if(a.length*32<b)return a;a=a.slice(0,Math.ceil(b/32));var c=a.length;b&=31;if(c>0&&b)a[c-1]=sjcl.bitArray.partial(b,a[c-1]&2147483648>>b-1,1);return a},partial:function(a,b,c){if(a===32)return b;return(c?b|0:b<<32-a)+a*0x10000000000},getPartial:function(a){return Math.round(a/0x10000000000)||32},equal:function(a,b){if(sjcl.bitArray.bitLength(a)!==sjcl.bitArray.bitLength(b))return false;var c=0,d;for(d=0;d<a.length;d++)c|=
a[d]^b[d];return c===0},P:function(a,b,c,d){var e;e=0;if(d===undefined)d=[];for(;b>=32;b-=32){d.push(c);c=0}if(b===0)return d.concat(a);for(e=0;e<a.length;e++){d.push(c|a[e]>>>b);c=a[e]<<32-b}e=a.length?a[a.length-1]:0;a=sjcl.bitArray.getPartial(e);d.push(sjcl.bitArray.partial(b+a&31,b+a>32?c:d.pop(),1));return d},k:function(a,b){return[a[0]^b[0],a[1]^b[1],a[2]^b[2],a[3]^b[3]]}};
sjcl.codec.utf8String={fromBits:function(a){var b="",c=sjcl.bitArray.bitLength(a),d,e;for(d=0;d<c/8;d++){if((d&3)===0)e=a[d/4];b+=String.fromCharCode(e>>>24);e<<=8}return decodeURIComponent(escape(b))},toBits:function(a){a=unescape(encodeURIComponent(a));var b=[],c,d=0;for(c=0;c<a.length;c++){d=d<<8|a.charCodeAt(c);if((c&3)===3){b.push(d);d=0}}c&3&&b.push(sjcl.bitArray.partial(8*(c&3),d));return b}};
sjcl.codec.hex={fromBits:function(a){var b="",c;for(c=0;c<a.length;c++)b+=((a[c]|0)+0xf00000000000).toString(16).substr(4);return b.substr(0,sjcl.bitArray.bitLength(a)/4)},toBits:function(a){var b,c=[],d;a=a.replace(/\s|0x/g,"");d=a.length;a+="00000000";for(b=0;b<a.length;b+=8)c.push(parseInt(a.substr(b,8),16)^0);return sjcl.bitArray.clamp(c,d*4)}};
sjcl.codec.base64={D:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",fromBits:function(a,b,c){var d="",e=0,f=sjcl.codec.base64.D,g=0,h=sjcl.bitArray.bitLength(a);if(c)f=f.substr(0,62)+"-_";for(c=0;d.length*6<h;){d+=f.charAt((g^a[c]>>>e)>>>26);if(e<6){g=a[c]<<6-e;e+=26;c++}else{g<<=6;e-=6}}for(;d.length&3&&!b;)d+="=";return d},toBits:function(a,b){a=a.replace(/\s|=/g,"");var c=[],d=0,e=sjcl.codec.base64.D,f=0,g;if(b)e=e.substr(0,62)+"-_";for(b=0;b<a.length;b++){g=e.indexOf(a.charAt(b));
if(g<0)throw new sjcl.exception.invalid("this isn't base64!");if(d>26){d-=26;c.push(f^g>>>d);f=g<<32-d}else{d+=6;f^=g<<32-d}}d&56&&c.push(sjcl.bitArray.partial(d&56,f,1));return c}};sjcl.codec.base64url={fromBits:function(a){return sjcl.codec.base64.fromBits(a,1,1)},toBits:function(a){return sjcl.codec.base64.toBits(a,1)}};sjcl.hash.sha256=function(a){this.a[0]||this.w();if(a){this.n=a.n.slice(0);this.i=a.i.slice(0);this.e=a.e}else this.reset()};sjcl.hash.sha256.hash=function(a){return(new sjcl.hash.sha256).update(a).finalize()};
sjcl.hash.sha256.prototype={blockSize:512,reset:function(){this.n=this.N.slice(0);this.i=[];this.e=0;return this},update:function(a){if(typeof a==="string")a=sjcl.codec.utf8String.toBits(a);var b,c=this.i=sjcl.bitArray.concat(this.i,a);b=this.e;a=this.e=b+sjcl.bitArray.bitLength(a);for(b=512+b&-512;b<=a;b+=512)this.C(c.splice(0,16));return this},finalize:function(){var a,b=this.i,c=this.n;b=sjcl.bitArray.concat(b,[sjcl.bitArray.partial(1,1)]);for(a=b.length+2;a&15;a++)b.push(0);b.push(Math.floor(this.e/
4294967296));for(b.push(this.e|0);b.length;)this.C(b.splice(0,16));this.reset();return c},N:[],a:[],w:function(){function a(e){return(e-Math.floor(e))*0x100000000|0}var b=0,c=2,d;a:for(;b<64;c++){for(d=2;d*d<=c;d++)if(c%d===0)continue a;if(b<8)this.N[b]=a(Math.pow(c,0.5));this.a[b]=a(Math.pow(c,1/3));b++}},C:function(a){var b,c,d=a.slice(0),e=this.n,f=this.a,g=e[0],h=e[1],i=e[2],k=e[3],j=e[4],l=e[5],m=e[6],n=e[7];for(a=0;a<64;a++){if(a<16)b=d[a];else{b=d[a+1&15];c=d[a+14&15];b=d[a&15]=(b>>>7^b>>>18^
b>>>3^b<<25^b<<14)+(c>>>17^c>>>19^c>>>10^c<<15^c<<13)+d[a&15]+d[a+9&15]|0}b=b+n+(j>>>6^j>>>11^j>>>25^j<<26^j<<21^j<<7)+(m^j&(l^m))+f[a];n=m;m=l;l=j;j=k+b|0;k=i;i=h;h=g;g=b+(h&i^k&(h^i))+(h>>>2^h>>>13^h>>>22^h<<30^h<<19^h<<10)|0}e[0]=e[0]+g|0;e[1]=e[1]+h|0;e[2]=e[2]+i|0;e[3]=e[3]+k|0;e[4]=e[4]+j|0;e[5]=e[5]+l|0;e[6]=e[6]+m|0;e[7]=e[7]+n|0}};
sjcl.mode.ccm={name:"ccm",encrypt:function(a,b,c,d,e){var f,g=b.slice(0),h=sjcl.bitArray,i=h.bitLength(c)/8,k=h.bitLength(g)/8;e=e||64;d=d||[];if(i<7)throw new sjcl.exception.invalid("ccm: iv must be at least 7 bytes");for(f=2;f<4&&k>>>8*f;f++);if(f<15-i)f=15-i;c=h.clamp(c,8*(15-f));b=sjcl.mode.ccm.G(a,b,c,d,e,f);g=sjcl.mode.ccm.I(a,g,c,b,e,f);return h.concat(g.data,g.tag)},decrypt:function(a,b,c,d,e){e=e||64;d=d||[];var f=sjcl.bitArray,g=f.bitLength(c)/8,h=f.bitLength(b),i=f.clamp(b,h-e),k=f.bitSlice(b,
h-e);h=(h-e)/8;if(g<7)throw new sjcl.exception.invalid("ccm: iv must be at least 7 bytes");for(b=2;b<4&&h>>>8*b;b++);if(b<15-g)b=15-g;c=f.clamp(c,8*(15-b));i=sjcl.mode.ccm.I(a,i,c,k,e,b);a=sjcl.mode.ccm.G(a,i.data,c,d,e,b);if(!f.equal(i.tag,a))throw new sjcl.exception.corrupt("ccm: tag doesn't match");return i.data},G:function(a,b,c,d,e,f){var g=[],h=sjcl.bitArray,i=h.k;e/=8;if(e%2||e<4||e>16)throw new sjcl.exception.invalid("ccm: invalid tag length");if(d.length>0xffffffff||b.length>0xffffffff)throw new sjcl.exception.bug("ccm: can't deal with 4GiB or more data");
f=[h.partial(8,(d.length?64:0)|e-2<<2|f-1)];f=h.concat(f,c);f[3]|=h.bitLength(b)/8;f=a.encrypt(f);if(d.length){c=h.bitLength(d)/8;if(c<=65279)g=[h.partial(16,c)];else if(c<=0xffffffff)g=h.concat([h.partial(16,65534)],[c]);g=h.concat(g,d);for(d=0;d<g.length;d+=4)f=a.encrypt(i(f,g.slice(d,d+4).concat([0,0,0])))}for(d=0;d<b.length;d+=4)f=a.encrypt(i(f,b.slice(d,d+4).concat([0,0,0])));return h.clamp(f,e*8)},I:function(a,b,c,d,e,f){var g,h=sjcl.bitArray;g=h.k;var i=b.length,k=h.bitLength(b);c=h.concat([h.partial(8,
f-1)],c).concat([0,0,0]).slice(0,4);d=h.bitSlice(g(d,a.encrypt(c)),0,e);if(!i)return{tag:d,data:[]};for(g=0;g<i;g+=4){c[3]++;e=a.encrypt(c);b[g]^=e[0];b[g+1]^=e[1];b[g+2]^=e[2];b[g+3]^=e[3]}return{tag:d,data:h.clamp(b,k)}}};
sjcl.mode.ocb2={name:"ocb2",encrypt:function(a,b,c,d,e,f){if(sjcl.bitArray.bitLength(c)!==128)throw new sjcl.exception.invalid("ocb iv must be 128 bits");var g,h=sjcl.mode.ocb2.A,i=sjcl.bitArray,k=i.k,j=[0,0,0,0];c=h(a.encrypt(c));var l,m=[];d=d||[];e=e||64;for(g=0;g+4<b.length;g+=4){l=b.slice(g,g+4);j=k(j,l);m=m.concat(k(c,a.encrypt(k(c,l))));c=h(c)}l=b.slice(g);b=i.bitLength(l);g=a.encrypt(k(c,[0,0,0,b]));l=i.clamp(k(l.concat([0,0,0]),g),b);j=k(j,k(l.concat([0,0,0]),g));j=a.encrypt(k(j,k(c,h(c))));
if(d.length)j=k(j,f?d:sjcl.mode.ocb2.pmac(a,d));return m.concat(i.concat(l,i.clamp(j,e)))},decrypt:function(a,b,c,d,e,f){if(sjcl.bitArray.bitLength(c)!==128)throw new sjcl.exception.invalid("ocb iv must be 128 bits");e=e||64;var g=sjcl.mode.ocb2.A,h=sjcl.bitArray,i=h.k,k=[0,0,0,0],j=g(a.encrypt(c)),l,m,n=sjcl.bitArray.bitLength(b)-e,o=[];d=d||[];for(c=0;c+4<n/32;c+=4){l=i(j,a.decrypt(i(j,b.slice(c,c+4))));k=i(k,l);o=o.concat(l);j=g(j)}m=n-c*32;l=a.encrypt(i(j,[0,0,0,m]));l=i(l,h.clamp(b.slice(c),
m).concat([0,0,0]));k=i(k,l);k=a.encrypt(i(k,i(j,g(j))));if(d.length)k=i(k,f?d:sjcl.mode.ocb2.pmac(a,d));if(!h.equal(h.clamp(k,e),h.bitSlice(b,n)))throw new sjcl.exception.corrupt("ocb: tag doesn't match");return o.concat(h.clamp(l,m))},pmac:function(a,b){var c,d=sjcl.mode.ocb2.A,e=sjcl.bitArray,f=e.k,g=[0,0,0,0],h=a.encrypt([0,0,0,0]);h=f(h,d(d(h)));for(c=0;c+4<b.length;c+=4){h=d(h);g=f(g,a.encrypt(f(h,b.slice(c,c+4))))}b=b.slice(c);if(e.bitLength(b)<128){h=f(h,d(h));b=e.concat(b,[2147483648|0,0,
0,0])}g=f(g,b);return a.encrypt(f(d(f(h,d(h))),g))},A:function(a){return[a[0]<<1^a[1]>>>31,a[1]<<1^a[2]>>>31,a[2]<<1^a[3]>>>31,a[3]<<1^(a[0]>>>31)*135]}};sjcl.misc.hmac=function(a,b){this.M=b=b||sjcl.hash.sha256;var c=[[],[]],d=b.prototype.blockSize/32;this.l=[new b,new b];if(a.length>d)a=b.hash(a);for(b=0;b<d;b++){c[0][b]=a[b]^909522486;c[1][b]=a[b]^1549556828}this.l[0].update(c[0]);this.l[1].update(c[1])};
sjcl.misc.hmac.prototype.encrypt=sjcl.misc.hmac.prototype.mac=function(a,b){a=(new this.M(this.l[0])).update(a,b).finalize();return(new this.M(this.l[1])).update(a).finalize()};
sjcl.misc.pbkdf2=function(a,b,c,d,e){c=c||1E3;if(d<0||c<0)throw sjcl.exception.invalid("invalid params to pbkdf2");if(typeof a==="string")a=sjcl.codec.utf8String.toBits(a);e=e||sjcl.misc.hmac;a=new e(a);var f,g,h,i,k=[],j=sjcl.bitArray;for(i=1;32*k.length<(d||1);i++){e=f=a.encrypt(j.concat(b,[i]));for(g=1;g<c;g++){f=a.encrypt(f);for(h=0;h<f.length;h++)e[h]^=f[h]}k=k.concat(e)}if(d)k=j.clamp(k,d);return k};
sjcl.random={randomWords:function(a,b){var c=[];b=this.isReady(b);var d;if(b===0)throw new sjcl.exception.notReady("generator isn't seeded");else b&2&&this.U(!(b&1));for(b=0;b<a;b+=4){(b+1)%0x10000===0&&this.L();d=this.u();c.push(d[0],d[1],d[2],d[3])}this.L();return c.slice(0,a)},setDefaultParanoia:function(a){this.t=a},addEntropy:function(a,b,c){c=c||"user";var d,e,f=(new Date).valueOf(),g=this.q[c],h=this.isReady();d=this.F[c];if(d===undefined)d=this.F[c]=this.R++;if(g===undefined)g=this.q[c]=0;this.q[c]=
(this.q[c]+1)%this.b.length;switch(typeof a){case "number":break;case "object":if(b===undefined)for(c=b=0;c<a.length;c++)for(e=a[c];e>0;){b++;e>>>=1}this.b[g].update([d,this.J++,2,b,f,a.length].concat(a));break;case "string":if(b===undefined)b=a.length;this.b[g].update([d,this.J++,3,b,f,a.length]);this.b[g].update(a);break;default:throw new sjcl.exception.bug("random: addEntropy only supports number, array or string");}this.j[g]+=b;this.f+=b;if(h===0){this.isReady()!==0&&this.K("seeded",Math.max(this.g,
this.f));this.K("progress",this.getProgress())}},isReady:function(a){a=this.B[a!==undefined?a:this.t];return this.g&&this.g>=a?this.j[0]>80&&(new Date).valueOf()>this.O?3:1:this.f>=a?2:0},getProgress:function(a){a=this.B[a?a:this.t];return this.g>=a?1:this.f>a?1:this.f/a},startCollectors:function(){if(!this.m){if(window.addEventListener){window.addEventListener("load",this.o,false);window.addEventListener("mousemove",this.p,false)}else if(document.attachEvent){document.attachEvent("onload",this.o);
document.attachEvent("onmousemove",this.p)}else throw new sjcl.exception.bug("can't attach event");this.m=true}},stopCollectors:function(){if(this.m){if(window.removeEventListener){window.removeEventListener("load",this.o,false);window.removeEventListener("mousemove",this.p,false)}else if(window.detachEvent){window.detachEvent("onload",this.o);window.detachEvent("onmousemove",this.p)}this.m=false}},addEventListener:function(a,b){this.r[a][this.Q++]=b},removeEventListener:function(a,b){var c;a=this.r[a];
var d=[];for(c in a)a.hasOwnProperty(c)&&a[c]===b&&d.push(c);for(b=0;b<d.length;b++){c=d[b];delete a[c]}},b:[new sjcl.hash.sha256],j:[0],z:0,q:{},J:0,F:{},R:0,g:0,f:0,O:0,a:[0,0,0,0,0,0,0,0],d:[0,0,0,0],s:undefined,t:6,m:false,r:{progress:{},seeded:{}},Q:0,B:[0,48,64,96,128,192,0x100,384,512,768,1024],u:function(){for(var a=0;a<4;a++){this.d[a]=this.d[a]+1|0;if(this.d[a])break}return this.s.encrypt(this.d)},L:function(){this.a=this.u().concat(this.u());this.s=new sjcl.cipher.aes(this.a)},T:function(a){this.a=
sjcl.hash.sha256.hash(this.a.concat(a));this.s=new sjcl.cipher.aes(this.a);for(a=0;a<4;a++){this.d[a]=this.d[a]+1|0;if(this.d[a])break}},U:function(a){var b=[],c=0,d;this.O=b[0]=(new Date).valueOf()+3E4;for(d=0;d<16;d++)b.push(Math.random()*0x100000000|0);for(d=0;d<this.b.length;d++){b=b.concat(this.b[d].finalize());c+=this.j[d];this.j[d]=0;if(!a&&this.z&1<<d)break}if(this.z>=1<<this.b.length){this.b.push(new sjcl.hash.sha256);this.j.push(0)}this.f-=c;if(c>this.g)this.g=c;this.z++;this.T(b)},p:function(a){sjcl.random.addEntropy([a.x||
a.clientX||a.offsetX,a.y||a.clientY||a.offsetY],2,"mouse")},o:function(){sjcl.random.addEntropy(new Date,2,"loadtime")},K:function(a,b){var c;a=sjcl.random.r[a];var d=[];for(c in a)a.hasOwnProperty(c)&&d.push(a[c]);for(c=0;c<d.length;c++)d[c](b)}};try{var s=new Uint32Array(32);crypto.getRandomValues(s);sjcl.random.addEntropy(s,1024,"crypto['getRandomValues']")}catch(t){}
sjcl.json={defaults:{v:1,iter:1E3,ks:128,ts:64,mode:"ccm",adata:"",cipher:"aes"},encrypt:function(a,b,c,d){c=c||{};d=d||{};var e=sjcl.json,f=e.c({iv:sjcl.random.randomWords(4,0)},e.defaults),g;e.c(f,c);c=f.adata;if(typeof f.salt==="string")f.salt=sjcl.codec.base64.toBits(f.salt);if(typeof f.iv==="string")f.iv=sjcl.codec.base64.toBits(f.iv);if(!sjcl.mode[f.mode]||!sjcl.cipher[f.cipher]||typeof a==="string"&&f.iter<=100||f.ts!==64&&f.ts!==96&&f.ts!==128||f.ks!==128&&f.ks!==192&&f.ks!==0x100||f.iv.length<
2||f.iv.length>4)throw new sjcl.exception.invalid("json encrypt: invalid parameters");if(typeof a==="string"){g=sjcl.misc.cachedPbkdf2(a,f);a=g.key.slice(0,f.ks/32);f.salt=g.salt}if(typeof b==="string")b=sjcl.codec.utf8String.toBits(b);if(typeof c==="string")c=sjcl.codec.utf8String.toBits(c);g=new sjcl.cipher[f.cipher](a);e.c(d,f);d.key=a;f.ct=sjcl.mode[f.mode].encrypt(g,b,f.iv,c,f.ts);return e.encode(f)},decrypt:function(a,b,c,d){c=c||{};d=d||{};var e=sjcl.json;b=e.c(e.c(e.c({},e.defaults),e.decode(b)),
c,true);var f;c=b.adata;if(typeof b.salt==="string")b.salt=sjcl.codec.base64.toBits(b.salt);if(typeof b.iv==="string")b.iv=sjcl.codec.base64.toBits(b.iv);if(!sjcl.mode[b.mode]||!sjcl.cipher[b.cipher]||typeof a==="string"&&b.iter<=100||b.ts!==64&&b.ts!==96&&b.ts!==128||b.ks!==128&&b.ks!==192&&b.ks!==0x100||!b.iv||b.iv.length<2||b.iv.length>4)throw new sjcl.exception.invalid("json decrypt: invalid parameters");if(typeof a==="string"){f=sjcl.misc.cachedPbkdf2(a,b);a=f.key.slice(0,b.ks/32);b.salt=f.salt}if(typeof c===
"string")c=sjcl.codec.utf8String.toBits(c);f=new sjcl.cipher[b.cipher](a);c=sjcl.mode[b.mode].decrypt(f,b.ct,b.iv,c,b.ts);e.c(d,b);d.key=a;return sjcl.codec.utf8String.fromBits(c)},encode:function(a){var b,c="{",d="";for(b in a)if(a.hasOwnProperty(b)){if(!b.match(/^[a-z0-9]+$/i))throw new sjcl.exception.invalid("json encode: invalid property name");c+=d+'"'+b+'":';d=",";switch(typeof a[b]){case "number":case "boolean":c+=a[b];break;case "string":c+='"'+escape(a[b])+'"';break;case "object":c+='"'+
sjcl.codec.base64.fromBits(a[b],1)+'"';break;default:throw new sjcl.exception.bug("json encode: unsupported type");}}return c+"}"},decode:function(a){a=a.replace(/\s/g,"");if(!a.match(/^\{.*\}$/))throw new sjcl.exception.invalid("json decode: this isn't json!");a=a.replace(/^\{|\}$/g,"").split(/,/);var b={},c,d;for(c=0;c<a.length;c++){if(!(d=a[c].match(/^(?:(["']?)([a-z][a-z0-9]*)\1):(?:(\d+)|"([a-z0-9+\/%*_.@=\-]*)")$/i)))throw new sjcl.exception.invalid("json decode: this isn't json!");b[d[2]]=
d[3]?parseInt(d[3],10):d[2].match(/^(ct|salt|iv)$/)?sjcl.codec.base64.toBits(d[4]):unescape(d[4])}return b},c:function(a,b,c){if(a===undefined)a={};if(b===undefined)return a;var d;for(d in b)if(b.hasOwnProperty(d)){if(c&&a[d]!==undefined&&a[d]!==b[d])throw new sjcl.exception.invalid("required parameter overridden");a[d]=b[d]}return a},W:function(a,b){var c={},d;for(d in a)if(a.hasOwnProperty(d)&&a[d]!==b[d])c[d]=a[d];return c},V:function(a,b){var c={},d;for(d=0;d<b.length;d++)if(a[b[d]]!==undefined)c[b[d]]=
a[b[d]];return c}};sjcl.encrypt=sjcl.json.encrypt;sjcl.decrypt=sjcl.json.decrypt;sjcl.misc.S={};sjcl.misc.cachedPbkdf2=function(a,b){var c=sjcl.misc.S,d;b=b||{};d=b.iter||1E3;c=c[a]=c[a]||{};d=c[d]=c[d]||{firstSalt:b.salt&&b.salt.length?b.salt.slice(0):sjcl.random.randomWords(2,0)};c=b.salt===undefined?d.firstSalt:b.salt;d[c]=d[c]||sjcl.misc.pbkdf2(a,c,b.iter);return{key:d[c].slice(0),salt:c.slice(0)}};


 // includes/plugin_data/js/data.js

ucm = ucm || {};

ucm.data = {
    lang: {
        Save: 'Save',
        Cancel: 'Cancel'
    },
    settings: {
        url: '?m=data&p=admin_data&display_mode=iframe'
    },
    init: function(){
        var t = this;
        setTimeout(function(){
            $('.custom_data_embed_wrapper .tableclass_rows tr').each(function(){
                $(this).off('click');
            });
        },700);
        $('.custom_data_open').click(function(e){
            var btn = this;
            t.popup_init(btn);
            var url = $.param($(btn).data('settings'));
            e.preventDefault();
            $("#data_popup").dialog({
                autoOpen: true,
                height: 800,
                width: 800,
                modal: true,
                buttons: {
                    'Close': function() {
                        $(this).dialog('close');
                    }
                },
                open: function() {
                    $('#data_popup_inner iframe').attr('src',ucm.data.settings.url + '&' + url);
                }
            });
            return false;
        });

    },
    popup_init: function(btn){
        $('#data_popup').remove();
        var settings = $(btn).parents('.custom_data_embed_wrapper').first().data('settings');
        $('body').append('<div id="data_popup" title="' + settings.title + '"><div id="data_popup_inner" style="height: 100%; position: relative;"><iframe src="about:blank" style="width:100%; height:100%; border:0" frameborder="0"></iframe> </div></div>');
        return $.param(settings);
    },
    popup: function(btn){
        var url = this.popup_init(btn);
        $("#data_popup").dialog({
            autoOpen: true,
            height: 800,
            width: 800,
            modal: true,
            buttons: {
                'Close': function() {
                    $(this).dialog('close');
                }
            },
            open: function() {
                $('#data_popup_inner iframe').attr('src',ucm.data.settings.url + '&' + url);
            }
        });
        return false;
    },
    popup_new: function(btn){
        var url = this.popup_init(btn);
        $("#data_popup").dialog({
            autoOpen: true,
            height: 800,
            width: 800,
            modal: true,
            buttons: {
                'Close': function() {
                    $(this).dialog('close');
                }
            },
            open: function() {
                $('#data_popup_inner iframe').attr('src',ucm.data.settings.url + '&' + url + '&data_record_id=new');
            }
        });
        return false;
    }
};

$(function(){ucm.data.init();});

 // includes/plugin_config/js/settings.js

ucm.settings_popup = {
    init: function(){
        // hunt for any elements that contain data-settings-url attributes and insert a new icon url into the container?
        $("[data-settings-url!=''][data-settings-url]").each(function(){
            $(this).prepend('<span class="data-settings-button ' + $(this).data('settings-class') + '"><a href="' + $(this).data('settings-url') + '" target="_blank">Settings</a></span>');
        });
    }
};
$(function(){
  ucm.settings_popup.init();
});


 // includes/plugin_finance/js/finance.js

ucm.finance = {
    init: function(){
        var t = this;

        // options for editing the tax of a finance item.

        // if the user is changing the sub amount total amount manually:
        $('#finance_sub_amount').on('change',function(){
            if(!t.changing_in_progress)t.changing='total';
            t.update_finance_total();
        }).on('keyup',function(){
            if(!t.changing_in_progress)t.changing='total';
            t.update_finance_total();
        });
        // if the user is changing the sub taxable total amount manually:
        $('#finance_taxable_amount').on('change',function(){
            if(!t.changing_in_progress){
                t.changing='total';
                if(parseFloat($(this).val()) > parseFloat($('#finance_sub_amount').val())){
                    // dont let them pick a higher taxable amount than sub amount
                    $('#finance_sub_amount').val($(this).val());
                }
            }
            t.update_finance_total();
        }).on('keyup',function(){
            if(!t.changing_in_progress){
                t.changing='total';
                if(parseFloat($(this).val()) > parseFloat($('#finance_sub_amount').val())){
                    // dont let them pick a higher taxable amount than sub amount
                    $('#finance_sub_amount').val($(this).val());
                }
            }
            t.update_finance_total();
        });
        // if the user is changing the total amount manually:
        $('#finance_total_amount').on('change',function(){
            if(!t.changing_in_progress)t.changing='subtotal';
            t.update_finance_total();
        }).on('keyup',function(){
            if(!t.changing_in_progress)t.changing='subtotal';
            t.update_finance_total();
        });

        $('#finance_tax_holder').on('change','.tax_percent',function(){
            t.update_finance_total();
        }).on('keyup','.tax_percent',function(){
            t.update_finance_total();
        });
        $('#tax_increment_checkbox').on('change',function(){
            t.update_finance_total();
        });
        t.update_finance_total();
    },
    // we are either updating the 'total' or we are updating the 'sub total'
    // depending on which one was 'changed' last.
    changing: 'total',
    changing_in_progress: false,
    update_finance_total: function(){
        var t = this;
        if($('#finance_tax_holder .dynamic_block').length > 1)$('.finance_tax_increment').show(); else $('.finance_tax_increment').hide();
        t.changing_in_progress = true;
        var sub_amount = parseFloat($('#finance_sub_amount').val());
        var taxable_amount = parseFloat($('#finance_taxable_amount').val());
        var original_taxable_amount = taxable_amount;
        var amount = parseFloat($('#finance_total_amount').val());
        if(
            (t.changing == 'total' && (!isNaN(taxable_amount) || taxable_amount>0)) ||
            (t.changing == 'subtotal' && (!isNaN(amount) || amount>0))
        ){
            var incremental = $('#tax_increment_checkbox')[0].checked;
            var tax_amount = parseFloat(0);
            var tax_percents = parseFloat(0);
            var madness = function(){
                var tax = parseFloat($(this).find('.tax_percent').val());
                if(!isNaN(tax) && tax>0){
                    if(incremental){
                        // incrementing tax along the way. to amount
                        if(t.changing == 'total'){
                            // user wants the 'total' to be updated based on the current 'subtotal' amount
                            var this_tax = (taxable_amount * (tax/100));
                            var this_tax_display = Math.round(this_tax*1000)/1000;
                            $(this).find('.tax_amount').html(this_tax_display);
                            $(this).find('.tax_amount_input').val(this_tax_display);
                            taxable_amount += this_tax; //(taxable_amount * (tax/100));
                        }else{
                            // user wants the 'subtotal' to be updated based on the current 'total' amount
                            var new_amount = amount / (1 + (tax / 100));
                            var this_tax = amount-new_amount;
                            var this_tax_display = Math.round(this_tax*1000)/1000;
                            $(this).find('.tax_amount').html(this_tax_display);
                            $(this).find('.tax_amount_input').val(this_tax_display);

                            amount = new_amount;
                        }
                    }else{
                        if(t.changing == 'total'){
                            // user wants the 'total' to be updated based on the current 'subtotal' amount
                            var this_tax = (taxable_amount * (tax/100));
                            var this_tax_display = Math.round(this_tax*1000)/1000;
                            $(this).find('.tax_amount').html(this_tax_display);
                            $(this).find('.tax_amount_input').val(this_tax_display);
                            tax_amount += this_tax; //(taxable_amount * (tax/100));
                        }else{
                            // todo - this doesn't work.
                            var this_tax = 0;
                            var this_tax_display = Math.round(this_tax*1000)/1000;
                            $(this).find('.tax_amount').html(this_tax_display);
                            $(this).find('.tax_amount_input').val(this_tax_display);

                            tax_percents += (tax/100);
                        }
                    }
                }
            };

            if(t.changing == 'total'){
                // user wants the 'total' to be updated based on the current 'subtotal' amount
                $('#finance_tax_holder .dynamic_block').each(madness);
                $('#finance_total_amount').val(Math.round((sub_amount + (taxable_amount-original_taxable_amount) + tax_amount)*100)/100);
                // update the sub total if these were the same before.
            }else{
                // user wants the 'subtotal' to be updated based on the current 'total' amount
                $($('#finance_tax_holder .dynamic_block').get().reverse()).each(madness);
                $('#finance_taxable_amount').val(Math.round((amount / (1 + (tax_percents)))*100)/100);
                $('#finance_sub_amount').val(Math.round((amount / (1 + (tax_percents)))*100)/100);
            }
        }
        t.changing_in_progress = false;
    }
};

 // includes/plugin_form/js/jquery.timepicker.min.js

/*!
 * jquery-timepicker v1.3.10 - A jQuery timepicker plugin inspired by Google Calendar. It supports both mouse and keyboard navigation.
 * Copyright (c) 2014 Jon Thornton - http://jonthornton.github.com/jquery-timepicker/
 * License:
 */

!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a(jQuery)}(function(a){function b(a){if(a.minTime&&(a.minTime=s(a.minTime)),a.maxTime&&(a.maxTime=s(a.maxTime)),a.durationTime&&"function"!=typeof a.durationTime&&(a.durationTime=s(a.durationTime)),a.disableTimeRanges.length>0){for(var b in a.disableTimeRanges)a.disableTimeRanges[b]=[s(a.disableTimeRanges[b][0]),s(a.disableTimeRanges[b][1])];a.disableTimeRanges=a.disableTimeRanges.sort(function(a,b){return a[0]-b[0]});for(var b=a.disableTimeRanges.length-1;b>0;b--)a.disableTimeRanges[b][0]<=a.disableTimeRanges[b-1][1]&&(a.disableTimeRanges[b-1]=[Math.min(a.disableTimeRanges[b][0],a.disableTimeRanges[b-1][0]),Math.max(a.disableTimeRanges[b][1],a.disableTimeRanges[b-1][1])],a.disableTimeRanges.splice(b,1))}return a}function c(b){var c=b.data("timepicker-settings"),f=b.data("timepicker-list");if(f&&f.length&&(f.remove(),b.data("timepicker-list",!1)),c.useSelect){f=a("<select />",{"class":"ui-timepicker-select"});var g=f}else{f=a("<ul />",{"class":"ui-timepicker-list"});var g=a("<div />",{"class":"ui-timepicker-wrapper",tabindex:-1});g.css({display:"none",position:"absolute"}).append(f)}if(c.noneOption)if(c.noneOption===!0&&(c.noneOption=c.useSelect?"Time...":"None"),a.isArray(c.noneOption)){for(var i in c.noneOption)if(parseInt(i,10)===i){var k=d(c.noneOption[i],c.useSelect);f.append(k)}}else{var k=d(c.noneOption,c.useSelect);f.append(k)}c.className&&g.addClass(c.className),null===c.minTime&&null===c.durationTime||!c.showDuration||(g.addClass("ui-timepicker-with-duration"),g.addClass("ui-timepicker-step-"+c.step));var l=c.minTime;"function"==typeof c.durationTime?l=s(c.durationTime()):null!==c.durationTime&&(l=c.durationTime);var n=null!==c.minTime?c.minTime:0,o=null!==c.maxTime?c.maxTime:n+v-1;n>=o&&(o+=v),o===v-1&&-1!==c.timeFormat.indexOf("H")&&(o=v);for(var t=c.disableTimeRanges,u=0,w=t.length,i=n;o>=i;i+=60*c.step){var x=i,z=r(x,c.timeFormat);if(c.useSelect){var A=a("<option />",{value:z});A.text(z)}else{var A=a("<li />");A.data("time",86400>=x?x:x%86400),A.text(z)}if((null!==c.minTime||null!==c.durationTime)&&c.showDuration){var B=q(i-l,c.step);if(c.useSelect)A.text(A.text()+" ("+B+")");else{var C=a("<span />",{"class":"ui-timepicker-duration"});C.text(" ("+B+")"),A.append(C)}}w>u&&(x>=t[u][1]&&(u+=1),t[u]&&x>=t[u][0]&&x<t[u][1]&&(c.useSelect?A.prop("disabled",!0):A.addClass("ui-timepicker-disabled"))),f.append(A)}if(g.data("timepicker-input",b),b.data("timepicker-list",g),c.useSelect)f.val(e(b.val(),c)),f.on("focus",function(){a(this).data("timepicker-input").trigger("showTimepicker")}),f.on("blur",function(){a(this).data("timepicker-input").trigger("hideTimepicker")}),f.on("change",function(){m(b,a(this).val(),"select")}),b.hide().after(f);else{var D=c.appendTo;"string"==typeof D?D=a(D):"function"==typeof D&&(D=D(b)),D.append(g),j(b,f),f.on("click","li",function(){b.off("focus.timepicker"),b.on("focus.timepicker-ie-hack",function(){b.off("focus.timepicker-ie-hack"),b.on("focus.timepicker",y.show)}),h(b)||b[0].focus(),f.find("li").removeClass("ui-timepicker-selected"),a(this).addClass("ui-timepicker-selected"),p(b)&&(b.trigger("hideTimepicker"),g.hide())})}}function d(b,c){var d,e,f;return"object"==typeof b?(d=b.label,e=b.className,f=b.value):"string"==typeof b?d=b:a.error("Invalid noneOption value"),c?a("<option />",{value:f,"class":e,text:d}):a("<li />",{"class":e,text:d}).data("time",f)}function e(b,c){if(a.isNumeric(b)||(b=s(b)),null===b)return null;var d=60*c.step;return r(Math.round(b/d)*d,c.timeFormat)}function f(){return new Date(1970,1,1,0,0,0)}function g(b){var c=a(b.target),d=c.closest(".ui-timepicker-input");0===d.length&&0===c.closest(".ui-timepicker-wrapper").length&&(y.hide(),a(document).unbind(".ui-timepicker"))}function h(a){var b=a.data("timepicker-settings");return(window.navigator.msMaxTouchPoints||"ontouchstart"in document)&&b.disableTouchKeyboard}function i(b,c,d){if(!d&&0!==d)return!1;var e=b.data("timepicker-settings"),f=!1,g=30*e.step;return c.find("li").each(function(b,c){var e=a(c);if("number"==typeof e.data("time")){var h=e.data("time")-d;return Math.abs(h)<g||h==g?(f=e,!1):void 0}}),f}function j(a,b){b.find("li").removeClass("ui-timepicker-selected");var c=s(l(a));if(null!==c){var d=i(a,b,c);if(d){var e=d.offset().top-b.offset().top;(e+d.outerHeight()>b.outerHeight()||0>e)&&b.scrollTop(b.scrollTop()+d.position().top-d.outerHeight()),d.addClass("ui-timepicker-selected")}}}function k(b){if(""!==this.value){var c=a(this);if(c.data("timepicker-list"),!c.is(":focus")||b&&"change"==b.type){var d=s(this.value);if(null===d)return c.trigger("timeFormatError"),void 0;var e=c.data("timepicker-settings"),f=!1;if(null!==e.minTime&&d<e.minTime?f=!0:null!==e.maxTime&&d>e.maxTime&&(f=!0),a.each(e.disableTimeRanges,function(){return d>=this[0]&&d<this[1]?(f=!0,!1):void 0}),e.forceRoundTime){var g=d%(60*e.step);g>=30*e.step?d+=60*e.step-g:d-=g}var h=r(d,e.timeFormat);f?m(c,h,"error")&&c.trigger("timeRangeError"):m(c,h)}}}function l(a){return a.is("input")?a.val():a.data("ui-timepicker-value")}function m(a,b,c){if(a.is("input")){a.val(b);var d=a.data("timepicker-settings");d.useSelect&&a.data("timepicker-list").val(e(b,d))}return a.data("ui-timepicker-value")!=b?(a.data("ui-timepicker-value",b),"select"==c?a.trigger("selectTime").trigger("changeTime").trigger("change"):"error"!=c&&a.trigger("changeTime"),!0):(a.trigger("selectTime"),!1)}function n(b){var c=a(this),d=c.data("timepicker-list");if(!d||!d.is(":visible")){if(40!=b.keyCode)return!0;h(c)||c.focus()}switch(b.keyCode){case 13:return p(c)&&y.hide.apply(this),b.preventDefault(),!1;case 38:var e=d.find(".ui-timepicker-selected");return e.length?e.is(":first-child")||(e.removeClass("ui-timepicker-selected"),e.prev().addClass("ui-timepicker-selected"),e.prev().position().top<e.outerHeight()&&d.scrollTop(d.scrollTop()-e.outerHeight())):(d.find("li").each(function(b,c){return a(c).position().top>0?(e=a(c),!1):void 0}),e.addClass("ui-timepicker-selected")),!1;case 40:return e=d.find(".ui-timepicker-selected"),0===e.length?(d.find("li").each(function(b,c){return a(c).position().top>0?(e=a(c),!1):void 0}),e.addClass("ui-timepicker-selected")):e.is(":last-child")||(e.removeClass("ui-timepicker-selected"),e.next().addClass("ui-timepicker-selected"),e.next().position().top+2*e.outerHeight()>d.outerHeight()&&d.scrollTop(d.scrollTop()+e.outerHeight())),!1;case 27:d.find("li").removeClass("ui-timepicker-selected"),y.hide();break;case 9:y.hide();break;default:return!0}}function o(b){var c=a(this),d=c.data("timepicker-list");if(!d||!d.is(":visible"))return!0;if(!c.data("timepicker-settings").typeaheadHighlight)return d.find("li").removeClass("ui-timepicker-selected"),!0;switch(b.keyCode){case 96:case 97:case 98:case 99:case 100:case 101:case 102:case 103:case 104:case 105:case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 65:case 77:case 80:case 186:case 8:case 46:j(c,d);break;default:return}}function p(a){var b=a.data("timepicker-settings"),c=a.data("timepicker-list"),d=null,e=c.find(".ui-timepicker-selected");if(e.hasClass("ui-timepicker-disabled"))return!1;if(e.length&&(d=e.data("time")),null!==d)if("string"==typeof d)a.val(d);else{var f=r(d,b.timeFormat);m(a,f,"select")}return!0}function q(a,b){var c,d,e=Math.round(a/60),f=[];return 60>e?f=[e,x.mins]:(c=Math.floor(e/60),d=e%60,30==b&&30==d&&(c+=x.decimal+5),f.push(c),f.push(1==c?x.hr:x.hrs),30!=b&&d&&(f.push(d),f.push(x.mins))),f.join(" ")}function r(a,b){if(null!==a){var c=new Date(u.valueOf()+1e3*a);if(!isNaN(c.getTime())){for(var d,e,f="",g=0;g<b.length;g++)switch(e=b.charAt(g)){case"a":f+=c.getHours()>11?x.pm:x.am;break;case"A":f+=c.getHours()>11?x.PM:x.AM;break;case"g":d=c.getHours()%12,f+=0===d?"12":d;break;case"G":f+=c.getHours();break;case"h":d=c.getHours()%12,0!==d&&10>d&&(d="0"+d),f+=0===d?"12":d;break;case"H":d=c.getHours(),a===v&&(d=24),f+=d>9?d:"0"+d;break;case"i":var h=c.getMinutes();f+=h>9?h:"0"+h;break;case"s":a=c.getSeconds(),f+=a>9?a:"0"+a;break;default:f+=e}return f}}}function s(a){if(""===a)return null;if(!a||a+0==a)return a;"object"==typeof a&&(a=a.getHours()+":"+t(a.getMinutes())+":"+t(a.getSeconds())),a=a.toLowerCase(),new Date(0);var b;if(-1===a.indexOf(":")?(b=a.match(/^([0-9]):?([0-5][0-9])?:?([0-5][0-9])?\s*([pa]?)m?$/),b||(b=a.match(/^([0-2][0-9]):?([0-5][0-9])?:?([0-5][0-9])?\s*([pa]?)m?$/))):b=a.match(/^(\d{1,2})(?::([0-5][0-9]))?(?::([0-5][0-9]))?\s*([pa]?)m?$/),!b)return null;var c,d=parseInt(1*b[1],10);c=b[4]?12==d?"p"==b[4]?12:0:d+("p"==b[4]?12:0):d;var e=1*b[2]||0,f=1*b[3]||0;return 3600*c+60*e+f}function t(a){return("0"+a).slice(-2)}var u=f(),v=86400,w={className:null,minTime:null,maxTime:null,durationTime:null,step:30,showDuration:!1,showOnFocus:!0,timeFormat:"g:ia",scrollDefaultNow:!1,scrollDefaultTime:!1,selectOnBlur:!1,disableTouchKeyboard:!1,forceRoundTime:!1,appendTo:"body",orientation:"ltr",disableTimeRanges:[],closeOnWindowScroll:!1,typeaheadHighlight:!0,noneOption:!1},x={am:"am",pm:"pm",AM:"AM",PM:"PM",decimal:".",mins:"mins",hr:"hr",hrs:"hrs"},y={init:function(d){return this.each(function(){var e=a(this),f=[];for(var g in w)e.data(g)&&(f[g]=e.data(g));var h=a.extend({},w,f,d);h.lang&&(x=a.extend(x,h.lang)),h=b(h),e.data("timepicker-settings",h),e.addClass("ui-timepicker-input"),h.useSelect?c(e):(e.prop("autocomplete","off"),e.on("click.timepicker focus.timepicker",y.show),e.on("change.timepicker",k),e.on("keydown.timepicker",n),e.on("keyup.timepicker",o),k.call(e.get(0)))})},show:function(b){var d=a(this),e=d.data("timepicker-settings");if(b){if(!e.showOnFocus)return!0;b.preventDefault()}if(e.useSelect)return d.data("timepicker-list").focus(),void 0;h(d)&&d.blur();var f=d.data("timepicker-list");if(!d.prop("readonly")&&(f&&0!==f.length&&"function"!=typeof e.durationTime||(c(d),f=d.data("timepicker-list")),!f.is(":visible"))){y.hide(),f.show();var j={};j.left="rtl"==e.orientation?d.offset().left+d.outerWidth()-f.outerWidth()+parseInt(f.css("marginLeft").replace("px",""),10):d.offset().left+parseInt(f.css("marginLeft").replace("px",""),10),j.top=d.offset().top+d.outerHeight(!0)+f.outerHeight()>a(window).height()+a(window).scrollTop()?d.offset().top-f.outerHeight()+parseInt(f.css("marginTop").replace("px",""),10):d.offset().top+d.outerHeight()+parseInt(f.css("marginTop").replace("px",""),10),f.offset(j);var k=f.find(".ui-timepicker-selected");if(k.length||(l(d)?k=i(d,f,s(l(d))):e.scrollDefaultNow?k=i(d,f,s(new Date)):e.scrollDefaultTime!==!1&&(k=i(d,f,s(e.scrollDefaultTime)))),k&&k.length){var m=f.scrollTop()+k.position().top-k.outerHeight();f.scrollTop(m)}else f.scrollTop(0);return a(document).on("touchstart.ui-timepicker mousedown.ui-timepicker",g),e.closeOnWindowScroll&&a(document).on("scroll.ui-timepicker",g),d.trigger("showTimepicker"),this}},hide:function(){var b=a(this),c=b.data("timepicker-settings");return c&&c.useSelect&&b.blur(),a(".ui-timepicker-wrapper:visible").each(function(){var b=a(this),c=b.data("timepicker-input"),d=c.data("timepicker-settings");d&&d.selectOnBlur&&p(c),b.hide(),c.trigger("hideTimepicker")}),this},option:function(d,e){var f=this,g=f.data("timepicker-settings"),h=f.data("timepicker-list");if("object"==typeof d)g=a.extend(g,d);else if("string"==typeof d&&"undefined"!=typeof e)g[d]=e;else if("string"==typeof d)return g[d];return g=b(g),f.data("timepicker-settings",g),h&&(h.remove(),f.data("timepicker-list",!1)),g.useSelect&&c(f),this},getSecondsFromMidnight:function(){return s(l(this))},getTime:function(a){var b=this,c=l(b);if(!c)return null;a||(a=new Date);var d=s(c),e=new Date(a);return e.setHours(d/3600),e.setMinutes(d%3600/60),e.setSeconds(d%60),e.setMilliseconds(0),e},setTime:function(a){var b=this,c=r(s(a),b.data("timepicker-settings").timeFormat);return m(b,c),b.data("timepicker-list")&&j(b,b.data("timepicker-list")),this},remove:function(){var a=this;if(a.hasClass("ui-timepicker-input")){var b=a.data("timepicker-settings");return a.removeAttr("autocomplete","off"),a.removeClass("ui-timepicker-input"),a.removeData("timepicker-settings"),a.off(".timepicker"),a.data("timepicker-list")&&a.data("timepicker-list").remove(),b.useSelect&&a.show(),a.removeData("timepicker-list"),this}}};a.fn.timepicker=function(b){return this.length?y[b]?this.hasClass("ui-timepicker-input")?y[b].apply(this,Array.prototype.slice.call(arguments,1)):this:"object"!=typeof b&&b?(a.error("Method "+b+" does not exist on jQuery.timepicker"),void 0):y.init.apply(this,arguments):this}});

 // includes/plugin_job/js/tasks.js

ucm.job = {

    ajax_task_url: '',
    create_invoice_popup_url: '',
    create_invoice_url: '',

    // init called from the job edit page
    init: function(){
        var t = this;
        $('body').delegate('.task_percentage_toggle','click',function(){
            var task_id = $(this).data('task-id');
            $.ajax({
                url: t.ajax_task_url,
                data: {
                    task_id:task_id,
                    toggle_completed: true
                },
                type: 'POST',
                dataType: 'json',
                success: function(r){
                    if(typeof r.message != 'undefined'){
                        ucm.add_message(r.message);
                        ucm.display_messages(true);
                    }
                    refresh_task_preview(task_id);
                }
            });
        }).delegate('.task_completed_checkbox','change',function(){
            $(this).parent().find('.task_email_auto_option').show();
        });
        $('#job_generate_invoice_button').click(function(){
            t.generate_invoice($(this).text());
            return false;
        });
        t.update_job_tax();
    },
    toggle_task_complete: function(task_id){

    },
    generate_invoice_done: false,
    generate_invoice: function(title){
        var t = this;

        $('#create_invoice_options_inner').load(t.create_invoice_popup_url,function(){
            $('#create_invoice_options').dialog({
                autoOpen: true,
                height: 560,
                width: 350,
                modal: true,
                title: title,
                buttons: {
                    Create: function() {
                        var url = t.create_invoice_url;
                        var items = $('.invoice_create_task:checked');
                        if(items.length>0){
                            items.each(function(){
                                url += '&task_id[]=' + $(this).data('taskid');
                            });
                            window.location.href=url;
                        }else{
                            $(this).dialog('close');
                        }
                    }
                }
            });
        });
    },

    update_job_tax: function(){
        if($('#job_tax_holder .dynamic_block').length > 1)$('.job_tax_increment').show(); else $('.job_tax_increment').hide();
    }
};



 // includes/plugin_invoice/js/invoice.js

ucm.invoice = {
    init: function(){
         this.update_invoice_tax();
        var c = function(e){
            var chk = $(e.target);
            if(chk.hasClass('payment_method_online')){
                $('.payment_type_online').show();
                $('.payment_type_offline').hide();
            }else{
                $('#payment_type_offline_info').html($('#text_'+chk.attr('id')).val());
                $('.payment_type_offline').show();
                $('.payment_type_online').hide();
            }
        };
        c({target:$('.payment_method:checked')[0]});
        $('.payment_method').change(c).mouseup(c);
    },
    update_invoice_tax: function(){
        if($('#invoice_tax_holder .dynamic_block').length > 1)$('.invoice_tax_increment').show(); else $('.invoice_tax_increment').hide();
    }
};

 // includes/plugin_help/js/help.js

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

 // includes/plugin_form/js/form.js

ucm = ucm || {};
ucm.form = {
    dynamic: function(object_id){

        var $object = $("#"+object_id);

        function set_add_remove_buttons(){
            $object.find('.remove_addit').show();
            $object.find('.add_addit').hide();
            $object.find('.add_addit:last').show();
            $object.find('.dynamic_block:only-child > .remove_addit').hide();
        }

        function selrem(e){
            e.preventDefault();
            var clickety = this;
            $(clickety).parents('.dynamic_block').remove();
            set_add_remove_buttons();
            return false;
        }
        function seladd(e){
            e.preventDefault();
            var clickety = this;
            //var box = $('#'+id+' .dynamic_block:last').clone(true);
            var x=0,old_names=[];
            // these pointless looking loops are because IE doesn't handle
            // cloning the name="" part of dynamic input boxes very well... ?
            $('input',$(clickety).parents('.dynamic_block')).each(function(){
                old_names[x++] = $(this).attr('name');
            });
            $('select',$(clickety).parents('.dynamic_block')).each(function(){
                old_names[x++] = $(this).attr('name');
            });
            var box = $(clickety).parents('.dynamic_block').clone(true); // todo - figure out if we need "true"
            x = 0;
            $('input',box).each(function(){
                if(typeof old_names[x] == 'string'){
                    $(this).attr('name', old_names[x]);
                }
                x++;
            });
            $('select',box).each(function(){
                if(typeof old_names[x] == 'string'){
                    $(this).attr('name', old_names[x]);
                }
                x++;
            });
            $('input,select',box).each(function(){
                $(this).val('');
                if($(this).hasClass('date_field')) {
                    $(this).removeClass('hasDatepicker');
                    $(this).datepicker('destroy');
                    // unique id for this date field/
                    $(this).attr('id', 'input_' + Math.floor(Math.random() * 1000));
                }

            });
            $('.dynamic_clear:input',box).val('');
            $('.dynamic_clear',box).html('');
            //$(clickety).after(box);
            $object.find('.dynamic_block:last').after( box);
            set_add_remove_buttons();
            load_calendars();
            return false;
        }

        $object.on('click','.add_addit',seladd);
        $object.on('click','.remove_addit',selrem);
        set_add_remove_buttons();

        return {

        };
    },

    dynamic_select_box: function(element){
        if($(element).val()=='create_new_item'){
            var current_val = $(element).val();
            if(current_val=='create_new_item')current_val = '';
            var id = $(element).attr('id');
            if(typeof id == 'object')id = $(element).prop('id');
            var name = $(element).attr('name');
            if(typeof name == 'object')name = $(element).prop('name');
            var html = '<input type="text" name="'+name+'" id="'+id+'" value="'+current_val+'">';
            // add a new input box.
            $(element).after('<span id="dynamic_select_box_placeholder"></span>');
            $(element).remove();
            var box = $(html);
            $('#dynamic_select_box_placeholder').after(box).remove();
            box[0].focus();
            box[0].select();
        }
    }

};

// backwards compat:
function dynamic_select_box(element){
    ucm.form.dynamic_select_box(element);
}
function seladd(){
    console.log('deprecated call to seladd()');
}
function selrem(){
    console.log('deprecated call to selrem()');
}
function set_add_del(object_id){
    console.log('deprecated call to set_add_del() - use ucm.form.dynamic() instead');
    new ucm.form.dynamic(object_id);
}

 // includes/plugin_backup/js/backup.js

ucm.backup = {
    file_list: [],
    database_list: [],
    backup_url: '',
    backup_post_data: {},
    backup_delay: 1000,
    lang:{
        pending:'Pending',
        process:'Processing',
        success:'Successfully backed up %s items',
        error:'Error',
        retry:'Retrying...'
    },
    init: function(){
        var db = $('#database_backup');
        for(var i in ucm.backup.database_list){
            if(ucm.backup.database_list.hasOwnProperty(i) && typeof ucm.backup.database_list[i].name != 'undefined'){
                ucm.backup.database_list[i].html = $('<li><span class="database_table">'+ucm.backup.database_list[i].name+'</span> <span class="backup_status">'+ucm.backup.lang.pending+'</span></li>');
                db.append(ucm.backup.database_list[i].html);
            }
        }
        var fs = $('#files_backup');
        for(var i in ucm.backup.file_list){
            if(ucm.backup.file_list.hasOwnProperty(i) && typeof ucm.backup.file_list[i].name != 'undefined'){
                ucm.backup.file_list[i].html = $('<li><span class="file_name">'+ucm.backup.file_list[i].name+'</span> <span class="backup_status">'+ucm.backup.lang.pending+'</span></li>');
                fs.append(ucm.backup.file_list[i].html);
            }
        }
    },
    start_backup: function(){
        this.backup_next_database();
        //this.backup_next_file();
    },
    backup_database_index: 0,
    backup_next_database: function(){
        if(typeof ucm.backup.database_list[ucm.backup.backup_database_index] != 'undefined'){
            $('.backup_status',ucm.backup.database_list[ucm.backup.backup_database_index].html).html(ucm.backup.lang.process);
            // ajax this one and wait for it to finish.
            var post_data = {};
            for(var i in ucm.backup.backup_post_data){
                if(ucm.backup.backup_post_data.hasOwnProperty(i)){
                    post_data[i] = ucm.backup.backup_post_data[i];
                }
            }
            post_data.backup_type = 'database';
            post_data.name = ucm.backup.database_list[ucm.backup.backup_database_index].name;
            $.ajax({
                url: ucm.backup.backup_url + (ucm.backup.backup_url.indexOf('?') > 0 ? '&' : '?' ) + (new Date).getTime(),
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success: function(d){
                    // did it work? update the status..
                    if(typeof d.retry != 'undefined'){
                        $('.backup_status',ucm.backup.database_list[ucm.backup.backup_database_index].html).html(ucm.backup.lang.retry);
                        setTimeout(function(){
                            ucm.backup.backup_next_database();
                        },5000);
                        return;
                    }else if(typeof d.count != 'undefined'){
                        $('.backup_status',ucm.backup.database_list[ucm.backup.backup_database_index].html).html(ucm.backup.lang.success.replace('%s', d.count)).addClass('success');
                    }else{
                        $('.backup_status',ucm.backup.database_list[ucm.backup.backup_database_index].html).html(ucm.backup.lang.error).addClass('error');
                    }
                    ucm.backup.backup_database_index++;
                    setTimeout(function(){
                            ucm.backup.backup_next_database();
                        },ucm.backup.backup_delay);
                    //ucm.backup.backup_next_database();
                },
                error: function(d){
                    alert('Failed to backup this database table ('+ucm.backup.database_list[ucm.backup.backup_database_index].name+'). Maybe it is too large? Skipping to next table...');
                    $('.backup_status',ucm.backup.database_list[ucm.backup.backup_database_index].html).html(ucm.backup.lang.error).addClass('error');
                    ucm.backup.backup_database_index++;
                    ucm.backup.backup_next_database();
                }
            });

        }else{
            // finished backing up all available databases.
            ucm.backup.completed_backup('database');
        }
    },
    backup_file_index: 0,
    backup_next_file: function(){
        if(typeof ucm.backup.file_list[ucm.backup.backup_file_index] != 'undefined'){
            $('.backup_status',ucm.backup.file_list[ucm.backup.backup_file_index].html).html(ucm.backup.lang.process);
            // ajax this one and wait for it to finish.
            var post_data = {};
            for(var i in ucm.backup.backup_post_data){
                if(ucm.backup.backup_post_data.hasOwnProperty(i)){
                    post_data[i] = ucm.backup.backup_post_data[i];
                }
            }
            post_data.backup_type = 'file';
            post_data.path = ucm.backup.file_list[ucm.backup.backup_file_index].path;
            post_data.recurisive = ucm.backup.file_list[ucm.backup.backup_file_index].recurisive;
            $.ajax({
                url: ucm.backup.backup_url + (ucm.backup.backup_url.indexOf('?') > 0 ? '&' : '?' ) + (new Date).getTime(),
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success: function(d){
                    // did it work? update the status..
                    if(typeof d.retry != 'undefined'){
                        $('.backup_status',ucm.backup.file_list[ucm.backup.backup_file_index].html).html(ucm.backup.lang.retry);
                        setTimeout(function(){
                            ucm.backup.backup_next_file();
                        },5000);
                        return;
                    }else if(typeof d.count != 'undefined'){
                        $('.backup_status',ucm.backup.file_list[ucm.backup.backup_file_index].html).html(ucm.backup.lang.success.replace('%s', d.count)).addClass('success');
                    }else{
                        $('.backup_status',ucm.backup.file_list[ucm.backup.backup_file_index].html).html(ucm.backup.lang.error).addClass('error');
                    }
                    ucm.backup.backup_file_index++;
                    setTimeout(function(){
                            ucm.backup.backup_next_file();
                        },ucm.backup.backup_delay);
                },
                error: function(d){
                    alert('Failed to backup this folder ('+ucm.backup.file_list[ucm.backup.backup_file_index].path+'). Maybe it is too large? Skipping to next folder...');
                    $('.backup_status',ucm.backup.file_list[ucm.backup.backup_file_index].html).html(ucm.backup.lang.error).addClass('error');
                    ucm.backup.backup_file_index++;
                    ucm.backup.backup_next_file();
                }
            });
        }else{
            // finished backing up all available databases.
            ucm.backup.completed_backup('file');
        }
    },
    completed_backup_count:0,
    completed_backup: function(type){
        this.completed_backup_count++;
        if(this.completed_backup_count == 1){
            // start the file backup process..
            this.backup_next_file();
        }
        if(this.completed_backup_count>=2){
            // finished both files and database backup. refresh the page.
            window.location.href = window.location.href + '&completed';
        }
    }
};
