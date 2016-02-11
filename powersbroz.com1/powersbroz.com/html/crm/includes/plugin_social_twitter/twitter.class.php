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

class ucm_twitter {

	public function __construct( ) {
		$this->reset();
	}

	private $accounts = array();

	private function reset() {
		$this->accounts = array();
	}


	public function get_accounts() {
		$this->accounts = get_multiple( 'social_twitter', array(), 'social_twitter_id' );
		return $this->accounts;
	}

	public static function format_person($data){
		$return = '';
		if($data && isset($data['screen_name'])){
			$return .= '<a href="http://twitter.com/'.$data['screen_name'].(isset($data['tweet_id']) && $data['tweet_id'] ? '/status/'.$data['tweet_id'] : '').'" target="_blank">';
		}
		if($data && isset($data['name'])){
			$return .= htmlspecialchars($data['name']);
		}
		if($data && isset($data['id'])){
			$return .= '</a>';
		}
		return $return;
	}

	private $all_messages = false;
	public function load_all_messages($search=array(),$order=array()){

		$sql = "SELECT m.*, mr.read_time FROM `"._DB_PREFIX."social_twitter_message` m ";
		$sql .= " LEFT JOIN `"._DB_PREFIX."social_twitter_message_read` mr ON m.social_twitter_message_id = mr.social_twitter_message_id";
		$sql .= " WHERE 1 ";
		if(isset($search['status']) && $search['status'] !== false){
			$sql .= " AND `status` = ".(int)$search['status'];
		}
		if(isset($search['social_message_id']) && $search['social_message_id'] !== false){
			$sql .= " AND `social_message_id` = ".(int)$search['social_message_id'];
		}
		if(isset($search['social_twitter_id']) && $search['social_twitter_id'] !== false){
			$sql .= " AND `social_twitter_id` = ".(int)$search['social_twitter_id'];
		}
		if(isset($search['generic']) && !empty($search['generic'])){
			$sql .= " AND `summary` LIKE '%".mysql_real_escape_string($search['generic'])."%'";
		}else{
			$sql .= " AND `type` != "._TWITTER_MESSAGE_TYPE_OTHERTWEET;
		}
		$sql .= " ORDER BY `message_time` DESC ";
		$this->all_messages = query($sql);
		return $this->all_messages;
	}
	public function get_next_message(){
		if(mysql_num_rows($this->all_messages)){
			return mysql_fetch_assoc($this->all_messages);
		}
		return false;
	}


	// used in our Wp "outbox" view showing combined messages.
	public function get_message_details($social_message_id){
		if(!$social_message_id)return array();
		$messages = $this->load_all_messages(array('social_message_id'=>$social_message_id));
		// we want data for our colum outputs in the WP table:
		/*'social_column_time'    => __( 'Date/Time', 'simple_social_inbox' ),
	    'social_column_social' => __( 'Social Accounts', 'simple_social_inbox' ),
		'social_column_summary'    => __( 'Summary', 'simple_social_inbox' ),
		'social_column_links'    => __( 'Link Clicks', 'simple_social_inbox' ),
		'social_column_stats'    => __( 'Stats', 'simple_social_inbox' ),
		'social_column_action'    => __( 'Action', 'simple_social_inbox' ),*/
		$data = array(
			'social_column_social' => '',
			'social_column_summary' => '',
			'social_column_links' => '',
		);
		$link_clicks = 0;
		foreach($messages as $message){
			$twitter_message = new ucm_twitter_message(false, $message['social_twitter_message_id']);
			$data['message'] = $twitter_message;
			$data['social_column_social'] .= '<div><img src="'.plugins_url('images/twitter-logo.png', dirname(__FILE__)).'" class="twitter_icon small"><a href="'.$twitter_message->get_link().'" target="_blank">'.htmlspecialchars( $twitter_message->get('twitter_account')->get( 'account_name' ) ) .'</a></div>';
			$data['social_column_summary'] .= '<div><img src="'.plugins_url('images/twitter-logo.png', dirname(__FILE__)).'" class="twitter_icon small"><a href="'.$twitter_message->get_link().'" target="_blank">'.htmlspecialchars( $twitter_message->get_summary() ) .'</a></div>';
			// how many link clicks does this one have?
			$sql = "SELECT count(*) AS `link_clicks` FROM ";
			$sql .= " `"._DB_PREFIX."social_twitter_message` m ";
			$sql .= " LEFT JOIN `"._DB_PREFIX."social_twitter_message_link` ml USING (social_twitter_message_id) ";
			$sql .= " LEFT JOIN `"._DB_PREFIX."social_twitter_message_link_click` lc USING (social_twitter_message_link_id) ";
			$sql .= " WHERE 1 ";
			$sql .= " AND m.social_twitter_message_id = ".(int)$message['social_twitter_message_id'];
			$sql .= " AND lc.social_twitter_message_link_id IS NOT NULL ";
			$sql .= " AND lc.user_agent NOT LIKE '%Google%' ";
			$sql .= " AND lc.user_agent NOT LIKE '%Yahoo%' ";
			$sql .= " AND lc.user_agent NOT LIKE '%Meta%' ";
			$sql .= " AND lc.user_agent NOT LIKE '%Slurp%' ";
			$sql .= " AND lc.user_agent NOT LIKE '%Bot%' ";
			$sql .= " AND lc.user_agent != 'Mozilla/5.0 ()' ";
			$res = qa1($sql);
			$link_clicks = $res && $res['link_clicks'] ? $res['link_clicks'] : 0;
			$data['social_column_links'] .= '<div><img src="'.plugins_url('images/twitter-logo.png', dirname(__FILE__)).'" class="twitter_icon small">'. $link_clicks  .'</div>';
		}
		if(count($messages) && $link_clicks > 0){
			//$data['social_column_links'] = '<div><img src="'.plugins_url('images/twitter-logo.png', dirname(__FILE__)).'" class="twitter_icon small">'. $link_clicks  .'</div>';
		}
		return $data;

	}

	public function get_unread_count($search=array()){
		if(!module_security::is_logged_in())return 0;
		$sql = "SELECT count(*) AS `unread` FROM `"._DB_PREFIX."social_twitter_message` m ";
		$sql .= " WHERE 1 ";
		$sql .= " AND m.social_twitter_message_id NOT IN (SELECT mr.social_twitter_message_id FROM `"._DB_PREFIX."social_twitter_message_read` mr WHERE mr.user_id = '".(int)module_security::get_loggedin_id()."' AND mr.social_twitter_message_id = m.social_twitter_message_id)";
		$sql .= " AND m.`status` = "._SOCIAL_MESSAGE_STATUS_UNANSWERED;
		if(isset($search['social_twitter_id']) && $search['social_twitter_id'] !== false){
			$sql .= " AND m.`social_twitter_id` = ".(int)$search['social_twitter_id'];
		}
		$sql .= " AND m.`type` != "._TWITTER_MESSAGE_TYPE_OTHERTWEET;
		$res = qa1($sql);
		return $res ? $res['unread'] : 0;
	}

	public function output_row($message, $settings){
		$twitter_message = new ucm_twitter_message(false, $message['social_twitter_message_id']);
		?>
		<tr class="<?php echo isset($settings['row_class']) ? $settings['row_class'] : '';?> twitter_message_row <?php echo !isset($message['read_time']) || !$message['read_time'] ? ' message_row_unread' : '';?>"
	        data-id="<?php echo (int) $message['social_twitter_message_id']; ?>"
	        data-social_twitter_id="<?php echo (int) $message['social_twitter_id']; ?>">
		    <td class="social_column_social">
			    <img src="<?php echo _BASE_HREF;?>includes/plugin_social_twitter/images/twitter-logo.png" class="twitter_icon">
			    <a href="<?php echo $twitter_message->get_link(); ?>"
		           target="_blank"><?php echo htmlspecialchars( $twitter_message->get('twitter_account')->get( 'account_name' ) ); ?></a> <br/>
			    <?php echo htmlspecialchars( $twitter_message->get_type_pretty() ); ?>
		    </td>
		    <td class="social_column_time"><?php echo print_date( $message['message_time'], true ); ?></td>
		    <td class="social_column_from">
			    <?php
		        // work out who this is from.
		        $from = $twitter_message->get_from();
			    ?>
			    <div class="social_from_holder social_twitter">
			    <div class="social_from_full">
				    <?php
					foreach($from as $id => $from_data){
						?>
						<div>
							<a href="//twitter.com/<?php echo htmlspecialchars($from_data['screen_name']);?>" target="_blank"><img src="<?php echo $from_data['image'];?>" class="social_from_picture"></a> <?php echo htmlspecialchars($from_data['screen_name']); ?>
						</div>
						<?php
					} ?>
			    </div>
		        <?php
		        reset($from);
		        $current = current($from);
		        echo '<a href="//twitter.com/'.htmlspecialchars($current['screen_name']).'" target="_blank">' . '<img src="'.$current['image'].'" class="social_from_picture"></a> ';
		        echo '<span class="social_from_count">';
		        if(count($from) > 1){
			        echo '+'.(count($from)-1);
		        }
		        echo '</span>';
		        ?>
			    </div>
		    </td>
		    <td class="social_column_summary">
			    <div class="twitter_message_summary<?php echo !isset($message['read_time']) || !$message['read_time'] ? ' unread' : '';?>"> <?php
				    echo $twitter_message->get_summary();
				    ?>
			    </div>
		    </td>
		    <!--<td></td>-->
			<td nowrap>
		        <?php if(module_social::can_i('view','Twitter Comments','Social','social')){ ?>

			        <a href="<?php echo module_social_twitter::link_open_twitter_message($message['social_twitter_id'],$message['social_twitter_message_id']);?>" class="socialtwitter_message_open social_modal btn btn-default btn-xs" data-modal-title="<?php echo _l('Tweet');?>"><?php _e( 'Open' );?></a>

		        <?php } ?>
		        <?php if(module_social::can_i('edit','Twitter Comments','Social','social')){ ?>
				    <?php if($twitter_message->get('status') == _SOCIAL_MESSAGE_STATUS_ANSWERED){  ?>
					    <a href="#" class="socialtwitter_message_action btn btn-default btn-xs"
					       data-action="set-unanswered" data-id="<?php echo (int)$twitter_message->get('social_twitter_message_id');?>" data-social_twitter_id="<?php echo (int)$twitter_message->get('social_twitter_id');?>"><?php _e( 'Un-Archive' ); ?></a>
				    <?php }else{ ?>
					    <a href="#" class="socialtwitter_message_action btn btn-default btn-xs"
					       data-action="set-answered" data-id="<?php echo (int)$twitter_message->get('social_twitter_message_id');?>" data-social_twitter_id="<?php echo (int)$twitter_message->get('social_twitter_id');?>"><?php _e( 'Archive' ); ?></a>
				    <?php } ?>
		        <?php } ?>
		    </td>
	    </tr>
		<?php
	}

