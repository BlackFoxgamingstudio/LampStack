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

if(isset($_REQUEST['email'])){
	include(module_theme::include_ucm('includes/plugin_customer/pages/customer_admin_email.php'));
	return;
}

$page_type = 'Customers';
$page_type_single = 'Customer';

$current_customer_type_id = module_customer::get_current_customer_type_id();
if($current_customer_type_id > 0){
	$customer_type = module_customer::get_customer_type($current_customer_type_id);
	if($customer_type && !empty($customer_type['type_name'])){
		$page_type = $customer_type['type_name_plural'];
		$page_type_single = $customer_type['type_name'];
	}
}

if(!module_customer::can_i('view',$page_type)){
    redirect_browser(_BASE_HREF);
}


$customer_id = (int)$_REQUEST['customer_id'];
$customer = array();

$customer = module_customer::get_customer($customer_id);

if($customer_id>0 && $customer['customer_id']==$customer_id){
    $module->page_title = _l($page_type_single.': %s',$customer['customer_name']);
}else{
    $module->page_title = _l($page_type_single.': %s',_l('New'));
}
// check permissions.
if(class_exists('module_security',false)){
    if($customer_id>0 && $customer['customer_id']==$customer_id){
        // if they are not allowed to "edit" a page, but the "view" permission exists
        // then we automatically grab the page and regex all the crap out of it that they are not allowed to change
        // eg: form elements, submit buttons, etc..
		module_security::check_page(array(
            'category' => 'Customer',
            'page_name' => $page_type,
            'module' => 'customer',
            'feature' => 'Edit',
		));
    }else{
		module_security::check_page(array(
			'category' => 'Customer',
            'page_name' => $page_type,
            'module' => 'customer',
            'feature' => 'Create',
		));
	}
	module_security::sanatise_data('customer',$customer);
}


