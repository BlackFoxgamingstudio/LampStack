<?php
$this->load_template('_header.php');
$this->load_template('_top.php');
?>

<div id="generalspace">
        
    <div id="container">
    
    	<div id="column1-info"><?php $this->load_template('_verticalmenu-pages.php'); ?></div>
        
        <div id="column2-info">
		
            <div id="page-info2">

				<div class="title"><?php echo $D->txtTitle; ?></div>
                <hr />
                <div class="areinfo">
                
                    <div><?php echo $D->texthtml?></div>
                    
				</div>

            </div>

        </div>
        
        <div class="sh"></div>
    
    </div>
            
</div>

<?php
$this->load_template('_foot.php');
$this->load_template('_footer.php');
?>