	public function init_js(){
		?>
		    ucm.social.twitter.api_url = '<?php echo module_social_twitter::link_social_ajax_functions();?>';
		    ucm.social.twitter.init();
		<?php
	}

	public function handle_process($process, $options = array()){
		switch($process){
			case 'send_social_message':
				check_admin_referer( 'social_send-message' );
				$message_count = 0;
				if(isset($options['social_message_id']) && (int)$options['social_message_id'] > 0 && isset($_POST['twitter_message']) && !empty($_POST['twitter_message'])){
					// we have a social message id, ready to send!
					// which twitter accounts are we sending too?
					$twitter_accounts = isset($_POST['compose_twitter_id']) && is_array($_POST['compose_twitter_id']) ? $_POST['compose_twitter_id'] : array();
					foreach($twitter_accounts as $twitter_account_id => $tf){
						if(!$tf)continue; // shoulnd't happen, as checkbox shouldn't post.
						$twitter_account = new ucm_twitter_account($twitter_account_id);
						if($twitter_account->get('social_twitter_id') == $twitter_account_id){
							// good to go! send us a message!


							$twitter_message = new ucm_twitter_message($twitter_account, false);
						    $twitter_message->create_new();
						    $twitter_message->update('social_twitter_id',$twitter_account->get('social_twitter_id'));
						    $twitter_message->update('social_message_id',$options['social_message_id']);
						    $twitter_message->update('summary',isset($_POST['twitter_message']) ? $_POST['twitter_message'] : '');
							if(isset($_POST['track_links']) && $_POST['track_links']){
								$twitter_message->parse_links();
							}
						    $twitter_message->update('type','pending');
						    $twitter_message->update('data',json_encode($_POST));
						    $twitter_message->update('user_id',get_current_user_id());
						    // do we send this one now? or schedule it later.
						    $twitter_message->update('status',_SOCIAL_MESSAGE_STATUS_PENDINGSEND);
						    if(isset($options['send_time']) && !empty($options['send_time'])){
							    // schedule for sending at a different time (now or in the past)
							    $twitter_message->update('message_time',$options['send_time']);
						    }else{
							    // send it now.
							    $twitter_message->update('message_time',0);
						    }
						    if(isset($_FILES['picture']['tmp_name']) && is_uploaded_file($_FILES['picture']['tmp_name'])){
							    $twitter_message->add_attachment($_FILES['picture']['tmp_name']);
						    }
							$now = time();
							if(!$twitter_message->get('message_time') || $twitter_message->get('message_time') <= $now){
								// send now! otherwise we wait for cron job..
								if($twitter_message->send_queued(isset($_POST['debug']) && $_POST['debug'])){
									$message_count ++;
								}
							}else{
						        $message_count ++;
								if(isset($_POST['debug']) && $_POST['debug']){
									echo "Message will be sent in cron job after ".print_date($twitter_message->get('message_time'),true);
								}
							}
						}
					}
				}
				return $message_count;
				break;
			case 'save_twitter_settings':
				check_admin_referer( 'save-twitter-settings' );
				if(isset($_POST['twitter_app_api_key'])){
					$this->update('api_key',$_POST['twitter_app_api_key']);
				}
				if(isset($_POST['twitter_app_api_secret'])){
					$this->update('api_secret',$_POST['twitter_app_api_secret']);
				}
				break;
			case 'save_twitter':
				$social_twitter_id = isset($_REQUEST['social_twitter_id']) ? (int)$_REQUEST['social_twitter_id'] : 0;
				check_admin_referer( 'save-twitter'.$social_twitter_id );
				$twitter = new ucm_twitter_account($social_twitter_id);
		        if(isset($_POST['butt_delete'])){
	                $twitter->delete();
			        $redirect = 'admin.php?page=simple_social_inbox_twitter_settings';
		        }else{
			        $twitter->save_data($_POST);
			        $social_twitter_id = $twitter->get('social_twitter_id');
			        if(isset($_POST['butt_save_reconnect'])){
				        $redirect = $twitter->link_connect();
			        }else {
				        $redirect = $twitter->link_edit();
			        }
		        }
				header("Location: $redirect");
				exit;

				break;
		}
	}

