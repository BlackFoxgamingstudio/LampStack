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
print_heading('Menu Configuration');

if(isset($_REQUEST['save_config'])&&is_array($_REQUEST['save_config'])){
    foreach($_REQUEST['save_config'] as $key=>$val){
        module_config::save_config($key,$val);
    }
    set_message('Menu order saved');
}
?>

<form action="" method="post">
    <table class="tableclass tableclass_rows">
        <thead>
        <tr>
            <th class="width2">
                <?php _e('Menu Item');?>
            </th>
            <th>
                <?php _e('Position');?>
            </th>
            <th>
                <?php _e('Icon');?>
	            <?php _h('Only works in themes that show menu icons. The font-awesome icon name from https://fortawesome.github.io/Font-Awesome/icons/');?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $c=0;
        foreach(get_multiple('config') as $config){
            if(preg_match('#_menu_order_(.*)#',$config['key'],$matches)){
                ?>
                <tr class="<?php echo $c++%2 ? 'odd' : 'even';?>">
                    <td>
                        <?php echo $matches[1];?>
                    </td>
                    <td>
                        <input type="text" name="save_config[<?php echo htmlspecialchars($matches[0]);?>]" value="<?php echo htmlspecialchars($config['val']);?>" size="6">
                    </td>
                    <td>
                        <input type="text" name="save_config[_menu_icon_<?php echo htmlspecialchars($matches[1]);?>]" value="<?php echo htmlspecialchars(module_config::c('_menu_icon_'.$matches[1]));?>" size="10">
                    </td>
                </tr>
                <?php
            }else{
                continue;
            }
        }
        ?>
        </tbody>
    </table>
    <input type="submit" name="save" value="<?php _e('Update menu order');?>">
</form>