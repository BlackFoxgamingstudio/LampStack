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

// used in main listing and search listing.

if(!isset($datas))die('No data found');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
	<thead>
		<tr class="title">
            <?php if(isset($_REQUEST['view_all'])){ ?>
            <th><?php _e('Parent');?></th>
            <?php }
            /*<th><?php echo _l('ID Number'); ?></th>
	        <th><?php echo _l('Status'); ?></th>
	        <th><?php echo _l('Date'); ?></th>
	        <?php */
	        // find what fields to show in the list
	        $list_fields = array();
	        $data_field_groups = $module->get_data_field_groups($data_type_id);
	        foreach($data_field_groups as $data_field_group){
	            $data_fields = $module->get_data_fields($data_field_group['data_field_group_id']);
	            foreach($data_fields as $data_field){
	                if($data_field['show_list']){
	                    $list_fields[$data_field['data_field_id']] = $data_field;
	                }
	            }
	        }
	        //todo - uasort by 'order' field.
	        foreach($list_fields as $list_field){
	            ?>
	            <th><?php echo $list_field['title'];?></th>
	            <?php
	        }
	        ?>
	    </tr>
    </thead>
    <tbody>
    <?php /*if($allow_search){ ?>
    <tr class="search">
        <td><input type="text" name="search[data_title]" value="<?php echo isset($search['data_title'])?htmlspecialchars($search['data_title']):''; ?>" /></td>
        <td></td>
        <td><?php echo print_select_box(get_col_vals("user","user_id","real_name"),"search[user_id]",(isset($search['user_id'])?$search['user_id']:'')); ?></td>
        <td><?php echo print_select_box($module->get_data_statuses(),"search[status]",isset($search['data_status'])?$search['data_status']:''); ?></td>
        <td></td>
        <td nowrap="nowrap">
	        <?php echo create_link("Reset","reset",$module->link()); ?>
            <?php echo create_link("Search","submit"); ?>
        </td>
    </tr>
    <?php
    } */
    $c=0;
	foreach($datas as $data){
		//$data = $module->get_data_record($data['data_record_id']);

		$list_data_items = $module->get_data_items($data['data_record_id']);
		?>
        <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
            <?php if(isset($_REQUEST['view_all'])){ ?>
            <td><?php
                foreach($module->get_data_link_keys() as $key){
                    if(isset($data[$key]) && (int)$data[$key] > 0){
                        switch($key){
                            case 'customer_id':
                                echo module_customer::link_open($data[$key],true);
                                break;
                            case 'job_id':
                                echo module_job::link_open($data[$key],true);
                                break;
                            case 'invoice_id':
                                echo module_invoice::link_open($data[$key],true);
                                break;
                            case 'quote_id':
                                echo module_quote::link_open($data[$key],true);
                                break;
                            case 'file_id':
                                echo module_file::link_open($data[$key],true);
                                break;
                        }
                    }
                }
                ?></td>
            <?php }/*<td><a href="<?php echo $module->link('',array("data_record_id"=>$data['data_record_id'],"customer_id"=>isset($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : false)); ?>"><?php echo $module->format_record_id($data['data_type_id'],$data['data_record_id']); ?></a></td>
            <td><?php echo $data['status']; ?></td>
            <td><?php echo print_date($data['date_updated']); ?></td>
            <?php */
            $first = true;
            foreach($list_fields as $list_field){
                $settings = @unserialize($list_data_items[$list_field['data_field_id']]['data_field_settings']);
	            if(!isset($settings['field_type']))$settings['field_type'] = isset($list_field['field_type'])  ? $list_field['field_type'] : false;
                $value = false;
				if(isset($list_data_items[$list_field['data_field_id']])){
					$value = $list_data_items[$list_field['data_field_id']]['data_text'];
				}else{
                    switch($settings['field_type']){
                        default:
					        $value = _l('N/A');
                            break;
                    }
				}
                $foo = @unserialize($value);
                if(is_array($foo)){
                    $value = $foo;
                }
                if(!isset($settings['data_field_id'])){
                     switch ( $settings['field_type'] ) {
                         case 'created_date_time':
                             $value = isset( $data['date_created'] ) && $data['date_created'] != '0000-00-00 00:00:00' ? print_date( strtotime( $data['date_created'] ), true ) : _l( 'N/A' );
                             break;
                         case 'created_date':
                             $value = isset( $data['date_created'] ) && $data['date_created'] != '0000-00-00 00:00:00' ? print_date( strtotime( $data['date_created'] ), false ) : _l( 'N/A' );
                             break;
                         case 'created_time':
                             $value = isset( $data['date_created'] ) && $data['date_created'] != '0000-00-00 00:00:00' ? date( module_config::c( 'time_format', 'g:ia' ), strtotime( $data['date_created'] ) ) : _l( 'N/A' );
                             break;
                         case 'updated_date_time':
                             $value = isset( $data['date_updated'] ) && $data['date_updated'] != '0000-00-00 00:00:00' ? print_date( strtotime( $data['date_updated'] ), true ) : ( isset( $data['date_created'] ) && $data['date_created'] != '0000-00-00 00:00:00' ? print_date( strtotime( $data['date_created'] ), true ) : _l( 'N/A' ) );
                             break;
                         case 'updated_date':
                             $value = isset( $data['date_updated'] ) && $data['date_updated'] != '0000-00-00 00:00:00' ? print_date( strtotime( $data['date_updated'] ), false ) : ( isset( $data['date_created'] ) && $data['date_created'] != '0000-00-00 00:00:00' ? print_date( strtotime( $data['date_created'] ), false ) : _l( 'N/A' ) );
                             break;
                         case 'updated_time':
                             $value = isset( $data['date_updated'] ) && $data['date_updated'] != '0000-00-00 00:00:00' ? date( module_config::c( 'time_format', 'g:ia' ), strtotime( $data['date_updated'] ) ) : ( isset( $data['date_created'] ) && $data['date_created'] != '0000-00-00 00:00:00' ? date( module_config::c( 'time_format', 'g:ia' ), strtotime( $data['date_created'] ) ) : _l( 'N/A' ) );
                             break;
                         case 'created_by':
                             $value = isset( $data['create_user_id'] ) && (int) $data['create_user_id'] > 0 ? module_user::link_open( $data['create_user_id'], true ) : _l( 'N/A' );
                             break;
                         case 'updated_by':
                             $value = isset( $data['update_user_id'] ) && (int) $data['update_user_id'] > 0 ? module_user::link_open( $data['update_user_id'], true ) : ( isset( $data['create_user_id'] ) && (int) $data['create_user_id'] > 0 ? module_user::link_open( $data['create_user_id'], true ) : _l( 'N/A' ) );
                             break;
                     }
                }else {
                    switch ( $settings['field_type'] ) {
                        case 'encrypted':
                            $value = '*******';
                            break;
                        case 'wysiwyg':
                            $value = module_security::purify_html( $value );
                            break;
                        case 'select':
                            // todo - do this for the other field types as well..
                            $settings['value'] = $value;
                            $value             = $module->get_form_element( $settings, true, $data );
                            break;
	                    case 'url':
                            if(!is_array($value))$value = array($value);
                            $foo = array();
                            foreach($value as $v){
                                $foo [] = '<a href="'.htmlspecialchars($v).'" target="_blank">'.htmlspecialchars($v).'</a>';
                            }
                            $value = implode(', ',$foo);
	                        break;
                        case 'file':
                            if ( is_array( $value ) && count( $value ) ) {
                                $value = $value['name'];
                            }
                            $value = htmlspecialchars( $value );
                            break;
                        default:
                            if ( is_array( $value ) && count( $value ) ) {
                                $foo = array();
                                foreach ( $value as $key => $val ) {
                                    if ( $val ) {
                                        $foo[] = $val == 1 ? $key : $val;
                                    }
                                }
                                $value = implode( ', ', $foo );
                            }
                            $value = htmlspecialchars( $value );
                            break;
                    }
                }
                //$value = isset($settings['field_type']) && $settings['field_type'] == 'encrypted' ? '*******' : (isset($list_data_items[$list_field['data_field_id']]) ? ($list_data_items[$list_field['data_field_id']]['data_text']) : _l('N/A'));
                if($first){
                    $first = false;
                    ?>
                    <td class="row_action"><a href="<?php echo $module->link('',array("data_record_id"=>$data['data_record_id'],"data_type_id"=>$data['data_type_id'],'customer_id'=>$data['customer_id'])); ?>" class="custom_data_open" data-settings="<?php echo htmlentities(json_encode(array("data_record_id"=>$data['data_record_id'],"data_type_id"=>$data['data_type_id'],'customer_id'=>$data['customer_id'])));?>"><?php echo $value;?></a></td>
                    <?php
                }else{
                    ?>
                    <td><?php
                        // todo: if(isset($list_data_items[$list_field['data_field_id']])) unserialize and check for array.
                        echo $value;?></td>
                    <?php
                }
	        }
	        ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