	public function handle_ajax($action, $simple_social_inbox_wp){
		switch($action){
			case 'send-message-reply':
				if (!headers_sent())header('Content-type: text/javascript');
				if(isset($_REQUEST['social_twitter_id']) && !empty($_REQUEST['social_twitter_id']) && isset($_REQUEST['id']) && (int)$_REQUEST['id'] > 0) {
					$ucm_twitter = new ucm_twitter_account($_REQUEST['social_twitter_id']);
					if($ucm_twitter->get('social_twitter_id') == $_REQUEST['social_twitter_id']){
						$ucm_twitter_message = new ucm_twitter_message( $ucm_twitter, $_REQUEST['id'] );
						if($ucm_twitter_message->get('social_twitter_message_id') == $_REQUEST['id']) {
							$return  = array();
							$message = isset( $_POST['message'] ) && $_POST['message'] ? $_POST['message'] : '';
							$debug   = isset( $_POST['debug'] ) && $_POST['debug'] ? $_POST['debug'] : false;
							if ( $message ) {
								ob_start();
								//$twitter_message->send_reply( $message, $debug );
								$new_twitter_message = new ucm_twitter_message( $ucm_twitter, false );
								$new_twitter_message->create_new();
								$new_twitter_message->update( 'reply_to_id', $ucm_twitter_message->get( 'social_twitter_message_id' ) );
								$new_twitter_message->update( 'social_twitter_id', $ucm_twitter->get( 'social_twitter_id' ) );
								$new_twitter_message->update( 'summary', $message );
								//$new_twitter_message->update('type','pending');
								$new_twitter_message->update( 'data', json_encode( $_POST ) );
								$new_twitter_message->update( 'user_id', get_current_user_id() );
								// do we send this one now? or schedule it later.
								$new_twitter_message->update( 'status', _SOCIAL_MESSAGE_STATUS_PENDINGSEND );
								if ( isset( $_FILES['picture']['tmp_name'] ) && is_uploaded_file( $_FILES['picture']['tmp_name'] ) ) {
									$new_twitter_message->add_attachment( $_FILES['picture']['tmp_name'] );
								}
								$worked            = $new_twitter_message->send_queued( isset( $_POST['debug'] ) && $_POST['debug'] );
								$return['message'] = ob_get_clean();
								if ( $debug ) {
									// just return message
								} else if ( $worked ) {
									// success, redicet!
									//set_message( _l( 'Message sent and conversation archived.' ) );
									//$return['redirect'] = module_social_twitter::link_open_message_view( $social_twitter_id );
									$return['redirect'] = 'admin.php?page=simple_social_inbox_main';
									//$return['success'] = 1;
								} else {
									// failed, no debug, force debug and show error.
								}
							}
							echo json_encode( $return );
						}
					}
				}

				break;
			case 'modal':
				if(isset($_REQUEST['socialtwittermessageid']) && (int)$_REQUEST['socialtwittermessageid'] > 0) {
					$ucm_twitter_message = new ucm_twitter_message( false,  $_REQUEST['socialtwittermessageid'] );
					if($ucm_twitter_message->get('social_twitter_message_id') == $_REQUEST['socialtwittermessageid']){

						$social_twitter_id = $ucm_twitter_message->get('twitter_account')->get('social_twitter_id');
						$social_twitter_message_id = $ucm_twitter_message->get('social_twitter_message_id');
						include( trailingslashit( $simple_social_inbox_wp->dir ) . 'pages/twitter_message.php');
					}

				}
				break;
			case 'set-answered':
				if (!headers_sent())header('Content-type: text/javascript');
				if(isset($_REQUEST['social_twitter_message_id']) && (int)$_REQUEST['social_twitter_message_id'] > 0){
					$ucm_twitter_message = new ucm_twitter_message(false, $_REQUEST['social_twitter_message_id']);
					if($ucm_twitter_message->get('social_twitter_message_id') == $_REQUEST['social_twitter_message_id']){
						$ucm_twitter_message->update('status',_SOCIAL_MESSAGE_STATUS_ANSWERED);
						?>
						jQuery('.socialtwitter_message_action[data-id=<?php echo (int)$ucm_twitter_message->get('social_twitter_message_id'); ?>]').parents('tr').first().hide();
						<?php
						// if this is a direct message, we also archive all other messages in it.
						if($ucm_twitter_message->get('type') == _TWITTER_MESSAGE_TYPE_DIRECT){
							$from = preg_replace('#[^0-9]#','',$ucm_twitter_message->get('twitter_from_id'));
							$to = preg_replace('#[^0-9]#','',$ucm_twitter_message->get('twitter_to_id'));
							if($from && $to) {
								$sql      = "SELECT * FROM `" . _SIMPLE_SOCIAL_DB_PREFIX . "social_twitter_message` WHERE `type` = " . _TWITTER_MESSAGE_TYPE_DIRECT . " AND `status` = " . (int) _SOCIAL_MESSAGE_STATUS_UNANSWERED . " AND social_twitter_id = " . (int) $ucm_twitter_message->get('twitter_account')->get( 'social_twitter_id' ) . " AND ( (`twitter_from_id` = '$from' AND `twitter_to_id` = '$to') OR (`twitter_from_id` = '$to' AND `twitter_to_id` = '$from') ) ";
								global $wpdb;
								$others = $wpdb->get_results($sql, ARRAY_A);
								if(count($others)){
									foreach($others as $other_message){
										$ucm_twitter_message = new ucm_twitter_message(false, $other_message['social_twitter_message_id']);
										if($ucm_twitter_message->get('social_twitter_message_id') == $other_message['social_twitter_message_id']) {
											$ucm_twitter_message->update( 'status', _SOCIAL_MESSAGE_STATUS_ANSWERED );
											?>
											jQuery('.socialtwitter_message_action[data-id=<?php echo (int) $ucm_twitter_message->get( 'social_twitter_message_id' ); ?>]').parents('tr').first().hide();
										<?php
										}
									}
								}
							}
						}
					}
				}
				break;
			case 'set-unanswered':
				if (!headers_sent())header('Content-type: text/javascript');
				if(isset($_REQUEST['social_twitter_message_id']) && (int)$_REQUEST['social_twitter_message_id'] > 0){
					$ucm_twitter_message = new ucm_twitter_message(false, $_REQUEST['social_twitter_message_id']);
					if($ucm_twitter_message->get('social_twitter_message_id') == $_REQUEST['social_twitter_message_id']){
						$ucm_twitter_message->update('status',_SOCIAL_MESSAGE_STATUS_UNANSWERED);
						?>
						jQuery('.socialtwitter_message_action[data-id=<?php echo (int)$ucm_twitter_message->get('social_twitter_message_id'); ?>]').parents('tr').first().hide();
						<?php
					}
				}
				break;
		}
		return false;
	}

}

class ucm_twitter_account{

	public function __construct($social_twitter_id){
		$this->load($social_twitter_id);
	}

	private $social_twitter_id = false; // the current user id in our system.
    private $details = array();

	private function reset(){
		$this->social_twitter_id = false;
		self::$api = false;
		$this->details = array();
		$fields = get_fields('social_twitter');
		foreach($fields as $field_id => $field_data){
			$this->{$field_id} = '';
		}
	}

	public function create_new(){
		$this->reset();
		$this->social_twitter_id = update_insert('social_twitter_id',false,'social_twitter',array());
		$this->load($this->social_twitter_id);
	}

    public function load($social_twitter_id = false){
	    if(!$social_twitter_id)$social_twitter_id = $this->social_twitter_id;
	    $this->reset();
	    $this->social_twitter_id = $social_twitter_id;
        if($this->social_twitter_id){
            $this->details = get_single('social_twitter','social_twitter_id',$this->social_twitter_id);
	        if(!is_array($this->details) || $this->details['social_twitter_id'] != $this->social_twitter_id){
		        $this->reset();
		        return false;
	        }
        }
        foreach($this->details as $key=>$val){
            $this->{$key} = $val;
        }
        return $this->social_twitter_id;
    }

	public function get_messages($search=array()){
		$twitter = new ucm_twitter();
		$search['social_twitter_id'] = $this->social_twitter_id;
		return $twitter->load_all_messages($search);
		//return get_multiple('social_twitter_message',$search,'social_twitter_message_id','exact','message_time DESC');
	}

	public function get($field){
		return isset($this->{$field}) ? $this->{$field} : false;
	}
	public function get_picture(){
		$data = @json_decode($this->get('user_data'),true);
		return $data && isset($data['profile_image_url_https']) && !empty($data['profile_image_url_https']) ? $data['profile_image_url_https'] : false;
	}

	public function save_data($post_data){
		if(!$this->get('social_twitter_id')){
			$this->create_new();
		}
		if(is_array($post_data)){
			$fields = get_fields('social_twitter');
			foreach($post_data as $key=>$val){
				// hack to get unchecked checkboxes in:
				if(strpos($key,'default_')!==false){
					$newkey = str_replace('default_','',$key);
					if(!isset($post_data[$newkey])){
						$key = $newkey;
						$val = 0;
					}
				}
				if(isset($fields[$key])){
					$this->update($key,$val);
				}
			}
		}
		$this->load();
		return $this->get('social_twitter_id');
	}
    public function update($field,$value){
	    // what fields to we allow? or not allow?
	    if(in_array($field,array('social_twitter_id')))return;
        if($this->social_twitter_id){
            $this->{$field} = $value;
            update_insert('social_twitter_id',$this->social_twitter_id,'social_twitter',array(
	            $field => $value,
            ));
        }
    }
	public function delete(){
		if($this->social_twitter_id) {
			// delete all the messages for this twitter account.
			$messages = get_multiple('social_twitter_message',array(
				'social_twitter_id' => $this->social_twitter_id,
			),'social_twitter_message_id');
			foreach($messages as $message){
				if($message && isset($message['social_twitter_id']) && $message['social_twitter_id'] == $this->social_twitter_id){
					delete_from_db( 'social_twitter_message', 'social_twitter_message_id', $message['social_twitter_message_id'] );
					delete_from_db( 'social_twitter_message_link', 'social_twitter_message_id', $message['social_twitter_message_id'] );
					delete_from_db( 'social_twitter_message_read', 'social_twitter_message_id', $message['social_twitter_message_id'] );
				}
			}
			delete_from_db( 'social_twitter', 'social_twitter_id', $this->social_twitter_id );
		}
	}

	public function is_active(){
		// is there a 'last_checked' date?
		if(!$this->get('last_checked')){
			return false; // never checked this account, not active yet.
		}else{
			// do we have a token?
			if($this->get('user_secret')){
				// assume we have access, we remove the token if we get a twitter failure at any point.
				// todo: remove on failure
				return true;
			}
		}
		return false;
	}

	private static $api = false;
	public function get_api(){
		if(!self::$api){
			require_once 'includes/plugin_social_twitter/includes/tmhOAuth.php';
			self::$api = new tmhOAuth(array(
				'consumer_key'    => module_config::c('social_twitter_api_key',''),
				'consumer_secret' => module_config::c('social_twitter_api_secret',''),
				'token'           => $this->get('user_key'),
				'secret'          => $this->get('user_secret'),
				'user_agent'      => 'UCM Twitter 0.1',
			));
		}
		return self::$api;
	}

