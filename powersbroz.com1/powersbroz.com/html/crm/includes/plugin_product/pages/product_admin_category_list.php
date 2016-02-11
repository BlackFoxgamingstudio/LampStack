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


if(!module_config::can_i('view','Settings')){
    redirect_browser(_BASE_HREF);
}

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
$product_categories = module_product::get_product_categories($search);

$heading = array(
    'title' => 'Product Categories',
    'type' => 'h2',
    'main' => true,
    'button' => array(),
);
if(module_product::can_i('create','Products')) {
    $heading['button'][] = array(
        'title' => "Create New Category",
        'type'  => 'add',
        'url'   => module_product::link_open_category('new'),
    );
}
print_heading($heading);
?>

<form action="" method="post">

<?php
/** START TABLE LAYOUT **/
$table_manager = module_theme::new_table_manager();
$columns = array();
$columns['product_name'] = array(
        'title' => _l('Category Name'),
        'callback' => function($product){
            echo module_product::link_open_category($product['product_category_id'],true,$product);
        },
        'cell_class' => 'row_action',
    );
$table_manager->set_id('product_category_list');
$table_manager->set_columns($columns);
$table_manager->set_rows($product_categories);
$table_manager->pagination = true;
$table_manager->print_table();
/** END TABLE LAYOUT **/
?>

</form>