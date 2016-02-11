<?php
 class module_custom_link extends module_base{
     public function init(){ }
     public function get_menu($holding_module=false,$holding_page=false,$type=false){
         $links=array();
         if(!$holding_module){
             // rendering the main menu:
             $links[]=array(
                  'm'=>'custom_link',
                  'p'=>'your_custom_page',
                 'name'=>'MindMap',
                 'order'=>999999,
             );
         }else if($holding_module == 'customer'){
             // rendering the customer menu
             $links[]=array(
              'name'=>'Customer Submenu Link',
              'm'=>'custom_link',
              'p'=>'your_custom_page',
              'order'=>999999,
              'args'=>array(
                'user_id'=>false,
                'some_other_id'=>123,
              ),
              'holder_module' => 'customer',
              'holder_module_page' => 'customer_admin_open',
              'menu_include_parent' => 0,
              'icon_name' => 'user',
              );
         }
         return $links;

     }
 }