	public function run_cron($debug = false){
		// find all messages that haven't been sent yet.
		$messages = $this->get_messages(array(
			'status' => _SOCIAL_MESSAGE_STATUS_PENDINGSEND,
		));
		$now = time();
		foreach($messages as $message){
			if(isset($message['message_time']) && $message['message_time'] < $now){
				$ucm_twitter_message = new ucm_twitter_message($this, $message['social_twitter_message_id']);
				$ucm_twitter_message->send_queued($debug);
			}
		}
	}
	public function import_data($debug = false){
		// pull in data from the twitter api using our user_secret and user_key and api keys.

		$api_key = module_config::c('social_twitter_api_key','');
		if($debug)echo "<br><br>\n\nConnecting to twitter via App API Key: ".$api_key."<br>\n";


		$tmhOAuth = $this->get_api();

		$code = $tmhOAuth->user_request(array(
			'url' => $tmhOAuth->url('1.1/account/verify_credentials')
		));

		$latest_user_data = false;

		$latest_search_values = array(
			'dm_sent' => 1,
			'dm_received' => 1,
			'mentions' => 1,
			'timeline' => 1,
			'retweets' => 1,
		);
		$searches_data = @json_decode($this->get('searches'),true);
		foreach($latest_search_values as $key=>$val){
			if(isset($searches_data[$key]))$latest_search_values[$key] = $searches_data[$key];
		}

		if ($code == 200){
			$data = json_decode($tmhOAuth->response['response'], true);
			if (isset($data['status'])) {
				$this->update('twitter_data',json_encode($data));

				if($debug) echo " Hello @".htmlspecialchars($data['screen_name']).", importing information...<br>\n";

				if($this->get('import_dm')){
					$timestart = microtime(true);
					if($debug) echo " Importing sent direct messages (after tweet ".$latest_search_values['dm_sent'].")<br>\n";
					$code_sent = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url('1.1/direct_messages/sent'),
						'params' => array(
					      'since_id' => $latest_search_values['dm_sent'],
					      'count' => module_config::c('social_twitter_search_count',20),
					    ),
					));
					if ($code_sent == 200){
						$dms_sent = json_decode($tmhOAuth->response['response'], true);
						foreach($dms_sent as $dm){
							if(isset($dm['id_str'])){
								if($debug) echo ' - importing DM ID: '.$dm['id_str']."<br>\n";
								$latest_search_values['dm_sent'] = max($latest_search_values['dm_sent'], $dm['id_str']);
								// does this exist in the database?
								$exists = get_single('social_twitter_message',array('social_twitter_id','twitter_message_id'),array($this->social_twitter_id,$dm['id_str']));
								// update/insert based on this item.
								update_insert('social_twitter_message_id',$exists && isset($exists['social_twitter_message_id']) ? $exists['social_twitter_message_id'] : false, 'social_twitter_message', array(
									'social_twitter_id' => $this->social_twitter_id,
									'twitter_message_id' => $dm['id_str'],
									'twitter_from_id' => isset($dm['sender_id_str']) ? $dm['sender_id_str'] : '',
									'twitter_from_name' => isset($dm['sender_screen_name']) ? $dm['sender_screen_name'] : '',
									'twitter_to_id' => isset($dm['recipient_id_str']) ? $dm['recipient_id_str'] : '',
									'twitter_to_name' => isset($dm['recipient_screen_name']) ? $dm['recipient_screen_name'] : '',
									'summary' => isset($dm['text']) ? $dm['text'] : '', //todo: swap out shortened urls in 'entities' array.
									'message_time' => isset($dm['created_at']) ? strtotime($dm['created_at']) : '',
									'data' => json_encode($dm),
									'type' => _TWITTER_MESSAGE_TYPE_DIRECT,
								));

								if(isset($dm['sender']) && isset($dm['sender_id_str']) && $dm['sender_id_str'] == $this->get('twitter_id')){
									$latest_user_data = $dm['sender'];
								}
							}
						}
					}else{
						echo 'Failed to access sent direct messages: '.$tmhOAuth->response['response'];
					}
					if($debug) echo " - took: ".(microtime(true)-$timestart) . " <br>\n";

					$timestart = microtime(true);
					if($debug) echo " Importing received direct messages (after tweet ".$latest_search_values['dm_received'].")<br>\n";
					$code_received = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url('1.1/direct_messages'),
						'params' => array(
					      'since_id' => $latest_search_values['dm_received'],
					      'count' => module_config::c('social_twitter_search_count',20),
					    ),
					));
					if ($code_received == 200){
						$dms_received = json_decode($tmhOAuth->response['response'], true);
						foreach($dms_received as $dm){
							if(isset($dm['id_str'])){
								if($debug) echo ' - importing DM ID: '.$dm['id_str']."<br>\n";
								$latest_search_values['dm_received'] = max($latest_search_values['dm_received'], $dm['id_str']);
								// does this exist in the database?
								$exists = get_single('social_twitter_message',array('social_twitter_id','twitter_message_id'),array($this->social_twitter_id,$dm['id_str']));
								// update/insert based on this item.
								update_insert('social_twitter_message_id',$exists && isset($exists['social_twitter_message_id']) ? $exists['social_twitter_message_id'] : false, 'social_twitter_message', array(
									'social_twitter_id' => $this->social_twitter_id,
									'twitter_message_id' => $dm['id_str'],
									'twitter_from_id' => isset($dm['sender_id_str']) ? $dm['sender_id_str'] : '',
									'twitter_from_name' => isset($dm['sender_screen_name']) ? $dm['sender_screen_name'] : '',
									'twitter_to_id' => isset($dm['recipient_id_str']) ? $dm['recipient_id_str'] : '',
									'twitter_to_name' => isset($dm['recipient_screen_name']) ? $dm['recipient_screen_name'] : '',
									'summary' => isset($dm['text']) ? $dm['text'] : '', //todo: swap out shortened urls in 'entities' array.
									'message_time' => isset($dm['created_at']) ? strtotime($dm['created_at']) : '',
									'data' => json_encode($dm),
									'type' => _TWITTER_MESSAGE_TYPE_DIRECT,
								));
								if(isset($dm['recipient']) && isset($dm['recipient_id_str']) && $dm['recipient_id_str'] == $this->get('twitter_id')){
									$latest_user_data = $dm['recipient'];
								}
							}
						}
					}else{
						echo 'Failed to access received direct messages: '.$tmhOAuth->response['response'];
					}
					if($latest_user_data){
						$this->update('user_data',json_encode($latest_user_data));
					}
					if($debug) echo " - took: ".(microtime(true)-$timestart) . " <br>\n";

				}
				if($this->get('import_mentions')){
					$timestart = microtime(true);
					if($debug) echo " Importing mentions (after tweet ".$latest_search_values['mentions'].")<br>\n";
					$code_sent = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url('1.1/statuses/mentions_timeline'),
						'params' => array(
					      'since_id' => $latest_search_values['mentions'],
					      'count' => module_config::c('social_twitter_search_count',20),
					    ),
					));
					if ($code_sent == 200){
						$mentions = json_decode($tmhOAuth->response['response'], true);
						foreach($mentions as $mention){
							if(isset($mention['id_str'])){
								$latest_search_values['mentions'] = max($latest_search_values['mentions'], $mention['id_str']);
								$new_tweet = new ucm_twitter_message($this, false);
								$new_tweet->load_by_twitter_id($mention['id_str'], $mention, _TWITTER_MESSAGE_TYPE_MENTION);
								if($debug) echo ' - importing mention ID: '.$mention['id_str']."<br>\n";
								/*// does this exist in the database?
								$exists = get_single('social_twitter_message',array('social_twitter_id','twitter_message_id'),array($this->social_twitter_id,$mention['id_str']));
								// update/insert based on this item.
								update_insert('social_twitter_message_id',$exists && isset($exists['social_twitter_message_id']) ? $exists['social_twitter_message_id'] : false, 'social_twitter_message', array(
									'social_twitter_id' => $this->social_twitter_id,
									'twitter_message_id' => $mention['id_str'],
									'twitter_from_id' => isset($mention['user']['id_str']) ? $mention['user']['id_str'] : '',
									'twitter_from_name' => isset($mention['user']['screen_name']) ? $mention['user']['screen_name'] : '',
									'twitter_to_id' => '',
									'twitter_to_name' => '',
									'summary' => isset($mention['text']) ? $mention['text'] : '', //todo: swap out shortened urls in 'entities' array.
									'message_time' => isset($mention['created_at']) ? strtotime($mention['created_at']) : '',
									'data' => json_encode($mention),
									'type' => _TWITTER_MESSAGE_TYPE_MENTION,
								));*/
							}
						}
					}else{
						echo 'Failed to access mentions: '.$tmhOAuth->response['response'];
					}
					if($debug) echo " - took: ".(microtime(true)-$timestart) . " <br>\n";
				}
				if($this->get('import_tweets')){
					$timestart = microtime(true);
					if($debug) echo " Importing my own tweets (after tweet ".$latest_search_values['timeline'].")<br>\n";
					$code_sent = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url('1.1/statuses/user_timeline'),
						'params' => array(
					      'since_id' => $latest_search_values['timeline'],
					      'count' => module_config::c('social_twitter_search_count',20),
					      'screen_name' => $this->get('twitter_name'),
					    ),
					));
					if ($code_sent == 200){
						$tweets = json_decode($tmhOAuth->response['response'], true);
						foreach($tweets as $tweet){
							if(isset($tweet['id_str'])){
								$latest_search_values['timeline'] = max($latest_search_values['timeline'], $tweet['id_str']);

								$new_tweet = new ucm_twitter_message($this, false);
								$new_tweet->load_by_twitter_id($tweet['id_str'], $tweet);
								if($debug) echo ' - importing tweet ID: '.$tweet['id_str']."<br>\n";

								/*// does this exist in the database (it really shouldn't because weshould be doing 'since' search correctly)
								$exists = get_single('social_twitter_message',array('social_twitter_id','twitter_message_id'),array($this->social_twitter_id,$tweet['id_str']));
								// update/insert based on this item.
								update_insert('social_twitter_message_id',$exists && isset($exists['social_twitter_message_id']) ? $exists['social_twitter_message_id'] : false, 'social_twitter_message', array(
									'social_twitter_id' => $this->social_twitter_id,
									'reply_to_id' => $reply_to_id,
									'twitter_message_id' => $tweet['id_str'],
									'twitter_from_id' => isset($tweet['user']['id_str']) ? $tweet['user']['id_str'] : '',
									'twitter_from_name' => isset($tweet['user']['screen_name']) ? $tweet['user']['screen_name'] : '',
									'twitter_to_id' => isset($tweet['in_reply_to_user_id_str']) ? $tweet['in_reply_to_user_id_str'] : '',
									'twitter_to_name' => isset($tweet['in_reply_to_screen_name']) ? $tweet['in_reply_to_screen_name'] : '',
									'summary' => isset($tweet['text']) ? $tweet['text'] : '', //todo: swap out shortened urls in 'entities' array.
									'message_time' => isset($tweet['created_at']) ? strtotime($tweet['created_at']) : '',
									'data' => json_encode($tweet),
									'type' => $type,
								));*/
							}
						}
					}else{
						echo 'Failed to access mentions: '.$tmhOAuth->response['response'];
					}
					if($debug) echo " - took: ".(microtime(true)-$timestart) . " <br>\n";

					$timestart = microtime(true);
					if($debug) echo " Importing retweets of my tweets (after tweet ".$latest_search_values['retweets'].")<br>\n";
					$code_sent = $tmhOAuth->user_request(array(
						'url' => $tmhOAuth->url('1.1/statuses/retweets_of_me'),
						'params' => array(
					      'since_id' => $latest_search_values['retweets'],
					      'count' => module_config::c('social_twitter_search_count',20),
					    ),
					));
					// only query the list of retweets for tweets newer than 2 weeks.
					$time_limit = strtotime('-2 weeks');
					if ($code_sent == 200){
						$tweets = json_decode($tmhOAuth->response['response'], true);
						foreach($tweets as $tweet){
							if(isset($tweet['id_str'])){
								$tweet_time = strtotime($tweet['created_at']);
								if($tweet_time < $time_limit){
									$latest_search_values['retweets'] = max($latest_search_values['retweets'], $tweet['id_str']);
								}

								$new_tweet = new ucm_twitter_message($this, false);
								// refresh these so the retweet_count and favorite_count get stored in the database again.
								$new_tweet->load_by_twitter_id($tweet['id_str'], $tweet, false, $debug, true);
								if($debug) echo ' - importing tweet ID: '.$tweet['id_str']."<br>\n";

								/*// does this exist in the database (it really shouldn't because weshould be doing 'since' search correctly)
								$exists = get_single('social_twitter_message',array('social_twitter_id','twitter_message_id'),array($this->social_twitter_id,$tweet['id_str']));
								// update/insert based on this item.
								update_insert('social_twitter_message_id',$exists && isset($exists['social_twitter_message_id']) ? $exists['social_twitter_message_id'] : false, 'social_twitter_message', array(
									'social_twitter_id' => $this->social_twitter_id,
									'reply_to_id' => $reply_to_id,
									'twitter_message_id' => $tweet['id_str'],
									'twitter_from_id' => isset($tweet['user']['id_str']) ? $tweet['user']['id_str'] : '',
									'twitter_from_name' => isset($tweet['user']['screen_name']) ? $tweet['user']['screen_name'] : '',
									'twitter_to_id' => isset($tweet['in_reply_to_user_id_str']) ? $tweet['in_reply_to_user_id_str'] : '',
									'twitter_to_name' => isset($tweet['in_reply_to_screen_name']) ? $tweet['in_reply_to_screen_name'] : '',
									'summary' => isset($tweet['text']) ? $tweet['text'] : '', //todo: swap out shortened urls in 'entities' array.
									'message_time' => isset($tweet['created_at']) ? strtotime($tweet['created_at']) : '',
									'data' => json_encode($tweet),
									'type' => $type,
								));*/
							}
						}
					}else{
						echo 'Failed to access mentions: '.$tmhOAuth->response['response'];
					}
					if($debug) echo " - took: ".(microtime(true)-$timestart) . " <br>\n";
				}

				foreach($latest_search_values as $key=>$val){
					$searches_data[$key] = $val;
				}
				$this->update('searches',json_encode($searches_data));

			}else{
				if($debug)echo 'Twitter failed to check status, please try connecting to twitter again from settings: '.$tmhOAuth->response['response'];
				return;
			}
		}else{
			if($debug)echo 'Twitter failed to check authentication, please try connecting to twitter again from settings: '.$tmhOAuth->response['response'];
			return;
		}

	}

	public function get_reply_tweet($tweet_status_id){
		$reply_to_id = false;
		if(empty($tweet_status_id))return $reply_to_id;

		$tweet = new ucm_twitter_message($this, false);
		$tweet->load_by_twitter_id($tweet_status_id);
		return $tweet->get('social_twitter_message_id');
		/*
		// check if it exists in the database already:
		$exists = get_single('social_twitter_message','twitter_message_id',$tweet_status_id);
		if(!$exists || $exists['twitter_message_id'] != $tweet_status_id){
			// grab from api and save in database.
			$tmhOAuth = $this->get_api();
			$twitter_code = $tmhOAuth->user_request(array(
				'url' => $tmhOAuth->url('1.1/statuses/show'),
				'params' => array(
			      'id' => $tweet_status_id,
			    ),
			));
			if ($twitter_code == 200) {
				$tweet = json_decode( $tmhOAuth->response['response'], true );
				//echo 'reply';print_r($tweet);exit;
				$new_reply_to_id = 0;
				if(isset($tweet['in_reply_to_status_id_str']) && !empty($tweet['in_reply_to_status_id_str'])){
					// import / find reply tweeet from db or api:
					$new_reply_to_id = $this->get_reply_tweet($tweet['in_reply_to_status_id_str']);
				}
				$type = _TWITTER_MESSAGE_TYPE_OTHERTWEET;
				if(isset($tweet['in_reply_to_user_id_str']) && $tweet['in_reply_to_user_id_str'] == $this->get('twitter_id')){
					$type = _TWITTER_MESSAGE_TYPE_MENTION;
				}else if(isset($tweet['user']['id_str']) && $tweet['user']['id_str'] == $this->get('twitter_id')){
					$type = _TWITTER_MESSAGE_TYPE_MYTWEET;
				}
				update_insert('social_twitter_message_id',false, 'social_twitter_message', array(
					'social_twitter_id' => $this->social_twitter_id,
					'reply_to_id' => $new_reply_to_id,
					'twitter_message_id' => $tweet['id_str'],
					'twitter_from_id' => isset($tweet['user']['id_str']) ? $tweet['user']['id_str'] : '',
					'twitter_from_name' => isset($tweet['user']['screen_name']) ? $tweet['user']['screen_name'] : '',
					'twitter_to_id' => isset($tweet['in_reply_to_user_id_str']) ? $tweet['in_reply_to_user_id_str'] : '',
					'twitter_to_name' => isset($tweet['in_reply_to_screen_name']) ? $tweet['in_reply_to_screen_name'] : '',
					'summary' => isset($tweet['text']) ? $tweet['text'] : '', //todo: swap out shortened urls in 'entities' array.
					'message_time' => isset($tweet['created_at']) ? strtotime($tweet['created_at']) : '',
					'data' => json_encode($tweet),
					'type' =>$type,
				));
			}
		}else{
			$reply_to_id = $exists['social_twitter_message_id'];
		}

		return $reply_to_id;*/

	}


	/**
	 * Links for wordpress
	 */
	public function link_connect(){
		return 'admin.php?page=simple_social_inbox_twitter_settings&do_twitter_connect=1&social_twitter_id='.$this->get('social_twitter_id');
	}
	public function link_edit(){
		return 'admin.php?page=simple_social_inbox_twitter_settings&social_twitter_id='.$this->get('social_twitter_id');
	}
	public function link_refresh(){
		return 'admin.php?page=simple_social_inbox_twitter_settings&do_twitter_refresh=1&social_twitter_id='.$this->get('social_twitter_id');
	}
	public function link_new_message(){
		return 'admin.php?page=simple_social_inbox_main&social_twitter_id='.$this->get('social_twitter_id').'&social_twitter_message_id=new';
	}

}




