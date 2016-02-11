<?php 
	// We check in which language we will work
	if (isset($_SESSION["DATAGLOBAL"][0]) && !empty($_SESSION["DATAGLOBAL"][0])) $C->LANGUAGE = $_SESSION["DATAGLOBAL"][0];

	$this->load_langfile('inside/dashboard.php');

	// We are here only if you're logged in
	if (!$this->user->is_logged) {
		echo('0: '.$this->lang('dashboard_no_session'));
		die();
	}
	
	$errored = 0;
	$txterror = '';
	
	$action=0;
	
	if (isset($_POST["todo"]) && $_POST["todo"] != '') $action = $this->db1->e($_POST["todo"]);
	if (!is_numeric($action)) {
		$errored = 1;
		$txterror .= 'Error. ';
		echo('0: '.$caderror);
		die();
	}

	// 
	if ($action == 1)	{

		$wa = $dch = 0;
		$title = $photo = $description = $album = '';
		
		if (isset($_POST["wa"]) && $_POST["wa"]!='') $wa = $this->db1->e($_POST["wa"]);
		if (isset($_POST["a"]) && $_POST["a"]!='') $album = $this->db1->e(htmlspecialchars($_POST["a"]));
		if (isset($_POST["t"]) && !empty($_POST["t"])) $title = $this->db1->e(htmlspecialchars($_POST["t"]));
		if (isset($_POST["ph"]) && !empty($_POST["ph"])) $photo = $this->db1->e($_POST["ph"]);
		if (isset($_POST["dch"]) && !empty($_POST["dch"])) $dch = $this->db1->e($_POST["dch"]);
		if (isset($_POST["d"]) && !empty($_POST["d"])) $description = $this->db1->e(htmlspecialchars($_POST["d"]));
		
		if ($dch == 0) { $errored = 1; $txterror .= 'Error. '; }
		if (empty($photo)) { $errored = 1; $txterror .= 'Error. '; }
		if (empty($title)) { $errored = 1; $txterror .= 'Error. '; }
		if (empty($album)) { $errored = 1; $txterror .= 'Error. '; }
		
	}

	if ($action == 2)	{
		$description = $namealbum = '';
		
		if (isset($_POST["n"]) && !empty($_POST["n"])) $namealbum = $this->db1->e(htmlspecialchars($_POST["n"]));
		if (isset($_POST["d"]) && !empty($_POST["d"])) $description = $this->db1->e(htmlspecialchars($_POST["d"]));
		
		if (empty($namealbum)) { $errored = 1; $txterror .= 'Error... '; }
		
	}

	if ($action == 3)	{
		$codalbum = '';
		if (isset($_POST["ca"]) && !empty($_POST["ca"])) $codalbum = $this->db1->e($_POST["ca"]);
		if (empty($codalbum)) { $errored = 1; $txterror .= 'Error... '; }
	}

	if ($action == 4)	{
		$description = $namealbum = $codea = '';
		
		if (isset($_POST["n"]) && !empty($_POST["n"])) $namealbum = $this->db1->e(htmlspecialchars($_POST["n"]));
		if (isset($_POST["d"]) && !empty($_POST["d"])) $description = $this->db1->e(htmlspecialchars($_POST["d"]));
		if (isset($_POST["c"]) && !empty($_POST["c"])) $codea = $this->db1->e(htmlspecialchars($_POST["c"]));
		
		if (empty($namealbum)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($codea)) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 5)	{
		$coditem = '';
		if (isset($_POST["ci"]) && !empty($_POST["ci"])) $coditem = $this->db1->e($_POST["ci"]);
		if (empty($coditem)) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 6)	{ 
		$description = $titlephoto = $codep = '';
		
		if (isset($_POST["n"]) && !empty($_POST["n"])) $titlephoto = $this->db1->e(htmlspecialchars($_POST["n"]));
		if (isset($_POST["d"]) && !empty($_POST["d"])) $description = $this->db1->e(htmlspecialchars($_POST["d"]));
		if (isset($_POST["c"]) && !empty($_POST["c"])) $codep = $this->db1->e(htmlspecialchars($_POST["c"]));
		
		if (empty($titlephoto)) { $errored = 1; $txterror .= 'Error... '; }
		if (empty($codep)) { $errored = 1; $txterror .= 'Error... '; }
	}
	
	if ($action == 7)	{

		$wa = 0;
		$title = $photo = $description = $album = $cyt = '';
		
		if (isset($_POST["wa"]) && $_POST["wa"]!='') $wa = $this->db1->e($_POST["wa"]);
		if (isset($_POST["a"]) && $_POST["a"]!='') $album = $this->db1->e(htmlspecialchars($_POST["a"]));
		if (isset($_POST["t"]) && !empty($_POST["t"])) $title = $this->db1->e(htmlspecialchars($_POST["t"]));
		if (isset($_POST["cyt"]) && !empty($_POST["cyt"])) $cyt = $this->db1->e($_POST["cyt"]);
		if (isset($_POST["d"]) && !empty($_POST["d"])) $description = $this->db1->e(htmlspecialchars($_POST["d"]));
		
		if (empty($cyt)) { $errored = 1; $txterror .= 'Error. '; }
		if (empty($title)) { $errored = 1; $txterror .= 'Error. '; }
		if (empty($album)) { $errored = 1; $txterror .= 'Error. '; }
		
	}
	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		// We save photo
		if ($action == 1) {	
			if ($dch == 1) {
				
				if ($wa == 1) {
					$codea = uniqueCode(11, 1, 'albums', 'code');
					$r = $this->db1->query("INSERT INTO albums SET code='".$codea."', iduser=".$this->user->id.", name='".$album."', created='".(time()-1)."'");
					$idalbum = $this->db1->insert_id();
					$this->db1->query("UPDATE users SET num_albums=num_albums+1 WHERE iduser=".$this->user->id.' LIMIT 1');
					$this->db2->query('INSERT INTO activities SET iduser='.$this->user->id.', action=2, iditem='.$idalbum.', typeitem=2, date="'.(time()-1).'"');
				} else {
					$idalbum = $album;
					$codea = $this->network->getCodeAlbum($idalbum);
				}
				
				$codef = uniqueCode(11, 1, 'items', 'code');
				$ext = explode(".", $photo);
				$finalphoto = $codef.'.'.$ext[count($ext)-1];
				
				if (strtolower($ext[count($ext)-1])=='gif') {
					$theaction = 6;
					$typeitem = 2;
				} else {
					$theaction = 3;
					$typeitem = 1;
				}
				
				$r = $this->db1->query("INSERT INTO items SET code='".$codef."', idalbum=".$idalbum.", iduser=".$this->user->id.", title='".$title."', description='".$description."', imageitem='".$finalphoto."', datecreation='".time()."', typeitem=".$typeitem);
				
				$iditem = $this->db1->insert_id();
				
				$this->db2->query('INSERT INTO activities SET iduser='.$this->user->id.', action='.$theaction.', iditem='.$iditem.', typeitem=1, date="'.time().'"');
				
				$this->db1->query("UPDATE users SET num_items=num_items+1 WHERE iduser=".$this->user->id.' LIMIT 1');
				$this->db1->query("UPDATE albums SET numitems=numitems+1 WHERE idalbum=".$idalbum.' LIMIT 1');

						
				$raiz = '../';
				$tmppath = $C->FOLDER_TMP;
				$photofolder = $C->FOLDER_PHOTOS;
					
				// move photo file

				if (file_exists($raiz.$tmppath.$photo)) {
					$mythumb = new thumb();
		
					$mythumb->loadImage($raiz.$tmppath.$photo);
					
					if ($mythumb->width < $C->widthPhoto0 || strtolower($ext[count($ext)-1])=='gif') {
						
						copy($raiz.$tmppath.$photo, $raiz.$photofolder.$finalphoto);
						
					} else {
					
						$mythumb->resize($C->widthPhoto0,'width');
						$mythumb->save($raiz.$photofolder.$finalphoto);
					
					}
					
					$mythumb->loadImage($raiz.$tmppath.$photo);
					$mythumb->crop($C->widthPhoto1, $C->widthPhoto1);
					$mythumb->save($raiz.$photofolder.'min1/'.$finalphoto);
		
					$mythumb->loadImage($raiz.$tmppath.$photo);
					$mythumb->crop($C->widthPhoto2, $C->widthPhoto2);
					$mythumb->save($raiz.$photofolder.'min2/'.$finalphoto);

					$mythumb->loadImage($raiz.$tmppath.$photo);
					$mythumb->crop($C->widthPhoto3, $C->widthPhoto3);
					$mythumb->save($raiz.$photofolder.'min3/'.$finalphoto);

					unlink($raiz.$tmppath.$photo);

					echo("1: ".$codea);
					return;
				} else {
					$txtreturn=$this->lang('dashboard_myitems_tab2_msg9');
					echo("0: ".$txtreturn);
					return;
				}	

			} else {
				$txtreturn = $this->leng('dashboard_myitems_tab2_msg5');
				echo("0: ".$txtreturn);
				return;
			}
		}

		if ($action == 2) {	
			$codea = uniqueCode(11, 1, 'albums', 'code');
			$r = $this->db1->query("INSERT INTO albums SET code='".$codea."', iduser=".$this->user->id.", name='".$namealbum."', description='".$description."', created='".time()."'");
			$idalbum = $this->db1->insert_id();
			$this->db1->query("UPDATE users SET num_albums=num_albums+1 WHERE iduser=".$this->user->id.' LIMIT 1');
			$this->db2->query('INSERT INTO activities SET iduser='.$this->user->id.', action=2, iditem='.$idalbum.', typeitem=2, date="'.time().'"');
			$txtreturn = $codea;
			echo('1: '.$txtreturn);
			return;
		}

		if ($action == 3) {
			$thealbum = new album($codalbum);
			$thealbum->deleteAlbum();
			unset($thealbum);
			echo('1: Ok');
			return;
		}
		
		if ($action == 4) {			
			$this->db1->query("UPDATE albums SET name='".$namealbum."', description='".$description."' WHERE code='".$codea."' AND iduser=".$this->user->id);
			echo('1: '.stripslashes(stripslashes($namealbum)).'|#||#|'.stripslashes(stripslashes($description)));
			return;
		}
		
		if ($action == 5) {
			$theitem = new item($coditem);
			$theitem->deleteItem();
			unset($theitem);
			echo('1: Ok');
			return;
		}
		
		if ($action == 6) {
			$this->db1->query("UPDATE items SET title='".$titlephoto."', description='".$description."' WHERE code='".$codep."' AND iduser=".$this->user->id);
			echo('1: '.stripslashes(stripslashes($titlephoto)).'|#||#|'.stripslashes(stripslashes($description)));
			return;
		}
		
		if ($action == 7) {
			
			if ($wa == 1) {
				$codea = uniqueCode(11, 1, 'albums', 'code');
				$r = $this->db1->query("INSERT INTO albums SET code='".$codea."', iduser=".$this->user->id.", name='".$album."', created='".(time()-1)."'");
				$idalbum = $this->db1->insert_id();
				$this->db1->query("UPDATE users SET num_albums=num_albums+1 WHERE iduser=".$this->user->id.' LIMIT 1');
				$this->db2->query('INSERT INTO activities SET iduser='.$this->user->id.', action=2, iditem='.$idalbum.', typeitem=2, date="'.(time()-1).'"');
			} else {
				$idalbum = $album;
				$codea = $this->network->getCodeAlbum($idalbum);
			}
			
			$codef = uniqueCode(11, 1, 'items', 'code');

			$finalphoto = $codef.'.jpg';
			
			$r = $this->db1->query("INSERT INTO items SET code='".$codef."', idalbum=".$idalbum.", iduser=".$this->user->id.", title='".$title."', description='".$description."', imageitem='".$finalphoto."', datecreation='".time()."', typeitem=3, urlvideo='http://www.youtube.com/watch?v=".$cyt."', codvideo='".$cyt."'");
			
			$iditem = $this->db1->insert_id();
			
			$this->db2->query('INSERT INTO activities SET iduser='.$this->user->id.', action=7, iditem='.$iditem.', typeitem=1, date="'.time().'"');
			
			$this->db1->query("UPDATE users SET num_items=num_items+1 WHERE iduser=".$this->user->id.' LIMIT 1');
			$this->db1->query("UPDATE albums SET numitems=numitems+1 WHERE idalbum=".$idalbum.' LIMIT 1');
			
			$raiz = '../';
			$tmppath = $C->FOLDER_TMP;
			$photofolder = $C->FOLDER_PHOTOS;
			
			$photo = copyfromUrl('http://i.ytimg.com/vi/'.$cyt.'/0.jpg',$raiz.$tmppath,$this->usuario->id.'_v_ff');

			if (file_exists($raiz.$tmppath.$photo)) {
				$mythumb = new thumb();
	
				$mythumb->loadImage($raiz.$tmppath.$photo);
					
				copy($raiz.$tmppath.$photo, $raiz.$photofolder.$finalphoto);

				
				$mythumb->loadImage($raiz.$tmppath.$photo);
				$mythumb->crop($C->widthPhoto1, $C->widthPhoto1);
				$mythumb->save($raiz.$photofolder.'min1/'.$finalphoto);
	
				$mythumb->loadImage($raiz.$tmppath.$photo);
				$mythumb->crop($C->widthPhoto2, $C->widthPhoto2);
				$mythumb->save($raiz.$photofolder.'min2/'.$finalphoto);

				$mythumb->loadImage($raiz.$tmppath.$photo);
				$mythumb->crop($C->widthPhoto3, $C->widthPhoto3);
				$mythumb->save($raiz.$photofolder.'min3/'.$finalphoto);

				unlink($raiz.$tmppath.$photo);

				echo("1: ".$codea);
				return;
			} else {
				$txtreturn=$this->lang('dashboard_myitems_tab3_txtmsg5');
				echo("0: ".$txtreturn);
				return;
			}


		}
	
	}
?>