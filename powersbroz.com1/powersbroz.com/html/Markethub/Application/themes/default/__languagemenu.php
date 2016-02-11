<div class="mrg10T mrg10B">
	<span><?php echo($this->lang('global_txt_languages'))?>: </span>
<?php
$this->set_lasturl();
$langurl = $this->get_lasturl();
foreach($D->listlanguages as $k=>$v){
	$langurl = str_replace('lang:'.$k.'/', '', $langurl);
	$langurl = str_replace('lang:'.$k, '', $langurl);
}
foreach($D->listlanguages as $k=>$v){
?>
	<span class="linkblue pdn10L"> <a href="<?php echo(rtrim($langurl,'/'))?>/lang:<?php echo($k)?>"><?php echo($v)?></a> </span>	
<?php    
}
?>
</div>
