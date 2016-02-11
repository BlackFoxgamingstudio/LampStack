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

if(!$invoice_safe)die('failed');

$invoice_id = (int)$_REQUEST['invoice_id'];
$invoice = module_invoice::get_invoice($invoice_id);
if($invoice_id>0 && $invoice && $invoice['invoice_id']==$invoice_id){
    $module->page_title = _l('Invoice: #%s',htmlspecialchars($invoice['name']));
	if(class_exists('module_security',false)){

        // make sure current customer can access this invoice
        if(!module_security::can_access_data('invoice',$invoice,$invoice_id)){
            echo 'Data access denied. Sorry.';
            exit;
        }

        module_security::check_page(array(
            'category' => 'Invoice',
            'page_name' => 'Invoices',
            'module' => 'invoice',
            'feature' => 'edit',
		));
	}
}else{
    $invoice_id = 0;
    $invoice = module_invoice::get_invoice($invoice_id);
	if(class_exists('module_security',false)){
		module_security::check_page(array(
            'category' => 'Invoice',
            'page_name' => 'Invoices',
            'module' => 'invoice',
            'feature' => 'create',
		));
	}
	module_security::sanatise_data('invoice',$invoice);
}
$invoice_items = module_invoice::get_invoice_items($invoice_id,$invoice);
$invoice_locked = ($invoice['date_sent'] && $invoice['date_sent'] != '0000-00-00') || ($invoice['date_paid'] && $invoice['date_paid'] != '0000-00-00');

$customer_data = array();
if($invoice['customer_id']){
    $customer_data = module_customer::get_customer($invoice['customer_id']);
}

$show_task_dates = module_config::c('invoice_task_list_show_date',1);
$colspan = 2;
if($show_task_dates)$colspan++;
?>


	<script type="text/javascript">
                    function setamount(a,invoice_item_id,rate){
                        var ee = parseFloat(a);
                        if(!rate)rate = $('#'+invoice_item_id+'invoice_itemrate').val(); //<?php echo $invoice['hourly_rate'];?>;
                        if(ee>0){
                            $('#'+invoice_item_id+'invoice_itemamount').val(ee * rate);
                        }
                    }
                    function editinvoice_item(invoice_item_id,hours){
                        $('#invoice_item_preview_'+invoice_item_id).hide();
                        $('#invoice_item_edit_'+invoice_item_id).show();
                        if(hours>0){
                            $('#complete_'+invoice_item_id).val(hours);
                            if(typeof $('#complete_t_'+invoice_item_id)[0] != 'undefined'){
                                $('#complete_t_'+invoice_item_id)[0].checked = true;
                            }
                        }else{
                            $('#invoice_item_desc_'+invoice_item_id)[0].focus();
                        }
                    }
                    $(function(){
                        $('.task_toggle_long_description').live('click',function(event){
                            event.preventDefault();
                            $(this).parent().find('.task_long_description').slideToggle(function(){
                                if($('textarea.edit_task_long_description').length>0){
                                    $('textarea.edit_task_long_description')[0].focus();
                                }
                            });
                            return false;
                        });
                    });
                </script>

