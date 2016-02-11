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

if(!$job_safe)die('denied');

$job_task_creation_permissions = module_job::get_job_task_creation_permissions();

$job_id = (int)$_REQUEST['job_id'];
$job = module_job::get_job($job_id);
$staff_members = module_user::get_staff_members();
$staff_member_rel = array();
foreach($staff_members as $staff_member){
    $staff_member_rel[$staff_member['user_id']] = $staff_member['name'];
}

$c = array();
$customers = module_customer::get_customers();
foreach($customers as $customer){
    $c[$customer['customer_id']] = $customer['customer_name'];
}
if(count($c)==1){
    $job['customer_id']=key($c);
}


// check permissions.
if(class_exists('module_security',false)){
    module_security::check_page(array(
        'category' => 'Job',
        'page_name' => 'Jobs',
        'module' => 'job',
        'feature' => 'create',
    ));
}

$job_tasks = module_job::get_tasks($job_id);
?>

<script type="text/javascript">
    var completed_tasks_hidden = false; // set with session variable / cookie
    var editing_task_id = false;
    function show_completed_tasks(){

    }
    function hide_completed_tasks(){

    }
	function setamount(a,task_id){
		var amount = 0;
	    if(a.match(/:/)){
		    var bits = a.split(':');
		    var hours = bits[0].length > 0 ? parseInt(bits[0]) : 0;
		    var minutes = 0;
		    if(typeof bits[1] != 'undefined' && bits[1].length > 0){
			    if(bits[1].length == 1){
				    // it's a 0 or a 123456789
				    if(bits[1] == "0"){
					    minutes = 0;
				    }else{
					    minutes = parseInt(bits[1] + "0");
				    }
			    }else{
				    minutes = parseInt(bits[1]);
			    }
		    }
		    if(hours > 0 || minutes > 0){
			    amount = <?php echo $job['hourly_rate'];?> * hours;
			    amount += <?php echo $job['hourly_rate'];?> * (minutes / 60);
	        }
	    }else{
		    var bits = a.split('<?php echo module_config::c('currency_decimal_separator','.');?>');
		    var number = bits[0].length > 0 ? parseInt(bits[0]) : 0;
		    number += typeof bits[1] != 'undefined' && parseInt(bits[1]) > 0 ? parseFloat("." + bits[1]) : 0;
		    amount = <?php echo $job['hourly_rate'];?> * number;
	    }
        var places = <?php echo str_pad('1',(int)module_config::c('currency_decimal_places',2)+1,'0',STR_PAD_RIGHT);?>;
        amount = Math.round(amount * places) / places;
	    $('#'+task_id+'taskamount').val(amount);
	    $('#'+task_id+'complete_hour').val(a);
    }
    function canceledittask(){
        if(editing_task_id){
            $('#task_edit_'+editing_task_id).html(loading_task_html);
            editing_task_id = false;
        }
        $('.task_edit').hide();
        $('.task_preview').show();
    }

    var last_job_name = '';
    function setnewjobtask(){
        var job_name = $('#job_name').val();
        var current_new_task = $('#task_desc_new').val();
        if(current_new_task == '' || current_new_task == last_job_name){
            $('#task_desc_new').val(job_name);
            last_job_name = job_name;
        }
    }
    $(function(){
        $('.task_toggle_long_description').click(function(event){
            event.preventDefault();
            $(this).parent().find('.task_long_description').slideToggle();
            return false;
        });
        <?php if(module_config::c('job_create_task_as_name',0)){ ?>
        $('#job_name').keyup(setnewjobtask).change(setnewjobtask);
        <?php } ?>

        if(typeof ucm.job != 'undefined'){
            ucm.job.init();
        }
    });
</script>

