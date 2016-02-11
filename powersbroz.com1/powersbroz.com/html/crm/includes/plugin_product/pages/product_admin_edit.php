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


if(!$module->can_i('view','Products') || !$module->can_i('edit','Products')){
    redirect_browser(_BASE_HREF);
}

// done in product_admin
//$product_id = (int)$_REQUEST['product_id'];
//$product = array();
//$product = module_product::get_product($product_id);

// check permissions.
if(class_exists('module_security',false)){
    if($product_id>0 && $product['product_id']==$product_id){
        // if they are not allowed to "edit" a page, but the "view" permission exists
        // then we automatically grab the page and regex all the crap out of it that they are not allowed to change
        // eg: form elements, submit buttons, etc..
		module_security::check_page(array(
            'category' => 'Product',
            'page_name' => 'Products',
            'module' => 'product',
            'feature' => 'Edit',
		));
    }else{
		module_security::check_page(array(
			'category' => 'Product',
            'page_name' => 'Products',
            'module' => 'product',
            'feature' => 'Create',
		));
	}
	module_security::sanatise_data('product',$product);
}

?>
<form action="" method="post" id="product_form">
	<input type="hidden" name="_process" value="save_product" />
	<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />

    <?php
    module_form::set_required(array(
        'fields' => array(
            'name' => 'Name',
        ))
    );
    module_form::prevent_exit(array(
        'valid_exits' => array(
            // selectors for the valid ways to exit this form.
            '.submit_button',
        ))
    );


	$fieldset_data = array(
	    'heading' => array(
	        'type' => 'h3',
	        'title' => 'Product Information',
	    ),
	    'class' => 'tableclass tableclass_form tableclass_full',
	    'elements' => array(),
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Name',
	    'fields' => array(
	        array(
	            'type' => 'text',
	            'name' => 'name',
	            'value' => $product['name'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Category',
	    'fields' => array(
	        array(
	            'type' => 'select',
	            'name' => 'product_category_id',
		        'options' => module_product::get_product_categories(),
		        'options_array_id' => 'product_category_name',
	            'value' => $product['product_category_id'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Hours/Quantity',
	    'fields' => array(
	        array(
	            'type' => 'text',
	            'name' => 'quantity',
	            'value' => $product['quantity'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Amount',
	    'fields' => array(
	        array(
	            'type' => 'currency',
	            'name' => 'amount',
	            'value' => $product['amount'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Description',
	    'fields' => array(
	        array(
	            'type' => 'textarea',
	            'name' => 'description',
	            'value' => $product['description'],
	        ),
	    )
	);
    $types = module_job::get_task_types();
    $types['-1'] = _l('Default');
	$fieldset_data['elements'][] = array(
	    'title' => 'Task Type',
	    'fields' => array(
	        array(
	            'type' => 'select',
	            'name' => 'default_task_type',
		        'options' => $types,
	            'value' => isset($product['default_task_type']) ? $product['default_task_type'] : -1,
		        'blank' => false,
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Billable',
	    'fields' => array(
	        array(
	            'type' => 'checkbox',
	            'name' => 'billable',
	            'value' => isset($product['billable']) ? $product['billable'] : 1,
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Taxable',
	    'fields' => array(
	        array(
	            'type' => 'checkbox',
	            'name' => 'taxable',
	            'value' => isset($product['taxable']) ? $product['taxable'] : 1,
	        ),
	    )
	);

	echo module_form::generate_fieldset($fieldset_data);
	unset($fieldset_data);


    $form_actions = array(
        'class' => 'action_bar action_bar_center',
        'elements' => array(
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'value' => _l('Save'),
            ),
            array(
	            'ignore' => !(int)$product_id,
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

