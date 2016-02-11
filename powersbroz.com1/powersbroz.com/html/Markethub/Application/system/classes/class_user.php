<?php
	
	class user
	{
		public $id;
		public $network;
		public $is_logged;
		public $info;
		public $sess;
		
		public function __construct()
		{
			$this->id	= FALSE;
			$this->network	= & $GLOBALS['network'];
			$this->db1		= & $GLOBALS['db1'];
			$this->db2		= & $GLOBALS['db2'];
			$this->info		= new stdClass;
			$this->is_logged	= FALSE;
			$this->sess		= array();
		}
		
		public function load()
		{
			if( ! $this->network->id ) {
				return FALSE;
			}
			
			global $C;
			$this->_session_start();
			if( isset($this->sess['IS_LOGGED'], $this->sess['LOGGED_USER']) && $this->sess['IS_LOGGED'] && $this->sess['LOGGED_USER'] ) { 
				$u	= & $this->sess['LOGGED_USER'];
				$u	= $this->network->get_user_by_id($u->iduser);
				if( ! $u ) {
					return FALSE;
				}
				if( $this->network->id && $this->network->id == $u->network_id ) {
					$this->is_logged	= TRUE;
					$this->info	= & $u;
					$this->id	= $this->info->iduser;
					$this->db2->query('UPDATE users SET lastclick="'.time().'" WHERE iduser='.$this->id.' LIMIT 1');

					if( $this->info->active == 0 ) {
						$this->logout();
						return FALSE;
					}
					return $this->id;
				}
			}
			return FALSE;
		}
		
		private function _session_start()
		{
			if( ! $this->network->id ) {
				return FALSE;
			}
			if( ! isset($_SESSION['NETWORKS_USR_DATA']) ) {
				$_SESSION['NETWORKS_USR_DATA']	= array();
			}
			if( ! isset($_SESSION['NETWORKS_USR_DATA'][$this->network->id]) ) {
				$_SESSION['NETWORKS_USR_DATA'][$this->network->id]	= array();
			}
			$this->sess	= & $_SESSION['NETWORKS_USR_DATA'][$this->network->id];
		}
		
		public function login($login, $pass)
		{
			global $C;
			if( ! $this->network->id ) {
				return FALSE;
			}
			if( $this->is_logged ) {
				return FALSE;
			}
			if( empty($login) ) {
				return FALSE;
			}
			$login = $this->db2->escape($login);
			$pass = $this->db2->escape($pass);
			
			
			// First check if there is a user with email or username
			$r = $this->db2->query("SELECT iduser, password, salt FROM users WHERE (email='".$login."' OR username='".$login."') AND active=1 LIMIT 1");

			if( ! $obj = $this->db2->fetch_object() ) {
				return FALSE;
			}

			$password = $obj->password;
			$salt = $obj->salt;

			$enteredkey = hash('sha512', $salt.$pass);

			if ($password != $enteredkey) return FALSE;
			
			$this->info	= $this->network->get_user_by_id($obj->iduser, TRUE);
			if( ! $this->info ) {
				return FALSE;
			}
			$this->is_logged = TRUE;
			$this->sess['IS_LOGGED'] = TRUE;
			$this->sess['LOGGED_USER'] = & $this->info;
			$this->id = $this->info->iduser;
			
			$ip	= $this->db2->escape( ip2long($_SERVER['REMOTE_ADDR']) );
			$this->db2->query('UPDATE users SET previousaccess=lastaccess, ippreviousaccess=iplastaccess, lastaccess="'.time().'", iplastaccess="'.$ip.'", lastclick="'.time().'" WHERE iduser='.$this->id.' LIMIT 1');

			$this->sess['total_pageviews']	= 0;
			return TRUE;
		}
				
		public function logout()
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$this->sess['IS_LOGGED']	= FALSE;
			$this->sess['LOGGED_USER']	= NULL;
			unset($this->sess['IS_LOGGED']);
			unset($this->sess['LOGGED_USER']);
			$this->id	= FALSE;
			$this->info	= new stdClass;
			$this->is_logged	= FALSE;
		}
		
		public function follow($whom_id, $how=TRUE)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$whom	= $this->network->get_user_by_id($whom_id);
			if( ! $whom ) {
				return FALSE;
			}
			
			$f	= $this->network->verifies_follower($this->id, $whom_id);
			if( $f && $how==TRUE ) {
				return TRUE;
			}
			
			if( !$f && $how==FALSE ) {
				return TRUE;
			}
			
			if( $how == TRUE ) {
				$this->db2->query('INSERT INTO relations SET subscriber='.$this->id.', leader='.$whom_id.', rltdate="'.time().'"');
				$this->db2->query('UPDATE users SET num_followers=num_followers+1, num_notifications=num_notifications+1 WHERE iduser="'.$whom_id.'" LIMIT 1');
				$this->db2->query('UPDATE users SET num_following=num_following+1, num_activities=num_activities+1 WHERE iduser="'.$this->id.'" LIMIT 1');
				
				$this->db2->query('INSERT INTO activities SET iduser='.$this->id.', action=1, iduser2='.$whom_id.', iditem=0, date="'.time().'"');
				$this->db2->query("INSERT INTO notifications SET notif_type=1, to_user_id=".$whom_id.", from_user_id=".$this->id.", date='".time()."'");				
			} else {
				$this->db2->query('DELETE FROM relations WHERE subscriber='.$this->id.' AND leader='.$whom_id);
				$this->db2->query('UPDATE users SET num_followers=num_followers-1  WHERE iduser="'.$whom_id.'" LIMIT 1');
				$this->db2->query('UPDATE users SET num_following=num_following-1, num_activities=num_activities-1 WHERE iduser="'.$this->id.'" LIMIT 1');

				$this->db2->query("DELETE FROM activities WHERE iduser=".$this->id." AND action=1 AND iduser2=".$whom_id);
				$this->db2->query("DELETE FROM notifications WHERE notif_type=1 AND to_user_id=".$whom_id." AND from_user_id=".$this->id);
				$numdeleted = $this->db2->affected_rows();
				if ($numdeleted > $whom->num_notifications) $numdeleted = $whom->num_notifications;
				$this->db2->query('UPDATE users SET num_notifications=num_notifications-'.$numdeleted.'  WHERE iduser="'.$whom_id.'" LIMIT 1');
			}
			
			return TRUE;
		}
		
		public function if_follow_user($user_id)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$res = $this->db2->fetch_field('SELECT idrelation FROM relations WHERE leader='.$user_id.' AND subscriber='.$this->id.' LIMIT 1');
			return $res? TRUE : FALSE;
		}
		
		public function if_user_follows_me($user_id)
		{
			if( ! $this->is_logged ) {
				return FALSE;
			}
			$res = $this->db2->fetch_field('SELECT idrelation FROM relations WHERE leader='.$this->id.' AND subscriber='.$user_id.' LIMIT 1');
			return $res? TRUE : FALSE;
		}
				
		public function write_pageview()
		{
			global $C;
			
			if( ! $this->is_logged || ! $C->write_page_view_is_active ) {
				return FALSE;
			}
			$this->sess['total_pageviews']	++;
			$dt	= date('Y-m-d H');
			$this->db2->query('UPDATE users_pageviews SET pageviews=pageviews+1 WHERE iduser="'.$this->id.'" AND date="'.$dt.'" LIMIT 1');
			if( $this->db2->affected_rows() == 0 ) {
				$this->db2->query('INSERT INTO users_pageviews SET pageviews=1, iduser="'.$this->id.'", date="'.$dt.'" ');
			}
		}

	}
	
?>