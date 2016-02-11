<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */


/**
 * FORM CLASS
 * Version: 2.21
 */
class module_form extends module_base{

    static $form_options = array();
    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
    public $version = 2.254;
    // 2.254 - 2016-01-04 - multi select date form fix
    // 2.253 - 2016-01-03 - multi select date form fix
    // 2.252 - 2015-03-08 - correct time format config
    // 2.251 - 2015-03-08 - wysiwyg as extra fields
    // 2.25 - 2015-03-08 - wysiwyg as extra fields
    // 2.249 - 2015-02-27 - new tinymce version
    // 2.248 - 2015-01-21 - added number form field
    // 2.247 - 2014-11-26 - improved form framework
    // 2.246 - 2014-09-02 - delete fixes
    // 2.245 - 2014-08-02 - responsive fixes

    // 2.21 - added optional attributes to form elemetns.
    // 2.22 - added id attribute and support for cryptography.
    // 2.221 - no default fields in mobile
    // 2.222 - fix for delete members (passing arrays in post data)
    // 2.223 - better select box form element generation
    // 2.224 - currency support in form settings
    // 2.225 - securing forms
    // 2.226 - 2013-07-30 - customer delete improvement
    // 2.227 - 2013-08-11 - starting work on search form UI improvements
    // 2.228 - 2013-08-27 - more work on form UI improvements
    // 2.229 - 2013-08-28 - more work on form UI improvements for upcoming themes
    // 2.23 - 2013-09-07 - bulk delete fix
    // 2.231 - 2013-09-29 - search bar improvement
    // 2.232 - 2013-11-11 - starting on new UI
    // 2.233 - 2013-11-15 - working on new UI
    // 2.234 - 2013-12-01 - wysiwyg form element improvement
    // 2.235 - 2013-12-06 - upgrade to tinymce 4
    // 2.236 - 2013-12-08 - upgrade to tinymce 4
    // 2.237 - 2014-01-18 - currency fix
    // 2.238 - 2014-01-21 - tinymce update
    // 2.239 - 2014-01-23 - searching by extra fields
    // 2.24 - 2014-02-07 - tinymce browser spellchecker
    // 2.241 - 2014-02-14 - multiple options fix
    // 2.242 - 2014-03-17 - time picker support
    // 2.243 - 2014-03-29 - form auth added to js
    // 2.244 - 2014-07-05 - better translations




	public static function get_class() {
        return __CLASS__;
    }
    public function init(){
		$this->module_name = "form";
		$this->module_position = 0;

        module_config::register_css('form','jquery.timepicker.css');
        module_config::register_js('form','jquery.timepicker.min.js');
        module_config::register_js('form','form.js');
	    hook_add('header_print_js','module_form::hook_header_print_js');
    }
    public static function init_form(){
        static $init_complete = false;
        if($init_complete)return;
        // we load any form settings from the session into
        // our local static variable so we can process things like required fields.
        if(isset($_SESSION['_plugin_form'])){
            self::$form_options = $_SESSION['_plugin_form'];
        }else{
            $_SESSION['_plugin_form'] = array();
        }
        $init_complete = true;
    }
    public static function save($form_name,$form){
        self::$form_options[$form_name] = $form;
        $_SESSION['_plugin_form'] = self::$form_options; // save for later.
    }
    public static function clear($form_name){
        if(isset(self::$form_options[$form_name])){
            unset(self::$form_options[$form_name]);
        }
        $_SESSION['_plugin_form'] = self::$form_options; // save for later.
    }

