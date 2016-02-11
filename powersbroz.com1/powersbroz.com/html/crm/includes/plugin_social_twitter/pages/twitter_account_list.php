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

if(!module_social::can_i('view','Twitter','Social','social')){
    die('No permissions to view Twitter accounts');
}

$twitter_accounts = module_social_twitter::get_accounts();

$header_buttons = array();
$header_buttons[] = array(
    'url' => module_social_twitter::link_open('new',false),
    'title' => 'Add New Twitter Account',
    'type' => 'add',
);

print_heading(array(
    'type' => 'h2',
    'title' => 'Twitter Accounts',
    'button' => $header_buttons,
));

?>

<table class="tableclass tableclass_full tableclass_rows">
    <thead>
    <tr class="title">
        <th><?php echo _l('Twitter Account Name'); ?></th>
        <th><?php echo _l('Last Checked'); ?></th>
       <!-- <th><?php /*echo _l('Twitter Searches'); */?></th>-->
    </tr>
    </thead>
    <tbody>
    <?php
    $c=0;
    foreach($twitter_accounts as $twitter_account){ ?>
        <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
            <td class="row_action">
                <?php echo module_social_twitter::link_open($twitter_account['social_twitter_id'],true,$twitter_account); ?>
            </td>
            <td>
                <?php
                echo print_date($twitter_account['last_checked'],true);
                ?>
            </td>
            <!--<td>
                <?php
/*
                */?>
            </td>-->
        </tr>
    <?php } ?>
  </tbody>
</table>