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


$ticket_count = 0;
switch(module_config::c('ticket_show_summary_type','unread')){
    case 'unread':
        $ticket_count = module_ticket::get_unread_ticket_count();
        break;
    case 'total':
    default:
        $ticket_count = module_ticket::get_total_ticket_count();
        break;
}

if($ticket_count>0){
    $module->page_title = _l('Tickets (%s)',$ticket_count);
}else{
    $module->page_title = _l('TIckets');
}


$search = (isset($_REQUEST['search']) && is_array($_REQUEST['search'])) ? $_REQUEST['search'] : array();
if(isset($_REQUEST['customer_id']) && (int)$_REQUEST['customer_id']>0){
    $search['customer_id'] = (int)$_REQUEST['customer_id'];
}else{
    $search['customer_id'] = false;
}


$search_statuses = module_ticket::get_statuses();
$search_statuses['2,3,5'] = 'New/Replied/In Progress';
if(!isset($search['status_id']) && module_ticket::can_edit_tickets()){
    $search['status_id'] = '2,3,5';
}

$tickets = module_ticket::get_tickets($search,true);
if(!isset($_REQUEST['nonext'])){
    $_SESSION['_ticket_nextprev'] = array();
    while($ticket = mysql_fetch_assoc($tickets)){
        $_SESSION['_ticket_nextprev'][] = $ticket['ticket_id'];
    }
    if(mysql_num_rows($tickets)>0){
        mysql_data_seek($tickets,0);
    }
}

$priorities = module_ticket::get_ticket_priorities();

?>

<h2>
    <?php if(module_ticket::can_i('create','Tickets')){ ?>
	<span class="button">
		<?php echo create_link("Add New ticket","add",module_ticket::link_open('new')); ?>
	</span>
    <?php } ?>
	<?php echo _l('Customer Tickets'); ?>
</h2>

<form action="" method="<?php echo _DEFAULT_FORM_METHOD;?>">

    <input type="hidden" name="customer_id" value="<?php echo isset($_REQUEST['customer_id']) ? (int)$_REQUEST['customer_id'] : '';?>">


<table class="search_bar" width="100%">
	<tr>
		<td>
			<?php echo _l('Number:');?>
		</td>
		<td>
			<input type="text" name="search[ticket_id]" value="<?php echo isset($search['ticket_id'])?htmlspecialchars($search['ticket_id']):''; ?>" size="5">
		</td>
		<td width="40">
			<?php echo _l('Subject:');?>
		</td>
		<td>
			<input type="text" name="search[generic]" value="<?php echo isset($search['generic'])?htmlspecialchars($search['generic']):''; ?>" size="10">
		</td>
		<td width="20">
			<?php echo _l('Date:');?>
		</td>
		<td>
			<input type="text" name="search[date_from]" value="<?php echo isset($search['date_from'])?htmlspecialchars($search['date_from']):''; ?>" class="date_field">
            <?php _e('to');?>
			<input type="text" name="search[date_to]" value="<?php echo isset($search['date_to'])?htmlspecialchars($search['date_to']):''; ?>" class="date_field">
		</td>
		<td width="30">
			<?php echo _l('Type:');?>
		</td>
		<td>
			<?php echo print_select_box(module_ticket::get_types(),'search[ticket_type_id]',isset($search['ticket_type_id'])?$search['ticket_type_id']:''); ?>
		</td>
		<td width="30">
			<?php echo _l('Status:');?>
		</td>
		<td>
			<?php echo print_select_box($search_statuses,'search[status_id]',isset($search['status_id'])?$search['status_id']:''); ?>
		</td>
		 <td width="30">
			<?php echo _l('Contact:');?>
		</td>
		<td>
			<input type="text" name="search[contact]" value="<?php echo isset($search['contact'])?htmlspecialchars($search['contact']):''; ?>" size="10">
		</td>
		 <td width="30">
			<?php echo _l('Priority:');?>
		</td>
		<td>
            <?php echo print_select_box(module_ticket::get_ticket_priorities(),'search[priority]',isset($search['priority'])?$search['priority']:''); ?>
		</td>
        <?php if(class_exists('module_envato',false)){ ?>
        <td width="27">
            <?php echo _l('Envato:');?>
        </td>
        <td>
            <?php echo print_select_box(module_envato::get_envato_items_rel(),'search[envato_item_id]',isset($search['envato_item_id'])?$search['envato_item_id']:''); ?>
        </td>
        <?php } ?>
		<td class="search_action">
			<?php echo create_link("Reset","reset",module_ticket::link_open(false)); ?>
			<?php echo create_link("Search","submit"); ?>
		</td>
	</tr>
</table>

<?php
$pagination = process_pagination($tickets,module_config::c('ticket_list_default_per_page',70),0,'ticket_list');
$colspan = 4;
?>

<?php echo $pagination['summary'];?>
    <?php echo $pagination['links'];?>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
	<thead>
	<tr class="title">
		<th><?php echo _l('Number'); ?></th>
		<th><?php echo _l('Subject'); ?></th>
		<th><?php echo _l('Last Date/Time'); ?></th>
		<th><?php echo _l('Contact'); ?></th>
    </tr>
    </thead>
    <tbody>
		<?php
		$c=0;
        $time = time();
        $today = strtotime(date('Y-m-d'));
        $seconds_into_today = $time - $today;

		foreach($pagination['rows'] as $ticket){
            //$ticket = module_ticket::get_ticket($ticket['ticket_id']);
            ?>
            <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
                <td class="row_action" nowrap="">
                    <?php echo module_ticket::link_open($ticket['ticket_id'],true,$ticket);?> (<?php echo $ticket['message_count'];?>)
                </td>
                <td>
                    <?php
                    // todo, pass off to envato module as a hook
                    $ticket['subject'] = preg_replace('#Message sent via your Den#','',$ticket['subject']);
                    if($ticket['priority']){

                    }
                    if($ticket['unread']){
                        echo '<strong>';
                        echo ' '._l('* '). ' ';
                        echo htmlspecialchars($ticket['subject']);
                        echo '</strong>';
                    }else{
                        echo htmlspecialchars($ticket['subject']);
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($ticket['last_message_timestamp']>0){
                        if($ticket['last_message_timestamp'] < $limit_time){
                            echo '<span class="important">';
                        }
                        echo print_date($ticket['last_message_timestamp'],true);
                        // how many days ago was this?
                        echo ' ';
                        //echo '<br>'.$seconds_into_today ."<br>".($ticket['last_message_timestamp']+1).'<br>';
                        if($ticket['last_message_timestamp']>=$today){
                            echo '<span class="success_text">';
                            _e('(today)');
                            echo '</span>';
                        }else{
                            $days = ceil(($today - $ticket['last_message_timestamp'])/86400);

                            _e(' (%s days)',abs($days));
                        }
                        if($ticket['last_message_timestamp'] < $limit_time){
                            echo '</span>';
                        }
                    }
                    ?>
                </td>
                <td>
                    <?php echo module_user::link_open($ticket['user_id'],true); ?>
                </td>
            </tr>
		<?php } ?>
	</tbody>
</table>
    <?php echo $pagination['links'];?>
</form>