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
$module->page_title = _l('Preview');
//print_heading('Newsletter Editor');

$newsletter_id = isset($_REQUEST['newsletter_id']) ? (int)$_REQUEST['newsletter_id'] : false;
if(!$newsletter_id){
    redirect_browser(module_newsletter::link_list(false));
}
//$newsletter = module_newsletter::get_newsletter($newsletter_id);

if(isset($_REQUEST['show'])){
    // render the newsletter and display it on screen with nothing else.

    echo module_newsletter::render($newsletter_id,false,false,'preview');
    exit;
}

?>


<table width="100%" cellpadding="5">
    <tbody>
    <tr>
        <td width="50%" valign="top">

            <?php
            print_heading(array(
                  'type' => 'h2',
                  'title' => 'Preview Newsletter',
                  'button' => array(
                      'url' => module_newsletter::link_open($newsletter_id),
                      'title' => 'Return to Editor',
                  ),
              ));
            ?>

<iframe src="<?php echo module_newsletter::link_preview($newsletter_id);?>&show=true" frameborder="0" style="width:100%; height:700px; border:0;" background="transparent"></iframe>


    </td></tr></tbody></table>