<form action="" method="post" id="invoice_form">
	<input type="hidden" name="_process" value="save_invoice" class="no_permissions" />
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
    <?php if($invoice['customer_id'] && !isset($_REQUEST['change_customer'])){ ?>
    <input type="hidden" name="customer_id" value="<?php echo $invoice['customer_id']; ?>" />
    <?php } ?>
    <input type="hidden" name="job_id" value="<?php echo isset($invoice['job_id']) ? (int)$invoice['job_id'] : 0; ?>" />
    <?php if(isset($_REQUEST['as_deposit'])){ ?>
    <input type="hidden" name="deposit_job_id" value="<?php echo isset($invoice['job_id']) ? (int)$invoice['job_id'] : 0; ?>" />
    <?php } ?>
    <input type="hidden" name="total_tax_rate" value="<?php echo $invoice['total_tax_rate']; ?>" />
    <input type="hidden" name="total_tax_name" value="<?php echo htmlspecialchars($invoice['total_tax_name']); ?>" />
    <input type="hidden" name="hourly_rate" value="<?php echo htmlspecialchars($invoice['hourly_rate']); ?>" />


    <?php

    $fields = array(
    'fields' => array(
        'name' => 'Name',
    ));
    module_form::set_required(
        $fields
    );
    module_form::prevent_exit(array(
        'valid_exits' => array(
            // selectors for the valid ways to exit this form.
            '.submit_button',
            '.save_invoice_item',
            '.save_invoice_payment',
            '.delete',
            '.apply_discount',
        ))
    );
    

    ?>

	<table cellpadding="10" width="100%">
		<tbody>
			<tr>
				<td valign="top" width="35%">
					<h3><?php echo _l('%sCredit Note Details',(!$invoice_id?_l('New '):'')); ?> (BETA!)</h3>



					<table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form tableclass_full">
						<tbody>
							<tr>
								<th class="width1">
									<?php echo _l('Credit Note #'); ?>
								</th>
								<td>
                                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($invoice['name']); ?>" />
								</td>
							</tr>
							<tr>
								<th class="width1">
									<?php echo _l('Invoice #'); ?>
								</th>
								<td>
                                    <?php echo module_invoice::link_open($invoice['credit_note_id'],true); ?>
								</td>
							</tr>
							<tr>
								<th>
									<?php echo _l('Status'); ?>
								</th>
								<td>
									<?php echo print_select_box(module_invoice::get_statuses(),'status',$invoice['status'],'',true,false,true); ?>
								</td>
							</tr>
							<tr>
								<th>
									<?php echo _l('Created Date'); ?>
								</th>
								<td>
									<input type="text" name="date_create" class="date_field" value="<?php echo print_date($invoice['date_create']);?>">
								</td>
							</tr>
                            <?php if((int)$invoice_id){ ?>
							<tr>
								<th>
									<?php echo _l('Sent Date'); ?>
								</th>
								<td>
									<input type="text" name="date_sent" id="date_sent" class="date_field" value="<?php echo print_date($invoice['date_sent']);?>">
								</td>
							</tr>
                            <?php } ?>

                            <tr>
                                <th>
                                    <?php echo _l('Tax'); ?>
                                </th>
                                <td>
                                    <input type="text" name="total_tax_name" value="<?php echo htmlspecialchars($invoice['total_tax_name']);?>" style="width:30px;">
                                    @
                                    <input type="text" name="total_tax_rate" value="<?php echo htmlspecialchars($invoice['total_tax_rate']);?>" style="width:35px;">%

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo _l('Currency'); ?>
                                </th>
                                <td>
                                    <?php echo print_select_box(get_multiple('currency','','currency_id'),'currency_id',$invoice['currency_id'],'',false,'code'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php echo _l('Hourly Rate'); ?>
                                </th>
                                <td>
                                    <?php echo currency('<input type="text" name="hourly_rate" class="currency" value="'.$invoice['hourly_rate'].'">',true,$invoice['currency_id']);
                                    echo _h('This hourly rate will be applied to all manual tasks (tasks that did not come from jobs) in this invoice');
                                    ?>
                                    <?php
                                    $other_rates = array();
                                    foreach($invoice_items as $invoice_item){
                                        if(isset($invoice_item['hourly_rate']) && $invoice_item['hourly_rate'] != $invoice['hourly_rate'] && $invoice_item['hourly_rate']>0){
                                            $other_rates[dollar($invoice_item['hourly_rate'])] = true;
                                        }
                                    }
                                    if(count($other_rates)){
                                        _e("(and %s)",implode(', ',array_keys($other_rates)));
                                    }
                                    ?>
                                </td>
                            </tr>

                            <?php if(count($invoice['job_ids'])){ ?>
							<tr>
								<th>
									<?php echo _l('Linked Job'); ?>
								</th>
								<td>
									<?php
                                    foreach($invoice['job_ids'] as $job_id){
                                        if((int)$job_id>0){
                                            echo module_job::link_open($job_id,true);
                                            echo "<br/>\n";
                                        }
                                    } ?>
								</td>
							</tr>
                            <?php } ?>
                            <tr>
                                <th>
                                    <?php echo _l('Customer'); ?>
                                </th>
                                <td>
                                    <?php
                                    if(!$invoice['customer_id'] || isset($_REQUEST['change_customer'])){
                                        // allow them to pick customer.
                                        $c = array();
                                        $customers = module_customer::get_customers();
                                        foreach($customers as $customer){
                                            $c[$customer['customer_id']] = $customer['customer_name'];
                                        }
                                        echo print_select_box($c,'customer_id',$invoice['customer_id']);
                                        if($invoice['customer_id'] && module_customer::can_i('view','Customers')){ ?>
                                            <a href="<?php echo module_customer::link_open($invoice['customer_id'],false);?>"><?php _e('Open');?></a>
                                            <?php
                                        }
                                    }else{
                                        echo module_customer::link_open($invoice['customer_id'],true);
                                    }
                                     ?>
                                </td>
                            </tr>
                            <?php if($invoice['customer_id']){ ?>
                            <tr>
                                <th>
                                    <?php echo _l('Contact'); ?>
                                </th>
                                <td>
                                    <?php
                                        $c = array();
                                        $res = module_user::get_contacts(array('customer_id'=>$invoice['customer_id']));
                                        while($row = array_shift($res)){
                                            $c[$row['user_id']] = $row['name'].' '.$row['last_name'];
                                        }
                                        if($invoice['user_id'] && !isset($c[$invoice['user_id']])){
                                            // this option isn't in the listing. add it in.
                                            $user_temp = module_user::get_user($invoice['user_id'],false);
                                            $c[$invoice['user_id']] = $user_temp['name'].' '.$user_temp['last_name'] . ' '._l('(under different customer)');
                                        }
                                        echo print_select_box($c,'user_id',$invoice['user_id']);
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>

						</tbody>
                        <?php
                         module_extra::display_extras(array(
                            'owner_table' => 'invoice',
                            'owner_key' => 'invoice_id',
                            'owner_id' => $invoice['invoice_id'],
                            'layout' => 'table_row',
                                 'allow_new' => module_job::can_i('create','Invoices'),
                                 'allow_edit' => module_job::can_i('create','Invoices'),
                            )
                        );
                        ?>
					</table>

                    
                    <?php if((int)$invoice_id>0){ ?>

                        <?php hook_handle_callback('invoice_sidebar',$invoice_id); ?>

                    <?php } ?>
                    
                    <?php
                    if($invoice_id && $invoice_id!='new'){
                        $note_summary_owners = array();
                        // generate a list of all possible notes we can display for this invoice.
                        // display all the notes which are owned by all the sites we have access to

                        module_note::display_notes(array(
                            'title' => 'Credit Note Notes',
                            'owner_table' => 'invoice',
                            'owner_id' => $invoice_id,
                            'view_link' => module_invoice::link_open($invoice_id),
                            'public' => array(
                                    'enabled'=>true,
                                    'title'=>'Public',
                                    'text'=>'Yes, show this note in invoice',
                                    'help'=>'If this is ticked then this note will be available to the customer and will be included in the {INVOICE_NOTES} shortcode in the invoice template.',
                                )
                            )
                        );
                        if(module_job::can_i('edit','Invoices')){
                            module_email::display_emails(array(
                                'title' => 'Credit Note Emails',
                                'search' => array(
                                    'invoice_id' => $invoice_id,
                                )
                            ));
                        }
                    }
                    ?>

                    <?php if ((int)$invoice_id > 0 && (!$invoice['date_sent'] || $invoice['date_sent'] == '0000-00-00') && module_security::is_page_editable()){ ?>

                    <h3 class="error_text"><?php _e('Send Credit Note');?></h3>
                        <div class="tableclass_form content">

                            <p style="text-align: center;">
                                <input type="submit" name="butt_email" id="butt_email2" value="<?php echo _l('Email Invoice'); ?>" class="submit_button" />
                                <?php _h('Click this button to email the invoice to the customer from the system'); ?>
                            </p>
                            <p style="text-align: center;">
                                <a href="#" onclick="$('#date_sent').val('<?php echo print_date(date('Y-m-d'));?>'); $('#invoice_form')[0].submit(); return false;" class="uibutton"><?php _e('Mark invoice as sent');?></a>
                                <?php _h('This invoice has not been sent yet. When this invoice has been sent to the customer please click this button or enter a "sent date" into the form above.'); ?>
                            </p>
                        </div>

                    <?php } ?>



                    <?php if((int)$invoice_id > 0){

                        ?>

                        <?php if(module_invoice::can_i('edit','Invoices')){ ?>

                        <h3><?php _e('Advanced');?></h3>
                        <table class="tableclass tableclass_form tableclass_full" cellpadding="0" cellspacing="0">
                            <tbody>
                            <tr>
                                <th class="width1">
                                    <?php _e('Customer Link');?>
                                </th>
                                <td>
                                    <a href="<?php echo module_invoice::link_public($invoice_id);?>" target="_blank"><?php echo _l('Click to view external link');?></a> <?php _h('You can send this link to your customer and they can preview the invoice, pay for the invoice as well as optionally download the invoice as a PDF'); ?>
                                </td>
                            </tr>
                            <!-- <tr>
                                <th>
                                    <?php _e('Cancel Date');?>
                                </th>
                                <td>
                                    <input type="text" name="date_cancel" value="<?php echo print_date($invoice['date_cancel']);?>" class="date_field">
                                    <?php _h('If the invoice has been cancelled set the date here. Payment reminders for this invoice will no longer be generated.'); ?>
                                </td>
                            </tr> -->
                            <?php // check if there are multiple invoice templates available
                            $find_other_templates = 'credit_note_pdf';
                            $current_template = isset($invoice['invoice_template_print']) && strlen($invoice['invoice_template_print']) ? $invoice['invoice_template_print'] : 'credit_note_print';
                            if(function_exists('convert_html2pdf') && isset($find_other_templates) && strlen($find_other_templates) && isset($current_template) && strlen($current_template)){
                                $other_templates = array();
                                foreach(module_template::get_templates() as $possible_template){
                                    if(strpos($possible_template['template_key'],$find_other_templates)!==false){
                                        // found another one!
                                        $other_templates[$possible_template['template_key']] = $possible_template['description'];
                                    }
                                }
                                if(count($other_templates)>1){
                                    ?>
                                    <tr>
                                        <th>
                                            <?php _e('PDF Template');?>
                                        </th>
                                        <td>
                                            <select name="invoice_template_print" id="invoice_template_print">
                                                <?php foreach($other_templates as $other_template_key => $other_template_name){ ?>
                                                    <option value="<?php echo htmlspecialchars($other_template_key);?>"<?php echo $current_template==$other_template_key ? ' selected':'';?>><?php echo htmlspecialchars($other_template_key);?></option>
                                                <?php } ?>
                                            </select>
                                            <?php _h('Choose the default template for PDF printing and PDF emailing. Name your custom templates invoice_print_SOMETHING for them to appear in this listing.'); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                            </tbody>
                        </table>

                        <?php } ?>
                        <?php
                    } ?>


				</td>
                <td valign="top">


					<h3><?php echo _l('Credit Note items'); ?></h3>
                    <div class="content_box_wheader">

                    <table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows tableclass_full">
                        <thead>
                        <tr>
                            <?php if(module_config::c('invoice_task_numbers',1)){ ?>
                            <th width="10">#</th>
                            <?php } ?>
                            <th class="invoice_item_column"><?php _e('Description');?></th>
                            <?php if($show_task_dates){ ?>
                            <th width="10%"><?php _e('Date');?></th>
                            <?php } ?>
                            <th width="10%"><?php echo module_config::c('task_hours_name',_l('Hours'));?></th>
                            <th width="10%"><?php _e('Price');?></th>
                            <th width="10%"><?php _e('Sub-Total');?></th>
                            <th width="80"> </th>
                        </tr>
                        </thead>
                        <?php if(!$invoice_locked && module_invoice::can_i('edit','Invoices')){ ?>
						<tbody>
                        <tr>
                            <?php if(module_config::c('invoice_task_numbers',1)){ ?>
                            <td>
                                <input type="text" name="invoice_invoice_item[new][task_order]" value="" id="next_task_number" size="3" class="edit_task_order">
                            </td>
                            <?php } ?>
                            <td>
                                <input type="text" name="invoice_invoice_item[new][description]" value="" style="width:90%;">
                                <a href="#" class="task_toggle_long_description">&raquo;</a>
                                <div class="task_long_description">
                                    <textarea name="invoice_invoice_item[new][long_description]" id="new_task_long_description" class="edit_task_long_description no_permissions"></textarea>
                                </div>
                            </td>
                            <?php if($show_task_dates){ ?>
                            <td>
                                <input type="text" name="invoice_invoice_item[new][date_done]" value="" class="date_field">
                            </td>
                            <?php } ?>
                            <td>
                                <input type="text" name="invoice_invoice_item[new][hours]" value="" id="newinvoice_itemqty" size="3" style="width:25px;" onchange="setamount(this.value,'new');" onkeyup="setamount(this.value,'new');">
                            </td>
                            <td>
                                <input type="text" name="invoice_invoice_item[new][hourly_rate]" value="<?php echo $invoice['hourly_rate'];?>" id="newinvoice_itemrate"  size="3" style="width:35px;" onchange="setamount($('#newinvoice_itemqty').val(),'new');" onkeyup="setamount($('#newinvoice_itemqty').val(),'new');">
                            </td>
                            <td nowrap="">
                                <?php echo currency('<input type="text" name="invoice_invoice_item[new][amount]" value="" id="newinvoice_itemamount" class="currency">',true,$invoice['currency_id']);?>
                            </td>
                            <td align="center">
                                <input type="submit" name="save" value="<?php _e('Add Item');?>" class="save_invoice_item small_button">
                            </td>
                        </tr>
						</tbody>
                        <?php }
                        ?>
                        <?php
                        $c=0;
                        /*[new3] => Array
                        (
                            [task_id] => 46
                            [job_id] => 15
                            [hours] => 0.00    ***********
                            [amount] => 20.00    ***********
                            [hourly_rate] => 60.00    ***********
                            [taxable] => 1
                            [billable] => 1
                            [fully_completed] => 1
                            [description] => test with fixed price ($20 one) sdgsdfg
                            [long_description] =>
                            [date_due] => 2012-05-18
                            [date_done] => 2012-07-16
                            [invoice_id] =>
                            [user_id] => 1
                            [approval_required] => 0
                            [task_order] => 8
                            [create_user_id] => 1
                            [update_user_id] => 1
                            [date_created] => 2012-07-16
                            [date_updated] => 2012-07-28
                            [id] => 46
                            [completed] =>
                            [custom_description] =>
                        )*/
                        foreach($invoice_items as $invoice_item_id => $invoice_item_data){



                            ?>
                                <?php if(!$invoice_locked){ ?>
                                <tbody id="invoice_item_edit_<?php echo $invoice_item_id;?>" style="display:none;">
                                <tr>
                                    <?php if(module_config::c('invoice_task_numbers',1)){ ?>
                                    <td>
                                        <input type="text" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][task_order]" value="<?php
                                            if(isset($invoice_item_data['custom_task_order']) && (int)$invoice_item_data['custom_task_order']>0){
                                                echo $invoice_item_data['custom_task_order'];
                                            }else if(isset($invoice_item_data['task_order']) && $invoice_item_data['task_order']>0){
                                                echo $invoice_item_data['task_order'];
                                            }
                                            ?>" size="3" class="edit_task_order">
                                    </td>
                                    <?php } ?>
                                    <td>
                                        <input type="hidden" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][task_id]" value="<?php echo htmlspecialchars($invoice_item_data['task_id']);?>">

                                        <input type="text" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][description]" value="<?php echo htmlspecialchars($invoice_item_data['custom_description'] ? $invoice_item_data['custom_description'] : $invoice_item_data['description']);?>" style="width:90%;" id="invoice_item_desc_<?php echo $invoice_item_id;?>">
                                        <br/>
                                        <textarea name="invoice_invoice_item[<?php echo $invoice_item_id;?>][long_description]" style="width:90%;"><?php echo htmlspecialchars($invoice_item_data['custom_long_description'] ? $invoice_item_data['custom_long_description'] : $invoice_item_data['long_description']);?></textarea>
                                        <?php if($invoice_item_data['task_id']){
                                            // echo htmlspecialchars($invoice_item_data['custom_description'] ? $invoice_item_data['custom_description'] : $invoice_item_data['description']);
                                            echo '<br/>';
                                            echo _l('(linked to job: %s)',module_job::link_open($invoice_item_data['job_id'],true));
                                        } else {
                                        } ?>
                                        <a href="#" onclick="if(confirm('<?php _e('Delete invoice item?');?>')){$(this).parent().find('input').val(''); $('#invoice_form')[0].submit();} return false;" class="delete ui-state-default ui-corner-all ui-icon ui-icon-trash" style="display:inline-block; float:right;">[x]</a>
                                    </td>
                                    <?php if($show_task_dates){ ?>
                                    <td>
                                        <input type="text" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][date_done]" value="<?php echo print_date($invoice_item_data['date_done']);?>" class="date_field">
                                    </td>
                                    <?php } ?>
                                    <td>
                                        <input type="text" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][hours]" value="<?php echo $invoice_item_data['hours'];?>" size="3" style="width:25px;"  onchange="setamount(this.value,'<?php echo $invoice_item_id;?>',<?php echo $invoice_item_data['task_hourly_rate'];?>);" id="<?php echo $invoice_item_id;?>invoice_itemqty" onkeyup="setamount(this.value,'<?php echo $invoice_item_id;?>',<?php echo $invoice_item_data['task_hourly_rate'];?>);">
                                    </td>
                                    <td>
                                        <input type="text" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][hourly_rate]" value="<?php echo $invoice_item_data['task_hourly_rate'];?>" id="<?php echo $invoice_item_id;?>invoice_itemrate"  size="3" style="width:35px;" onchange="setamount($('#<?php echo $invoice_item_id;?>invoice_itemqty').val(),<?php echo $invoice_item_id;?>);" onkeyup="setamount($('#<?php echo $invoice_item_id;?>invoice_itemqty').val(),<?php echo $invoice_item_id;?>);">
                                    </td>
                                    <td nowrap="">
                                        <?php echo currency('<input type="text" name="invoice_invoice_item['.$invoice_item_id.'][amount]" value="'.$invoice_item_data['invoice_item_amount'].'" id="'.$invoice_item_id.'invoice_itemamount" class="currency">',true,$invoice['currency_id']);?>
                                    </td>
                                    <td nowrap="nowrap" align="center">
                                        <input type="submit" name="ts" class="save_invoice_item small_button" value="<?php _e('Save');?>">
                                    </td>
                                </tr>
                                <tr>
                                    <?php if(module_config::c('invoice_task_numbers',1)){ ?>
                                    <td>
                                    </td>
                                    <?php } ?>
                                    <td>
                                    </td>
                                    <?php if($show_task_dates){ ?>
                                    <td>
                                    </td>
                                    <?php } ?>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td colspan="2">
                                        <input type="hidden" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][taxable_t]" value="1">
                                        <input type="checkbox" name="invoice_invoice_item[<?php echo $invoice_item_id;?>][taxable]" id="invoice_taxable_item_<?php echo $invoice_item_id;?>" value="1" <?php echo $invoice_item_data['taxable'] ? ' checked':'';?> tabindex="17"> <label for="invoice_taxable_item_<?php echo $invoice_item_id;?>"><?php _e('Item is taxable');?></label>
                                    </td>

                                </tr>
                                </tbody>
                                <?php } ?>
                                <tbody id="invoice_item_preview_<?php echo $invoice_item_id;?>">
                                <tr class="<?php echo $c++%2 ? 'odd':'even';?>">
                                    <?php if(module_config::c('invoice_task_numbers',1)){ ?>
                                    <td>
                                        <?php
                                        if(isset($invoice_item_data['custom_task_order']) && (int)$invoice_item_data['custom_task_order']>0){
                                            echo $invoice_item_data['custom_task_order'];
                                        }else if(isset($invoice_item_data['task_order']) && $invoice_item_data['task_order']>0){
                                            echo $invoice_item_data['task_order'];
                                        }
                                        ?>
                                    </td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                        $desc = $invoice_item_data['custom_description'] ? htmlspecialchars($invoice_item_data['custom_description']) : htmlspecialchars($invoice_item_data['description']);
                                        if($invoice_locked){
                                            echo $desc;
                                        }else{ ?>
                                            <a href="#" onclick="editinvoice_item('<?php echo $invoice_item_id;?>',0); return false;"><?php echo (!trim($desc)) ? 'N/A' : $desc;?></a>
                                        <?php }
                                        $long_description = trim($invoice_item_data['custom_long_description'] ? htmlspecialchars($invoice_item_data['custom_long_description']) : htmlspecialchars($invoice_item_data['long_description']));
                                        if($long_description != ''){ ?>
                            <a href="#" class="task_toggle_long_description">&raquo;</a>
                            <div class="task_long_description" <?php if(module_config::c('invoice_show_long_desc',1)){ ?> style="display:block;" <?php } ?>><?php echo forum_text($long_description);?></div>
                            <?php }else{ ?>
    &nbsp;
    <?php } ?>
                                    </td>
                                    <?php if($show_task_dates){ ?>
                                    <td>
                                        <?php echo print_date($invoice_item_data['date_done']);?>
                                    </td>
                                    <?php } ?>
                                    <td>
                                        <?php echo $invoice_item_data['hours']>0 ? $invoice_item_data['hours'] : '-';?>
                                    </td>
                                    <td>

                                        <?php
                                        if($invoice_item_data['task_hourly_rate']!=0){
                                            echo dollar($invoice_item_data['task_hourly_rate'],true,$invoice['currency_id']);
                                        }else{
                                            echo '-';
                                        }
                                            ?>
                                    </td>
                                    <td>
                                        <span class="currency">
                                        <?php
                                            echo dollar($invoice_item_data['invoice_item_amount'],true,$invoice['currency_id']);
                                            ?>
                                        </span>
                                    </td>
                                    <td align="center">
                                        &nbsp;
                                    </td>
                                </tr>
                            </tbody>
                        <?php } ?>
                        <?php if(true){ //(int)$invoice_id>0 ?>
                        <tfoot style="border-top:1px solid #CCC;">
                        <tr>
                            <td colspan="<?php echo $colspan;?>">
                                &nbsp;
                            </td>
                            <td>
                                <?php _e('Sub:');?>
                            </td>
                            <td>
                                <span class="currency">
                                <?php echo dollar($invoice['total_sub_amount'],true,$invoice['currency_id']);?>
                                </span>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>


                            <tr>
                                <td colspan="<?php echo $colspan;?>">
                                    &nbsp;
                                </td>
                                <td>
                                    <?php _e('Tax:');?>
                                </td>
                                <td>
                                    <span class="currency">
                                    <?php echo dollar($invoice['total_tax'],true,$invoice['currency_id']);?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $invoice['total_tax_name'] ;?> =
                                    <?php echo $invoice['total_tax_rate'] . '%' ;?>
                                </td>
                            </tr>
                        <tr>
                            <td colspan="<?php echo $colspan;?>">
                                &nbsp;
                            </td>
                            <td>
                                <?php _e('Total:');?>
                            </td>
                            <td>
                                <span class="currency" style="text-decoration: underline; font-weight: bold;">
                                    <?php echo dollar($invoice['total_amount'],true,$invoice['currency_id']);?>
                                </span>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        </tfoot>
<?php } ?>
					</table>
                    </div> <!-- content box -->

                    
                </td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center">
					<input type="submit" name="butt_save" id="butt_save" value="<?php echo _l('Save Credit Note'); ?>" class="submit_button save_button" />
					<?php if((int)$invoice_id){ ?>
                        <?php if($invoice['date_paid'] && $invoice['date_paid']!='0000-00-00'){ ?>
                            <input type="submit" name="butt_email" id="butt_email" value="<?php echo _l('Email Receipt'); ?>" class="submit_button save_button" />
                        <?php }else{ ?>
					        <input type="submit" name="butt_email" id="butt_email" value="<?php echo _l('Email Credit Note'); ?>" class="submit_button" />
                        <?php } ?>
                        <?php if(function_exists('convert_html2pdf')){
                            if(!module_invoice::can_i('edit','Invoices')){ ?>
                                <input type="button" name="butt_print" id="butt_print" value="<?php echo _l('Print PDF'); ?>" class="submit_button no_permissions" onclick="window.location.href='<?php echo module_invoice::link_public_print($invoice_id);?>';" />
                            <?php }else{ ?>
                                <input type="submit" name="butt_print" id="butt_print" value="<?php echo _l('Print PDF'); ?>" class="submit_button" />
                            <?php } ?>
                        <?php } ?>
					<?php } ?>
					<?php if((int)$invoice_id && module_invoice::can_i('delete','Invoices')){ ?>
					<input type="submit" name="butt_del" id="butt_del" value="<?php echo _l('Delete'); ?>" class="submit_button delete_button" />
					<?php } ?>
					<input type="button" name="cancel" value="<?php echo _l('Cancel'); ?>" onclick="window.location.href='<?php echo module_invoice::link_open(false); ?>';" class="submit_button" />
				</td>
			</tr>
		</tbody>
	</table>


</form>
