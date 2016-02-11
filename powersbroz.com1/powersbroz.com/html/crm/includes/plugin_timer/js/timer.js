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