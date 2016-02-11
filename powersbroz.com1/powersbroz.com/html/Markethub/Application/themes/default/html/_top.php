
	<div id="top-site">
        <div id="top-site-block">
            <div id="container">
            
				<?php
				if ($D->is_logged == 1) $this->load_template('_topbar-inside.php');
				else $this->load_template('_topbar.php');				
				?>
                                    
            </div>
        
        </div>
    
    </div>