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

$task_decimal_places = module_config::c('task_amount_decimal_places',-1);
if($task_decimal_places < 0){
    $task_decimal_places = false; // use default currency dec places.
}
$task_decimal_places_trim = module_config::c('task_amount_decimal_places_trim',0);


?>
<tr class="task_row_<?php echo $quote_task_id;?> task_preview <?php echo ($task_editable) ? ' task_editable' : '';?>" rel="<?php echo $quote_task_id;?>">
        <?php if($show_task_numbers){ ?>
            <td valign="top" class="task_order task_drag_handle"><?php echo $task_data['task_order'];?></td>
        <?php } ?>
        <td valign="top">
            <?php
            if($task_editable){ // $task_editable ?>
                <a href="#" onclick="edittask(<?php echo $quote_task_id;?>,0); return false;" class=""><?php echo (!trim($task_data['description'])) ? 'N/A' : htmlspecialchars($task_data['description']);?></a>
<?php }else{ ?>
                    <span class=""><?php echo (!trim($task_data['description'])) ? 'N/A' : htmlspecialchars($task_data['description']);?></span>
<?php }


            if(isset($task_data['long_description']) && $task_data['long_description'] != ''){ ?>
                <a href="#" class="task_toggle_long_description">&raquo;</a>
                <div class="task_long_description" <?php if(module_config::c('quote_tasks_show_long_desc',0)){ ?> style="display:block;" <?php } ?>><?php echo forum_text(trim($task_data['long_description']));?></div>
            <?php }else{ ?>
                &nbsp;
            <?php }
            if(function_exists('hook_handle_callback'))hook_handle_callback('quote_task_after',$task_data['quote_id'],$task_data['quote_task_id'],$quote,$task_data);
            ?>
        </td>
        <td valign="top" class="task_drag_handle">
            <?php
            if($task_data['hours'] == 0 && $task_data['manual_task_type'] == _TASK_TYPE_AMOUNT_ONLY){
            // only amount, no hours or qty
            }else{
	            if($task_data['hours']!=0){
		            if($task_data['manual_task_type'] == _TASK_TYPE_HOURS_AMOUNT && function_exists('decimal_time_out')){
			            $hours_value = decimal_time_out($task_data['hours']);
		            }else {
			            $hours_value = number_out( $task_data['hours'], true );
		            }
	            }else{
		            $hours_value = false;
	            }
                echo $hours_value ? $hours_value : '-';
            }

            ?>
        </td>
        <td valign="top" class="task_drag_handle">
            <span class="currency <?php echo $task_data['billable'] ? 'success_text' : 'error_text';?>">
            <?php echo $task_data['amount'] != 0 ? dollar($task_data['amount'],true,$quote['currency_id'],$task_decimal_places_trim,$task_decimal_places) : dollar($task_data['hours']*$quote['hourly_rate'],true,$quote['currency_id'],$task_decimal_places_trim,$task_decimal_places);?>
                <?php if($task_data['manual_task_type'] == _TASK_TYPE_QTY_AMOUNT){
                    $full_amount = $task_data['hours'] * $task_data['amount'];
                    if($full_amount != $task_data['amount']){
                        echo '<br/>('.dollar($full_amount,true,$quote['currency_id']).')';
                    }
                } ?>
            </span>
        </td>
        <?php if(module_config::c('quote_allow_staff_assignment',1)){ ?>
            <td valign="top" class="task_drag_handle">
                <?php echo isset($staff_member_rel[$task_data['user_id']]) ? $staff_member_rel[$task_data['user_id']] : ''; ?>
            </td>
        <?php } ?>
        <td align="center" valign="top">
            <?php if($task_editable){ ?>
                <?php if(module_config::c('quote_task_edit_icon',0)){ // old icon:  ?>
                <a href="#" class="ui-state-default ui-corner-all ui-icon ui-icon-pencil" title="<?php _e( 'Edit' );?>" onclick="edittask(<?php echo $quote_task_id;?>,<?php echo ($task_data['hours']>0?($task_data['hours']):1);?>); return false;"><?php _e('Edit');?></a>
                <?php }else{ ?>
                    <input type="button" name="edit" value="<?php _e('Edit');?>" class="small_button" onclick="edittask(<?php echo $quote_task_id;?>,<?php echo ($task_data['hours']>0?($task_data['hours']):1);?>); return false;">
                <?php } ?>

            <?php } ?>
        </td>
    </tr>