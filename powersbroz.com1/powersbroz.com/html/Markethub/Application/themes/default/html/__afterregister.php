	<div id="afterregister">
		<div class="centered txtsize03 bold mrg20T mrg20B"><?php echo $this->lang('register_txtok1');?></div>
		<div class="centered mrg20T mrg20B txtsize02"><?php echo $this->lang('register_txtok2');?></div>
		<div class="centered mrg10T txtsize03 boldo link2">
			<button id="btloginafter" name="btloginafter" type="button" class="btn btn-blue"><?php echo $this->lang('register_txtok3');?></button>
		</div>
	</div>
	
	<script>
	$("#btloginafter").click(function(){
		self.location = siteurl + 'login';
	})
	</script>