<form action="" method="post" id="job_form" class="job_form_new">
    <input type="hidden" name="_process" value="save_job" />
    <input type="hidden" name="job_id" value="<?php echo $job_id; ?>" />
    <input type="hidden" name="customer_id" value="<?php echo (int)$job['customer_id']; ?>" />
    <input type="hidden" name="quote_id" value="<?php echo (int)$job['quote_id']; ?>" />


    <?php

    $fields = array(
    'fields' => array(
        'name' => 'Name',
    ));
    module_form::set_required(
        $fields
    );
    //module_form::set_default_field('task_desc_new');
    module_form::set_default_field('job_name');
    module_form::prevent_exit(array(
        'valid_exits' => array(
            // selectors for the valid ways to exit this form.
            '.submit_button',
            '.save_task',
            '.delete',
            '.task_defaults',
        ))
    );


    hook_handle_callback('layout_column_half',1,'35');


    /**** JOB DETAILS ****/
    $fieldset_data = array(
        'heading' => array(
            'type' => 'h3',
            'title' => 'Job Details',
        ),
        'class' => 'tableclass tableclass_form tableclass_full',
        'elements' => array(
            'name' => array(
                'title' => 'Job Title',
                'field' => array(
                    'type' => 'text',
                    'name' => 'name',
                    'value' => $job['name'],
                ),
            ),
            'type' => array(
                'title' => 'Type',
                'field' => array(
                    'type' => 'select',
                    'name' => 'type',
                    'value' => $job['type'],
                    'blank' => false,
                    'options' => module_job::get_types(),
                    'allow_new' => true,
                ),
            ),
            'hourly_rate' => array(
                'title' => 'Hourly Rate',
                'ignore' => !module_invoice::can_i('view','Invoices'),
                'field' => array(
                    'type' => 'currency',
                    'name' => 'hourly_rate',
                    'value' => number_out($job['hourly_rate']), // todo: is number_out($job['hourly_rate']) needed?
                ),
            ),
            'status' => array(
                'title' => 'Status',
                'field' => array(
                    'type' => 'select',
                    'name' => 'status',
                    'value' => $job['status'],
                    'blank' => false,
                    'options' => module_job::get_statuses(),
                    'allow_new' => true,
                ),
            ),
            'date_quote' => array(
                'title' => 'Quote Date',
	            'ignore' => !module_config::c('job_allow_quotes',0),
                'field' => array(
                    'type' => 'date',
                    'name' => 'date_quote',
                    'value' => print_date($job['date_quote']),
                    'help' => 'This is the date the Job was quoted to the Customer. Once this Job Quote is approved, the Start Date will be set below.',
                ),
            ),
            'date_start' => array(
                'title' => 'Start Date',
                'field' => array(
                    'type' => 'date',
                    'name' => 'date_start',
                    'value' => print_date($job['date_start']),
                    'help' => 'This is the date the Job is scheduled to start work. This can be a date in the future. If you have the Calendar, this is the date that will be used for the Calendar event.',
                ),
            ),
            'time_start' => array(
                'title' => 'Start Time',
	            'ignore' => !(class_exists('module_calendar',false) && module_config::c('job_show_times',1)),
                'field' => array(
                    'type' => 'time',
                    'name' => 'time_start',
                    'value' => isset($job['time_start']) ? $job['time_start'] : '',
                    'help' => 'This is the time the Job is scheduled to start.  If you have the Calendar, this is the time that will be used for the Calendar event.',
                ),
            ),
            'time_end' => array(
                'title' => 'End Time',
	            'ignore' => !(class_exists('module_calendar',false) && module_config::c('job_show_times',1)),
                'field' => array(
                    'type' => 'time',
                    'name' => 'time_end',
                    'value' => isset($job['time_end']) ? $job['time_end'] : '',
                    'help' => 'This is the time the Job is scheduled to finish.  If you have the Calendar, this is the time that will be used for the Calendar event.',
                ),
            ),
            'date_due' => array(
                'title' => 'Due Date',
                'field' => array(
                    'type' => 'date',
                    'name' => 'date_due',
                    'value' => print_date($job['date_due']),
                ),
            ),
            'date_completed' => array(
                'title' => 'Finished Date',
                'field' => array(
                    'type' => 'date',
                    'name' => 'date_completed',
                    'value' => print_date($job['date_completed']),
                ),
            ),
            'user_id' => array(
                'title' => 'Staff Member',
	            'ignore' => !module_config::c('job_allow_staff_assignment',1),
                'field' => array(
                    'type' => 'select',
	                'options' => $staff_member_rel,
                    'name' => 'user_id',
                    'value' => $job['user_id'],
	                'blank' => false,
	                'help' => 'Assign a staff member to this job. You can also assign individual tasks to different staff members. Staff members are users who have EDIT permissions on Job Tasks.',
                ),
            ),
        ),
        'extra_settings' => array(
            'owner_table' => 'job',
            'owner_key' => 'job_id',
            'owner_id' => $job['job_id'],
            'layout' => 'table_row',
             'allow_new' => module_job::can_i('create','Jobs'),
             'allow_edit' => module_job::can_i('create','Jobs'),
        ),
    );
    $incrementing = false;
    if(!isset($job['taxes']) || !count($job['taxes'])){
        $job['taxes'][] = array(); // at least have 1?
    }
    foreach($job['taxes'] as $tax){
        if(isset($tax['increment']) && $tax['increment']){
            $incrementing = true;
        }
    }
    ob_start();
    ?>
    <span class="job_tax_increment">
        <input type="checkbox" name="tax_increment_checkbox" id="tax_increment_checkbox" value="1" <?php echo $incrementing ? ' checked' : '';?>> <?php _e('incremental');?>
    </span>
    <div id="job_tax_holder">
    <?php
    foreach($job['taxes'] as $id=>$tax){  ?>
        <div class="dynamic_block">
            <input type="hidden" name="tax_ids[]" class="dynamic_clear" value="<?php echo isset($tax['job_tax_id']) ? (int)$tax['job_tax_id'] : 0;?>">
            <input type="text" name="tax_names[]" class="dynamic_clear" value="<?php echo isset($tax['name']) ? htmlspecialchars($tax['name']) : '';?>" style="width:30px;">
            @
            <input type="text" name="tax_percents[]" class="dynamic_clear" value="<?php echo isset($tax['percent']) ? htmlspecialchars(number_out($tax['percent'], module_config::c('tax_trim_decimal', 1), module_config::c('tax_decimal_places',module_config::c('currency_decimal_places',2)))) : '';?>" style="width:35px;">%
            <a href="#" class="add_addit" onclick="seladd(this); ucm.job.update_job_tax(); return false;">+</a>
            <a href="#" class="remove_addit" onclick="selrem(this); ucm.job.update_job_tax(); return false;">-</a>
        </div>
    <?php } ?>
    </div>
    <script type="text/javascript">
        set_add_del('job_tax_holder');
    </script>
    <?php
    $fieldset_data['elements']['tax'] = array(
        'title' => 'Tax',
        'fields' => array(
            ob_get_clean(),
        ),
    );
    $fieldset_data['elements']['currency'] = array(
        'title' => 'Currency',
        'field' => array(
            'type' => 'select',
            'options' => get_multiple('currency','','currency_id'),
            'name' => 'currency_id',
            'value' => $job['currency_id'],
            'options_array_id' => 'code',
        ),
    );

    echo module_form::generate_fieldset($fieldset_data);
    unset($fieldset_data);


    if(module_config::c('job_enable_description',1)){

        if(!module_job::can_i('edit','Jobs') && !$job['description']){
            // no description, no ability to edit description, don't show anything.
        }else{
            // can edit description
            $fieldset_data = array(
                'heading' => array(
                    'title' => _l('Job Description'),
                    'type' => 'h3',
                ),
                'class' => 'tableclass tableclass_form tableclass_full',

            );
            if(module_job::can_i('edit','Jobs')){
                $fieldset_data['elements'] = array(
                    array(
                        'field' => array(
                            'type' => 'wysiwyg',
                            'name' => 'description',
                            'value' => $job['description'],
                        ),
                    )
                );
            }else{
                $fieldset_data['elements'] = array(
                    array(
                        'fields' => array(
                            module_security::purify_html($job['description']),
                        ),
                    )
                );
            }
            echo module_form::generate_fieldset($fieldset_data);
            unset($fieldset_data);
        }
    }

    /**** ADVANCED ***/

    if(module_job::can_i('view','Job Advanced')){
        ob_start();
        ?>
        <table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form tableclass_full">
            <tbody>
                <?php if(class_exists('module_website',false) && module_website::is_plugin_enabled()){ ?>
                <tr>
                    <th class="width1">
                        <?php echo _l('Assign '.module_config::c('project_name_single','Website')); ?>
                    </th>
                    <td>
                        <?php
                        $c = array();
                        // change between websites within this customer?
                        // or websites all together?
                        $res = module_website::get_websites(array('customer_id'=>(isset($_REQUEST['customer_id'])?(int)$_REQUEST['customer_id']:false)));
                        //$res = module_website::get_websites();
                        while($row = array_shift($res)){
                            $c[$row['website_id']] = $row['name'];
                        }
                        echo print_select_box($c,'website_id',$job['website_id']);
                        ?>
                        <?php if($job['website_id'] && module_website::can_i('view','Websites')){ ?>
                            <a href="<?php echo module_website::link_open($job['website_id'],false);?>"><?php _e('Open');?></a>
                        <?php } ?>
                        <?php _h('This will be the '.module_config::c('project_name_single','Website').' this job is assigned to - and therefor the customer. Every job should have a'.module_config::c('project_name_single','Website').' assigned. Clicking the open link will take you to the '.module_config::c('project_name_single','Website'));?>
                    </td>
                </tr>
                <?php }else if(!class_exists('module_website',false) && module_config::c('show_ucm_ads',1)){ ?>
                <tr>
                    <th>
                        <?php echo module_config::c('project_name_single','Website'); ?>
                    </th>
                    <td>
                        (website option available in <a href="http://codecanyon.net/item/ultimate-client-manager-pro-edition/2621629?ref=dtbaker" target="_blank">UCM Pro Edition</a>)
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <th>
                        <?php echo _l('Assign Customer'); ?>
                    </th>
                    <td>
                        <?php
                        $c = array();
                        $customers = module_customer::get_customers();
                        foreach($customers as $customer){
                            $c[$customer['customer_id']] = $customer['customer_name'];
                        }
                        echo print_select_box($c,'customer_id',$job['customer_id']);
                        ?>
                        <?php if($job['customer_id'] && module_customer::can_i('view','Customers')){ ?>
                        <a href="<?php echo module_customer::link_open($job['customer_id'],false);?>"><?php _e('Open');?></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php if(class_exists('module_quote',false) && module_quote::is_plugin_enabled() && module_quote::can_i('view','Quotes')){ ?>
                <tr>
                    <th>
                        <?php echo _l('Assign Quote'); ?>
                    </th>
                    <td>
                        <?php
                        $c = array();
                        $quotes = module_quote::get_quotes(array('customer_id'=>$job['customer_id']));
                        foreach($quotes as $quote){
                            $c[$quote['quote_id']] = $quote['name'];
                        }
                        echo print_select_box($c,'quote_id',$job['quote_id']);
                        ?>
                        <?php if($job['quote_id'] && module_quote::can_i('view','Quotes')){ ?>
                        <a href="<?php echo module_quote::link_open($job['quote_id'],false);?>"><?php _e('Open');?></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <th class="width1">
                        <?php echo _l('Renewal Date'); ?>
                    </th>
                    <td>
                        <?php if(file_exists('includes/plugin_invoice/pages/invoice_recurring.php')){ ?>
                        <input type="text" name="date_renew" class="date_field" value="<?php echo print_date($job['date_renew']);?>">
                        <br/>
                        <?php
                        $element = array(
                            'type' => 'checkbox',
                            'name' => 'renew_auto',
                            'value' => isset($job['renew_auto']) && $job['renew_auto'],
                            'label' => 'Automatically Renew',
                            'help' => 'This Job will be automatically renewed on this date. A new Job will be created as a copy from this Job.',
                        );
                        module_form::generate_form_element($element);
                        echo '<br/>';
                        $element = array(
                            'type' => 'checkbox',
                            'name' => 'renew_invoice',
                            'value' => isset($job['renew_invoice']) && $job['renew_invoice'],
                            'label' => 'Automatically Invoice',
                            'help' => 'When this Job is renewed the tasks will be automatically completed and an invoice will be automatically created and emailed to the customer.',
                        );
                        module_form::generate_form_element($element);
                        }else{
                            ?>
                            (recurring jobs available in <a href="http://codecanyon.net/item/ultimate-client-manager-pro-edition/2621629?ref=dtbaker" target="_blank">UCM Pro Edition</a>)
                            <?php
                        }
                        ?>
                    </td>

                    <tr>
                        <th>
                            <?php _e('Task Type');?>
                        </th>
                        <td>
                            <?php
                            echo print_select_box(module_job::get_task_types(),'default_task_type',isset($job['default_task_type'])?$job['default_task_type']:0,'',false);?>
                            <?php _h('The default is hourly rate + amount. This will show the "Hours" column along with an "Amount" column. Inputing a number of hours will auto complete the price based on the job hourly rate. <br>Quantity and Amount will allow you to input a Quantity (eg: 2) and an Amount (eg: $100) and the final price will be $200 (Quantity x Amount). The last option "Amount Only" will just have the amount column for manual input of price. Change the advanced setting "default_task_type" between 0, 1 and 2 to change the default here.'); ?>

                        </td>
                    </tr>
		            <tr>
		                <th>
		                    <?php _e('Discount Amount'); ?>
		                </th>
		                <td>
		                    <?php  echo (!module_security::is_page_editable()) ?
		                        '<span class="currency">'.dollar($job['discount_amount'],true,$job['currency_id']).'</span>' :
		                        currency('<input type="text" name="discount_amount" value="'.number_out($job['discount_amount']).'" class="currency">') ?>
		                    <?php  _h('Here you can apply a before tax discount to this job. You can name this anything, eg: DISCOUNT, CREDIT, REFUND, etc..'); ?>
		                </td>
		            </tr>
		            <tr>
		                <th>
		                    <?php _e('Discount Name'); ?>
		                </th>
		                <td>
		                    <?php  echo (!module_security::is_page_editable()) ?
		                        htmlspecialchars(_l($job['discount_description'])) :
		                        '<input type="text" name="discount_description" value="'.htmlspecialchars(_l($job['discount_description'])).'" style="width:80px;">' ?>
		                </td>
		            </tr>
		            <tr>
		                <th>
		                    <?php _e('Discount Type'); ?>
		                </th>
		                <td>
		                    <?php echo print_select_box(array('0'=>_l('Before Tax'),1=>_l('After Tax')),'discount_type',$job['discount_type']);?>
		                </td>
		            </tr>
                    <?php
                    if(class_exists('module_extra',false) && module_extra::is_plugin_enabled()){
                $job_default_tasks = module_job::get_default_tasks();
                    if(module_config::c('job_enable_default_tasks',1) && count($job_default_tasks)){


                    ?>
                    <tr>
                        <th>
                            <?php _e('Task Defaults'); ?>
                        </th>
                        <td>
                            <?php
                            echo print_select_box($job_default_tasks,'default_task_list_id','','',true,'');
                            ?>
                            <input type="button" name="i" id="insert_saved" value="<?php _e('Insert');?>" class="small_button task_defaults">
                            <input type="hidden" name="default_tasks_action" id="default_tasks_action" value="0">
                            <script type="text/javascript">
                                $(function(){
                                    $('#insert_saved').click(function(){
                                        // set a flag and submit our form.
                                        $('#default_tasks_action').val('insert_default');
                                        $('#job_form')[0].submit();
                                    });
                                });
                            </script>
                            <?php _h('Here you can insert a previously saved set of default tasks.'); ?>
                        </td>
                    </tr>
                    <?php }
                    } ?>
                </tr>

            </tbody>
        </table>
        <?php

        $fieldset_data = array(
            'heading' => array(
                'title' => _l('Advanced'),
                'type' => 'h3',
            ),
            'elements_before' => ob_get_clean(),
        );
        echo module_form::generate_fieldset($fieldset_data);
        unset($fieldset_data);
    }
$form_actions = array(
        'class' => 'action_bar action_bar_left',
        'elements' => array(
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'value' => _l('Save Job'),
            ),
            array(
                'type' => 'button',
                'name' => 'cancel',
                'value' => _l('Cancel'),
                'class' => 'submit_button',
                'onclick' => "window.location.href='".module_job::link_open(false)."';",
            ),
        ),
    );
    echo module_form::generate_form_actions($form_actions);


    hook_handle_callback('layout_column_half',2,'65');

    if(module_job::can_i('edit','Job Tasks')||module_job::can_i('view','Job Tasks')){

        $header = array(
            'title_final' => _l('Job Tasks %s',''),
            'button' => array(),
            'type' => 'h3',
        );

        ob_start();
        ?>

        <table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows tableclass_full">
            <thead>
            <tr>
                <?php if(module_config::c('job_show_task_numbers',1)){ ?>
                <th width="10">#</th>
                <?php } ?>
                <th class="task_column task_width"><?php _e('Description');?></th>
                <th width="10" class="task_type_label">
                <?php if($job['default_task_type']==_TASK_TYPE_AMOUNT_ONLY){
                    }else if($job['default_task_type']==_TASK_TYPE_QTY_AMOUNT){
                    _e(module_config::c('task_qty_name',_l('Qty')));
                    }else if($job['default_task_type']==_TASK_TYPE_HOURS_AMOUNT){
                    _e(module_config::c('task_hours_name',_l('Hours')));
                    } ?>
                </th>
                <th width="72"><?php _e('Amount');?></th>
                <th width="83"><?php _e('Due Date');?></th>
                <?php if(module_config::c('job_allow_staff_assignment',1)){ ?>
                <th width="78"><?php _e('Staff');?></th>
                <?php } ?>
                <th width="32" nowrap="nowrap">%</th>
                <th width="60"> </th>
            </tr>
            </thead>
            <?php
                if(module_security::is_page_editable() && module_job::can_i('create','Job Tasks') && $job_task_creation_permissions != _JOB_TASK_CREATION_NOT_ALLOWED){ ?>
            <tbody>
            <tr>
                <?php if(module_config::c('job_show_task_numbers',1)){ ?>
                    <td valign="top">&nbsp;</td>
                <?php } ?>
                <td valign="top">
                    <input type="text" name="job_task[new][description]" id="task_desc_new" class="edit_task_description" value=""><?php
                    if(class_exists('module_product',false)){
                        module_product::print_job_task_dropdown('new');
                    } ?><a href="#" class="task_toggle_long_description ui-icon ui-icon-plus">&raquo;</a>
                    <div class="task_long_description">
                        <textarea name="job_task[new][long_description]" id="task_long_desc_new" class="edit_task_long_description"></textarea>
                    </div>
                </td>
                <td valign="top">
                    <input type="text" name="job_task[new][hours]" value="" size="3" style="width:25px;" onchange="setamount(this.value,'new');" onkeyup="setamount(this.value,'new');" id="task_hours_new">
                </td>
                <td valign="top" nowrap="">
                    <?php echo currency('<input type="text" name="job_task[new][amount]" value="" id="newtaskamount" class="currency">');?>
                </td>
                <td valign="top">
                    <input type="text" name="job_task[new][date_due]" value="<?php echo print_date($job['date_due']);?>" class="date_field">
                </td>
                <?php if(module_config::c('job_allow_staff_assignment',1)){ ?>
                    <td valign="top">
                        <?php echo print_select_box($staff_member_rel,'job_task[new][user_id]',
                            isset($staff_member_rel[module_security::get_loggedin_id()]) ? module_security::get_loggedin_id() : false, 'job_task_staff_list', ''); ?>
                    </td>
                <?php } ?>
                <td valign="top">
                    <input type="checkbox" name="job_task[new][new_fully_completed]" value="1">
                </td>
                <td align="center" valign="top">
                    <input type="submit" name="save" value="<?php _e('New Task');?>" class="save_task small_button">

                    <!-- these are overridden from the products selection -->
                    <input type="hidden" name="job_task[new][billable_t]" value="1">
                    <input type="hidden" name="job_task[new][billable]" value="1" id="billable_t_new">
                    <input type="hidden" name="job_task[new][taxable_t]" value="1">
                    <input type="hidden" name="job_task[new][taxable]" value="<?php echo module_config::c('task_taxable_default',1) ? 1 : 0;?>" id="taxable_t_new">
                    <input type="hidden" name="job_task[new][manual_task_type]" value="-1" id="manual_task_type_new">
                </td>
            </tr>
            </tbody>
            <?php } ?>
            <?php
            $c=0;
            $task_number = 0;
            foreach($job_tasks as $task_id => $task_data){
                $task_number++;
                if(module_security::is_page_editable() && module_job::can_i('edit','Job Tasks')){ ?>
                    <tbody id="task_edit_<?php echo $task_id;?>" style="display:none;" class="task_edit"></tbody>
                <?php  } else {
                    $task_editable = false;
                }
                echo module_job::generate_task_preview($job_id,$job,$task_id,$task_data,false,array(
                    'from_quote' => isset($_REQUEST['from_quote_id']),
                ));
                ?>
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][description]" value="<?php echo htmlspecialchars($task_data['description']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][long_description]" value="<?php echo htmlspecialchars($task_data['long_description']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][hours]" value="<?php echo htmlspecialchars($task_data['hours']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][amount]" value="<?php echo htmlspecialchars($task_data['amount']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][date_due]" value="<?php echo htmlspecialchars($task_data['date_due']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][manual_task_type]" value="<?php echo htmlspecialchars($task_data['manual_task_type']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][billable_t]" value="1">
	            <input type="hidden" name="job_task[new<?php echo $task_number;?>][taxable_t]" value="1">
	            <input type="hidden" name="job_task[new<?php echo $task_number;?>][billable]" value="<?php echo htmlspecialchars($task_data['billable']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][taxable]" value="<?php echo htmlspecialchars($task_data['taxable']);?>">
                <input type="hidden" name="job_task[new<?php echo $task_number;?>][user_id]" value="<?php echo (int)$task_data['user_id'];?>">
                <?php
            } ?>
            </table>

            <?php
        $fieldset_data = array(
            'heading' => $header,
            'elements_before' => ob_get_clean(),
        );
        echo module_form::generate_fieldset($fieldset_data);
        unset($fieldset_data);

    }  // end can i view job tasks


hook_handle_callback('layout_column_half','end');

     ?>

</form>