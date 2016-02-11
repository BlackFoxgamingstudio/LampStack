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

	$url="";
	
	if (isset($_POST["url"]) && $_POST["url"]!="") $url=$this->db1->e($_POST["url"]);
	
	if (empty($url)) { $errored=1; $txterror.="Error. ";}

	
	if ($errored == 1) {
		echo('0: '.$txterror);
	} else {
		
		if (!$url = fitsUrl($url)) {
			$txterror = $this->lang('dashboard_myitems_tab3_txtmsg2');
			echo("0: ".$txterror);
			return;			
		} else {
			if (!$codigoYt = getCodeYoutube($url)) {
				$txterror = $this->lang('dashboard_myitems_tab3_txtmsg2');
				echo("0: ".$txterror);
				return;
			} else {
				$thevideo = new youtube('http://www.youtube.com/watch?v='.$codigoYt);
				
				
				$videoTitle = $thevideo->getTitle();
				$videoDescription = str_cut($thevideo->getDescription(),290);
				$minifotodelvideo = $thevideo->getUrlImage('default');
				$imgvideo = $this->lang('dashboard_myitems_tab3_txtimgvideo');
				$titlevideo = $this->lang('dashboard_myitems_tab3_txttitlevideo');
				$descrvideo = $this->lang('dashboard_myitems_tab3_txtdescripvideo');
				$bprevovideo = $this->lang('dashboard_myitems_tab3_bpreviewother');
				$bguarvideo = $this->lang('dashboard_myitems_tab3_bsavevideo');
	
				$formvideo = <<<CADENA
				
<div class="centered">
	<div class="grey1">$imgvideo</div>
	<div class="mrg5T"><img src="$minifotodelvideo"></div>
	<div class="mrg10T grey1">$titlevideo</div>
	<div><input type="text" name="vtitle" id="vtitle" class="boxinput withbox" value="$videoTitle"></div>
	<div class="mrg10T grey1">$descrvideo</div>
	<div><textarea name="vdescription" rows="3" class="boxinput withbox" id="vdescription">$videoDescription</textarea></div>
	
	<div id="msgerror2" class="redbox"></div>
	
	<div class="mrg10T"><input type="button" name="bcancel" id="bcancel" value="$bprevovideo" class="bgrey2 hand"> <input type="submit" name="bsave" id="bsave" value="$bguarvideo" class="bblue hand"><input name="codyt" id="codyt" type="hidden" value="$codigoYt" /></div>
</div>

CADENA;


				$formvideo .= '
				<script>
				$("#bsave").click(function(){
					saveVideo("#msgerror2","#msgok","#bsave","#formedit");
					return false;
				});
				
				$("#bcancel").click(function(){
					$("#urlvideo").val("");
					$("#msgvideoyt").slideUp("slow",function(){
						$("#msgvideoyt").hide(function(){
							$("#spacevideo").slideDown("slow");
						});
					});
					return false;
				});
				</script>';

				echo('1: '.$formvideo);
				return;
	
			}
			
		}
	
	}

?>