class ucm_twitter_message{

	public function __construct($twitter_account = false, $social_twitter_message_id = false){
		$this->twitter_account = $twitter_account;
		$this->load($social_twitter_message_id);
	}

	/* @var $twitter_account ucm_twitter_account */
	private $twitter_account = false;
	private $social_twitter_message_id = false; // the current user id in our system.
    private $details = array();

	private function reset(){
		$this->social_twitter_message_id = false;
		$this->details = array();
	}

	public function create_new(){
		$this->reset();
		$this->social_twitter_message_id = update_insert('social_twitter_message_id',false,'social_twitter_message',array());
		$this->load($this->social_twitter_message_id);
	}

	public function load_by_twitter_id($twitter_id, $tweet=false, $type = false, $debug = false, $force = false){

		if(!$this->twitter_account || !$this->twitter_account->get('social_twitter_id')){
			return false;
		}
		$this->social_twitter_message_id = 0;
		$exists = get_single('social_twitter_message',array('social_twitter_id','twitter_message_id'),array($this->twitter_account->get('social_twitter_id'),$twitter_id));
		if($exists && $exists['twitter_message_id'] == $twitter_id){
			$this->load($exists['social_twitter_message_id']);
			if($this->social_twitter_message_id != $exists['social_twitter_message_id']){
				$this->reset(); // shouldn't happen.
			}
			if(!$force && $this->social_twitter_message_id == $exists['social_twitter_message_id']){
				return $this->social_twitter_message_id;
			}
		}
		if(!$tweet || $force){
			$tmhOAuth = $this->twitter_account->get_api();
			if($type == _TWITTER_MESSAGE_TYPE_DIRECT){
				$twitter_code = $tmhOAuth->user_request( array(
					'url'    => $tmhOAuth->url( '1.1/direct_messages/show' ),
					'params' => array(
						'id' => $twitter_id,
					),
				) );
			}else {
				$twitter_code = $tmhOAuth->user_request( array(
					'url'    => $tmhOAuth->url( '1.1/statuses/show' ),
					'params' => array(
						'id' => $twitter_id,
					),
				) );
			}
			if ($twitter_code == 200) {
				$tweet = json_decode( $tmhOAuth->response['response'], true );
			}
		}
		if($tweet){
			//echo 'reply';print_r($tweet);exit;
			$new_reply_to_id = 0;
			if(isset($tweet['in_reply_to_status_id_str']) && !empty($tweet['in_reply_to_status_id_str'])){
				// import / find reply tweeet from db or api:
				$new_reply_to_id = $this->twitter_account->get_reply_tweet($tweet['in_reply_to_status_id_str']);
			}
			if($type === false){
				// type should be a MYTWEET or a MYRETWEET if it's on the user_timeline:
				if(isset($tweet['retweeted_status']['id_str']) && !empty($tweet['retweeted_status']['id_str'])){
					if(isset($tweet['user']['id_str']) && $tweet['user']['id_str'] == $this->twitter_account->get('twitter_id')){
						$type = _TWITTER_MESSAGE_TYPE_MYRETWEET;
					}else{
						$type = _TWITTER_MESSAGE_TYPE_OTHERRETWEET;
					}
					$new_reply_to_id = $this->twitter_account->get_reply_tweet($tweet['retweeted_status']['id_str']);
				}else if(isset($tweet['in_reply_to_user_id_str']) && $tweet['in_reply_to_user_id_str'] == $this->twitter_account->get('twitter_id')){
					$type = _TWITTER_MESSAGE_TYPE_MENTION;
				}else if(isset($tweet['user']['id_str']) && $tweet['user']['id_str'] == $this->twitter_account->get('twitter_id')){
					$type = _TWITTER_MESSAGE_TYPE_MYTWEET;
				}else{
					$type = _TWITTER_MESSAGE_TYPE_OTHERTWEET;
				}
			}
			// todo: unarchive tweet if the retweet or fav action happens
			$this->social_twitter_message_id = update_insert('social_twitter_message_id',$this->social_twitter_message_id, 'social_twitter_message', array(
				'social_twitter_id' => $this->twitter_account->get('social_twitter_id'),
				'reply_to_id' => $new_reply_to_id,
				'twitter_message_id' => $tweet['id_str'],
				'twitter_from_id' => isset($tweet['user']['id_str']) ? $tweet['user']['id_str'] : (isset($tweet['sender_id_str']) ? $tweet['sender_id_str'] : ''),
				'twitter_from_name' => isset($tweet['user']['screen_name']) ? $tweet['user']['screen_name'] : (isset($tweet['sender_screen_name']) ? $tweet['sender_screen_name'] : ''),
				'twitter_to_id' => isset($tweet['in_reply_to_user_id_str']) ? $tweet['in_reply_to_user_id_str'] : (isset($tweet['recipient_id_str']) ? $tweet['recipient_id_str'] : ''),
				'twitter_to_name' => isset($tweet['in_reply_to_screen_name']) ? $tweet['in_reply_to_screen_name'] : (isset($tweet['recipient_screen_name']) ? $tweet['recipient_screen_name'] : ''),
				'summary' => isset($tweet['text']) ? $tweet['text'] : '', //todo: swap out shortened urls in 'entities' array.
				'message_time' => isset($tweet['created_at']) ? strtotime($tweet['created_at']) : '',
				'data' => json_encode($tweet),
				'type' =>$type,
			));
			$this->load($this->social_twitter_message_id);
		}



		return $this->social_twitter_message_id;
	}

