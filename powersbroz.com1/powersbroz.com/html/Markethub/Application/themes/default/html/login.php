<?php
$this->load_template('_home-header.php');
$this->load_template('_top.php');
?>
<script src="<?php echo $C->SITE_URL ?>themes/default/js/js_login.js"></script>
<script src="<?php echo $C->SITE_URL ?>themes/default/js/md5.js"></script>
<div id="generalspace">
	<div id="container" class="centered">
		<div class="title mrg40T"><?php echo $this->lang('login_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));?></div>

        <div id="arealogin" class="mrg40B">
            <div>
              <form id="form1" name="form1" method="post" action="">
                
                <div class="mrg20T"><span><input type="text" name="usernamel" id="usernamel" class="box-input centered withboxonly" placeholder="<?php echo $this->lang('login_un')?>"></span></div>
                <div class="mrg20T"><input type="password" name="passwordl" id="passwordl" class="box-input centered withboxonly" placeholder="<?php echo $this->lang('login_pw')?>"></div>
                <div id="errorlogin" class="alert-error mrg10T mrg10L pdn10 centered hide"></div>
                <div class="mrg20T"><span><button id="btactionlogin" name="btactionlogin" type="submit" class="btn btn-blue"><?php echo $this->lang('login_bt')?></button></span></div>
                <div class="sh"></div>
              </form>
            </div>
        </div>

	</div>
</div>
<script>
var ltxterror1 = '<?php echo $this->lang('login_error1')?>';
var ltxterror2 = '<?php echo $this->lang('login_error2')?>';
var ltxterror3 = '<?php echo $this->lang('login_error3')?>';
var ltxterror4 = '<?php echo $this->lang('login_error4')?>';
var txtconnerror = '<?php echo $this->lang('global_txt_no_request')?>';
$('#btactionlogin').click(function(){
	actionLogin('#btactionlogin', '#errorlogin');
	return false;
})
</script>
<?php
$this->load_template('_home-foot.php');
$this->load_template('_footer.php');
?>