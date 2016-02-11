<?php
 class module_custom_link2 extends module_base{
     public function init(){ }
     public function get_menu($holding_module=false,$holding_page=false,$type=false){
         $links=array();
         if(!$holding_module){
             // rendering the main menu:
             $links[]=array(
                  'm'=>'custom_link2',
                  'p'=>'your_custom_page2',
                 'name'=>'project m',
                 'order'=>999999,
             );
         }else if($holding_module == 'customer'){
             // rendering the customer menu
             $links[]=array(
              'name'=>'Customer Submenu Link2',
              'm'=>'custom_link2',
              'p'=>'your_custom_page2',
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