    public function load($social_twitter_message_id = false){
	    if(!$social_twitter_message_id)$social_twitter_message_id = $this->social_twitter_message_id;
	    $this->reset();
	    $this->social_twitter_message_id = $social_twitter_message_id;
        if($this->social_twitter_message_id){
            $this->details = get_single('social_twitter_message','social_twitter_message_id',$this->social_twitter_message_id);
	        if(!is_array($this->details) || !isset($this->details['social_twitter_message_id']) || $this->details['social_twitter_message_id'] != $this->social_twitter_message_id){
		        $this->reset();
		        return false;
	        }
        }
        foreach($this->details as $key=>$val){
            $this->{$key} = $val;
        }
	    if(!$this->twitter_account && $this->get('social_twitter_id')){
		    $this->twitter_account = new ucm_twitter_account($this->get('social_twitter_id'));
	    }
        return $this->social_twitter_message_id;
    }

	public function get($field){
		return isset($this->{$field}) ? $this->{$field} : false;
	}


    public function update($field,$value){
	    // what fields to we allow? or not allow?
	    if(in_array($field,array('social_twitter_message_id')))return;
        if($this->social_twitter_message_id){
            $this->{$field} = $value;
            update_insert('social_twitter_message_id',$this->social_twitter_message_id,'social_twitter_message',array(
	            $field => $value,
            ));
        }
    }

