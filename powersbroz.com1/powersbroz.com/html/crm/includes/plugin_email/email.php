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


// for the email table.
define('_MAIL_STATUS_PENDING',1);
define('_MAIL_STATUS_OVER_QUOTA',5);
define('_MAIL_STATUS_SENT',2);
define('_MAIL_STATUS_FAILED',4);



class module_email extends module_base{

	public $replace_values;

    public $email_id; // queued email id in system.
    public $website_id;
    private $email_fields; // in db.


	public $to = array();
	public $cc = array();
	public $bcc = array();
    public $message_html;
    public $from;
    public $attachments;
    public $message_text;
    public $subject;
    public $custom_data = array();
    public $sent_time;
    public $status;
    public $reply_to;
    public $bounce_address = '';
    public $error_text;
    public $message_id = '';
    public $debug_message = '';

    public $invoice_id=0;
    public $quote_id=0;
    public $job_id=0;
    public $note_id=0;
    public $prevent_duplicates=false;

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
    function init(){
		$this->module_name = "email";
		$this->module_position = 1666;

        $this->version = 2.349;
        // 2.349 - 2015-11-19 - email integration with Custom Data
        // 2.348 - 2015-04-05 - better email duplicate checking
        // 2.347 - 2015-03-26 - remove website option if disabled
        // 2.346 - 2015-02-05 - AUTO_LOGIN_KEY template tag fix for invoice emails
        // 2.345 - 2015-01-25 - email custom data improvements
        // 2.344 - 2015-01-20 - added more email debugging
        // 2.343 - 2014-12-27 - email sql update fix
        // 2.342 - 2014-12-21 - email page speed improvement
        // 2.341 - 2014-12-19 - send limit fix
        // 2.34 - 2014-12-09 - AUTO_LOGIN_KEY template tag added
        // 2.339 - 2014-12-08 - AUTO_LOGIN_KEY template tag added

        // 2.23 - do the email string replace twice so we catch everything.
        // 2.24 - auth
        // 2.25 - bug fix, replace with arrays.
        // 2.251 - menu change.
        // 2.252 - link rewirte
        // 2.253 - permission fix
        // 2.254 - default to address for multi contacts
        // 2.255 - custom from email address
        // 2.301 - BIG UPDATE! with internal mail queue and quota limiting
        // 2.302 - from email address set to full name of user.
        // 2.303 - ability to add multiple attachments to Job/Invoice/etc.. email
        // 2.304 - showing email history in invoice/jobs/etc..
        // 2.305 - show which user emailed invoice/job/etc..
        // 2.306 - choose different templates when sending an email
        // 2.307 - email compose fix
        // 2.308 - started work on generic email compose for customers.
        // 2.309 - easier ssl/tls options added to email settings area
        // 2.31 - bcc/cc fix
        // 2.311 - related job/website email fix
        // 2.312 - 2013-04-12 - SQL fix in upgrade
        // 2.313 - 2013-04-16 - fix for sending customer emails
        // 2.314 - 2013-04-27 - default from email address is current user
        // 2.315 - 2013-05-28 - email template tag improvements
        // 2.316 - 2013-05-28 - email template first_name/last_name addition
        // 2.317 - 2013-06-04 - remember selected job/website when chagning email template
        // 2.318 - 2013-06-07 - email_from_logged_in_user option added
        // 2.319 - 2013-07-25 - print email
        // 2.32 - 2013-09-13 - invoice linked to company
        // 2.321 - 2013-11-15 - working on new UI
        // 2.322 - 2013-11-19 - working on new UI
        // 2.323 - 2014-01-03 - email tracking in files
        // 2.324 - 2014-01-20 - new quote feature
        // 2.325 - 2014-02-03 - delete emails when customer is deleted fix
        // 2.326 - 2014-02-15 - {AUTO_LOGIN_LINK} added to some emails
        // 2.327 - 2014-02-19 - bounce address fix
        // 2.328 - 2014-03-17 - speed fix in SQL
        // 2.329 - 2014-07-01 - better duplicate mail prevention
        // 2.33 - 2014-07-15 - full name in emails
	    // 2.331 - 2014-07-15 - email settings fix
	    // 2.332 - 2014-07-15 - cc option in email form
	    // 2.333 - 2014-08-15 - responsive improvements
	    // 2.334 - 2014-09-29 - email bug fix
	    // 2.335 - 2014-10-06 - new version of phpMailer
	    // 2.336 - 2014-10-13 - show template name in selection dropdown
	    // 2.337 - 2014-11-26 - email attachment bug fix
	    // 2.338 - 2014-11-27 - quote email bug fix

        hook_add('customer_deleted','module_email::hook_customer_deleted');

		$this->reset();
	}

