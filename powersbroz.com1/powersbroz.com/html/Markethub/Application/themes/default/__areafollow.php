<div>
	<script> ssgg=0; </script>

    <?php if ($D->is_my_profile) {?>
    
    <div id="areafollow">
    	<div id="bteditprofile" class="botonf rounded editprofile txtaction"><?php echo $this->lang('profile_areafollow_editprofile'); ?></div>
    </div>

    <script>
        $('#bteditprofile').click(function(){
            location.href = siteurl + "dashboard/myinformation";
        });
    </script>            
    
    <?php } else { ?>

    <div id="areafollow">

            <div class="botonf rounded js-action-follow follow txtaction"><?php echo $this->lang('profile_areafollow_follow'); ?></div>
            <div class="botonfs rounded js-action-unfollow following txtaction"><?php echo $this->lang('profile_areafollow_following'); ?></div>
            <div class="botonfns rounded js-action-unfollow unfollow txtaction"><?php echo $this->lang('profile_areafollow_unfollow'); ?></div>                    
    </div>
    
    <div id="msgnotloggedin" class="hide mrg20T">
		<div class="only-redbox"><?php echo $this->lang('profile_areafollow_youmustbe');?> <span class="linkblue"><a href="<?php echo $C->SITE_URL?>login"><?php echo $this->lang('profile_areafollow_signed');?></a></span>.</div>
    </div>
    
	<script>
		<?php if ($D->i_follow_him) {?>
			$('.unfollow').css('display','none');
			$('.follow').css('display','none');
			$('.following').css('display','block');
			ssgg=2;
		<?php } else { ?>
			$('.following').css('display','none');
			$('.unfollow').css('display','none');			
		<?php } ?>    
    </script>
    
	<?php if ($D->is_logged) { ?>
	
	<script>
		
		$('.js-action-unfollow').hover(function(){
			if ($('.following').css('display')=='block') {
				if (ssgg>1){
					$('.following').css('display','none');
					$('.unfollow').css('display','block');
				}
			}
		},function(){
			if ($('.unfollow').css('display')=='block') {
				$('.following').css('display','block');
				$('.unfollow').css('display','none');
			}
			ssgg=ssgg+1;
		});

	</script>

	<script src="<?php echo $C->SITE_URL?>themes/default/js/js_gestorfollow.js" type="text/javascript"></script>
	<script>
		var msg_norequest = '<?php echo $this->lang('dashboard_no_request')?>';
		var uidprofile = <?php echo $D->u->iduser; ?>;
		var showprofile = <?php echo $D->show_profile?>;
		$('.follow').click(function(){ jsFollow(uidprofile); });
		$('.unfollow').click(function(){ jsUnfollow(uidprofile); });
    </script>
	
	<?php } else { ?>

	<script> 
	$('.follow').one('click',function(){
	
		$("#areafollow").fadeOut("slow",function(){
			$("#areafollow").hide(function(){
				$("#msgnotloggedin").fadeIn('slow');
			});
		});			
	
	});  
	</script>
	
	<?php } //en isloged ?>
    

	<?php } // end is my profile ?>
</div>