    private static function _get_error_msg($error_fields=array()){
        ob_start();
        ?>
        <div class="ui-widget">
                <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
                    <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                    <strong>Alert:</strong> Required fields missing.</p>
                    <?php if($error_fields){ ?>
                    <ul>
                        <?php foreach($error_fields as $field=>$value){ ?>
                        <li><?php echo htmlspecialchars($value);?></li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
        <?php
        return preg_replace('/\s+/',' ',ob_get_clean());
    }
    private static function _serialize_post(&$data,$post_data,$prefix='') {
		foreach($post_data as $key=>$val){
			if(preg_match('/^form_saver_/',$key)){
				continue;
			}else if(is_array($val)){
				self::_serialize_post($data,$val,$key);
			}else{
				// normal string, just add it to the array.
				$data[$prefix.(strlen($prefix)?'[':'').$key.(strlen($prefix)?']':'')] = $val;
			}
		}
	}
    public static function set_required($options){
        self::init_form();
        $form_name = (isset($options['form_name'])) ? $options['form_name'] : md5($_SERVER['REQUEST_URI']);
        $required_fields = (isset($options['fields']) && is_array($options['fields'])) ? $options['fields'] : array();
        $required_email_fields = (isset($options['emails']) && is_array($options['emails'])) ? $options['emails'] : array();
        $form = isset(self::$form_options[$form_name]) ? self::$form_options[$form_name] : array();
        $form['required_fields'] = $required_fields;
        $form['return_url'] = $_SERVER['REQUEST_URI']; // wont work for post data.
        $error_fields = isset($form['error_fields']) ? $form['error_fields'] : array();
        $data_to_load = isset($form['data_to_load']) ? $form['data_to_load'] : array();
        ?>
        <span id="plugin_form_header_<?php echo htmlspecialchars($form_name);?>">
            <?php
            if(isset($form['show_error']) && $form['show_error']){
                echo self::_get_error_msg($error_fields);
            } ?>
        </span>
        <input type="hidden" name="_plugin_form_name" id="_plugin_form_<?php echo htmlspecialchars($form_name);?>" value="<?php echo htmlspecialchars($form_name);?>">
        <?php
        // now some javascript to apply 'required' fields to all specified
        ?>
        <script type="text/javascript">
            <?php if($data_to_load){ ?>
            if(typeof Base64 == 'undefined'){
                var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(a){var b="";var c,chr2,chr3,enc1,enc2,enc3,enc4;var i=0;a=Base64._utf8_encode(a);while(i<a.length){c=a.charCodeAt(i++);chr2=a.charCodeAt(i++);chr3=a.charCodeAt(i++);enc1=c>>2;enc2=((c&3)<<4)|(chr2>>4);enc3=((chr2&15)<<2)|(chr3>>6);enc4=chr3&63;if(isNaN(chr2)){enc3=enc4=64}else if(isNaN(chr3)){enc4=64}b=b+this._keyStr.charAt(enc1)+this._keyStr.charAt(enc2)+this._keyStr.charAt(enc3)+this._keyStr.charAt(enc4)}return b},decode:function(a){var b="";var c,chr2,chr3;var d,enc2,enc3,enc4;var i=0;a=a.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(i<a.length){d=this._keyStr.indexOf(a.charAt(i++));enc2=this._keyStr.indexOf(a.charAt(i++));enc3=this._keyStr.indexOf(a.charAt(i++));enc4=this._keyStr.indexOf(a.charAt(i++));c=(d<<2)|(enc2>>4);chr2=((enc2&15)<<4)|(enc3>>2);chr3=((enc3&3)<<6)|enc4;b=b+String.fromCharCode(c);if(enc3!=64){b=b+String.fromCharCode(chr2)}if(enc4!=64){b=b+String.fromCharCode(chr3)}}b=Base64._utf8_decode(b);return b},_utf8_encode:function(a){a=a.replace(/\r\n/g,"\n");var b="";for(var n=0;n<a.length;n++){var c=a.charCodeAt(n);if(c<128){b+=String.fromCharCode(c)}else if((c>127)&&(c<2048)){b+=String.fromCharCode((c>>6)|192);b+=String.fromCharCode((c&63)|128)}else{b+=String.fromCharCode((c>>12)|224);b+=String.fromCharCode(((c>>6)&63)|128);b+=String.fromCharCode((c&63)|128)}}return b},_utf8_decode:function(a){var b="";var i=0;var c=c1=c2=0;while(i<a.length){c=a.charCodeAt(i);if(c<128){b+=String.fromCharCode(c);i++}else if((c>191)&&(c<224)){c2=a.charCodeAt(i+1);b+=String.fromCharCode(((c&31)<<6)|(c2&63));i+=2}else{c2=a.charCodeAt(i+1);c3=a.charCodeAt(i+2);b+=String.fromCharCode(((c&15)<<12)|((c2&63)<<6)|(c3&63));i+=3}}return b}}
            }
            <?php } ?>

            $(function(){
                var plugin_form_clicked = false;
                var plugin_form_fields = <?php
                    echo json_encode($required_fields);
                ?>;
                var plugin_email_fields = <?php
                    echo json_encode($required_email_fields);
                ?>;
                var plugin_error_fields = <?php
                    echo json_encode($error_fields);
                ?>;
                <?php
                // code copied from my "save form for later" program
                if($data_to_load){ ?>
                    var form_saver_permitted_input_types = {};
                    <?php foreach(explode(',','textarea,text,radio,hidden,checkbox,password,select,select-one') as $type){
                        $type = trim($type);
                        if(!$type)continue;
                        ?>
                        form_saver_permitted_input_types['<?php echo $type;?>'] = 'yes';
                    <?php } ?>
                    // all saved data, base64encoded for basic js syntax error safety.
                    var form_saver_data = {};
                    <?php foreach($data_to_load as $key=>$val){ ?>
                        form_saver_data['<?php echo base64_encode($key);?>'] = '<?php echo base64_encode($val);?>';
                    <?php } ?>
                <?php } ?>

                var plugin_form_frm = $('#_plugin_form_<?php echo htmlspecialchars($form_name);?>').parents('form');
                if(typeof plugin_form_frm == 'undefined' || !plugin_form_frm){
                    alert('Form Plugin initialisation failed. Contact developer.');
                    return;
                }
                $(':submit',plugin_form_frm).mousedown(function() {
                    plugin_form_clicked = this;
                });
                // loop through all applicable input options and apply required javascript/class
                $('input,textarea,select',plugin_form_frm).each(function(){
                    var n = $(this).attr('name');
                    var attr_type = (jQuery(this).attr('type')+'').toLowerCase();
                    if(attr_type == 'hidden')return;
                    if(typeof plugin_form_fields[n] != 'undefined'){
                        $(this).addClass('plugin_form_required');
                        if(typeof ucm.form != 'undefined' && typeof ucm.form.set_required == 'function' ){
                            ucm.form.set_required(this);
                        }else{
                            $(this).after(' <span class="required">*</span>');
                        }
                    }
                    if(typeof plugin_email_fields[n] != 'undefined'){
                        jQuery(this).addClass('plugin_form_required_email');
                    }
                    if(typeof plugin_error_fields[n] != 'undefined'){
                        $(this).addClass('ui-state-error');
                    }
                    <?php if($data_to_load){ ?>
                    if(typeof form_saver_data[Base64.encode(jQuery(this).attr('name'))] != 'undefined'){
                        // see if this is in one of the permitted form types.
                        if(typeof form_saver_permitted_input_types[attr_type] == 'undefined' || form_saver_permitted_input_types[attr_type] != 'yes'){
                            return; // skip this input. not allowed.
                        }
                        var attr_value = Base64.decode(form_saver_data[Base64.encode(jQuery(this).attr('name'))]);
                        if(jQuery(this)[0].disabled){
                            // don't update disabled elements.
                        }else if(attr_type == 'radio' || attr_type == 'checkbox'){
                            if(jQuery(this).val() == attr_value){
                                jQuery(this)[0].checked=true;
                            }
                        }else{
                            // it's a normal input box that we can update it's value.
                            jQuery(this).val(attr_value);
                        }
                    }
                    <?php } ?>
                });
                $(plugin_form_frm).submit(function(){
                    // check required fields on submit
                    if(plugin_form_clicked && ($(plugin_form_clicked).attr('name').match(/cancel/i) || $(plugin_form_clicked).attr('name').match(/butt_del/i))){
                        $('#_plugin_form_<?php echo htmlspecialchars($form_name);?>').after('<input type="hidden" name="_plugin_form_cancel" value="true">');
                        return true;
                    }
                    var plugin_form_error = false;
                    $('.plugin_form_required',this).each(function(){
                        if(!jQuery(this)[0].disabled && (jQuery(this).hasClass('plugin_form_required_email'))){
                            var reg = /^([A-Za-z0-9_\-\.\+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                           var address = jQuery(this).val();
                           if(!reg.test(address)) {
                              jQuery(this).addClass('ui-state-error');
                                if(!plugin_form_error){
                                    // focus first element.
                                    jQuery(this)[0].focus();
                                }
                                plugin_form_error = true;
                                var chge = function(){
                                    if(reg.test(jQuery(this).val())){
                                        jQuery(this).removeClass('ui-state-error');
                                    }else{
                                        jQuery(this).addClass('ui-state-error');
                                    }
                                };
                                jQuery(this).keyup(chge).change(chge);
                           }
                        }
                        if(!$(this)[0].disabled && ($(this).val() == '' || !$(this).val())){
                            $(this).addClass('ui-state-error');
                            if(!plugin_form_error){
                                // focus first element.
                                $(this)[0].focus();
                            }
                            plugin_form_error = true;
                            var chg = function(){
                                if($(this).val() != '' || $(this).val()){
                                    $(this).removeClass('ui-state-error');
                                }else{
                                    $(this).addClass('ui-state-error');
                                }
                            };
                            $(this).keyup(chg).change(chg);
                        }
                    });
                    if(plugin_form_error){
                        // show error message
						<?php if(isset($options['fail_js']) && $options['fail_js']){
							echo $options['fail_js'];
						} ?>
						alert('Required fields missing, please complete required fields.');
                        /*$('#plugin_form_header_<?php echo htmlspecialchars($form_name);?>').html('<?php echo addcslashes(self::_get_error_msg(),"'");?>');*/
                    }
                    return !plugin_form_error;
                });
            });
        </script>
        <?php
        self::save($form_name,$form);
    }

    public static function check_required(){
        self::init_form();
        // check if any of these forms have been submitted right now.
        $form_name = isset($_REQUEST['_plugin_form_name']) ? $_REQUEST['_plugin_form_name'] : false;
        // check if a cancel button was clicked.
        if($form_name){
            if(isset($_REQUEST['_plugin_form_cancel'])){
                self::clear($form_name);
                return true;
            }else{
                // incase their browser doesn't support javascript.
                // we hackishly look to see ifa  cancel button was clicked.
                foreach($_REQUEST as $key=>$val){
                    if(preg_match('/cancel/i',$key) && preg_match('/cancel/i',$val)){
                        self::clear($form_name);
                        return true;
                    }
                }
            }
        }
        if(isset($form_name) && isset(self::$form_options[$form_name])){
            $form = self::$form_options[$form_name];
            $form['error_fields'] = array();
            $form['show_error'] = false;
            $required_fields = isset($form['required_fields']) ? $form['required_fields'] : array();
            // check these required fields are set. if not, we redirect (via POST) user back to where they came from along with message.
            $error_fields = array();
            foreach($required_fields as $field=>$name){
                if(!isset($_REQUEST[$field]) || !trim($_REQUEST[$field])){
                    $error_fields[$field] = $name;
                }
            }
            if($error_fields){
                $form['error_fields'] = $error_fields;
                // we also remember all posted data, so that it can be re-inserted back into
                // the form upon redirect. useful when creating 'new' records etc..
                $form['data_to_load'] = array();
                self::_serialize_post($form['data_to_load'],array_merge($_POST,$_GET),'');
                header("Location: ".$form['return_url']);
                $form['show_error'] = true;
                self::save($form_name,$form);
                exit;
            }
            self::clear($form_name);
        }
    }


    public static function confirm_delete($post_key,$message,$cancel_url='',$other_options=array()){
        if(!isset($_SESSION['_delete_data'])){
            $_SESSION['_delete_data'] = array();
        }
        $hash = md5('delete '.$post_key.' '.(isset($_REQUEST[$post_key])?$_REQUEST[$post_key]:''));
        if(isset($_REQUEST['_confirm_delete']) && $_REQUEST['_confirm_delete'] == $hash && isset($_REQUEST['really_confirm_delete']) && $_REQUEST['really_confirm_delete'] == 'yep'){
            // the user has clicked on the confirm delete button!
            if(isset($_SESSION['_delete_data'][$hash])){
                unset($_SESSION['_delete_data'][$hash]);
            }
            return true;
        }
        // we take the post data, and check if we're confirming or not.
        if(!$cancel_url)$cancel_url = $_SERVER['REQUEST_URI'];
        $post_data = $_POST;
        $post_uri = $_SERVER['REQUEST_URI'];
        // serialise this data and redirect to the delete confirm page.
        $data = array($message,$post_data,$post_uri,$cancel_url,$post_key,$other_options);
        $_SESSION['_delete_data'][$hash] = base64_encode(serialize($data));
        //redirect_browser(_BASE_HREF.'form.confirm_delete/?hash='.$hash);
        redirect_browser(_BASE_HREF.'?m[0]=form&p[0]=confirm_delete&hash='.$hash);
        return false;
    }


    public static function prevent_exit($options){
        $valid_exits = $options['valid_exits'];
        $id = md5(mt_rand(0,100));
        ?>
        <input type="hidden" name="prevent_exit_<?php echo $id;?>" id="prevent_exit_<?php echo $id;?>" value="true">
        <script type="text/javascript">
            var change_detected = false;
            $(function(){
                var plugin_form_prevent_exit = $('#prevent_exit_<?php echo $id;?>').parents('form');
                if(typeof plugin_form_prevent_exit == 'undefined' || !plugin_form_prevent_exit){
                    alert('Form Plugin initialisation failed. Contact developer.');
                    return;
                }
                $('input,select,textarea',plugin_form_prevent_exit).change(function(){
                    change_detected = true;
                    $(this).addClass('form-change');
                });
                <?php foreach($valid_exits as $valid_exit){ ?>
                $('<?php echo $valid_exit;?>',plugin_form_prevent_exit).click(function(){
                    change_detected = false;
                });
                <?php } ?>
            });
            window.onbeforeunload = function(){
                // check for changes to the form.
                if(change_detected){
                    return 'Leave page and discard changes?';
                }
            };
        </script>
        <?php
    }

    private static $_default_field = false;
    public static function set_default_field($field) {
        if(get_display_mode()=='mobile')return false;
        if(self::$_default_field)return false;
        self::$_default_field = $field;
        ?>
            <script type="text/javascript">
                $(function(){
                    if($('#<?php echo $field;?>').length>0){
                        $('#<?php echo $field;?>')[0].focus();
                    }
                });
            </script>
        <?php
        return true;
    }

    public static function generate_form_element($setting){

        if(isset($setting['ignore'])&&$setting['ignore'])return;
        // type defaults

        if($setting['type']=='currency'){
            $setting['class'] = (isset($setting['class']) ? $setting['class'] . ' ': '') . 'currency';
        }
        if($setting['type']=='date'){
            $setting['class'] = (isset($setting['class']) ? $setting['class'] . ' ': '') . 'date_field';
            $setting['type'] = 'text';
        }
        if($setting['type']=='time'){
            $setting['class'] = (isset($setting['class']) ? $setting['class'] . ' ': '') . 'time_field';
            $setting['type'] = 'text';
        }
        if($setting['type']=='select' || $setting['type']=='wysiwyg'){
            if(!isset($setting['id'])||!$setting['id']){
                $setting['id'] = $setting['name'];
            }
        }
        if($setting['type']=='save_button'){
            $setting['type'] = 'submit';
            $setting['class'] = (isset($setting['class']) ? $setting['class'] . ' ': '') . 'submit_button save_button';
        }
        if($setting['type']=='delete_button'){
            $setting['type'] = 'submit';
            $setting['class'] = (isset($setting['class']) ? $setting['class'] . ' ': '') . 'submit_button delete_button';
        }


        if(isset($setting['label']) && (!isset($setting['id'])||!$setting['id'])){
            // labels need ids
            $setting['id'] = md5($setting['name']);
        }

        if(!isset($setting['value']))$setting['value']='';


        ob_start();

        // handle multiple options
        $loop_count = 1;
        if(isset($setting['multiple']) && $setting['multiple']){
            // has to have at least 1 value
            if($setting['multiple'] === true){
                // create our wrapper id.
                $multiple_id = md5(serialize($setting));
                echo '<div id="'.$multiple_id.'">';
            }else{
                $multiple_id = $setting['multiple'];
            }
            if(!isset($setting['values']))$setting['values'] = array($setting['value']);
            $loop_count = count($setting['values']);
        }
        for($x=0; $x<$loop_count; $x++){

            if(isset($setting['multiple']) && $setting['multiple']){
                $setting['value'] = isset($setting['values'][$x]) ? $setting['values'][$x] : false;
                echo '<div class="dynamic_block">';
                // unique ID for each multi select box
	            if(empty($setting['id'])){
	                $setting['id'] = md5(serialize($setting));
	            }
	            if(empty($setting['old_id'])) $setting['old_id'] = $setting['id'];
	            $setting['id'] = $setting['old_id'] . $x;
            }

	        $attributes = '';
	        foreach(array('size','style','autocomplete','placeholder','class','id','onclick') as $attr){
	            if(isset($setting[$attr])){
	                $attributes .= ' '.$attr.'="'.$setting[$attr].'"';
	            }
	        }


            switch($setting['type']){
                case 'currency':
                    echo currency('<input type="text" name="'.$setting['name'].'" value="'.htmlspecialchars($setting['value']).'"'.$attributes.'>',true,isset($setting['currency_id']) ? $setting['currency_id'] : false);
                    break;
                case 'number':
                    ?>
                    <input type="number" name="<?php echo $setting['name'];?>" value="<?php echo htmlspecialchars($setting['value']);?>"<?php echo $attributes;?>>
                    <?php
                    break;
                case 'text':
                    ?>
                    <input type="text" name="<?php echo $setting['name'];?>" value="<?php echo htmlspecialchars($setting['value']);?>"<?php echo $attributes;?>>
                    <?php
                    break;
                case 'password':
                    ?>
                    <input type="password" name="<?php echo $setting['name'];?>" value="<?php echo htmlspecialchars($setting['value']);?>"<?php echo $attributes;?>>
                    <?php
                    break;
                case 'hidden':
                    ?>
                    <input type="hidden" name="<?php echo $setting['name'];?>" value="<?php echo htmlspecialchars($setting['value']);?>"<?php echo $attributes;?>>
                    <?php
                    break;
                case 'textarea':
                    ?>
                    <textarea name="<?php echo $setting['name'];?>" rows="6" cols="50"<?php echo $attributes;?>><?php echo htmlspecialchars($setting['value']);?></textarea>
                    <?php
                    break;
                case 'wysiwyg':
                    self::load_wysiwyg();
                    ?>
                    <?php if(!isset($setting['options']) || !isset($setting['options']['inline']) || $setting['options']['inline']){ ?>
	                    <div style="border:1px solid #EFEFEF;" data-name="<?php echo $setting['name'];?>" <?php echo $attributes;?>><?php echo module_security::purify_html($setting['value']);?></div>
		                <?php if($setting['name'] != $setting['id']){ ?>
		                <!-- we update this on change, needed because tinymce jquery has issues with name[] form elements -->
		                <input type="hidden" name="<?php echo $setting['name'];?>" id="<?php echo $setting['id'];?>_postback" value="<?php echo htmlspecialchars(module_security::purify_html($setting['value']));?>">
		                <?php } ?>
                    <?php }else{ ?>
                    <textarea name="<?php echo $setting['name'];?>" rows="6" cols="50"<?php echo $attributes;?>><?php echo htmlspecialchars($setting['value']);?></textarea>
                    <?php } ?>

                    <script type="text/javascript">

                        $(function() {
                            $('#<?php echo $setting['id'];?>').tinymce({
                                // Location of TinyMCE script
                                <?php if(!isset($setting['options']) || !isset($setting['options']['inline']) || $setting['options']['inline']){ ?>
                                inline: true,
                                <?php } ?>
                                script_url : '<?php echo _BASE_HREF;?>includes/plugin_form/js/tinymce4.0.11/tinymce.min.js',
                                relative_urls : false,
                                convert_urls : false,
                                // General options
                                theme : "modern",
                                statusbar: false,
                                /*plugins: [
                                    "advlist autolink lists link image charmap print preview anchor",
                                    "searchreplace visualblocks code fullscreen",
                                    "insertdatetime media table contextmenu paste"
                                ],
                                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",*/

                                plugins: [
                                    "advlist autolink autoresize link image lists charmap print preview hr anchor pagebreak",
                                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                                    "table directionality emoticons template textcolor paste textcolor"
                                ],

                                toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect",
                                toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
                                toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking",

                                menubar: false,
                                toolbar_items_size: 'small',
                                width : '100%',

                                browser_spellcheck: true,
                                contextmenu: false,

                                setup : function(ed) {
                                    ed.on("init", function() {
                                        if(typeof tinymce_focus != 'undefined'){
                                            $(ed.getDoc()).contents().find('body').focus(function(){tinymce_focus();});
                                        }
                                        if(typeof tinymce_blur != 'undefined'){
                                            $(ed.getDoc()).contents().find('body').blur(function(){tinymce_blur();});
                                        }
                                    });
	                                <?php if(!isset($setting['options']) || !isset($setting['options']['inline']) || $setting['options']['inline']){
		                                if($setting['name'] != $setting['id']){ ?>
		                                ed.on("change", function() {
			                                $('#<?php echo $setting['id'];?>_postback').val(ed.getContent());
								          });
	                                <?php } } ?>
                                }
                            });
                        });
                    </script>
                    <?php
                    break;
                case 'select':
                    // copied from print_select_box()
                    if(isset($setting['allow_new']) && $setting['allow_new']){
                        $attributes .= ' onchange="dynamic_select_box(this);"';

                    }
                    ?>
                    <select name="<?php echo $setting['name'];?>"<?php echo $attributes;?>>
                        <?php if(!isset($setting['blank'])||$setting['blank']){ ?>
                        <option value=""><?php echo (!isset($setting['blank'])||$setting['blank'] === true) ? _l('- Select -') : htmlspecialchars($setting['blank']);?></option>
                        <?php }

                        $found_selected = false;
                        $current_val = 'Enter new value here';
                        $sel = '';
                        foreach($setting['options'] as $key => $val){
                            if(is_array($val)){
                                if(!$setting['options_array_id']){
                                    if(isset($val[$setting['id']]))$setting['options_array_id'] = $setting['id'];
                                    else $setting['options_array_id'] = key($val);
                                }
                                $printval = $val[$setting['options_array_id']];
                            }else{
                                $printval = $val;
                            }
                            if(strlen($printval)==0)continue;
                            $sel .= '<option value="'.htmlspecialchars($key).'"';
                            // to handle 0 elements:
                            if($setting['value'] !== false && ($setting['value'] !== '') && $key == $setting['value']){
                                $current_val = $printval;
                                $sel .= ' selected';
                                $found_selected = true;
                            }
                            $sel .= '>'.htmlspecialchars($printval).'</option>';
                        }
                        if($setting['value'] && !$found_selected){
                            $sel .= '<option value="'.htmlspecialchars($setting['value']).'" selected>'.htmlspecialchars($setting['value']).'</option>';
                        }
                        if(isset($setting['allow_new']) && $setting['allow_new'] && get_display_mode() != 'mobile'){
                            $sel .= '<option value="create_new_item">'._l(' - Create New - ') .'</option>';
                        }
                        if(isset($setting['allow_new']) && $setting['allow_new']){
                            //$sel .= '<input type="text" name="new_'.$id.'" style="display:none;" value="'.$current_val.'">';
                        }
                        echo $sel;
                        ?>
                        <?php /*foreach($setting['options'] as $key=>$val){ ?>
                        <option value="<?php echo $key;?>"<?php echo $setting['value'] == $key ? ' selected':'' ?>><?php echo htmlspecialchars($val);?></option>
                        <?php }*/ ?>
                    </select>
                    <?php
                    break;
                case 'checkbox':
                    ?>
                    <input type="hidden" name="default_<?php echo $setting['name'];?>" value="1">
                    <input type="checkbox" name="<?php echo $setting['name'];?>" value="1" <?php if($setting['value']) echo ' checked'; ?><?php echo $attributes;?>>
                    <?php
                    break;
                case 'check':
                    ?>
                    <input type="checkbox" name="<?php echo $setting['name'];?>" value="<?php echo $setting['value'];?>" <?php if($setting['checked']) echo ' checked'; ?><?php echo $attributes;?>>
                    <?php
                    break;
                case 'submit':
                    ?>
                    <input type="submit" name="<?php echo htmlspecialchars($setting['name']);?>" value="<?php echo htmlspecialchars($setting['value']); ?>" <?php echo $attributes;?>/>
                    <?php
                    break;
                case 'button':
                    ?>
                    <input type="button" name="<?php echo htmlspecialchars($setting['name']);?>" value="<?php echo htmlspecialchars($setting['value']); ?>" <?php echo $attributes;?>/>
                    <?php
                    break;
                case 'html':
					?> <div<?php echo $attributes;?>><?php echo $setting['value'];?></div>
					<?php
                    break;

            }

            if(isset($setting['multiple']) && $setting['multiple']){
                echo '<a href="#" class="add_addit">+</a> <a href="#" class="remove_addit">-</a>';
                echo '</div>';
            }
        }

        if(isset($setting['multiple']) && $setting['multiple']){
            if($setting['multiple'] === true){
                echo '</div>';
            }
            echo '<script type="text/javascript"> new ucm.form.dynamic("'.$multiple_id.'"); </script>';
        }

        $html = ob_get_clean();


        if(isset($setting['encrypt']) && $setting['encrypt'] && class_exists('module_encrypt',false)){
            $html = module_encrypt::parse_html_input($setting['page_name'],$html);
        }
        echo $html;
        if(isset($setting['label']) && strlen($setting['label'])){
            echo '<label for="'.htmlspecialchars($setting['id']).'">' . _l($setting['label']) .'</label>';
        }
        if(isset($setting['help']) && (count($setting['help']) || strlen($setting['help']))){
            _h($setting['help']);
        }
    }

    private static $load_wysiwyg_done = false;
    public static function load_wysiwyg(){
        if(!self::$load_wysiwyg_done){
            self::$load_wysiwyg_done = true;
            ?> <script type="text/javascript" src="<?php echo _BASE_HREF;?>includes/plugin_form/js/tinymce4.0.11/jquery.tinymce.min.js"></script> <?php
        }
    }

	public static function hook_header_print_js(){
		if(module_security::is_logged_in()) {
			?>
			<script type="text/javascript">
			ucm.form_auth_key = '<?php echo self::get_secure_key(); ?>';
			ucm.lang.ok = '<?php _e('OK');?>';
			ucm.lang.mins = '<?php _e('mins');?>';
			ucm.lang.hr = '<?php _e('hr');?>';
			ucm.lang.hrs = '<?php _e('hrs');?>';
			ucm.lang.am = '<?php _e('am');?>';
			ucm.lang.AM = '<?php _e('AM');?>';
			ucm.lang.pm = '<?php _e('pm');?>';
			ucm.lang.PM = '<?php _e('PM');?>';
			ucm.settings = ucm.settings || {};
			ucm.settings.time_format = '<?php echo preg_replace('#[^a-zA-Z:]#','',module_config::c('time_format','g:ia'));?>';
			</script>
			<?php
		}
	}
    public static function check_secure_key(){
        if(!isset($_REQUEST['form_auth_key']) || $_REQUEST['form_auth_key'] != self::get_secure_key()){
            return false;
        }
        return true;
    }
    public static function print_form_auth(){
        ?>
        <input type="hidden" name="form_auth_key" value="<?php echo htmlspecialchars(self::get_secure_key());?>">
        <?php
    }
    public static function get_secure_key(){
        // generate a secure key for all sensitive form submissions.
        $hash = module_config::c('secure_hash',0);
        if(!$hash){
            $hash = md5(microtime().mt_rand(1,4000).__FILE__.time()); // not very secure. meh.
            module_config::save_config('secure_hash',$hash);
        }
        $hash = md5($hash."secure for user ".module_security::get_loggedin_id()." with name ".module_security::get_loggedin_name().session_id());
        return $hash;
    }

    public static function search_bar($options){
        // let the themes override this search bar function.
        $result = hook_handle_callback('search_bar',$options);
        if(is_array($result)){
            // has been handed by a theme.
            return current($result);
        }
        $defaults = array(
            'type' => 'table',
            'title' => _l('Filter By:'),
            'elements' => array(),
            'actions' => array(
                'search' => create_link("Search","submit"),
            ),
        );
        $options = array_merge($defaults,$options);
        //todo - hook in here for themes.
        ob_start();
        ?>
        <table class="search_bar">
            <tbody>
            <tr>
                <?php if($options['title']){ ?>
                    <th><?php echo $options['title']; ?></th>
                <?php } ?>
                <?php foreach($options['elements'] as $element){
                    if(isset($element['field']) && !isset($element['fields'])){
                        $element['fields'] = array($element['field']);
                    }
                    if(isset($element['title']) && $element['title']){
                    ?>
                    <td class="search_title">
                        <?php echo $element['title'];?>
                    </td>
                    <?php }
                    if(isset($element['fields'])){ ?>
                    <td class="search_input">
                        <?php if(is_array($element['fields'])){
                            foreach($element['fields'] as $dataid => $field){
                                if(is_array($field)){
                                    // treat this as a call to the form generate option
                                    self::generate_form_element($field);
                                    echo ' ';
                                }else{
                                    echo $field.' ';
                                }
                            }
                        }else{
                            echo $element['fields'];
                        }
                        ?>
                    </td>
                    <?php
                    }
                }
                if(class_exists('module_extra',false) && isset($options['extra_fields']) && $options['extra_fields']){
                    // find out if any extra fields are searchable
                    module_extra::print_search_bar($options['extra_fields']);
                }
                if($options['actions']){
                    ?>
                    <td class="search_action">
                    <?php
                    foreach($options['actions'] as $action_id => $action){
                        echo $action .' ';
                    }
                    ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            </tbody>
        </table>
        <?php

        return ob_get_clean();
    }

    public static function generate_fieldset($options){
        // let the themes override this search bar function.
        $result = hook_handle_callback('generate_fieldset',$options);
        if(is_array($result)){
            // has been handed by a theme.
            return current($result);
        }

        $defaults = array(
	        'id' => false,
            'type' => 'table',
            'title' => false,
            'title_type' => 'h3',
            'heading' => false,
            'row_title_class' => 'width1',
            'row_data_class' => '',
            'elements' => array(),
            'class' => 'tableclass tableclass_form',
            'extra_settings' => array(),
            'elements_before' => '',
            'elements_after' => '',
        );
        $options = array_merge($defaults,$options);
		if(function_exists('hook_filter_var')){
			$options = hook_filter_var('generate_fieldset_options',$options);
		}
        //todo - hook in here for themes.
        ob_start();
        if($options['heading']){
            print_heading($options['heading']);
        }else if($options['title']){ ?>
            <<?php echo $options['title_type'];?>><?php _e($options['title']); ?></<?php echo $options['title_type'];?>>
        <?php } ?>
        <?php echo $options['elements_before'];?>
        <?php if($options['elements']){ ?>
        <table class="<?php echo $options['class'];?>">
            <tbody>
            <?php
            foreach($options['elements'] as $element){
                if(isset($element['ignore']) && $element['ignore'])continue;
                if(isset($element['field']) && !isset($element['fields'])){
                    $element['fields'] = array($element['field']);
                    unset($element['field']);
                }
                ?>
                <tr>
                    <?php if((isset($element['message'])&&$element['message']) || (isset($element['warning'])&&isset($element['warning']))){ ?>
                        <td colspan="2" align="center">
                            <?php if(isset($element['message'])){ ?>
                                <?php echo $element['message'];?>
                            <?php }else if(isset($element['warning'])){ ?>
                                <span class="error_text"><?php echo $element['warning'];?></span>
                            <?php } ?>

                        </td>
                    <?php }else{ ?>
                        <?php if(isset($element['title'])){ ?>
                        <th class="<?php echo isset($element['row_title_class']) ? $element['row_title_class'] : $options['row_title_class'];?>">
                            <?php echo htmlspecialchars(_l($element['title']));?>
                        </th>
                        <?php }
                        if(isset($element['fields'])){ ?>
                        <td class="<?php echo isset($element['row_data_class']) ? $element['row_data_class'] : $options['row_data_class'];?>">
                            <?php if(is_array($element['fields'])){
                                foreach($element['fields'] as $dataid => $field){
                                    if(is_array($field)){
                                        // treat this as a call to the form generate option
                                        self::generate_form_element($field);
                                        echo ' ';
                                    }else if(is_closure($field)){
                                        $field();
                                    }else{
                                        echo $field.' ';
                                    }
                                }
                            }else{
                                echo $element['fields'];
                            }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
                <?php
                }
            }
            if(class_exists('module_extra') && module_extra::is_plugin_enabled() && $options['extra_settings']){
                module_extra::display_extras($options['extra_settings']);
            }
            ?>
            </tbody>
        </table>
        <?php }
        echo $options['elements_after'];?>
        <?php

        return ob_get_clean();
    }

    public static function generate_form_actions($options){
        // let the themes override this form action function.
        $result = hook_handle_callback('generate_form_actions',$options);
        if(is_array($result)){
            // has been handed by a theme.
            return current($result);
        }
        $defaults = array(
            'type' => 'action_bar',
            'class' => 'action_bar',
            'elements' => array(),
        );
        $options = array_merge($defaults,$options);
        //todo - hook in here for themes.
        ob_start();
        ?>
        <div class="<?php echo $options['class'];?>">
            <?php
            foreach($options['elements'] as $element){
                if(is_array($element) && !is_array(current($element))){
                    $element = array($element);
                }
                $element['fields'] = $element;
                ?>
                <span class="action">
                    <?php if(isset($element['fields'])){ ?>
                    <span class="action_element">
                        <?php if(is_array($element['fields'])){
                            foreach($element['fields'] as $dataid => $field){
                                if(is_array($field)){
                                    // treat this as a call to the form generate option
                                    self::generate_form_element($field);
                                    echo ' ';
                                }else{
                                    echo $field.' ';
                                }
                            }
                        }else{
                            echo $element['fields'];
                        }
                        ?>
                    </span>
                <?php
                }
                ?>
                </span>
            <?php } ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /*private static $_bulk_operations_options = array();
    public static function enable_pagination_bulk_operations($options){
        hook_add('pagination_hook_init','module_form::hook_pagination_hook_init');
        hook_add('pagination_hook_display','module_form::hook_pagination_hook_display');
        self::$_bulk_operations_options = $options;
    }
    public static function hook_pagination_hook_init(){

    }
    public static function hook_pagination_hook_display(){

    }*/


}