    public function pre_menu(){

        // the link within Admin > Settings > Emails.
        if($this->can_i('view','Email Settings','Config')){
            $this->links[] = array(
                "name"=>"Email",
                "p"=>"email_settings",
                "icon"=>"icon.png",
                "args"=>array('email_template_id'=>false),
                'holder_module' => 'config', // which parent module this link will sit under.
                'holder_module_page' => 'config_admin',  // which page this link will be automatically added to.
                'menu_include_parent' => 0,
            );
        }
        // only display if a customer has been created.
        if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] && $_REQUEST['customer_id']!='new'){
            $this->links[] = array(
                "name"=>'Emails',
                "p"=>"email_admin",
                'args'=>array('email_id'=>false),
                'holder_module' => 'customer', // which parent module this link will sit under.
                'holder_module_page' => 'customer_admin_open',  // which page this link will be automatically added to.
                'menu_include_parent' => 0,
                'icon_name' => 'envelope-o',
            );
        }
    }

     public static function link_generate($email_id=false,$options=array(),$link_options=array()){

        $key = 'email_id';
        if($email_id === false && $link_options){
            foreach($link_options as $link_option){
                if(isset($link_option['data']) && isset($link_option['data'][$key])){
                    ${$key} = $link_option['data'][$key];
                    break;
                }
            }
            if(!${$key} && isset($_REQUEST[$key])){
                ${$key} = $_REQUEST[$key];
            }
        }
        $bubble_to_module = false;
        if(!isset($options['type']))$options['type']='email';
        $options['page'] = 'email_admin';
        if(!isset($options['arguments'])){
            $options['arguments'] = array();
        }
        $options['arguments']['email_id'] = $email_id;
        $options['module'] = 'email';
        if(isset($options['data'])){
            $data = $options['data'];
        }else{
            $data = array();
            if($email_id>0){
                $data = self::get_email($email_id);
            }
            $options['data'] = $data;
        }
        if(!isset($data['customer_id'])&&isset($_REQUEST['customer_id']) && (int)$_REQUEST['customer_id']){
            $data['customer_id']=(int)$_REQUEST['customer_id'];
        }
        // what text should we display in this link?
        $options['text'] = (!isset($data['subject'])||!trim($data['subject'])) ? 'N/A' : $data['subject'];
        if(isset($data['customer_id']) && $data['customer_id']>0){
            $bubble_to_module = array(
                'module' => 'customer',
                'argument' => 'customer_id',
            );
        }
        array_unshift($link_options,$options);

        if(!module_security::has_feature_access(array(
            'name' => 'Customers',
            'module' => 'customer',
            'category' => 'Customer',
            'view' => 1,
            'description' => 'view',
        ))
        ){
            $bubble_to_module = false;
            /*if(!isset($options['full']) || !$options['full']){
                return '#';
            }else{
                return isset($options['text']) ? $options['text'] : 'N/A';
            }*/

        }
        if($bubble_to_module){
            global $plugins;
            return $plugins[$bubble_to_module['module']]->link_generate(false,array(),$link_options);
        }else{
            // return the link as-is, no more bubbling or anything.
            // pass this off to the global link_generate() function
            return link_generate($link_options);

        }
    }

	public static function link_open($email_id,$full=false){
        return self::link_generate($email_id,array('full'=>$full));
    }
    
	public function reset(){
		// clear all local variables.
		$this->replace_values = array();
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
		$this->error_text = '';
		$this->from = array();
		$this->attachments = array();
		$this->bounce_address = '';
		$this->reply_to= '';
		$this->subject= '';
		$this->message_html= '';
		$this->message_text= '';
		$this->sent_time= 0;

        $this->invoice_id=0;
        $this->quote_id=0;
        $this->job_id=0;
        $this->note_id=0;

        $this->email_fields = get_fields('email');
	}
	

     public function process(){
		if('send_email' == $_REQUEST['_process']){
			$this->_handle_send_email();
		}

	}

    public function get_summary($field_type,$field_id,$field_key) {
        global $plugins;
        switch($field_type){
            case 'customer':
                if($field_key=='name')$field_key = 'customer_name';
                $data = $plugins['customer']->get_customer($field_id);
                $primary_user_id = $data['primary_user_id'];
                $data = $plugins['user']->get_user($primary_user_id,false);
                return isset($data[$field_key]) ? $data[$field_key] : '';
            case 'user':
                $data = $plugins['user']->get_user($field_id,false);
                return isset($data[$field_key]) ? $data[$field_key] : '';
        }
        return false;
    }

    


    /**
     * Create a new email ready to send.
     * @return module_email
     */
    public static function &new_email(){
		$email = new self();
        $email -> reset();
        return $email;
    }
    public function replace($key,$val){
        $this->replace_values[$key] = $val;
    }
	/**
	 * Adds the sender of this email.
	 * @param  $type
	 * @param  $id
	 * @return void
	 */
    public function set_from($type,$id){
        $this->from = array(
            'type' => $type,
            'id' => $id,
            'name' => $this->get_summary($type,$id,'name').' '.$this->get_summary($type,$id,'last_name'),
            'email' => $this->get_summary($type,$id,'email'),
        );
    }
	/**
	 * Adds the sender of this email manually.
	 * @param  $email
	 * @param  $name
	 * @return void
	 */
    public function set_from_manual($email,$name=''){
        $this->from = array(
            'type' => 'manual',
            'id' => false,
            'name' => $name,
            'email' => $email,
        );
    }
	/**
	 * Adds the reply to of this email.
	 * @param  $type
	 * @param  $id
	 * @return void
	 */
    public function set_reply_to($email,$name){
        $this->reply_to = array($email,$name);
    }
	/**
	 * Adds a recipient to this email.
	 * @param  $type
	 * @param  $id
	 * @return void
	 */
    public function set_to($type,$id,$email='',$name=''){
        // grab the details of the recipient.
		// add it as a recipient to this email
        if(!$email){
            $email = $this->get_summary($type,$id,'email');
        }
        if(!$name){
            $name = $this->get_summary($type,$id,'name');
        }
        $this->to[] = array(
            'type' => $type,
            'id' => $id,
            'name' => $name,
            'email' => $email,
        );

    }
	/**
	 * Adds the to of this email manually.
	 * @param  $email
	 * @param  $name
	 * @return void
	 */
    public function set_to_manual($email,$name=''){
        $this->to[] = array(
            'type' => 'manual',
            'id' => false,
            'name' => $name,
            'email' => $email,
        );
    }
    public function set_cc_manual($email,$name=''){
        $this->cc[] = array(
            'name' => $name,
            'email' => $email,
        );
    }
    public function set_bcc_manual($email,$name=''){
        $this->bcc[] = array(
            'name' => $name,
            'email' => $email,
        );
    }
    public function set_bounce_address($email){
        $this->bounce_address = $email;
    }
	/**
	 * Adds an attachment to the email
	 * the attachment name will be worked out from the path.
	 * @param  $path
	 * @return void
	 */
	public function add_attachment($path){
		$this->attachments[] = $path;
	}

	/**
	 * Sets the text for the email.
	 * @param  $text
	 * @return void
	 */
	public function set_text($text,$html=false){
		if($html){
			$this->message_html = $text;
			// convert it to text if none exists.
			if (!$this->message_text) {
				$this->message_text = strip_tags(preg_replace('/<br/', "\n<br", preg_replace('#\s+#', ' ', $text)));
			}
		}else{
			$this->message_text = $text;
			// convert it to html if none exists.
			if (!$this->message_html) {
				$this->message_html = nl2br($text);
			}
		}
	}
    public function set_html($html){
        $this->set_text($html,true);
    }
    public function set_subject($subject){
        $this->subject=$subject;
    }
    public function AddAttachment($file_path,$file_name=''){
        $this->attachments[$file_path] = array(
            'path' => $file_path,
            'name' => $file_name,
        );
    }
    public function is_email_limit_ok(){
        $limit_ok = true;
        switch(module_config::c('email_limit_period','day')){
            case 'day':
                $start_time = strtotime("-24 hours");
                break;
            case 'hour':
                $start_time = strtotime("-1 hour");
                break;
            case 'minute':
                $start_time = time() - 60;
                break;
            default:
                $start_time = 0;
        }
        $send_limit = (int)module_config::c('email_limit_amount',0);

        if($start_time > 0 && $send_limit > 0){
            // found a limit, see if it's broken
            $sql = "SELECT COUNT(email_id) AS send_count FROM `"._DB_PREFIX."email` WHERE sent_time > '$start_time'";
            $res = qa1($sql);
            if($res && $res['send_count']){
                // newsletters have been sent out - is it over the limit?
                if($res['send_count'] >= $send_limit){
                    $limit_ok = false;
                }
            }
        }
        return $limit_ok;
    }

	public function set_custom_data($data_type_id, $data_record_id){


		global $plugins;
		$data_record = $plugins['data']->get_data_record($data_record_id);
		$data_record_revisions = $plugins['data']->get_data_record_revisions($data_record_id);
		end($data_record_revisions);
		$current_revision = current($data_record_revisions);
		$current_revision['number'] = count($data_record_revisions);
		$view_revision_id = $current_revision['data_record_revision_id'];
		if($current_revision && $view_revision_id) {
			// user wants a custom revision, we pull out the custom $data_field_groups
			// and we tell the form layout to use the serialized cached field layout information
			$data_field_groups = unserialize( $current_revision['field_group_cache'] );
			$data_items = $plugins['data']->get_data_items($data_record_id,$view_revision_id);
			// we dont always read from cache, because then any ui changes wouldn't be reflected in older reports (if we want to change older reports)
			foreach ( $data_field_groups as $data_field_group ) {
				$data_field_group_id = $data_field_group['data_field_group_id'];
				//$data_field_group    = $plugins['data']->get_data_field_group( $data_field_group_id );
				$data_fields         = $plugins['data']->get_data_fields( $data_field_group_id );
				foreach ( $data_fields as $data_field ) {
					$data_field_id = $data_field['data_field_id'];
					if ( isset( $data_items[ $data_field_id ] ) ) {
						$data_field['value'] = $data_items[ $data_field_id ]['data_text']; // todo, could be data_number or data_varchar as well... hmmm
					}
//					echo "Replace '" . $data_field['title'] . "' with '" . $plugins['data']->get_form_element( $data_field, true, isset( $data_record ) ? $data_record : array() ) . "' <br>";
					$this->replace( $data_field['title'], $plugins['data']->get_form_element( $data_field, true, isset( $data_record ) ? $data_record : array() ) );
				}
			}
		}
	}
	/**
	 * Sends the email we created above, startign with the new_email() method.
	 * @return bool
	 */
	public function send($debug = false){

        if(_DEBUG_MODE){
            module_debug::log(array('title'=>'Email Module','data'=>'Starting to send email'));
        }

        // we have to check our mail quota:
        if(!$this->is_email_limit_ok()){
	        if($debug)echo 'Email over quota, please wait a while and try again.';
            $this->status=_MAIL_STATUS_OVER_QUOTA;
            $this->error_text=_l('Email over quota, please wait a while and try again.');
            return false;
        }
        //$this->status=_MAIL_STATUS_OVER_QUOTA;//testing.

        // we have to add this email to the "email" table ready to be sent out.
        // once the email is queued for sending it will be processed (only if we are within our email quota)
        // if we are not in our email quota then the email will either be queued for sending or an error will be returned.
        // todo: queue for sending later
        // NOTE: at the moment we just return an error and do not queue the email for later sending.
        // emails are removed from the 'email' table if we are over quota for now.


        // preprocessing
        if(!$this->from){
            $this->set_from_manual(module_config::c('admin_email_address'),module_config::c('admin_system_name'));
        }
        if(!$this->to){
            $this->set_to_manual(module_config::c('admin_email_address'),module_config::c('admin_system_name'));
        }
        // process the message replacements etc..
        foreach($this->to as $to){
            $this->replace('TO_NAME',$to['name']);
            $this->replace('TO_EMAIL',$to['email']);
	        if(isset($to['type']) && $to['type'] == 'user' && isset($to['id']) && $to['id'] > 1){
		        $this->replace('AUTO_LOGIN_KEY',module_security::get_auto_login_string($to['id']));
	        }
        }
        $this->replace('FROM_NAME',$this->from['name']);
        $this->replace('FROM_EMAIL',$this->from['email']);
        // hack - we do this loop twice because some replace keys may have replace keys in them.
        for($x=0;$x<2;$x++){
            foreach($this->replace_values as $key=>$val){
                if(is_array($val))continue;
                //$val = str_replace(array('\\', '$'), array('\\\\', '\$'), $val);
                $key = '{'.strtoupper($key).'}';
                // reply to name
                foreach($this->to as &$to){
                    if($to['name']){
                        $to['name'] = str_replace($key,$val,$to['name']);
                    }
                }
                // replace subject
                $this->subject = str_replace($key,$val,$this->subject);
                // replace message html
                $this->message_html = str_replace($key,$val,$this->message_html);
                // replace message text.html
                $this->message_text = str_replace($key,$val,$this->message_text);
            }
        }




        // get all the data together in an array that will be saved to the email table
        $header_data = array();
        if($this->reply_to){
            $header_data['ReplyToEmail'] = $this->reply_to[0];
            $header_data['ReplyToName'] = $this->reply_to[1];
            $header_data['Sender'] = isset($this->bounce_address) ? $this->bounce_address : $this->reply_to[0];
        }else{
            $header_data['Sender'] = isset($this->bounce_address) ? $this->bounce_address : false;
        }
        $header_data['FromEmail'] = isset($this->from['email']) ? $this->from['email'] : '';
        $header_data['FromName'] = isset($this->from['name']) ? $this->from['name'] : '';
        $header_data['to'] = $this->to;
        $header_data['cc'] = $this->cc;
        $header_data['bcc'] = $this->bcc;
        
        $email_data = array(
            'create_time' => time(),
            'status' => _MAIL_STATUS_PENDING,
            'customer_id' => isset($this->customer_id) ? $this->customer_id : 0,
            'file_id' => isset($this->file_id) ? $this->file_id : 0,
            'company_id' => isset($this->company_id) ? $this->company_id : 0,
            'newsletter_id' => isset($this->newsletter_id) ? $this->newsletter_id : 0,
            'send_id' => isset($this->send_id) ? $this->send_id : 0,
            'debug' => isset($this->debug_message) ? $this->debug_message : '',
            'message_id' => $this->message_id,
            'subject' => $this->subject,
            'headers' => $header_data, // computed above....
            'custom_data' => json_encode($this->custom_data), // computed above....
            'html_content' => $this->message_html,
            'text_content' => $this->message_text,
            'attachments' => array(), // below
        );
        foreach($this->email_fields as $fieldname=>$fd){
            if($fieldname != 'email_id' && property_exists($this,$fieldname) && !isset($email_data[$fieldname])){
                $email_data[$fieldname] = $this->{"$fieldname"};
            }
        }


        if($this->attachments){
            foreach($this->attachments as $file){
                if(is_array($file)){
                    $file_path = $file['path'];
                    $file_name = $file['name'];
                }else{
                    $file_path = $file;
                    $file_name = '';
                }
                if(is_file($file_path)){
	                // todo - sanatise this.
				    // ticket.php : $file_path = 'includes/plugin_ticket/attachments/'.$attachment['ticket_message_attachment_id'];
				    // pdfs : temp/Invoice_asdf.pdf temp/Quote_asdf.pdf etc..
				    // newsletters : includes/plugin_file/upload/
				    // custom data : includes/plugin_data/upload/
	                $path = realpath($file_path);
	                // only allow sending from certain folders.
	                if(strlen($path) && (stripos($path,_UCM_FOLDER)===0 || is_uploaded_file($path))){
		                if(
			                stripos($path, _UCM_FOLDER.'includes/plugin_ticket/attachments/') === 0 ||
			                stripos($path, _UCM_FOLDER.'temp/') === 0 ||
			                stripos($path, _UCM_FOLDER.'includes/plugin_file/upload/') === 0 ||
			                stripos($path, _UCM_FOLDER.'includes/plugin_data/upload/') === 0 ||
			                is_uploaded_file($path)
		                ){
			                $email_data['attachments'][$path] = $file_name;
		                }else{
			                //echo "Not match $path <br>";
		                }
	                }else{
		                //echo "Not match $path with "._UCM_FOLDER;
	                }
                }
            }
        }

		if($this->prevent_duplicates){
			if($debug)echo "checking for duplicate emails within 2 hours...";
			$sql = "SELECT * FROM `"._DB_PREFIX."email` WHERE `create_time` >= ". (int)(time() - 7200) ." AND `subject` = '".mysql_real_escape_string($email_data['subject'])."'  ";
			$found_field = false;
			foreach(array('invoice_id','job_id','website_id','quote_id') as $prevent_default_check){
				if(property_exists($this, $prevent_default_check) && isset($email_data[$prevent_default_check]) && (int)$email_data[$prevent_default_check] > 0){
					$sql .= " AND `".$prevent_default_check."` = ".(int)$email_data[$prevent_default_check];
					$found_field = true;
				}
			}
			if($found_field){
				$previous = qa($sql);
				if(count($previous)){
					// check content matches.
					$found_previous = false;
					foreach($previous as $prev){
						if(md5($this->message_html) == md5($prev['html_content'])){
							$found_previous = true;
							break;
						}
					}
					if($found_previous) {
						if ( $debug ) {
							echo " - found previous email (id: ";
						}
						if ( $debug ) {
							foreach ( $previous as $p ) {
								echo $p['email_id'] . ":" . $p['subject'].' ';
							}
						}
						if ( $debug ) {
							echo ")! NOT sending this email!! ";
						}
						return false;
					}
				}
				if($debug)echo " - no previous emails found, sending... ";
			}
		}

        $email_id = update_insert('email_id',false,'email',$email_data);
        //echo '<pre>'.$email_id;print_r($email_data);exit;

        $this->_send_queued_email($email_id, $debug);
        $this->email_id = $email_id;
        return ($this->status == _MAIL_STATUS_SENT);

    }
    private function _send_queued_email($email_id, $debug=false){

        if(!$email_id)return false;
        $this->reset();
        $email_data = get_single('email','email_id',$email_id);
        if(!$email_data || $email_data['email_id'] != $email_id)return false;

	    if(defined('_BLOCK_EMAILS') && _BLOCK_EMAILS){
            if($debug)echo " - emails blocked for testing, fake send <br>\n ";
		    update_insert('email_id',$email_id,'email',array(
                'sent_time' => time(),
                'status' => _MAIL_STATUS_SENT,
            ));
            $this->status = _MAIL_STATUS_SENT;
		    return true;
	    }

        $headers = unserialize($email_data['headers']);
        $attachments = unserialize($email_data['attachments']);

        try{

		    require_once("class.phpmailer.php");
            $mail = new PHPMailer();
            //$mail -> Hostname = 'yoursite.com';
            $mail->CharSet = 'UTF-8';
            // turn on HTML emails
            $mail->isHTML(true);
            // SeT SMTP or php Mail method:
            if(module_config::c('email_smtp',0)){
                if(_DEBUG_MODE){
                    module_debug::log(array('title'=>'Email Module','data'=>'Connecting via SMTP to: '.module_config::c('email_smtp_hostname','')));
                }
                $mail->IsSMTP();
                // turn on SMTP authentication
                $mail->SMTPSecure = module_config::c('email_smtp_auth','');
                $mail->SMTPAuth = module_config::c('email_smtp_authentication',0);
                $mail->Host     = module_config::c('email_smtp_hostname','');
                if($mail->SMTPAuth){
                    $mail->Username = module_config::c('email_smtp_username','');
                    $mail->Password = module_config::c('email_smtp_password','');
                }
            }else{
                $mail->IsMail();
            }

            // pull out the data from $email_data
            $mail->MessageID = $email_data['message_id'];
            $mail->Subject     = $email_data['subject'];
            $mail->Body    = $email_data['html_content'];
            $mail->AltBody    = $email_data['text_content'];

            // from the headers:
            $mail->Sender = $headers['Sender'];
            if(isset($headers['ReplyToEmail'])){
                $mail->AddReplyTo($headers['ReplyToEmail'],isset($headers['ReplyToName']) ? $headers['ReplyToName'] : '');
            }
		    $mail->From     = $headers['FromEmail'];
			$mail->FromName = $headers['FromName'];
            $test_to_str = '';
            foreach ($headers['to'] as $to) {
                $mail->AddAddress($to['email'], $to['name']);
                $test_to_str .= " TO: ".$to['email'] .' - '.$to['name'];
            }
            foreach($headers['cc'] as $cc){
                $mail->AddCC($cc['email'],$cc['name']);
            }
            foreach($headers['bcc'] as $bcc){
                $mail->AddBCC($bcc['email'],$bcc['name']);
            }

            // attachemnts
			foreach($attachments as $file_path => $file_name){
				if(is_file($file_path)){
					$mail->AddAttachment($file_path,$file_name);
				}
			}



        // debugging.
//        $html = $this->message_html;
//        $mail->ClearAllRecipients();
//        $mail->AddAddress('davidtest@blueteddy.com.au','David Test');
//        $html = $test_to_str.$html;


            if(_DEBUG_MODE){
                module_debug::log(array('title'=>'Email Module','data'=>'Sending to: '.$test_to_str));
            }
            if(!$mail->Send()){
                $this->error_text = $mail->ErrorInfo;
                // update sent times and status on success.
                update_insert('email_id',$email_id,'email',array(
                    'status' => _MAIL_STATUS_FAILED,
                ));
                // TODO: delete email from the database insetad of letting it queue later.
                // todo: re-do this later to leave the email there for quing.
                delete_from_db('email','email_id',$email_id);
                $this->status = _MAIL_STATUS_FAILED;
                if(_DEBUG_MODE){
                    module_debug::log(array('title'=>'Email Module','data'=>'Send failed: '.$this->error_text));
                }
                if($debug){
                    echo "Failed to send email: ".$this->error_text;
                }
                // todo - send error to admin ?
            }else{
                // update sent times and status on success.
                update_insert('email_id',$email_id,'email',array(
                    'sent_time' => time(),
                    'status' => _MAIL_STATUS_SENT,
                ));
                $this->status = _MAIL_STATUS_SENT;
                if(_DEBUG_MODE){
                    module_debug::log(array('title'=>'Email Module','data'=>'Send success'));
                }
            }

            /*  echo '<hr>';
            echo $this->subject;
            print_r($this->from);
            print_r($this->to);echo $this->status;*/

            //$this->status=_MAIL_STATUS_OVER_QUOTA;//testing.

            // todo : incrase mail count so that it sits within our specified boundaries.

            // true on succes, false on fail.
            return ($this->status == _MAIL_STATUS_SENT);
        }catch(Exception $e){
            return false;
        }
	}


    public static function print_compose($options) {

        include('pages/email_compose_basic.php');
    }

    public static function get_email_compose_options($options) {
        $from_email=$from_name=false;
        if(module_security::is_logged_in() && module_config::c('email_from_logged_in_user',1)){
            $my_details = module_user::get_user(module_security::get_loggedin_id());
	        if(isset($my_details['password']))unset($my_details['password']);
            $from_email = $my_details['email'];
            $from_name = $my_details['name'].(module_config::c('email_from_full_name',1)?' '.$my_details['last_name']:'');
        }
        $new_options = array(
            'subject' => html_entity_decode(isset($_REQUEST['subject']) ? $_REQUEST['subject'] : (isset($options['subject']) ? $options['subject'] : '')),
            'content' =>  isset($_REQUEST['content']) ? $_REQUEST['content'] : (isset($options['content']) ? $options['content'] : ''),
            'cancel_url' =>  isset($options['cancel_url']) ? $options['cancel_url'] : false,
            'complete_url' => isset($options['complete_url']) ? $options['complete_url'] : (isset($options['cancel_url']) ? $options['cancel_url'] : false),
            'from_email' => isset($_REQUEST['from_email']) && $_REQUEST['from_email'] ? $_REQUEST['from_email'] : ($from_email?$from_email:module_config::c('admin_email_address')),
            'from_name' => isset($_REQUEST['from_name']) && $_REQUEST['from_name'] ? $_REQUEST['from_name'] : ($from_name?$from_name:module_config::c('admin_system_name')),
            'to' => isset($_REQUEST['to']) ? $_REQUEST['to'] : (isset($options['to']) ? $options['to'] : array()),
            'to_select' => isset($_REQUEST['to_select']) ? $_REQUEST['to_select'] : (isset($options['to_select']) ? $options['to_select'] : false),
            'cc' => isset($_REQUEST['custom_cc']) ? $_REQUEST['custom_cc'] : (isset($options['cc']) ? $options['cc'] : ''),
            'bcc' => isset($_REQUEST['bcc']) ? $_REQUEST['bcc'] : (isset($options['bcc']) ? $options['bcc'] : ''),
            'attachments' => isset($options['attachments']) ? $options['attachments'] : array(),
            'success_callback' => isset($options['success_callback']) ? $options['success_callback'] : '',
        );
	    foreach($new_options['to'] as $key=>$val){
		    if(isset($val['password'])){
			    unset($new_options['to'][$key]['password']);
		    }
	    }
        foreach(array('website_id','invoice_id','job_id','quote_id') as $key){
            if(isset($_REQUEST[$key])){
                $new_options[$key] = $_REQUEST[$key];
            }
        }
        $options = array_merge($options,$new_options);
        return $options; 
    }

    private function _handle_send_email(){
        $options = @unserialize(base64_decode($_REQUEST['options']));
        if(!$options)$options=array();
        $options = $this->get_email_compose_options($options);
        if(isset($_REQUEST['custom_to'])){
            $custom_to = is_array($_REQUEST['custom_to']) ? $_REQUEST['custom_to'] : array($_REQUEST['custom_to']);
            $to = array();
            foreach($custom_to as $ct){
                $ct = explode('||',$ct);
                $ct['email'] = $ct[0];
                $ct['name'] = isset($ct[1]) ? $ct[1] : '';
                $ct['user_id'] = isset($ct[2]) ? (int)$ct[2] : 0;
                $to[] = $ct;
            }
        }else{
            $to = isset($options['to']) && is_array($options['to']) ? $options['to'] : array();;
        }

        $email = $this->new_email();
        $email->subject = $options['subject'];
        foreach($to as $t){
            if(isset($t['user_id']) && $t['user_id'] > 0){
                $email->set_to('user',$t['user_id'],$t['email'],$t['name'].(isset($t['last_name']) && module_config::c('email_to_full_name',1)?' '.$t['last_name']:''));
            }else{
                $email->set_to_manual($t['email'],$t['name'].(isset($t['last_name']) && module_config::c('email_to_full_name',1)?' '.$t['last_name']:''));
            }
        }
        // set from is the default from address.
        if(isset($options['from_email'])){
            $email->set_from_manual($options['from_email'],isset($options['from_name'])?$options['from_name']:'');
            $email->set_bounce_address($options['from_email']);
        }
        if($options['cc'] && is_array($options['cc'])){
            foreach($options['cc'] as $cc_details){
                $bits = explode('||',$cc_details);
                if(count($bits)>=2 && $bits[0]){
                    $email->set_cc_manual($bits[0],$bits[1]);
                }
            }
        }
        if($options['bcc']){
            $bcc = explode(',',$options['bcc']);
            foreach($bcc as $b){
                $b = trim($b);
                if(strlen($b)){
                    $email->set_bcc_manual($b,'');
                }
            }
        }
        if(isset($options['company_id'])){
            $email->company_id = $options['company_id'];
        }
        if(isset($options['customer_id'])){
            // todo: verify this is a legit customer id we can send emails to.
            $email->customer_id = $options['customer_id'];
            if($options['customer_id']>0){
                foreach(module_customer::get_replace_fields($options['customer_id']) as $key=>$val){
                    //echo "Replacing $key with $val <br>";
                    $email->replace($key,$val);
                }
            }
        }
        if(isset($options['newsletter_id'])){
            $email->newsletter_id = $options['newsletter_id'];
        }
        if(isset($options['file_id'])){
            $email->file_id = $options['file_id'];
        }
        if(isset($options['send_id'])){
            $email->send_id = $options['send_id'];
        }
        if(isset($options['invoice_id'])){
            $email->invoice_id = $options['invoice_id'];
            if($options['invoice_id']>0){
                foreach(module_invoice::get_replace_fields($options['invoice_id']) as $key=>$val){
                    $email->replace($key,$val);
                }
            }
        }
        if(isset($options['job_id'])){
            $email->job_id = $options['job_id'];
            if($options['job_id']>0){
                foreach(module_job::get_replace_fields($options['job_id']) as $key=>$val){
                    $email->replace($key,$val);
                }
            }
        }
        if(isset($options['website_id'])){
            $email->website_id = $options['website_id'];
            if($options['website_id']>0){
                foreach(module_website::get_replace_fields($options['website_id']) as $key=>$val){
                    $email->replace($key,$val);
                }
            }
        }
        if(isset($options['quote_id'])){
            $email->quote_id = $options['quote_id'];
            if($options['quote_id']>0){
                foreach(module_quote::get_replace_fields($options['quote_id']) as $key=>$val){
                    $email->replace($key,$val);
                }
            }
        }

	    // custom data integration
	    if (class_exists('module_data',false) && module_config::c('custom_data_in_email',1) && $options['customer_id']>0 && !empty($_REQUEST['custom_data_info']) && !empty($_REQUEST['custom_data_related'])) {
		    global $plugins;
		    // find all possible custom data entries
		    $data_types = $plugins['data']->get_data_types();
		    foreach ( $data_types as $data_type ) {
			    switch ( $data_type['data_type_menu'] ) {
				    case _CUSTOM_DATA_MENU_LOCATION_CUSTOMER:
					    if ( $plugins['data']->can_i( 'view', $data_type['data_type_name'] ) ) {
						    $search = array(
							    'customer_id'  => $options['customer_id'],
							    'data_type_id' => $data_type['data_type_id']
						    );
						    // we have to limit the data types to only those created by current user if they are not administration
						    $datas = $plugins['data']->get_datas( $search );
						    if ( $datas ) {
							    // found some! does this exist in one of our inputs?
							    if(!empty($_REQUEST['custom_data_info'][$data_type['data_type_id']]) && !empty($_REQUEST['custom_data_related'][$data_type['data_type_id']])){
								    $data_record_id = $_REQUEST['custom_data_related'][$data_type['data_type_id']];
								    $data_info = json_decode($_REQUEST['custom_data_info'][$data_type['data_type_id']],true);
								    if(is_array($data_info) && isset($datas[$data_record_id])){
									    // we have a winner!

									    $list_fields       = array();
									    $data_field_groups = $plugins['data']->get_data_field_groups( $data_type['data_type_id'] );
									    foreach ( $data_field_groups as $data_field_group ) {
										    $data_fields = $plugins['data']->get_data_fields( $data_field_group['data_field_group_id'] );
										    foreach ( $data_fields as $data_field ) {
											    if ( $data_field['show_list'] ) {
												    $list_fields[ $data_field['data_field_id'] ] = $data_field;
											    }
										    }
									    }

									    $list_data_items = $plugins['data']->get_data_items( $data_record_id );
									    foreach($list_fields as $list_field) {
										    $settings = @unserialize( $list_data_items[ $list_field['data_field_id'] ]['data_field_settings'] );
										    if ( ! isset( $settings['field_type'] ) ) {
											    $settings['field_type'] = isset( $list_field['field_type'] ) ? $list_field['field_type'] : false;
										    }
										    $value = false;
										    if ( isset( $list_data_items[ $list_field['data_field_id'] ] ) ) {
											    $value = $list_data_items[ $list_field['data_field_id'] ]['data_text'];
										    }
										    if($value){
											    $data_info['key'] = $value;
											    break;
										    }
									    }

									    $data_info['data_record_id'] = $data_record_id[$data_type['data_type_id']];
									    $email->custom_data[$data_type['data_type_id']] = $data_info;

									    $email->set_custom_data($data_type['data_type_id'], $data_record_id);
								    }
							    }
						    }

					    }
			    }
		    }
	    }

        // final override for first_name last_name if selected from the custom to drop down
        foreach($to as $t){
            if(isset($t['user_id']) && $t['user_id']>0){
                $user = module_user::get_user($t['user_id']);
                if($user){
                    if(strpos($options['content'],'{AUTO_LOGIN_LINK}')!==false && $t['user_id'] != 1){
                        $email->replace('AUTO_LOGIN_LINK',module_security::generate_auto_login_link($t['user_id']));
                    }
                    $email->replace('first_name',$user['name']);
                    $email->replace('last_name',$user['last_name']);
                }
            }
        }

        if(isset($options['note_id'])){
            $email->note_id = $options['note_id'];
        }
        if(isset($options['debug_message'])){
            $email->debug_message = $options['debug_message'];
        }
        $email->set_html($options['content']);
        foreach($options['attachments'] as $attachment){
            $email->AddAttachment($attachment['path'],$attachment['name']);
        }
        // new addition, manually added attachments.
        if(isset($_FILES['manual_attachment']) && isset($_FILES['manual_attachment']['tmp_name'])){
            foreach($_FILES['manual_attachment']['tmp_name'] as $key => $tmp_name){
                if(is_uploaded_file($tmp_name) && isset($_FILES['manual_attachment']['name'][$key]) && strlen($_FILES['manual_attachment']['name'][$key])){
                    $email->AddAttachment($tmp_name,$_FILES['manual_attachment']['name'][$key]);
                }
            }
        }
        if($email->send()){
            if(isset($options['success_callback_args']) && count($options['success_callback_args']) && $options['success_callback'] && is_callable($options['success_callback'])){
                // new callback method using call_user_func_array
                $args = $options['success_callback_args'];
                $args['email_id'] = $email->email_id;
                if(preg_match('#module_\w#',$options['success_callback'])){
                    call_user_func($options['success_callback'],$args);
                }
            }/*else if($options['success_callback']){
                eval($options['success_callback']);
            }*/
            set_message('Email sent successfully');
            redirect_browser($options['complete_url']);
        }else{
            set_error('Sending email failed: '.$email->error_text);
            redirect_browser($options['cancel_url']);
        }
    }

    
    public static function get_email($email_id){
        $email = get_single('email','email_id',$email_id);
        if($email){
            $email['attachments'] = @unserialize($email['attachments']);
        }
        // todo: permission check
        if(!$email){
            // todo: defaults
            $email=array(
                'email_id' => 0,
                'subject' => '',
                'attachments' => false,
                'html_content' => '',
                'text_content' => '',
                'headers' => '',
                'customer_id' => isset($_REQUEST['customer_id']) ? (int)$_REQUEST['customer_id'] : 0,
                'job_id' => isset($_REQUEST['job_id']) ? (int)$_REQUEST['job_id'] : 0,
                'website_id' => isset($_REQUEST['website_id']) ? (int)$_REQUEST['website_id'] : 0,
            );
        }
        if(!is_array($email['attachments'])){
            $email['attachments'] = array();
        }
        return $email;
    }
    public static function get_emails($search){
        return get_multiple('email',$search,'email_id','exact','email_id DESC');
    }
    public static function display_emails($options) {

        if(!isset($options['search']['status'])){
            $options['search']['status'] = _MAIL_STATUS_SENT;
        }
        $emails = self::get_emails($options['search']);
        if(count($emails)>0){
            include("pages/email_widget.php");
        }
    }

    public static function hook_customer_deleted($callback_name, $customer_id, $remove_linked_data){
        if((int)$customer_id>0){
            delete_from_db('email','customer_id',$customer_id);
        }
    }

    public function get_install_sql(){
        return 'CREATE TABLE `'._DB_PREFIX.'email` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `create_time` int(11) NOT NULL DEFAULT \'0\',
  `sent_time` int(11) NOT NULL DEFAULT \'0\',
  `status` tinyint(1) NOT NULL DEFAULT \'0\',
  `customer_id` int(11) NOT NULL DEFAULT \'0\',
  `company_id` int(11) NOT NULL DEFAULT \'0\',
  `job_id` int(11) NOT NULL DEFAULT \'0\',
  `invoice_id` int(11) NOT NULL DEFAULT \'0\',
  `website_id` int(11) NOT NULL DEFAULT \'0\',
  `note_id` int(11) NOT NULL DEFAULT \'0\',
  `newsletter_id` int(11) NOT NULL DEFAULT \'0\',
  `file_id` int(11) NOT NULL DEFAULT \'0\',
  `quote_id` int(11) NOT NULL DEFAULT \'0\',
  `send_id` int(11) NOT NULL DEFAULT \'0\',
  `debug` varchar(50) NOT NULL DEFAULT \'\',
  `message_id` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `headers` TEXT NOT NULL DEFAULT \'\',
  `custom_data` TEXT NOT NULL DEFAULT \'\',
  `html_content` TEXT NOT NULL DEFAULT \'\',
  `text_content` TEXT NOT NULL DEFAULT \'\',
  `attachments` TEXT NOT NULL DEFAULT \'\',
  `date_created` datetime NOT NULL,
  `date_updated` datetime NULL,
  `create_user_id` int(11) NOT NULL,
  `update_user_id` int(11) NULL,
  `create_ip_address` varchar(15) NOT NULL,
  `update_ip_address` varchar(15) NULL,
  PRIMARY KEY (`email_id`),
  INDEX (  `job_id` ,  `invoice_id` ,  `note_id` ,  `status` )
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;';
    }

    public function get_upgrade_sql(){
        $sql = '';

        $fields = get_fields('email');
        if(!isset($fields['job_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `job_id` int(11) NOT NULL DEFAULT \'0\' AFTER `customer_id`;';
        }
        if(!isset($fields['invoice_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `invoice_id` int(11) NOT NULL DEFAULT \'0\' AFTER `job_id`;';
        }
        if(!isset($fields['note_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `note_id` int(11) NOT NULL DEFAULT \'0\' AFTER `invoice_id`;';
        }
        if(!isset($fields['website_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `website_id` int(11) NOT NULL DEFAULT \'0\' AFTER `invoice_id`;';
        }
        if(!isset($fields['company_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `company_id` int(11) NOT NULL DEFAULT \'0\' AFTER `customer_id`;';
        }
        if(!isset($fields['file_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `file_id` int(11) NOT NULL DEFAULT \'0\' AFTER `newsletter_id`;';
        }
        if(!isset($fields['quote_id'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `quote_id` int(11) NOT NULL DEFAULT \'0\' AFTER `file_id`;';
        }
        if(!isset($fields['custom_data'])){
            $sql .= 'ALTER TABLE  `'._DB_PREFIX.'email` ADD  `custom_data` TEXT NOT NULL DEFAULT \'\' AFTER `headers`;';
        }

        self::add_table_index('email','customer_id');
        self::add_table_index('email','invoice_id');
        self::add_table_index('email','job_id');
        self::add_table_index('email','note_id');
        self::add_table_index('email','website_id');
        self::add_table_index('email','company_id');
        self::add_table_index('email','file_id');
        self::add_table_index('email','quote_id');
        self::add_table_index('email','status');

        return $sql;
    }


}

