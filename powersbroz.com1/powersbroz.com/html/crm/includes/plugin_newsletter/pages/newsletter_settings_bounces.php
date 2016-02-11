<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */
if(isset($_REQUEST['go'])){
    ob_end_clean();
    echo '<pre>';
    _e("Checking for bounces, please wait...");
    echo "\n\n";
    module_newsletter::check_bounces(true);
    echo "\n\n";
    _e("done.");
    echo '</pre>';

    exit;
}

$module->page_title = _l('Newsletter Bounce Checking');
print_heading('Newsletter Bounce Checking');

?>
<p><?php _e('Bounces are checked automatically using the CRON job, however if you want to check for bounces manually (ie: to see any error) please click the button below.');?></p>
<form action="" method="post">
<input type="submit" name="go" value="<?php _e('Check for bounces');?>">
</form>