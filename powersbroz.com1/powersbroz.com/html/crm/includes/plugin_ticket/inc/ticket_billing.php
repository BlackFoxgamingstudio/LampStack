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
if($ticket_id > 0 && module_config::c('ticket_allow_billing',1) && module_ticket::can_edit_tickets()){
	$done_in_ticket_billing = true;
	$responsive_summary = array();
	$quotes = array();
	if(class_exists('module_quote',false) && module_quote::is_plugin_enabled()) {
		$quotes = module_quote::get_quotes( array( 'ticket_id' => $ticket_id ) );
		foreach($quotes as $quote){
			$responsive_summary[] = module_quote::link_open($quote['quote_id'],true,$quote);
		}
	}
	$fieldset_data = array(
        'heading' => array(
            'type' => 'h3',
            'title' => 'Ticket Billing',
	        'responsive' => array(
		        'summary' => implode(', ',$responsive_summary),
	        ),
        ),
        'class' => 'tableclass tableclass_form tableclass_full',
        'elements' => array(),
    );

	$c = array();
    $res = module_customer::get_customers();
    while($row = array_shift($res)){
        $c[$row['customer_id']] = $row['customer_name'];
    }
    if($ticket['customer_id']<0)$ticket['customer_id']=false;

    $fieldset_data['elements'][] = array(
        'title' => _l('Customer'),
        'fields' => array(
            array(
                'type' => 'select',
                'name' => 'change_customer_id',
                'value' => $ticket['customer_id'],
	            'options' => $c,
            ),
            array(
                'type' => 'button',
                'name' => 'new_customer',
                'value' => _l('New'),
                'onclick' => "window.location.href='".module_customer::link_open('new',false) . "&move_user_id=".$ticket['user_id']."';",
            ),
        )
    );
	if(class_exists('module_quote',false) && module_quote::is_plugin_enabled()){
		$quote_list = '';
		foreach($quotes as $quote){
			$quote_list .= module_quote::link_open($quote['quote_id'],true,$quote) .' (<a href="#" onclick="ucm.ticket.add_to_message($(this).data(\'link\'));return false;" data-link="<a href=\''.module_quote::link_public($quote['quote_id']).'\'>View Quote</a>">insert link</a>) <br/>';
		}
		$fieldset_data['elements'][] = array(
	        'title' => _l('Quotes'),
	        'fields' => array(
		        $quote_list,
	            array(
	                'type' => 'button',
	                'name' => 'new_quote',
	                'value' => _l('New'),
	                'onclick' => "window.location.href='".module_quote::link_open('new',false) . "&ticket_id=".$ticket_id."';",
	            ),
	        )
	    );
	}


    echo module_form::generate_fieldset($fieldset_data);
}