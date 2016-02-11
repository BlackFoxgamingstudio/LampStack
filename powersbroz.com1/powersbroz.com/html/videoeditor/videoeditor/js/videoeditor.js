
/**
 * VideoEditor
 * 
 * @version 1.5
 * @author Andchir <andchir@gmail.com>
 */

var videoEditor = {
    
    video: {
        path: '',
        type: 'input',
        name: '',
        time: '',
        duration: 0,
        frames: 0,
        fps: 24,
        segments: []
    },
    
    options: {
        lang: 'en',//language
        player_type: 'flowplayer',//flowplayer | videojs | codoplayer
        preloader_url: 'img/preloader.gif',
        delete_source: false
    },
    
    handlerActive: 0,
    timer: null,
    join_active: false,
    join_arr: [],
    player: null,
    
    /* init */
    init: function(){
        
        $("#time-range").slider({
            range: true,
            min: 0,
            max: 100,
            values: [0,100],
            step: 0.01,
            stop: function( event, ui ) {
                
                var index = $(ui.handle).prevAll('.ui-slider-handle').size();
                var time = videoEditor.secondsToTime( ui.value / videoEditor.video.fps );
                
                var is_mp3 = videoEditor.video.name.indexOf('.mp3') == videoEditor.video.name.length -4;
                
                //Create audio markers
                if( is_mp3 ){
                    videoEditor.makeAudioMarkers();
                }
                
                videoEditor.getFrame( time, index );
                videoEditor.handlerActive = index;
                $("#time-range .ui-slider-handle").removeClass('ui-handle-active');
                $(ui.handle).addClass('ui-handle-active');
                videoEditor.stopVideo();
                
            }
        });
        
        //click on input list item
        $(document).on( 'click', '#input-list a', function(e){
            
            e.preventDefault();
            
            if ( videoEditor.join_active ) return;
            
            $( '#input-list a' ).removeClass('active');
            $( this ).addClass('active');
            
            videoEditor.getVideo();
            
        });
        
        $( '#btnStepBackward,#btnStepForward' ).bind('click',videoEditor.videoStep);
        $( '#btnGetSegmet' ).bind('click',videoEditor.getSegment);
        $( '#btnRemoveSegmet' ).bind('click',videoEditor.removeSegment);
        $( '#btnSubmit' ).bind('click',videoEditor.createVideo);
        $( '#btnRemove' ).bind('click',videoEditor.removeVideo);
        $( '#btnUpload' ).bind('click',videoEditor.uploadVideo);
        $( '#btnPlay' ).bind('click',videoEditor.playVideo);
        $( '#btnJoin' ).bind('click',videoEditor.joinInit);
        $( '#btnLog' ).bind('click',videoEditor.viewLog);
        $('#opt_quality').bind('change',function(){
            var value = $(this).val();
            if ( value == '' || value == 0 ) {
                videoEditor.resetConvertOptions();
            }else{
                $('#opt_size').removeAttr('disabled');
                $('#opt_size').find('option:first').prop('selected','selected');
                $('#opt_format').removeAttr('disabled');
                $('#opt_format').find('option:first').prop('selected','selected');
            }
        });
        $('#opt_size').val('');
        $('#opt_format').val('');
        
        $(document).on( 'click', '#listOutput button.remove', videoEditor.removeVideo );
        $(document).on( 'click', '#listOutput button.play', videoEditor.playVideo );
        $(document).on( 'click', '#listOutput button.add_to_join', videoEditor.addToJoin );
        $(document).on( 'click', '#listOutput button.rename', videoEditor.rename );
        $(document).on( 'click', '#segments div', videoEditor.selectSegment );
        
        //on resize
        $(window).bind( 'resize', function(){
            videoEditor.centerImage( $('#video-preview .left') );
            videoEditor.centerImage( $('#video-preview .right') );
        });
        
        videoEditor.resetConvertOptions();
        
    },
    
    /**
     * Create audio markers
     *
     */
    makeAudioMarkers: function(){
        
        var values = $("#time-range").slider( "values" );
        var opt_max = $("#time-range").slider( "option", "max" );
        var opt_min = $("#time-range").slider( "option", "min" );
        
        if ( $('#video-player .audio-markers').size() == 0 ) {
            $('#video-player').append('<div class="audio-markers"></div>');
        }
        
        var marker_left = ( values[0] / opt_max ) * 100;
        var marker_width = ( ( values[1] - values[0] ) / opt_max ) * 100;
        
        $('#video-player .audio-markers')
        .css({
            left: marker_left + '%',
            width: marker_width + '%'
        });
        
    },
    
    /**
     * resetConvertOptions
     * 
     */
    resetConvertOptions: function(){
        $('#opt_quality').val(0);
        $('#opt_size').prop('disabled','disabled').val('');
        $('#opt_format').prop('disabled','disabled').val('');
    },
    
    /**
     * ajaxRequest
     *
     */
    ajaxRequest: function( post_data, callback ){
        
        $.ajax({
            type: "POST",
            cache: false,
            dataType: 'json',
            url: 'action.php',
            data: post_data,
            success: function(response){
                
                if ( typeof callback == 'function' ) {
                    callback(response);
                }
                
            },error: function(jqXHR, textStatus, errorThrown){
                if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
            }
        });
        
    },
    
    /* centerImage */
    centerImage: function( img_parent ){
        
        var margin_top = ( ( img_parent.height() - $('img',img_parent).height() ) / 2 );
        
        $('img',img_parent).css(
            {
                "margin-top": margin_top + 'px',
                "visibility": "visible"
            }
        );
        
    },
    
    /* timeToSeconds */
    timeToSeconds: function( time ){
        
        var time_arr = time.split(':');
        var dur = 0;
        var t = [ 3600, 60, 1 ];
        
        for( var i in time_arr ){
            dur += ( parseFloat( time_arr[i] ) * t[i] );
        }
        
        return dur;
        
    },
    
    /* secondsToTime */
    secondsToTime: function( in_seconds ){
        
        var time = '';
        in_seconds = parseFloat( in_seconds.toFixed(2) );
        
        var hours   = Math.floor(in_seconds / 3600);
        var minutes = Math.floor((in_seconds - (hours * 3600)) / 60);
        var seconds = in_seconds - (hours * 3600) - (minutes * 60);
        //seconds = Math.floor( seconds );
        seconds = seconds.toFixed(2);
        
        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        var time = hours+':'+minutes+':'+seconds;
        
        return time;
        
    },
    
    /* getUrl */
    getUrl: function(){
        
        var output = window.location.href.replace('index.php','');
        if ( output.indexOf('?') > -1 ) {
            output = output.substr( 0, output.indexOf('?') );
        }
        
        return output;
        
    },
    
    /* updateTimeDuration */
    updateTimeDuration: function(){
        
        var time = videoEditor.secondsToTime( videoEditor.video.duration );
        
        $('.time-line .label:eq(1)').text( time );
        
        $("#time-range").slider( "option", "max", videoEditor.video.frames );
        $("#time-range").slider( "values", [ 0, videoEditor.video.frames ] );
        
    },
    
    /* getVideo */
    getVideo: function(e){
        
        if ( typeof e != 'undefined' ) e.preventDefault;
        
        if ( $( '#input-list a' ).size() > 0 && !videoEditor.join_active ) {
            
            videoEditor.video.segments = [];
            if( !$('#segments').is(':hidden') ) $('#segments').empty().slideUp();
            
            var video_name = $( '#input-list a.active' ).data('value');
            if ( video_name.indexOf('/') > -1 ) {
                video_name = video_name.substr( ( video_name.indexOf('/') + 1 ) );
            }
            videoEditor.video.name = video_name;
            videoEditor.video.type = 'input';
            var is_mp3 = video_name.indexOf('.mp3') == video_name.length -4;
            
            videoEditor.loading( true );
            
            //is mp3
            if ( is_mp3 ) {
                
                $('#video-preview .left').empty();
                $('#video-preview .right').empty();
                
                if ( $('#video-player').size() == 0 ) {
                    $('#video-preview').append( '<div id="video-player"></div>' );
                }
                $('#video-player').show().html( '<img class="audio-img" src="action.php?act=audio_waveform&audio=' + video_name + '" />' );
                
                $('#video-player img')
                .hide()
                .bind('load',function(){
                    $('#video-player').show().find('img').show();
                    videoEditor.loading( false );
                });
                
            }
            
            var post_data = { action: 'get_video', name:  video_name };
            
            $.ajax({
                type: "POST",
                cache: false,
                dataType: 'json',
                url: 'action.php',
                data: post_data,
                success: function(response){
                    
                    var uniqid = (new Date().getTime()).toString(16);
                    
                    if ( response.data.path ) {
                        videoEditor.video.path = response.data.path;
                    }
                    if ( response.data.time ) {
                        videoEditor.video.time = response.data.time;
                        videoEditor.video.duration = videoEditor.timeToSeconds( response.data.time );
                        videoEditor.video.frames = videoEditor.video.duration * videoEditor.video.fps;
                        $('#v_time_in').val( '00:00:00' );
                        $('#v_time_out').val( videoEditor.secondsToTime(videoEditor.video.duration) );
                        videoEditor.updateTimeDuration();
                    }
                    
                    if ( response.data.image_in ) {
                        $('#video-preview .left').html( '<img src="tmp/' + response.data.image_in+'?v='+uniqid+'">' );
                        $('#video-preview .left img')
                        .css("visibility","hidden")
                        .bind('load',function(){
                            videoEditor.centerImage( $('#video-preview .left') );
                        });
                    }
                    if ( response.data.image_out ) {
                        $('#video-preview .right').html( '<img src="tmp/' + response.data.image_out+'?v='+uniqid+'">' );
                        $('#video-preview .right img')
                        .css("visibility","hidden")
                        .bind('load',function(){
                            videoEditor.centerImage( $('#video-preview .right') );
                        });
                    }
                    
                    videoEditor.stopVideo();
                    videoEditor.loading( false );
                    
                },error: function(jqXHR, textStatus, errorThrown){
                    if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
                }
            });
            
        }
        
    },
    
    /* getFrame */
    getFrame: function( time, index ){
        
        if ( videoEditor.video.name.indexOf('.mp3') == videoEditor.video.name.length -4 ) {
            $('#v_time_'+(index == 0 ? 'in' : 'out')).val( time );
            return;
        }
        
        var suffix = index == 0 ? '-in' : '-out';
        
        var post_data = { action: 'get_frame', name:  videoEditor.video.name, time:  time, suffix: suffix };
        
        $.ajax({
            type: "POST",
            cache: false,
            dataType: 'json',
            url: 'action.php',
            data: post_data,
            success: function(response){
                
                if( !!response.data.image ){
                    
                    var img_cont = index == 0 ? $('#video-preview .left') : $('#video-preview .right');
                    var uniqid = (new Date().getTime()).toString(16);
                    
                    img_cont.html( '<img src="tmp/' + response.data.image+'?v='+uniqid+'">' );
                    
                    $('img',img_cont)
                    .css("visibility","hidden")
                    .bind('load',function(){
                        videoEditor.centerImage( img_cont );
                    });
                    
                    $('#v_time_'+(index == 0 ? 'in' : 'out')).val( time );
                    
                }
                
            },error: function(jqXHR, textStatus, errorThrown){
                if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
            }
        });
        
    },
    
    /* videoStep */
    videoStep: function(e){
        
        e.preventDefault();
        
        videoEditor.stopVideo();
        
        var target = !$(e.target).is('button') ? $(e.target).parent() : $(e.target);
        var index = target.parent().prevAll().size();
        var values = $( "#time-range" ).slider( "values" );
        var stepSize = 5;
        var newValue = index == 0 ? values[videoEditor.handlerActive]-stepSize : values[videoEditor.handlerActive]+stepSize;
        var is_mp3 = videoEditor.video.name.indexOf('.mp3') == videoEditor.video.name.length -4;
        
        //Create audio markers
        if( is_mp3 ){
            videoEditor.makeAudioMarkers();
        }
        
        if( newValue >= 0 ){
            $( "#time-range" ).slider( "values", videoEditor.handlerActive, newValue );
            var time = videoEditor.secondsToTime( newValue / videoEditor.video.fps );
            videoEditor.getFrame( time, videoEditor.handlerActive );
        }
        
    },
    
    /* getListInput */
    getListInput: function( callback ){
        
        var post_data = { action: 'get_list', type: 'input' };
        
        $.ajax({
            type: "POST",
            cache: false,
            dataType: 'json',
            url: 'action.php',
            data: post_data,
            success: function(response){
                
                if ( !!response.data && !!response.data.list ) {
                    
                    $('#listInput').empty();
                    
                    for ( var i in response.data.list ) {
                        
                        if ( !response.data.list.hasOwnProperty(i) ) continue;
                        
                        var name = response.data.list[i].name;
                        var ext = name.split('.').pop();
                        var title_str = '';
                        if ( name.length - 4 > 15 ){
                            title_str = ' title="'+name+'"';
                            name = name.substr(0,15) + '...' + ext;
                        }
                        var size = response.data.list[i].size.toString() + ' MB';
                        
                        var row = '<a href="#" class="list-group-item" data-value="' + response.data.list[i].path + response.data.list[i].name + '"' + title_str + '>' + name + ' (' + size + ')</a>';
                        
                        $('#listInput').append(row);
                        
                    }
                    
                    $('#listInput a:first').addClass('active');
                    
                    if ( typeof callback == 'function' ) {
                        callback(response);
                    }
                    
                }
                
            },error: function(jqXHR, textStatus, errorThrown){
                if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
            }
        });
        
    },
    
    /* getListOutput */
    getListOutput: function( callback ){
        
        var post_data = { action: 'get_list', type: 'output' };
        
        $.ajax({
            type: "POST",
            cache: false,
            dataType: 'json',
            url: 'action.php',
            data: post_data,
            success: function(response){
                
                if ( !!response.data && !!response.data.list ) {
                    
                    $('#listOutput').html( '<table class="table table-bordered table-hover"></table>' );
                    
                    if ( response.data.list.length > 0 ) {
                        
                        var disabled = !videoEditor.join_active;
                        
                        for ( var i in response.data.list ) {
                            
                            if ( !response.data.list.hasOwnProperty(i) ) continue;
                            
                            var name = response.data.list[i].name;
                            var path = response.data.list[i].path;
                            var size = response.data.list[i].size.toString() + ' MB';
                            
                            var row = '<tr>';
                            row += '<td><a href="output/' + path + name + '" target="_blank">' + name + '</a></td>';
                            row += '<td>' + size + '</a></td><td>' + response.data.list[i].time + '</a></td>';
                            row += '<td class="text-right">';
                            row += ' <button class="btn btn-default btn-sm play" data-value="' + path + name + '" data-toggle="tooltip" title="' + ve_lang[videoEditor.options.lang]['play'] + '"><span class="glyphicon glyphicon-play"></span></button>';
                            row += ' <button class="btn btn-default btn-sm add_to_join" data-value="' + name + '" ' + ( disabled ? 'disabled="disabled" ' : '' ) + 'data-toggle="tooltip" title="' + ve_lang[videoEditor.options.lang]['add_segment'] + '"><span class="glyphicon glyphicon-save"></span></button>';
                            row += ' <button class="btn btn-default btn-sm rename" data-value="' + name + '" data-toggle="tooltip" title="' + ve_lang[videoEditor.options.lang]['rename'] + '"><span class="glyphicon glyphicon-edit"></span></button>';
                            row += ' <button class="btn btn-default btn-sm remove" data-value="' + name + '" data-toggle="tooltip" title="' + ve_lang[videoEditor.options.lang]['delete'] + '"><span class="glyphicon glyphicon-remove"></span></button>';
                            row += '</td></tr>';
                            
                            $('#listOutput table').append(row);
                            
                        }
                    }
                    
                }
                
                if ( typeof callback == 'function' ) {
                    callback(response);
                }
                
            },error: function(jqXHR, textStatus, errorThrown){
                if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
            }
        });
        
    },
    
    /* getSegment */
    getSegment: function(e){
        
        e.preventDefault();
        
        if ( videoEditor.join_active ) return;
        
        var values = $( "#time-range" ).slider( "values" );
        var persents = [];
        persents[0] = ( values[0] / ( videoEditor.video.duration * videoEditor.video.fps) * 100 ).toFixed(2);
        persents[1] = ( values[1] / ( videoEditor.video.duration * videoEditor.video.fps) * 100 ).toFixed(2);
        
        videoEditor.video.segments.push(values);
        
        $('#segments').slideDown();
        $('#segments').append( '<div style="width:'+(persents[1]-persents[0])+'%;left:'+persents[0]+'%;">' + videoEditor.video.segments.length+'</div>' );
        
    },
    
    /* selectSegment */
    selectSegment: function(e){
        
        e.preventDefault();
        
        if ( !$(e.target).is('.active') ) {
            $('#segments div').removeClass('active');
            $(e.target).addClass('active');
        }else{
            $(e.target).removeClass('active');
        }
        
    },
    
    /* removeSegment */
    removeSegment: function(e){
        
        e.preventDefault();
        
        if( !videoEditor.join_active ) videoEditor.stopVideo();
        
        var segmentElem = $('#segments div.active');
        if ( segmentElem.size() == 0 ) {
            segmentElem = $('#segments div:last');
        }
        
        if ( segmentElem.size() > 0 ) {
            
            var segmentIndex = segmentElem.prevAll().size();
            
            //remove segment
            if ( !videoEditor.join_active ) {
                
                videoEditor.video.segments.splice(segmentIndex,1);
                $('#segments').empty();
                
                for( var i in videoEditor.video.segments ){
                    if ( !videoEditor.video.segments.hasOwnProperty(i) ) continue;
                    
                    var persents = [];
                    persents[0] = ( videoEditor.video.segments[i][0] / ( videoEditor.video.duration * videoEditor.video.fps) * 100 ).toFixed(2);
                    persents[1] = ( videoEditor.video.segments[i][1] / ( videoEditor.video.duration * videoEditor.video.fps) * 100 ).toFixed(2);
                    
                    $('#segments').append( '<div style="width:'+(persents[1]-persents[0])+'%;left:'+persents[0]+'%;">'+(parseInt(i)+1)+'</div>' );
                    
                }
                
            //remove video from join
            }else{
                
                videoEditor.join_arr.splice(segmentIndex,1);
                videoEditor.updatePieces();
                
            }
            
        }
        
        if ( videoEditor.video.segments.length == 0 && !videoEditor.join_active ) {
            $('#segments').slideUp();
        }
    },
    
    /* uploadVideo */
    uploadVideo: function(e){
        
        e.preventDefault();
        
        $('#uploadModal').modal();
        
    },
    
    /* removeVideo */
    removeVideo: function(e){
        
        e.preventDefault();
        
        videoEditor.stopVideo();
        
        var target = !$(e.target).is('button') ? $(e.target).parent() : $(e.target);
        var video_name = !!target.data('value') ? target.data('value') : videoEditor.video.name;
        var video_type = !!target.data('value') ? 'output' : 'input';
        
        $('#modal .modal-dialog').removeClass('modal-lg');
        $('#modal .modal-title').text( ve_lang[videoEditor.options.lang]['confirm'] );
        $('#modal .modal-body').html('<p>'+ve_lang[videoEditor.options.lang]['confirm_del_txt']+'</p>');
        $('#modal .modal-footer button:eq(0)')
        .text( ve_lang[videoEditor.options.lang]['yes'] )
        .show()
        .unbind('click')
        .bind('click',function(){
            
            var post_data = { action: 'remove_video', name: video_name, type: video_type };
            $.ajax({
                type: "POST",
                cache: false,
                dataType: 'json',
                url: 'action.php',
                data: post_data,
                success: function(response){
                    
                    if( video_type == 'input' ) videoEditor.getListInput( videoEditor.getVideo );
                    if( video_type == 'output' ) videoEditor.getListOutput();
                    
                    if ( !!response && !!response.error ) {
                        
                        $('#modal .modal-title').text( ve_lang[videoEditor.options.lang]['error'] );
                        $('#modal .modal-body').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> '+response.msg+'</div>');
                        
                    }else{
                        $('#modal').modal('hide');
                    }
                    
                },error: function(jqXHR, textStatus, errorThrown){
                    if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
                }
            });
            
        });
        
        $('#modal').modal();
        
    },
    
    /* createVideo */
    createVideo: function(e){
        
        if( typeof e != 'undefined' ) e.preventDefault();
        
        $('#createVideoModal').modal('hide');
        
        var times = [];
        var options = {};
        options.quality = $('#opt_quality').val();
        options.size = $('#opt_size').val();
        options.format = $('#opt_format').val();
        var action_name = 'create_video';
        
        //join videos
        if ( videoEditor.join_active ) {
            
            var video_name = [];
            
            for( var i in videoEditor.join_arr ){
                if ( !videoEditor.join_arr.hasOwnProperty(i) ) continue;
                
                var temp = videoEditor.join_arr[i][0].split('/');
                var v_name = temp[1] ? temp[1] : temp[0];
                
                video_name.push( v_name );
                
            }
            
            action_name = 'join_video';
            
        }
        //create video
        else if ( videoEditor.video.segments.length > 0 ){
            
            for( var i in videoEditor.video.segments ){
                if ( !videoEditor.video.segments.hasOwnProperty(i) ) continue;
                
                var time_in = videoEditor.secondsToTime( ( videoEditor.video.segments[i][0] / videoEditor.video.fps ) );
                var time_out = videoEditor.secondsToTime( ( videoEditor.video.segments[i][1] / videoEditor.video.fps ) );
                
                times.push( [ time_in, time_out ] );
                
            }
            
            var video_name = videoEditor.video.name;
            
        }else{
            
            var values = $( "#time-range" ).slider( "values" );
            var opt_max = $("#time-range").slider( "option", "max" );
            
            if ( values[0] != 0 || values[1] != opt_max ) {
                
                var time_in = videoEditor.secondsToTime( values[0] / videoEditor.video.fps );
                var time_out = videoEditor.secondsToTime( values[1] / videoEditor.video.fps );
                
                times.push( [ time_in, time_out ] );
                
            }
            
            var video_name = videoEditor.video.name;
            
        }
        
        videoEditor.showProgressBar();
        
        /*
        //not convertation
        if ( !options.quality || options.quality == 0 ) {
            
            $('.progress-bar','#modal')
            .css({
                width: '100%'
            })
            .find('span')
            .removeClass('sr-only');
            
        }
        */
        
        var post_data = {
            action: action_name,
            name: video_name,
            segments: times,
            quality: options.quality,
            size: options.size,
            format: options.format,
            type: videoEditor.video.type,
            delete_source: videoEditor.options.delete_source ? 1 : 0
        };
        
        var callback_func = function(response){
            
            videoEditor.stopVideo();
            
            if ( response && response.error ) {
                
                $('#modal .modal-title').text( ve_lang[videoEditor.options.lang]['error'] );
                $('#modal .modal-body').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> '+response.msg+'</div>');
                
            }
            
            else if ( !!response && !!response.msg && response.msg == 'in_process' ) {
                
                videoEditor.timer = setTimeout( videoEditor.showProgress, 3000 );
                
            }else{
                
                $('#modal').modal('hide');
                
                videoEditor.getListOutput( function(){
                    
                    $('#listOutput table tr:first').addClass('warning');
                    
                });
                
                videoEditor.onOperationComplete();
                
            }
            
        }
        
        videoEditor.ajaxRequest( post_data, callback_func );
        
        return true;
        
    },
    
    /**
     * onOperationComplete
     *
     */
    onOperationComplete: function(){
        
        videoEditor.video.type = 'input';
        videoEditor.options.delete_source = false;
        
    },
    
    /* playVideo */
    playVideo: function(e){
        
        e.preventDefault();
        
        clearInterval( videoEditor.timer );
        
        var target = !$(e.target).is('button') ? $(e.target).parent() : $(e.target);
        var video_dir = target.data('value') ? 'output' : 'input';
        var video_name = target.data('value') ? target.data('value') : videoEditor.video.path + videoEditor.video.name;
        var video_url = videoEditor.getUrl() + video_dir + '/' + video_name;
        var is_mp3 = video_name.indexOf('.mp3') == video_name.length -4;
        
        if ( !is_mp3 && $('#v_player').size() == 0 ) {
            
            $('#video-player').remove();
            videoEditor.player = null;
            
            var p_html = '<div id="video-player">\
            <button class="btn-close"><span class="glyphicon glyphicon-remove"></span></button>\
            <button class="btn-getposition pos-in" title="' + ve_lang[videoEditor.options.lang]['marker_start'] + '"><b>[</b></button>\
            <button class="btn-getposition pos-out" title="' + ve_lang[videoEditor.options.lang]['marker_end'] + '"><b>]</b></button>\
            <div id="v_player"></div>\
            </div>';
            $('#video-preview').append( p_html );
            $('#video-player button.btn-close').bind( 'click', videoEditor.stopVideo );
            $('#video-player button.btn-getposition').bind( 'click', videoEditor.acceptPositionVideo );
            
        }
        
        if ( is_mp3 ) {
            videoEditor.stopVideo();
        }else{
            videoEditor.stopAudio();
        }
        
        if ( video_dir == 'input' ) {
            $('#video-player .btn-getposition').show();
        }else{
            $('#video-player .btn-getposition').hide();
        }
        
        $('#video-player').show();
        
        if ( videoEditor.join_active && $('#segments div.active').size() > 0 ) {
            
            var segmentElem = $('#segments div.active');
            var segmentIndex = segmentElem.prevAll().size();
            video_name = videoEditor.join_arr[segmentIndex][0];
            video_url = videoEditor.getUrl() + 'output/' + video_name;
            
        }
        
        //make preview video
        else if( video_dir == 'input' ){
            
            if ( $('#segments div.active').size() > 0 ) {
                
                var segmentElem = $('#segments div.active');
                var segmentIndex = segmentElem.prevAll().size();
                var values = videoEditor.video.segments[segmentIndex];
                
            }else{
                
                var values = $( "#time-range" ).slider( "values" );
                
            }
            
            if ( values[0] > 0 || values[1] < videoEditor.video.duration * videoEditor.video.fps ) {
                
                var time_in = videoEditor.secondsToTime( values[0] / videoEditor.video.fps );
                var time_out = videoEditor.secondsToTime( values[1] / videoEditor.video.fps );
                videoEditor.makePreviewVideo( video_name, [ time_in, time_out ] );
                
                return true;
                
            }
            
        }
        
        $('#segments div').removeClass('active');
        videoEditor.makePlayer( video_url );
        
        return true;
        
    },
    
    /* stopAudio */
    stopAudio: function(){
        
        if ( $('#video-preview audio').size() > 0 ) {
            $('#video-player audio').get(0).pause();
            $('#video-preview audio').remove();
        }
        
    },
    
    /* stopVideo */
    stopVideo: function( e, hide ){
        
        if( typeof e != 'undefined' && e != null ) e.preventDefault();
        if( typeof hide == 'undefined' ) var hide = true;
        
        videoEditor.stopAudio();
        
        var current_video_url = '';
        
        //flowplayer
        if ( videoEditor.options.player_type == 'flowplayer' ) {
            
            if ( videoEditor.player != null ) {
                
                current_video_url = videoEditor.player.video.src;
                videoEditor.player.stop();
                videoEditor.player.unload();
                
            }
            
        }
        //videojs
        else if ( videoEditor.options.player_type == 'videojs' ) {
            
            if ( videoEditor.player != null ) {
                current_video_url = !!videoEditor.player.cache_ ? videoEditor.player.cache_.src : '';
                videoEditor.player.pause();
                videoEditor.player.dispose();
            }
            
        }
        //codoplayer
        else if ( videoEditor.options.player_type == 'codoplayer' ) {
            
            if ( videoEditor.player != null ) {
                current_video_url = videoEditor.player.playlist.getCurrentClip().src[0];
                videoEditor.player.pause();
                videoEditor.player.destroy();
            }
            
        }
        
        if( current_video_url && current_video_url.indexOf('_preview.') > -1 ){
            videoEditor.stopPreviewVideo();
        }
        
        videoEditor.player = null;
        clearInterval( videoEditor.timer );
        $('#v_player').empty();
        $('#video-preview .video-preview-inner').show();
        
        var is_mp3 = videoEditor.video.name.indexOf('.mp3') == videoEditor.video.name.length -4;
        
        if( hide && !is_mp3 ){ $('#video-player').hide(); }
        
        videoEditor.centerImage( $('#video-preview .left') );
        videoEditor.centerImage( $('#video-preview .right') );
        
    },
    
    /* acceptPositionVideo */
    acceptPositionVideo: function(e){
        
        if( typeof e != 'undefined' ) e.preventDefault();
        
        if ( !$('#video-player').is(':hidden') ) {
            
            if ( videoEditor.options.player_type == 'flowplayer' ){
                videoEditor.player.pause();
                var currentPos = videoEditor.player.video.time;
            }
            else if( videoEditor.options.player_type == 'videojs' ){
                videoEditor.player.pause();
                var currentPos = videoEditor.player.currentTime();
            }else if( videoEditor.options.player_type == 'codoplayer' ){
                videoEditor.player.pause();
                var currentPos = videoEditor.player.media.getCurrentTime();
            }
            
            var target = $(e.target).is('button') ? $(e.target) : $(e.target).parent('button');
            var values = $( "#time-range" ).slider( "values" );
            var newValue = ( currentPos * videoEditor.video.fps ) + values[0];
            var handleIndex = target.is('.pos-in') ? 0 : 1;
            
            $( "#time-range" ).slider( "values", handleIndex, newValue );
            
            videoEditor.makePreviewVideo();
            videoEditor.getFrame( videoEditor.secondsToTime( newValue / videoEditor.video.fps ), handleIndex );
            
        }
        
    },
    
    /* makePlayer */
    makePlayer: function( video_url ){
        
        var video_ext = video_url.split('.').pop();
        var uniqid = (new Date().getTime()).toString(16);
        video_url += '?v=' + uniqid;
        
        //Create pm3 player
        if ( video_ext == 'mp3' ) {
            
            var player_html = '<audio autoplay controls>\
                <source src="' + video_url + '" type="audio/mpeg">\
            </audio>';
            
            $('#video-player audio').remove();
            $('#video-player').append( player_html );
            
            return;
        }
        
        //flowplayer
        if ( videoEditor.options.player_type == 'flowplayer' ) {
            
            if ( videoEditor.player == null ) {
                
                var player_html = '<div id="video1" class="flowplayer no-hover" data-swf="js/flowplayer/html5/flowplayer.swf">\
                    <video width="100%" height="300" autoplay>\
                        <source type="video/'+video_ext+'" src="'+video_url+'">\
                    </video>\
                </div>';
                
                $('#v_player').html( player_html );
                
                $("#video1").flowplayer({
                    engine: 'html5',
                    adaptiveRatio: false,
                    flashfit: true,
                    fullscreen: false
                });
                
                videoEditor.player = flowplayer($("#video1"));
                
            }
            else{
                
                if ( videoEditor.player.paused ) {
                    videoEditor.player.load( video_url );
                }else{
                    videoEditor.player.pause( function(){
                        videoEditor.player.load( video_url );
                    } );
                }
                
            }
        
        }
        //videojs
        else if ( videoEditor.options.player_type == 'videojs' ) {
            
            if ( videoEditor.player == null ) {
                
                var player_html = '<video id="video1" class="video-js vjs-default-skin vjs-big-play-centered"\
                    controls preload="auto" width="100%" height="300">\
                    <source src="'+video_url+'" type="video/'+video_ext+'" />\
                </video>';
                
                $('#v_player').html( player_html );
                
                videoEditor.player = videojs("video1", {
                    autoplay: true
                });
                
            }else{
                
                videoEditor.player.pause();
                videoEditor.player.src(video_url);
                
            }
            
        }
        //codoplayer
        else if ( videoEditor.options.player_type == 'codoplayer' ) {
            
            if ( videoEditor.player == null ) {
                
                var player_html = '<div id="video1"></div>';
                $('#v_player').html( player_html );
                
                videoEditor.player = CodoPlayer([{
                    src: video_url
                }],
                {
                    //width: 790,
                    height: 300,
                    preload: true,
                    autoplay: true,
                    volume: 80,
                    engine: "auto",
                    playlist: false,
                    controls: {
                        show: 'auto',
                        hideDelay: 3,
                        play: true,
                        playBtn: true,
                        seek: true,
                        volume: 'horizontal',
                        fullscreen: true,
                        title: false,
                        time: true
                    }
                }, '#video1');
                
            }else{
                
                videoEditor.player.pause();
                videoEditor.player.playlist.set( { src: video_url } );
                videoEditor.player.play();
                
            }
            
        }
        
        $('#video-preview .video-preview-inner').hide();
        
        return true;
        
    },
    
    /* showPreloader */
    showPreloader: function( elem ){
        
        videoEditor.stopVideo(null,false);
        
        $(elem).html( '<img src="' + videoEditor.options.preloader_url + '">' );
        $('img',elem)
        .bind('load',function(){
            videoEditor.centerImage( $(elem) );
        });
        
    },
    
    /* loading */
    loading: function( active, container ){
        
        if ( $('#loading').size() == 0 ) {
            $(document.body).append('<div id="loading"></div>');
        }
        
        if ( typeof container == 'undefined' ) {
            var container = '#video-preview';
        }
        
        if ( active ) {
            var pos = $( container ).position();
            var pos_left = Math.round( pos.left + ( $( container ).innerWidth() / 2 ) );
            var pos_top = Math.round( pos.top + ( $( container ).innerHeight() / 2 ) );
            $('#loading').css( { left: pos_left + 'px', top: pos_top + 'px', display: 'block' } );
        }else{
            $('#loading').hide();
        }
        
    },
    
    /* makePrevidewVideo */
    makePreviewVideo: function( video_name, times ){
        
        if ( typeof video_name == 'undefined' ) {
            var video_name = videoEditor.video.name;
        }
        if ( typeof times == 'undefined' ) {
            var values = $( "#time-range" ).slider( "values" );
            var times = [];
            times[0] = videoEditor.secondsToTime( values[0] / videoEditor.video.fps );
            times[1] = videoEditor.secondsToTime( values[1] / videoEditor.video.fps );
        }
        
        videoEditor.loading( true );
        videoEditor.stopVideo(null,false);
        var post_data = { action: 'create_preview', name: video_name, times:  times };
        
        videoEditor.ajaxRequest( post_data, function(response){
                
                if ( !!response.msg && response.msg == 'OK' ) {
                    
                    var video_url = videoEditor.getUrl() + 'tmp/' + response.data.video;
                    
                    videoEditor.makePlayer( video_url );
                    videoEditor.loading( false );
                    
                }
                
            }
        );
        
    },
    
    
    /**
     * stopPreviewVide
     *
     */
    stopPreviewVideo: function(){
        
        this.ajaxRequest( {  action: 'remove_preview' } );
        
    },
    
    
    /* showProgressBar */
    showProgressBar: function(){
        
        var progress_bar = '\
        <div class="progress">\
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0.1" aria-valuemin="0" aria-valuemax="100" style="width:0;">\
                <span class="sr-only">' + ve_lang[videoEditor.options.lang]['process'] + '...</span>\
            </div>\
        </div>\
        ';
        
        var title_text = ve_lang[videoEditor.options.lang]['process'];
        
        $('#modal .modal-dialog').removeClass('modal-lg');
        $('#modal .modal-title').text( title_text );
        $('#modal .modal-footer button:eq(0)').hide().unbind('click');
        $('#modal .modal-body').html(progress_bar);
        $('#modal').modal();
        
        return true;
        
    },
    
    /* showProgress */
    showProgress: function(){
        
        if ( $('#modal').is(':hidden') ) {
            videoEditor.showProgressBar();
        }
        
        var post_data = { action: 'progress' };
        
        $.ajax({
            type: "POST",
            cache: false,
            dataType: 'json',
            url: 'action.php',
            data: post_data,
            success: function(response){
                
                if ( !response.data ) return;
                
                var percent = response.data.percent ? response.data.percent : 0;
                var queue_percent = response.data.queue_percent ? response.data.queue_percent : 0;
                
                //complete
                if ( queue_percent == 100 ) {
                    
                    clearTimeout( videoEditor.timer );
                    
                    $('#modal').modal('hide');
                    
                    videoEditor.getListOutput( function(){
                        
                        $('#listOutput table tr:first').addClass('warning');
                        
                    });
                    
                    videoEditor.onOperationComplete();
                
                //show percent
                }else{
                    
                    var progressbar = $('.progress-bar','#modal');
                    
                    progressbar
                    .css({
                        width: queue_percent + '%'
                    });
                    
                    progressbar
                    .find('span')
                    .removeClass('sr-only')
                    .text( queue_percent + '%' );
                    
                    if ( response.data.queue_size && response.data.queue_size > 0 ) {
                        
                        var title_text = ve_lang[videoEditor.options.lang]['process'];
                        title_text += ' <small>' + ( response.data.queue_size - response.data.queue_left ) + '/' + response.data.queue_size + '</small>'; 
                        
                        $('#modal .modal-title').html( title_text );
                        
                    }
                    
                    videoEditor.timer = setTimeout( videoEditor.showProgress, 2000 );
                    
                }
                
            },error: function(jqXHR, textStatus, errorThrown){
                if(typeof(console)!='undefined') console.log(textStatus+' '+errorThrown);
            }
        });
        
        return true;
        
    },
    
    
    /**
     * joinInit
     *
     */
    joinInit: function( e, start ){
        
        var button = e && typeof e != 'undefined' ? $(e.target) : $('#btnJoin');
        if ( typeof start == 'undefined' ) {
            var start = !button.is('.active');
        }
        
        videoEditor.stopVideo();
        
        if ( start ) {
            
            videoEditor.join_active = true;
            videoEditor.video.segments = [];
            $('#segments').hide();
            
            button.removeClass('btn-info').addClass('active btn-default');
            $('#listOutput .add_to_join').removeAttr('disabled');
            
            $('#time-range').hide();
            $('#segments').empty().css('margin-top',0).show();
            //$('#opt_quality').val('').find('option:first').prop('disabled',true);
            
            if ( $('#video-player').size() == 0 ) {
                
                $('#video-preview').append('<div id="video-player"><div id="v_player"></div></div>');
                
            }else{
                $('#video-player').show().html('<div id="v_player"></div>');
            }
            
        }else{
            
            videoEditor.join_active = false;
            videoEditor.join_arr = [];
            videoEditor.options.delete_source = false;
            
            button.removeClass('active btn-default').addClass('btn-info');
            $('#listOutput .add_to_join').prop('disabled',true);
            
            $('#segments').hide().removeAttr('style').hide();
            $('#time-range').show();
            $('#video-player').remove();
            $('#segments').empty();
            //$('#opt_quality').val(0).find('option:first').removeAttr('disabled');
            videoEditor.getVideo();
            
        }
        
        return false;
        
    },
    
    
    /**
     * addToJoin
     *
     */
    addToJoin: function(e){
        
        var target = !$(e.target).is('button') ? $(e.target).parent() : $(e.target);
        var video_name = target.data('value');
        var post_data = { action: 'get_video', type: 'output', name:  video_name };
        
        var callback_func = function( response ){
            
            if ( !!response.data && !!response.data.time ) {
                
                var duration_time = response.data.time;
                var file_path = response.data.path;
                videoEditor.join_arr.push( [ file_path + video_name, duration_time ] );
                
                if ( $('#segments').is(':hidden') ) {
                    $('#segments').slideDown();
                }
                
                videoEditor.updatePieces();
                
            }
            
        }
        
        videoEditor.ajaxRequest( post_data, callback_func );
        
        
    },
    
    
    /* rename */
    rename: function(e){
        
        var title_text = ve_lang[videoEditor.options.lang]['rename'];
        var target = !$(e.target).is('button') ? $(e.target).parent() : $(e.target);
        var video_name = target.data('value');
        var ext = video_name.split('.').pop();
        var old_name =  video_name.replace( ( '.' + ext ), '' );
        
        var content = '<div class="input-group">\
            <input type="text" class="form-control" name="new_name" value="' + old_name + '">\
            <span class="input-group-addon">.' + ext + '</span>\
        </div>';
        
        $('#modal .modal-dialog').removeClass('modal-lg');
        $('#modal .modal-body').html( content );
        $('#modal .modal-title').text( title_text );
        $('#modal .modal-footer button:eq(0)')
        .text( ve_lang[videoEditor.options.lang]['save'] )
        .show()
        .unbind('click')
        .bind('click',function(){
            
            var post_data = {
                action: 'rename',
                old_name: video_name,
                new_name: $('#modal .modal-body input[name="new_name"]').val()
            };
            
            videoEditor.ajaxRequest( post_data, function( response ){
                
                if ( response && response.error ) {
                    
                    $('#modal .modal-body .alert').remove();
                    $('#modal .modal-body').prepend('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> '+response.msg+'</div>');
                    
                }else{
                    
                    videoEditor.getListOutput();
                    $('#modal').modal('hide');
                    
                }
                
            });
            
        });
        
        $('#modal').modal();
        
    },
    
    
    /* updatePieces */
    updatePieces: function(){
        
        if ( videoEditor.join_active ) {
            
            var total_duration = 0;
            for( var i in videoEditor.join_arr ){
                
                if ( videoEditor.join_arr.hasOwnProperty(i) ) {
                    
                    total_duration += videoEditor.timeToSeconds( videoEditor.join_arr[i][1] );
                    
                }
                
            }
            
            videoEditor.video.time = videoEditor.secondsToTime( total_duration );
            videoEditor.video.duration = total_duration;
            $('.time-line .label:eq(1)').text( videoEditor.video.time );
            
            $('#segments').empty();
            var total_percent = 0;
            
            for( var i in videoEditor.join_arr ){
                
                if ( videoEditor.join_arr.hasOwnProperty(i) ) {
                    
                    var seconds = videoEditor.timeToSeconds( videoEditor.join_arr[i][1] );
                    var persent = ( seconds / videoEditor.video.duration * 100 );
                    if ( persent % 1 !== 0 ) {
                        persent = persent.toFixed(2);
                    }
                    
                    $('#segments').append( '<div style="width:'+persent+'%;left:'+total_percent.toFixed(2)+'%;" title="'+videoEditor.join_arr[i][0]+'">'+videoEditor.join_arr[i][1]+'</div>' );
                    
                    total_percent += parseFloat( persent );
                    
                }
                
            }
            
        }
        
        
    },
    
    /**
     * viewLog
     *
     */
    viewLog: function(){
        
        var title_text = ve_lang[videoEditor.options.lang]['view_log'];
        
        $('#modal .modal-dialog').addClass('modal-lg');
        $('#modal .modal-body').empty();
        $('#modal .modal-title').text( title_text );
        $('#modal .modal-footer button:eq(0)').hide().unbind('click');
        $('#modal').modal();
        
        var post_data = {
            action: 'show_log'
        };
        
        videoEditor.ajaxRequest( post_data, function( response ){
            
            if ( response && response.error ) {
                
                $('#modal .modal-body').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> '+response.msg+'</div>');
                
            }
            
            else if( response.data ){
                
                if ( response.data.output && response.data.event_log ) {
                    
                    var inner_html = '<div role="tabpanel"><ul class="nav nav-tabs" role="tablist">\
                    <li role="presentation" class="active"><a href="#content1" aria-controls="content1" role="tab" data-toggle="tab">' + ve_lang[videoEditor.options.lang]['log_last_operation'] + '</a></li>\
                    <li role="presentation"><a href="#content2" aria-controls="content2" role="tab" data-toggle="tab">' + ve_lang[videoEditor.options.lang]['log_commands'] + '</a></li>\
                    </ul><div class="tab-content">';
                    inner_html += '<div role="tabpanel" class="tab-pane active" id="content1"><pre class="max-height-400">' + response.data.output + '</pre></div>';
                    inner_html += '<div role="tabpanel" class="tab-pane" id="content2"><pre class="max-height-400">' + response.data.event_log + '</pre></div>';
                    inner_html += '</div></div>';
                    
                }else{
                    
                    var inner_html = '<pre class="max-height-400">' + ( response.data.output ? response.data.output : response.data.event_log ) + '</pre>';
                    
                }
                
                $('#modal .modal-body').html( inner_html );
                
            }
            
        });
        
        
    }
    
}


$(document).bind('ready',function(){
    
    videoEditor.init();
    videoEditor.getListInput( videoEditor.getVideo );
    videoEditor.getListOutput();
    
});
