<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>

<div id="generalspace">
        
    <div id="container">
    
    	<div id="directory">
    
            <div class="title"><?php echo $this->lang('global_txt_directory'); ?></div>
            
            <div class="mrg10T">
            	<div class="fleft"><input name="q" type="text" id="q" placeholder="<?php echo $this->lang('global_txt_search_users');?>" class="boxinput rounded"></div>
                <div class="fleft"><a href="javascript:searchPeople();"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/icobsearch.png"></a></div>
                <div class="sh"></div>
            </div>

    		<div class="mrg20T"><?php echo $D->htmlUsers; ?></div>
            
            <div class="sh"></div>
            
            <?php if ($D->totalPag > 1) { ?>
            
            <div id="pagination">
            
            <?php if ($D->pageCurrent != 1) { ?>
            	<span><a href="<?php echo $C->SITE_URL?>directory/people/p:<?php echo($D->pageCurrent - 1)?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/arrow-left.png"></a></span>
            <?php } ?>
                
            <?php for($i=$D->firstPage; $i<=$D->lastPage; $i++) { ?>
            	<?php if ($i == $D->pageCurrent) {?>
            	<span class="current"><?php echo $i ?></span>
                <?php } else {?>
                <span class="nocurrent"><a href="<?php echo $C->SITE_URL?>directory/people/p:<?php echo $i?>"><?php echo $i?></a></span>
                <?php } ?>			
			<?php } ?>
            	
            <?php if ($D->pageCurrent != $D->lastPage) { ?>
            	<span><a href="<?php echo $C->SITE_URL?>directory/people/p:<?php echo($D->pageCurrent + 1)?>"><img src="<?php echo $C->SITE_URL?>themes/default/imgs/arrow-right.png"></a></span>
            <?php } ?>
                
            </div>
            
            <div class="sh"></div>
            
            <?php } ?>
            
        </div>
    
    </div>
            
</div>

<script>
function searchPeople() {
	var query = $('#q').val();
	if (query != this.defaultValue){
		document.location = siteurl + 'directory/people/q:' + escape(query.replace(' ','+'));
	}
}

function searchPeople13() {
	var query = $('#q').val();

	$('#q').keypress(function(x) {
		if (x.keyCode == 13) {
			query = $(this).val();
			if (query != this.defaultValue){
				document.location = siteurl + 'directory/people/q:' + escape(query.replace(' ','+'));
			}
		}
	});
}

searchPeople13();
</script>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>