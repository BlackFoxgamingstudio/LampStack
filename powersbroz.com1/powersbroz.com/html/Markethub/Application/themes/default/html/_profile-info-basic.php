	<div id="infobasic">
    	<table width="100%" border="0" class="table-data">
            <tr>
                <td colspan="5">
                	<?php if ($D->he_follows_me) { ?><div><span class="hefollows"><?php echo $this->lang('profile_infobasic_followsyou'); ?></span></div><?php } ?>
                    <div id="titleuser">
                        <span><?php echo $D->nameUser; ?></span> 
                        <?php if ($D->isUserVerified == 1) { ?>
                        <span><img src="<?php echo $C->SITE_URL?>themes/default/imgs/userverified.png"></span>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="space-data pdn10R">
                	<div class="info-profile"><a href="<?php echo $C->SITE_URL.$D->u->username?>/items"><span class="basic-number"><?php echo $D->u->num_items; ?></span><br/><span class="basic-text"><?php echo ($D->u->num_items==1?$this->lang('profile_infobasic_photo'):$this->lang('profile_infobasic_photos'))?></span></a></div>
                </td>
                <td class="space-data space-data-border">
                	<div class="info-profile"><a href="<?php echo $C->SITE_URL.$D->u->username?>/following"><span id="numfollowing" class="basic-number"><?php echo $D->u->num_following; ?></span><br/><span class="basic-text"><?php echo $this->lang('profile_infobasic_following')?></span></a></div>
                </td>
                <td class="space-data pdn10L">
                	<div class="info-profile"><a href="<?php echo $C->SITE_URL.$D->u->username?>/followers"><span id="numfollowers" class="basic-number"><?php echo $D->u->num_followers; ?></span><br/><span class="basic-text"><?php echo ($D->u->num_followers==1?$this->lang('profile_infobasic_follower'):$this->lang('profile_infobasic_followers'))?></span></a></div>
                </td>
                <td class="space-data-empty">&nbsp;</td>
                <td class="space-data-empty">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5"><?php $this->load_template('__areafollow.php'); ?></td>
            </tr>
		</table>

    </div>
    <div class="sh"></div>
    
    <hr class="mrg20T mrg10B">