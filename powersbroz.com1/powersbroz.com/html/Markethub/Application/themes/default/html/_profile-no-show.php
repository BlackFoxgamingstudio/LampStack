
<?php if ($D->show_profile == 0) { ?>

<div class="only-redbox mrg20T">
	<div class="bold txtsize03"><?php echo $this->lang('profile_noshow_isprivate'); ?></div>
	<?php if ($D->is_logged) {?>
    <div class="mrg20T"><?php echo $this->lang('profile_noshow_youfollowuser'); ?></div>
    <? } else { ?>
	<div class="mrg20T"><?php echo $this->lang('profile_noshow_youmustbe'); ?> <span class="linkblue"><a href="<?php echo $C->SITE_URL?>"><?php echo $this->lang('profile_noshow_signed'); ?></a></span>.</div>
	<?php } ?>
</div>

<?php } ?>