	public function parse_links(){
		if(!$this->get('social_twitter_message_id'))return;
		// strip out any links in the tweet and write them to the twitter_message_link table.
		$url_clickable = '~
		            ([\\s(<.,;:!?])                                        # 1: Leading whitespace, or punctuation
		            (                                                      # 2: URL
		                    [\\w]{1,20}+://                                # Scheme and hier-part prefix
		                    (?=\S{1,2000}\s)                               # Limit to URLs less than about 2000 characters long
		                    [\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]*+         # Non-punctuation URL character
		                    (?:                                            # Unroll the Loop: Only allow puctuation URL character if followed by a non-punctuation URL character
		                            [\'.,;:!?)]                            # Punctuation URL character
		                            [\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]++ # Non-punctuation URL character
		                    )*
		            )
		            (\)?)                                                  # 3: Trailing closing parenthesis (for parethesis balancing post processing)
		    ~xS'; // The regex is a non-anchored pattern and does not have a single fixed starting character.
		          // Tell PCRE to spend more time optimizing since, when used on a page load, it will probably be used several times.
		$summary = ' ' . $this->get('summary') . ' ';
		if(strlen($summary) && preg_match_all($url_clickable,$summary,$matches)){
			foreach($matches[2] as $id => $url){
				$url = trim($url);
				if(strlen($url)) {
					// wack this url into the database and replace it with our rewritten url.
					$social_twitter_message_link_id = ucm_update_insert( 'social_twitter_message_link_id', false, 'social_twitter_message_link', array(
						'social_twitter_message_id' => $this->get('social_twitter_message_id'),
						'link' => $url,
					) );
					if($social_twitter_message_link_id) {
						$new_link = trailingslashit( get_site_url() );
						$new_link .= strpos( $new_link, '?' ) === false ? '?' : '&';
						$new_link .= _SIMPLE_SOCIAL_TWITTER_LINK_REWRITE_PREFIX . '=' . $social_twitter_message_link_id;
						// basic hash to stop brute force.
						if(defined('AUTH_KEY')){
							$new_link .= ':'.substr(md5(AUTH_KEY.' twitter link '.$social_twitter_message_link_id),1,5);
						}
						$newsummary = trim(preg_replace('#'.preg_quote($url,'#').'#',$new_link,$summary, 1));
						if(strlen($newsummary)){// just incase.
							$summary = $newsummary;
						}
					}
				}
			}
		}
		$this->update('summary',$summary);
	}

	public function delete(){
		if($this->social_twitter_message_id) {
			delete_from_db( 'social_twitter_message', 'social_twitter_message_id', $this->social_twitter_message_id );
		}
	}

	public function mark_as_read(){
		if($this->social_twitter_message_id && module_security::is_logged_in()){
			$sql = "REPLACE INTO `"._DB_PREFIX."social_twitter_message_read` SET `social_twitter_message_id` = ".(int)$this->social_twitter_message_id.", `user_id` = ".(int)module_security::get_loggedin_id().", read_time = ".(int)time();
			query($sql);
		}
	}

	public function get_summary() {
		// who was the last person to contribute to this post? show their details here instead of the 'summary' box maybe?
		$summary = $this->get( 'summary' );
	    if(empty($summary))$summary = _l('N/A');
	    $return = htmlspecialchars( strlen( $summary ) > 80 ? substr( $summary, 0, 80 ) . '...' : $summary );
		$data = @json_decode($this->get('data'),true);
		//print_r($data);
		if($data && ((isset($data['retweet_count']) && $data['retweet_count'] > 0) || (isset($data['favorite_count']) && $data['favorite_count'] > 0))){
			$return .= '<br/>( ';
			if(isset($data['retweet_count']) && $data['retweet_count'] > 0){
				$return .= _l('Retweets: %s',$data['retweet_count']);
			}
			$return .=  ' ';
			if(isset($data['favorite_count']) && $data['favorite_count'] > 0){
				$return .= _l('Favorites: %s',$data['favorite_count']);
			}
			$return .=  ' )';
		}
		return $return;
	}

	private $can_reply = false;
	public function output_block($level){

		if(!$this->get('social_twitter_message_id') || $level < -3)return;

		$twitter_data = @json_decode($this->get('data'),true);

		// any previous messages?
		if($level <= 0){
			if($this->get('reply_to_id')){
				// this tweet is a reply to a previous tweet!
				?>
				<div class="twitter_previous_messages">
					<?php
					$reply_message = new ucm_twitter_message($this->twitter_account, $this->get('reply_to_id'));
					$reply_message->output_block($level-1);
					?>
				</div>
				<?php
			}else if($this->get('type') == _TWITTER_MESSAGE_TYPE_DIRECT){
				// find previous message(s)
				$from = preg_replace('#[^0-9]#','',$this->get('twitter_from_id'));
				$to = preg_replace('#[^0-9]#','',$this->get('twitter_to_id'));
				if($from && $to){
					$sql = "SELECT * FROM `"._DB_PREFIX."social_twitter_message` WHERE `type` = "._TWITTER_MESSAGE_TYPE_DIRECT." AND message_time <= ".(int)$this->get('message_time')." AND social_twitter_message_id != ".(int)$this->social_twitter_message_id." AND ( (`twitter_from_id` = $from AND `twitter_to_id` = $to) OR (`twitter_from_id` = $to AND `twitter_to_id` = $from) ) ORDER BY `message_time` DESC LIMIT 1";
					$previous = qa1($sql);
					if($previous && $previous['social_twitter_message_id']){
						?>
						<div class="twitter_previous_messages twitter_direct">
							<?php
							$reply_message = new ucm_twitter_message($this->twitter_account, $previous['social_twitter_message_id']);
							$reply_message->output_block($level-1);
							?>
						</div>
						<?php
					}
				}
			}
		}

		$message_from = isset($twitter_data['user']) ? $twitter_data['user'] : (isset($twitter_data['sender']) ? $twitter_data['sender'] : false);

		if($this->get('summary')){
			if($message_from && $this->get('type') != _TWITTER_MESSAGE_TYPE_DIRECT){
				$message_from['tweet_id'] = isset($twitter_data['id_str']) ? $twitter_data['id_str'] : false;
			}
			//echo '<pre>'; print_r($twitter_data); echo '</pre>';
			?>
			<div class="twitter_comment <?php echo $level != 0 ? ' twitter_comment_clickable' : 'twitter_comment_current';?>" data-id="<?php echo $this->social_twitter_message_id;?>" data-link="<?php echo module_social_twitter::link_open_twitter_message($this->get('social_twitter_id'),$this->social_twitter_message_id);?>" data-title="<?php echo _l('Tweet');?>">
				<div class="twitter_comment_picture">
					<?php 
					if(isset($twitter_data['user']['id_str'])){
						$pic = array(
							'screen_name' => isset($twitter_data['user']['screen_name']) ? $twitter_data['user']['screen_name'] : '',
							'image' => isset($twitter_data['user']['profile_image_url_https']) ? $twitter_data['user']['profile_image_url_https'] : '',
						);
					}else if(isset($twitter_data['sender']['id_str'])){
						$pic = array(
							'screen_name' => isset($twitter_data['sender']['screen_name']) ? $twitter_data['sender']['screen_name'] : '',
							'image' => isset($twitter_data['sender']['profile_image_url_https']) ? $twitter_data['sender']['profile_image_url_https'] : '',
						);
					}else{
						$pic = false;
					}
					if($pic){
						?>
						<img src="<?php echo $pic['image'];?>">
						<?php
					}
					?>
				</div>
				<div class="twitter_comment_header">
					<?php _e('From:'); echo ' '; echo $message_from ? ucm_twitter::format_person($message_from) : 'N/A'; ?>
					<span><?php $time = strtotime($this->get('message_time'));
					echo $time ? ' @ ' . print_date($time,true) : '';

					if ( $this->get('user_id') ) {
						echo ' (sent by ' . module_user::link_open( $this->get('user_id'), true ) . ')';
					}
					?>
					</span>
				</div>
				<div class="twitter_comment_body">
					<?php if(isset($twitter_data['entities']['media']) && is_array($twitter_data['entities']['media'])){
						foreach($twitter_data['entities']['media'] as $media) {
							if ( $media['type'] == 'photo' ) {
								?>
								<div class="twitter_picture">
									<?php if (isset( $media['url'] ) && $media['url']){ ?> <a
										href="<?php echo htmlspecialchars( $media['url'] ); ?>"
										target="_blank"> <?php } ?>
										<img src="<?php echo htmlspecialchars( $media['media_url_https'] ); ?>">
										<?php if (isset( $media['url'] ) && $media['url']){ ?> </a> <?php } ?>
								</div>
							<?php
							}
						}
					} ?>
					<div>
						<?php echo forum_text($this->get('summary'));?>
					</div>
					<div class="twitter_comment_stats">
						<?php
						$data = @json_decode($this->get('data'),true);
						//print_r($data);
						if($data && ((isset($data['retweet_count']) && $data['retweet_count'] > 0) || (isset($data['favorite_count']) && $data['favorite_count'] > 0))){
							if(isset($data['retweet_count']) && $data['retweet_count'] > 0){
								echo _l('Retweets: %s',$data['retweet_count']);
							}
							echo ' ';
							if(isset($data['favorite_count']) && $data['favorite_count'] > 0){
								echo _l('Favorites: %s',$data['favorite_count']);
							}
						} ?>
					</div>
				</div>
				<div class="twitter_comment_actions">
					<?php if($this->can_reply){ ?>
						<a href="#" class="twitter_reply_button"><?php _e('Reply');?></a>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
		<?php if($level == 0){ ?>
			<div class="twitter_comment_replies">
			<?php
			//if(strpos($twitter_data['message'],'picture')){
				//echo '<pre>'; print_r($twitter_data); echo '</pre>';
			//}

			if($this->can_reply){
				$this->reply_box($level, $message_from);
			}
			?>
			</div>
		<?php
		}

		if($level >= 0){
			// any follow up messages?
			if($this->get('type') == _TWITTER_MESSAGE_TYPE_DIRECT) {
				$from = preg_replace('#[^0-9]#','',$this->get('twitter_from_id'));
				$to = preg_replace('#[^0-9]#','',$this->get('twitter_to_id'));
				if($from && $to){
					$sql = "SELECT * FROM `"._DB_PREFIX."social_twitter_message` WHERE `type` = "._TWITTER_MESSAGE_TYPE_DIRECT." AND message_time >= ".(int)$this->get('message_time')." AND social_twitter_message_id != ".(int)$this->social_twitter_message_id." AND ( (`twitter_from_id` = $from AND `twitter_to_id` = $to) OR (`twitter_from_id` = $to AND `twitter_to_id` = $from) ) ORDER BY `message_time` ASC LIMIT 1";
					$next = qa1($sql);
					if($next && $next['social_twitter_message_id']){
						?>
						<div class="twitter_next_messages twitter_direct">
							<?php
							$reply_message = new ucm_twitter_message($this->twitter_account, $next['social_twitter_message_id']);
							$reply_message->output_block($level + 1);
							?>
						</div>
						<?php
					}
				}
			}else{
				$next = get_multiple( 'social_twitter_message', array(
						'social_twitter_id' => $this->twitter_account->get( 'social_twitter_id' ),
						'reply_to_id' => $this->social_twitter_message_id,
					), 'social_twitter_message_id' );
				if ( $next ) {
					foreach($next as $n) {
						// this tweet is a reply to a previous tweet!
						if($n['social_twitter_message_id']) {
							?>
							<div class="twitter_next_messages">
								<?php
								$reply_message = new ucm_twitter_message( $this->twitter_account, $n['social_twitter_message_id'] );
								$reply_message->output_block( $level + 1 );
								?>
							</div>
							<?php
						}
					}
				}
			}
		}

	}

	public function full_message_output($can_reply = false){
		$this->can_reply = $can_reply;
		// used in social_twitter_list.php to display the full message and its comments


		$this->output_block(0);
	}

	public function reply_box($level=0, $message_from = array()){
		if($this->twitter_account &&  $this->social_twitter_message_id && (int)$this->get('social_twitter_id') > 0 && $this->get('social_twitter_id') == $this->twitter_account->get('social_twitter_id')) {
			// who are we replying to?
			$account_data = @json_decode($this->twitter_account->get('twitter_data'),true);
			?>
			<div class="twitter_comment twitter_comment_reply_box twitter_comment_reply_box_level<?php echo $level;?>">
				<div class="twitter_comment_picture">
					<?php if($account_data && isset($account_data['id_str'])){
						$pic = array(
							'screen_name' => isset($account_data['screen_name']) ? $account_data['screen_name'] : '',
							'image' => isset($account_data['profile_image_url_https']) ? $account_data['profile_image_url_https'] : '',
						);
					}else{
						$pic = false;
					}
					if($pic){
						?>
						<img src="<?php echo $pic['image'];?>">
						<?php
					} ?>
				</div>
				<div class="twitter_comment_header">
					<?php echo ucm_twitter::format_person( $account_data ); ?>
				</div>
				<div class="twitter_comment_reply">
					<textarea placeholder="Write a reply..." class="twitter_compose_message"><?php
						if($message_from && isset($message_from['screen_name']) && $this->get('type') != _TWITTER_MESSAGE_TYPE_DIRECT){
							echo '@'.htmlspecialchars($message_from['screen_name']).' ';
						}
						?></textarea>
					<button data-id="<?php echo (int)$this->social_twitter_message_id;?>" data-account-id="<?php echo (int)$this->get('social_twitter_id');?>"><?php _e('Send');?></button>
					<div style="clear:both;">
				    <span class="twitter_characters_remain"><span>140</span> characters remaining.</span>
					<br/>
					(debug) <input type="checkbox" name="debug" class="reply-debug" value="1">
						</div>
				</div>
				<div class="twitter_comment_actions"></div>
			</div>
		<?php
		}else{
			?>
			<div class="twitter_comment twitter_comment_reply_box">
				(incorrect settings, please report this bug)
			</div>
			<?php
		}
	}

	public function get_link() {
		return '//twitter.com/'.htmlspecialchars($this->twitter_account->get('twitter_name')).'/status/'.$this->get('twitter_message_id');
	}

	private $attachment_name = '';
	public function add_attachment($local_filename){
		if(is_file($local_filename)){
			$this->attachment_name = $local_filename;
		}
	}
	public function send_queued($debug = false){
		if($this->twitter_account && $this->social_twitter_message_id) {
			// send this message out to twitter.
			// this is run when user is composing a new message from the UI,
			if ( $this->get( 'status' ) == _SOCIAL_MESSAGE_STATUS_SENDING )
				return; // dont double up on cron.
			$this->update( 'status', _SOCIAL_MESSAGE_STATUS_SENDING );

			$user_post_data = @json_decode($this->get('data'),true);

			if($debug)echo "Sending a new message to twitter account ID: ".$this->twitter_account->get('twitter_name')." <br>\n";
			$result = false;

			$tmhOAuth = $this->twitter_account->get_api();


			$post_data = array(
				'status' => $this->get('summary'),
			);
			$reply_message = false;
			if($this->get('reply_to_id')){
				$reply_message = new ucm_twitter_message(false, $this->get('reply_to_id'));
				if($reply_message && $reply_message->get('twitter_message_id')){
					$post_data['in_reply_to_status_id'] = $reply_message->get('twitter_message_id');
				}else{
					$reply_message = false;
				}
			}

			// todo: message or link are required.
			$now = time();
			$send_time = $this->get('message_time');

			if($reply_message && $reply_message->get('type') == _TWITTER_MESSAGE_TYPE_DIRECT){
				// send a direct reply, not a public tweet

				// a hack for DM, we dont reply to the most recent message (because we could reply to ourselves) we reply to the most recent message with a different author.
				$send_dm_to_id = $reply_message->get('twitter_from_id');
				if(!$send_dm_to_id){
					echo "Failed, no DM reply ID ";
					$this->delete();
					return false;
				}
				$our_twitter_id = preg_replace('#[^0-9]#','',$this->twitter_account->get('twitter_id'));
				if($our_twitter_id && $send_dm_to_id == $our_twitter_id){
					// dont reply to ourselves!
					$to = preg_replace('#[^0-9]#','',$reply_message->get('twitter_to_id'));
					if($our_twitter_id != $to){
						$send_dm_to_id = $to;
					}
				}

				if($debug){
					echo "Posting to 1.1/direct_messagse/new to user id ".$send_dm_to_id."   <br>";
				}

				$this->update('type',_TWITTER_MESSAGE_TYPE_DIRECT);

				$twitter_code = $tmhOAuth->user_request(array(
					'method' => 'POST',
					'url' => $tmhOAuth->url('1.1/direct_messages/new'),
					'params' => array(
						'user_id' => $send_dm_to_id,
						'text' => $this->get('summary'),
					),
				));
				if ($twitter_code == 200) {
					$result = json_decode( $tmhOAuth->response['response'], true );
				}else{
					$result = false;
				}

			}else{


                //if($post_data['in_reply_to_status_id']){
					$this->update('type',_TWITTER_MESSAGE_TYPE_MYTWEET);
				//}
				if(isset($user_post_data['twitter_post_type']) && $user_post_data['twitter_post_type'] == 'picture' && !empty($this->attachment_name) && is_file($this->attachment_name)){
					// we're posting a photo! change the post source from /feed to /photos

					//$post_data['source'] = new CURLFile($this->attachment_name, 'image/jpg'); //'@'.$this->attachment_name;
					$post_data['media[]'] = file_get_contents($this->attachment_name);

					if($debug){
						echo "Posting to 1.1/statuses/update_with_media with data: <br>";
						print_r($post_data);
					}

					$twitter_code = $tmhOAuth->user_request(array(
						'method' => 'POST',
						'multipart' => true,
						'url' => $tmhOAuth->url('1.1/statuses/update_with_media'),
						'params' => $post_data,
					));
					if ($twitter_code == 200) {
						$result = json_decode( $tmhOAuth->response['response'], true );
					}else{
						$result = false;
					}

				}else{

					if($debug){
						echo "Posting to 1.1/statuses/update with data: <br>";
						print_r($post_data);
					}

					$twitter_code = $tmhOAuth->user_request(array(
						'method' => 'POST',
						'url' => $tmhOAuth->url('1.1/statuses/update'),
						'params' => $post_data,
					));
					if ($twitter_code == 200) {
						$result = json_decode( $tmhOAuth->response['response'], true );
					}else{
						$result = false;
					}

				}
			}
			if($debug)echo "API Post Result: <br>\n".var_export($result,true)." <br>\n";
			if($result && isset($result['id_str'])){
				$this->update('twitter_message_id',$result['id_str']);
				// reload this message and comments from the graph api.
				$this->load_by_twitter_id($this->get('twitter_message_id'),false, $this->get('type') == _TWITTER_MESSAGE_TYPE_DIRECT ? _TWITTER_MESSAGE_TYPE_DIRECT : false, $debug, true);
			}else{
				echo 'Failed to send message. Error was: '.var_export($tmhOAuth->response['response'],true);
				// remove from database.
				$this->delete();
				return false;
			}

			// successfully sent, mark is as answered.
			$this->update( 'status', _SOCIAL_MESSAGE_STATUS_ANSWERED );
			if($reply_message){
				//archive the message we replied to as well
				$reply_message->update( 'status', _SOCIAL_MESSAGE_STATUS_ANSWERED );
			}
			return true;
		}
		return false;
	}


	public function get_type_pretty() {
		$type = $this->get('type');
		switch($type){
			case _TWITTER_MESSAGE_TYPE_MENTION:
				return 'Mention';
				break;
			case _TWITTER_MESSAGE_TYPE_OTHERTWEET:
				return 'Tweet';
				break;
			case _TWITTER_MESSAGE_TYPE_MYTWEET:
				return 'My Tweet';
				break;
			case _TWITTER_MESSAGE_TYPE_MYRETWEET:
				return 'My Retweet';
				break;
			case _TWITTER_MESSAGE_TYPE_OTHERRETWEET:
				return 'Retweet';
				break;
			case _TWITTER_MESSAGE_TYPE_DIRECT:
				return 'Direct';
				break;
			default:
				return ucwords($type);
		}
	}

	public function get_from() {
		if($this->social_twitter_message_id){
			$from = array();
			$data = @json_decode($this->get('data'),true);
			if(isset($data['user']['id_str'])){
				$from[$data['user']['id_str']] = array(
					'screen_name' => isset($data['user']['screen_name']) ? $data['user']['screen_name'] : '',
					'image' => isset($data['user']['profile_image_url_https']) ? $data['user']['profile_image_url_https'] : '',
				);
			}
			if(isset($data['sender']['id_str'])){
				$from[$data['sender']['id_str']] = array(
					'screen_name' => isset($data['sender']['screen_name']) ? $data['sender']['screen_name'] : '',
					'image' => isset($data['sender']['profile_image_url_https']) ? $data['sender']['profile_image_url_https'] : '',
				);
			}
			if(isset($data['recipient']['id_str'])){
				$from[$data['recipient']['id_str']] = array(
					'screen_name' => isset($data['recipient']['screen_name']) ? $data['recipient']['screen_name'] : '',
					'image' => isset($data['recipient']['profile_image_url_https']) ? $data['recipient']['profile_image_url_https'] : '',
				);
			}
			return $from;
		}
		return array();
	}

	public function link_open(){
		return 'admin.php?page=simple_social_inbox_main&social_twitter_id='.$this->twitter_account->get('social_twitter_id').'&social_twitter_message_id='.$this->social_twitter_message_id;
	}


}