?>
<form action="" method="post" id="customer_form">
	<input type="hidden" name="_process" value="save_customer" />
	<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
	<input type="hidden" name="_redirect" value="" id="form_redirect" />

    <?php
    $required = array(
	    'fields' => array(
		    'customer_name' => 'Name',
		    'name' => 'Contact Name',
	    ))
    ;
    if(module_config::c('user_email_required',1)){
	    $required['fields']['email'] = true;
    }
    module_form::set_required($required);
    module_form::prevent_exit(array(
        'valid_exits' => array(
            // selectors for the valid ways to exit this form.
            '.submit_button',
        ))
    );

    module_form::print_form_auth();

    //!(int)$customer['customer_id'] &&
    if(isset($_REQUEST['move_user_id']) && (int)$_REQUEST['move_user_id']>0 && module_customer::can_i('create','Customers')){
        // we have to move this contact over to this customer as a new primary user id
        $customer['primary_user_id'] = (int)$_REQUEST['move_user_id'];
        ?>
        <input type="hidden" name="move_user_id" value="<?php echo $customer['primary_user_id'];?>">
        <?php
    }

    hook_handle_callback('layout_column_half',1);

    /** COMPANY INFORMATION **/

    if(class_exists('module_company',false) && module_company::can_i('view','Company') && module_company::is_enabled()){
	    $responsive_summary = array();
        $companys = module_company::get_companys();
	    foreach($companys as $company){
		    if(isset($customer['company_ids'][$company['company_id']]) || (!$customer_id && !module_company::can_i('edit','Company'))){
			    $responsive_summary[] = htmlspecialchars($company['name']);
		    }
        }
        $heading = array(
            'type' => 'h3',
            'title' => 'Company Information',
	        'responsive' => array(
		        'title' => 'Company',
		        'summary' => implode(', ',$responsive_summary),
	        ),
        );
        if(module_company::can_i('edit','Company')){
            $help_text = addcslashes(_l("Here you can select which Company this Customer belongs to. This is handy if you are running multiple companies through this system and you would like to separate customers between different companies."),"'");
            $heading['button'] =  array(
              'url' => '#',
              'onclick' => "alert('$help_text'); return false;",
              'title' => 'help',
          );
        }
        //print_heading($heading);
        $company_fields = array();
        foreach($companys as $company){
            $company_fields[] = array(
                'type' => 'hidden',
                'name' => "available_customer_company[".$company['company_id']."]",
                'value' => 1,
            );
            $company_fields[] = array(
                'type' => 'check',
                'name' => "customer_company[".$company['company_id']."]",
                'value' => $company['company_id'],
                'checked' => isset($customer['company_ids'][$company['company_id']]) || (!$customer_id && !module_company::can_i('edit','Company')),
                'label' => htmlspecialchars($company['name']),
            );
        }
        $fieldset_data = array(
            'heading' => $heading,
            'class' => 'tableclass tableclass_form tableclass_full',
            'elements' => array(
                'company' => array(
                    'title' => _l('Company'),
                    'fields' => $company_fields,
                ),
            )
        );
        echo module_form::generate_fieldset($fieldset_data);
    }

    /** CUSTOMER INFORMATION **/

    $responsive_summary = array();
    $responsive_summary[] = htmlspecialchars($customer['customer_name']);
    $fieldset_data = array(
        'heading' => array(
            'type' => 'h3',
            'title' => $page_type_single.' Information',
	        'responsive' => array(
		        'title' => $page_type_single,
		        'summary' => implode(', ',$responsive_summary),
	        ),
        ),
        'class' => 'tableclass tableclass_form tableclass_full',
        'elements' => array(
            'name' => array(
                'title' => _l('Name'),
                'field' => array(
                    'type' => 'text',
                    'name' => 'customer_name',
                    'value' => $customer['customer_name'],
                ),
            ),
            'type' => array(
                'title' => _l('Type'),
                'ignore' => (!module_customer::get_customer_types()),
                'field' => array(
                    'type' => 'select',
                    'name' => 'customer_type_id',
                    'value' => $customer['customer_type_id'],
                    'blank' => false,
                    'options' => module_customer::get_customer_types(),
                    'options_array_id' => 'type_name',
                ),
            ),
        ),
    );
    if(class_exists('module_extra',false) && module_extra::is_plugin_enabled() && module_extra::can_i('view',$page_type)){
	    $fieldset_data['extra_settings'] = array(
            'owner_table' => 'customer',
            'owner_key' => 'customer_id',
            'owner_id' => $customer_id,
            'layout' => 'table_row',
            'allow_new' => module_extra::can_i('create',$page_type),
            'allow_edit' => module_extra::can_i('edit',$page_type),
        );
    }
    if($customer_id && $customer_id!='new' && class_exists('module_file') && module_file::is_plugin_enabled()){
        ob_start();
        module_file::display_files(array(
            //'title' => 'Certificate Files',
            'owner_table' => 'customer',
            'owner_id' => $customer_id,
            //'layout' => 'list',
             'layout' => 'gallery',
             'editable' => module_security::is_page_editable(),
            )
        );
        $fieldset_data['elements']['logo'] = array(
            'title' => _l('Logo'),
            'field' => ob_get_clean(),
        );
    }

    echo module_form::generate_fieldset($fieldset_data);
    unset($fieldset_data);



    /** PRIMARY CONTACT DETAILS **/

    // we use the "user" module to find the user details
    // for the currently selected primary contact id
    if($customer['primary_user_id']){

        if(!module_user::can_i('view','All '.$page_type_single.' Contacts','Customer','customer') && $customer['primary_user_id'] != module_security::get_loggedin_id()){
            ob_start();
            echo '<div class="content_box_wheader"><table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form"><tbody><tr><td>';
            _e('Details hidden');
            echo '</td></tr></tbody></table></div>';
	        $responsive_summary = array();
		    $responsive_summary[] = htmlspecialchars($customer['customer_name']);
            $fieldset_data = array(
                'heading' => array(
                    'type' => 'h3',
                    'title' => 'Primary Contact Details',
			        'responsive' => array(
				        'title' => 'Primary Contact',
				        'summary' => implode(', ',$responsive_summary),
			        ),
                ),
                'class' => 'tableclass tableclass_form tableclass_full',
                'elements_before' => ob_get_clean(),
            );
            if($customer['primary_user_id']){
                $fieldset_data['heading']['button'] = array(
                    'title' => 'More',
                    'url' => module_user::link_open_contact($customer['primary_user_id'],false)
                );
            }
            echo module_form::generate_fieldset($fieldset_data);
            unset($fieldset_data);
        }else if(!module_user::can_i('edit','All '.$page_type_single.' Contacts','Customer','customer') && $customer['primary_user_id'] != module_security::get_loggedin_id()){
            // no permissions to edit.
            ob_start();
            echo '<div class="content_box_wheader"><table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form"><tbody><tr><td>';
	        ob_start();
            module_user::print_contact_summary($customer['primary_user_id'],'text',array('name','last_name','email'));
	        $short_user_details = ob_get_clean();
            echo '</td></tr></tbody></table></div>';
            $fieldset_data = array(
                'heading' => array(
                    'type' => 'h3',
                    'title' => 'Primary Contact Details',
			        'responsive' => array(
				        'title' => 'Primary Contact',
				        'summary' => htmlspecialchars($short_user_details),
			        ),
                ),
                'class' => 'tableclass tableclass_form tableclass_full',
                'elements_before' => ob_get_clean(),
            );
            if($customer['primary_user_id']){
                $fieldset_data['heading']['button'] = array(
                    'title' => 'More',
                    'url' => module_user::link_open_contact($customer['primary_user_id'],false)
                );
            }
            echo module_form::generate_fieldset($fieldset_data);
            unset($fieldset_data);
        }else{
            module_user::print_contact_form($customer['primary_user_id']);
        }
    }else{
        // hack to create new contact details.
        module_user::print_contact_form(false);
    }


    /*** ADDRESS **/

    if(class_exists('module_address',false)){
        module_address::print_address_form($customer_id,'customer','physical','Address');
    }



    /** ADVANCED AREA **/

    $fieldset_data = array(
        'heading' => array(
            'type' => 'h3',
            'title' => 'Advanced',
	        'responsive' => array(
		        'summary' => '',
	        ),
        ),
        'class' => 'tableclass tableclass_form tableclass_full',
        'elements' => array(),
    );
    if(module_customer::can_i('edit','Customer Staff')){
        $staff_members = module_user::get_staff_members();
        $staff_member_rel = array();
        foreach($staff_members as $staff_member){
            $staff_member_rel[$staff_member['user_id']] = $staff_member['name'];
        }
        if(!isset($customer['staff_ids']) || !is_array($customer['staff_ids']) || !count($customer['staff_ids'])){
            $customer['staff_ids']= array(false);
        }
        $fieldset_data['elements']['staff_ids'] = array(
            'title' => module_config::c('customer_staff_name','Staff'),
            'fields' => array(
                '<div id="staff_ids_holder" style="float:left;">',
                array(
                    'type' => 'select',
                    'name' => 'staff_ids[]',
                    'options' => $staff_member_rel,
                    'multiple' => 'staff_ids_holder',
                    'values' => $customer['staff_ids'],
                ),
                '</div>',
                _hr('Assign a staff member to this customer. Staff members are users who have EDIT permissions on Job Tasks. Click the plus sign to add more staff members. You can apply the "Only Assigned Staff" permission in User Role settings to restrict staff members to these customers.'),
            )
        );
    }else if(module_customer::get_customer_data_access() == _CUSTOMER_ACCESS_STAFF){
	    $staff_members = module_user::get_staff_members();
        $staff_member_rel = array();
        foreach($staff_members as $staff_member){
            $staff_member_rel[$staff_member['user_id']] = $staff_member['name'];
        }
	    if(isset($staff_member_rel[module_security::get_loggedin_id()])){
		    $fieldset_data['elements']['staff_ids'] = array(
	            'title' => module_config::c('customer_staff_name','Staff'),
	            'fields' => array(
	                array(
	                    'type' => 'hidden',
	                    'name' => 'single_staff_id',
	                    'value' => module_security::get_loggedin_id(),
	                ),
		            $staff_member_rel[module_security::get_loggedin_id()],
	            )
	        );
	    }
    }
    if(module_customer::can_i('edit','Customer Credit')){
        $fieldset_data['elements']['credit'] = array(
            'title' => _l('Credit'),
            'fields' => array(
                array(
                    'type' => 'currency',
                    'name' => 'credit',
                    'value' => number_out($customer['credit']),
                    'help' => 'If the customer is given a credit here you will have an option to apply this credit to an invoice. If a customer over pays an invoice you will be prompted to add that overpayment as credit onto their account.',
                ),
            )
        );
    }
    if(module_invoice::can_i('edit','Invoices')){
        if(isset($customer['default_tax'])){
            $fieldset_data['elements']['default_tax'] = array(
                'title' => _l('Default Tax'),
                'fields' => array(
                    array(
                        'type' => 'check',
                        'name' => 'default_tax_system',
                        'checked' => $customer['default_tax']<0,
                        'value' => 1,
                    ),
                    _l('Use system default (%s @ %s%%)',module_config::c('tax_name','TAX'),module_config::c('tax_percent',10)),
                    '<br/>',
                    _l('Or custom tax:'),
                    array(
                        'type' => 'text',
                        'name' => 'default_tax_name',
                        'value' => $customer['default_tax_name'],
                        'style' => 'width:30px;',
                    ),
                    ' @ ',
                    array(
                        'type' => 'text',
                        'name' => 'default_tax',
                        'value' => $customer['default_tax']>=0 ? $customer['default_tax'] : '',
                        'style' => 'width:35px;',
                    ),
                    '%',
                    _hr('If your customer needs a deafult tax rate that is different from the system default please enter it here.')
                )
            );
        }
        if(isset($customer['default_invoice_prefix'])){
            $fieldset_data['elements'][] = array(
                'title' => _l('Invoice Prefix'),
                'fields' => array(
                    array(
                        'type' => 'text',
                        'name' => 'default_invoice_prefix',
                        'value' => $customer['default_invoice_prefix'],
                        'help' => 'Every time an invoice is generated for this customer the INVOICE NUMBER will be prefixed with this value.',
                        'size' => 5,
                    ),
                )
            );
        }
	    $fieldset_data['elements'][] = array(
            'title' => _l('Statement'),
            'fields' => array(
                array(
                    'type' => 'submit',
	                'class' => 'submit_button',
                    'name' => 'butt_send_email',
                    'value' => _l('Send Email'),
                    'help' => 'Generate an email containing a summary of the account.',
                ),
            )
        );
    }

    echo module_form::generate_fieldset($fieldset_data);


    hook_handle_callback('layout_column_half',2);


    if($customer_id && $customer_id!='new'){

        if(class_exists('module_group',false) && module_group::is_plugin_enabled()){
            module_group::display_groups(array(
                 'title' => $page_type_single.' Groups',
                'owner_table' => 'customer',
                'owner_id' => $customer_id,
                'view_link' => $module->link_open($customer_id),

            ));
        }

        $note_summary_owners = array();
        // generate a list of all possible notes we can display for this customer.
        // display all the notes which are owned by all the sites we have access to

        // display all the notes which are owned by all the users we have access to
        foreach(module_user::get_contacts(array('customer_id'=>$customer_id)) as $val){
            $note_summary_owners['user'][] = $val['user_id'];
        }
        if(class_exists('module_website',false) && module_website::is_plugin_enabled()){
            foreach(module_website::get_websites(array('customer_id'=>$customer_id)) as $val){
                $note_summary_owners['website'][] = $val['website_id'];
            }
        }
        if(class_exists('module_job',false) && module_job::is_plugin_enabled()){
            foreach(module_job::get_jobs(array('customer_id'=>$customer_id)) as $val){
                $note_summary_owners['job'][] = $val['job_id'];
                foreach(module_invoice::get_invoices(array('job_id'=>$val['job_id'])) as $val){
                    $note_summary_owners['invoice'][$val['invoice_id']] = $val['invoice_id'];
                }
            }
        }
        if(class_exists('module_invoice',false) && module_invoice::is_plugin_enabled()){
            foreach(module_invoice::get_invoices(array('customer_id'=>$customer_id)) as $val){
                $note_summary_owners['invoice'][$val['invoice_id']] = $val['invoice_id'];
            }
        }
        if(class_exists('module_note',false) && module_note::is_plugin_enabled()){
            module_note::display_notes(array(
                'title' => 'All '.$page_type_single.' Notes',
                'owner_table' => 'customer',
                'owner_id' => $customer_id,
                'view_link' => $module->link_open($customer_id),
                'display_summary' => true,
                'summary_owners' => $note_summary_owners
                )
            );
        }


    }
    hook_handle_callback('customer_edit',$customer_id);

    hook_handle_callback('layout_column_half','end');

    $form_actions = array(
        'class' => 'action_bar action_bar_center',
        'elements' => array(
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'onclick' => "$('#form_redirect').val('".$module->link_open(false)."');",
                'value' => _l('Save and Return'),
            ),
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'value' => _l('Save'),
            ),
            array(
                'ignore' => !(module_customer::can_i('delete','Customers') && $customer_id > 0),
                'type' => 'delete_button',
                'name' => 'butt_del',
                'value' => _l('Delete'),
            ),
            array(
                'type' => 'button',
                'name' => 'cancel',
                'value' => _l('Cancel'),
                'class' => 'submit_button',
                'onclick' => "window.location.href='".$module->link_open(false)."';",
            ),
        ),
    );
    echo module_form::generate_form_actions($form_actions);

    ?>



</form>

