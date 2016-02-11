/**
* Character Counter v1.0
* ======================
*
* Character Counter is a simple, Twitter style character counter.
* 
* https://github.com/dtisgodsson/jquery-character-counter
*
* @author Darren Taylor
* @author Email: shout@darrenonthe.net
* @author Twitter: darrentaytay
* @author Website: http://darrenonthe.net
*
*/
(function($) {

    $.fn.characterCounter = function(options){
      
        var defaults = {
            exceeded: false,
            limit: 140,
            counterWrapper: 'span',
            counterCssClass: 'counter-block help-block',
            counterFormat: '%1',
            counterExceededCssClass: 'exceeded',
            onExceed: function(count) {},
            onDeceed: function(count) {},
            customFields: {}
        }; 
            
        var options = $.extend(defaults, options);

        return this.each(function() {
            $(this).next('.counter-block').remove();
            $(this).after(generateCounter());
            bindEvents(this);
            checkCount(this);
        });
        
        function customFields(params)
        {
            var html='';

            for (var i in params)
            {
                html += ' ' + i + '="' + params[i] + '"';
            }

            return html;
        }

        function generateCounter()
        {
            var classString = options.counterCssClass;

            if(options.customFields.class)
            {
                classString += " " + options.customFields.class;
                delete options.customFields['class'];
            }
            
            return '<'+ options.counterWrapper +customFields(options.customFields)+' class="' + classString + '"></'+ options.counterWrapper +'>';
        }

        function renderText(count)
        {
            return options.counterFormat.replace(/%1/, count);
        }

        function checkCount(element)
        {
            var characterCount  = $(element).val().length;
            var remaining        = options.limit - characterCount;

            if( remaining < 0 )
            {
                $(element).next(".counter-block").addClass(options.counterExceededCssClass);
                options.exceeded = true;
                options.onExceed(characterCount);
            }
            else
            {
                if(options.exceeded) {
                    $(element).next(".counter-block").removeClass(options.counterExceededCssClass);
                    options.onDeceed(characterCount);
                    options.exceeded = false;
                }
            }

            $(element).next(".counter-block").html(renderText(remaining));
        };    

        function bindEvents(element)
        {
            $(element)
                .bind("keyup", function () { 
                    checkCount(element); 
                })
                .bind("paste", function () { 
                    var self = this;
                    setTimeout(function () { checkCount(self); }, 0);
                });
        }
    };

})(jQuery);
