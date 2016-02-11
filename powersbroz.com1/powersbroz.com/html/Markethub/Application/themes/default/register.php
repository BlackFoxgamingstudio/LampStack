<?php
$this->load_template('_home-header.php');
$this->load_template('_top.php');
?>
<script src="<?php echo $C->SITE_URL ?>themes/default/js/js_register.js"></script>
<script src="<?php echo $C->SITE_URL ?>themes/default/js/md5.js"></script>
<div id="generalspace">
	<div id="container" class="centered">
		<div class="title mrg30T"><?php echo $this->lang('register_title', array('#SITE_TITLE#'=>$C->SITE_TITLE));?></div>

        <div id="areasignup" class="mrg20T mrg40B">
            <div>
              <form id="form1" name="form1" method="post" action="">
                
                <div class="mrg20T"><span><input type="text" name="email" id="email" class="box-input centered withboxonly" placeholder="<?php echo $this->lang('register_em')?>"></span></div>
                <div class="mrg20T"><span><input type="text" name="usernamer" id="usernamer" class="box-input centered withboxonly" placeholder="<?php echo $this->lang('register_un')?>"></span></div>
                <div class="mrg20T"><span><input type="password" name="passwordr" id="passwordr" class="box-input centered withboxonly" placeholder="<?php echo $this->lang('register_pw')?>"></span></div>

                
                
                <div id="errorsignup" class="alert-error mrg10T mrg10L pdn10 centered hide"></div>
                <div class="mrg20T"><span><button id="bactionsignup" name="bactionsignup" type="submit" class="btn btn-green"><?php echo $this->lang('register_bt')?></button></span></div>

                
                <div class="sh"></div>
              </form>
            </div>
        </div>
        
        <div id="registerok" class="hide mrg40T mrg40B"></div>


	</div>
</div>
<script>
var rtxterror1 = '<?php echo $this->lang('register_error1')?>';
var rtxterror2 = '<?php echo $this->lang('register_error2')?>';
var rtxterror3 = '<?php echo $this->lang('register_error3')?>';
var rtxterror4 = '<?php echo $this->lang('register_error4')?>';
var rtxterror5 = '<?php echo $this->lang('register_error8')?>';
$('#bactionsignup').click(function(){
	actionRegister('#bactionsignup', '#errorsignup', '#areasignup', '#registerok');
	return false;
})
</script>

<?php
$this->load_template('_home-foot.php');
$this->load_template('_footer.php');
?>