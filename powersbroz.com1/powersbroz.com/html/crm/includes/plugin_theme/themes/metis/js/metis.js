ucm.metis = {
    init: function(){

        // do the action button duplication.
        $('.action_bar_duplicate').each(function(){
            if(!$(this).hasClass('action_bar_single')){
                $(this).clone(true).addClass('hidden-xs').prependTo($(this).parents('form').first());
            }
        });
        // current selected icon to header area.
        if($('.head .main-bar h3 .fa').length == 0){
            $('#menu li.active i.fa').each(function(){
                $(this).clone(true).addClass('cloned').prependTo('.head .main-bar h3');
            });
        }
        $('.submit_button').each(function(){
            if(!$(this).hasClass('btn')){
                $(this).addClass('btn');
            }
        });

        // this also exists in the adminlte js file. update both if changing here:
        $('.responsive-toggle-button,.box-responsive header').click(function(e){
            if(typeof e.target.parentElement != 'undefined' && e.target.parentElement.tagName == 'A' && $(e.target.parentElement).attr('href') != '#'){
                e.stopPropagation();
                return true;
            }
            if(typeof(e.target.tagName) != 'undefined' && e.target.tagName == 'A' && $(e.target).attr('href') != '#'){
                e.stopPropagation();
                return true;
            }
            var p = $(this).parents('.box-responsive').first();
            if($(p).hasClass('responsive-toggled')){
                $(p).removeClass('responsive-toggled');
            }else{
                $(p).addClass('responsive-toggled');
            }
            return false;
        });

    }
};