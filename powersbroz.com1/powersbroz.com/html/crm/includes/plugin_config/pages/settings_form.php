<form action="" method="post">


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
    module_form::prevent_exit(array(
        'valid_exits' => array(
            // selectors for the valid ways to exit this form.
            '.submit_button',
        ))
    );

    module_form::print_form_auth();


    $fieldset_data = array(
        'elements' => array(),
    );

    if(isset($settings['settings']) && is_array($settings['settings'])){
        if(isset($settings['title'])){
            $fieldset_data['heading'] = array(
                'title' => $settings['title'],
                'type' => 'h3',
            );
        }else if(isset($settings['heading'])){
            $fieldset_data['heading'] = $settings['heading'];
        }
        $settings = $settings['settings'];
    }

    ob_start();
    ?>

    <input type="hidden" name="_config_settings_hook" value="save_config">

    <table class="tableclass tableclass_rows">
        <thead>
        <tr>
            <!--<th>
                <?php /*_e('Key');*/?>
            </th>-->
            <th width="30%">
                <?php _e('Description');?>
            </th>
            <th>
                <?php _e('Value');?>
            </th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($settings as $setting){ ?>
            <tr>
                <!--<td>
                    <?php /*echo $setting['key'];*/?>
                </td>-->
                <th><?php echo $setting['description'];?></th>
                <td>

                    <?php

                    if($setting['type']=='html'){
                        echo $setting['html'];
                    }else{
                        $setting['name'] = 'config['.$setting['key'].']';
                        $setting['value'] = module_config::c($setting['key'],$setting['default']);
                        module_form::generate_form_element($setting);
                    }


                    /*switch($setting['type']){
                        case 'number':
                        ?>
                            <input type="text" name="config[<?php echo $setting['key'];?>]" value="<?php echo htmlspecialchars(module_config::c($setting['key'],$setting['default']));?>" size="20">
                            <?php
                        break;
                        case 'text':
                        ?>
                            <input type="text" name="config[<?php echo $setting['key'];?>]" value="<?php echo htmlspecialchars(module_config::c($setting['key'],$setting['default']));?>" size="60">
                            <?php
                        break;
                        case 'textarea':
                        ?>
                            <textarea name="config[<?php echo $setting['key'];?>]" rows="6" cols="50"><?php echo htmlspecialchars(module_config::c($setting['key'],$setting['default']));?></textarea>
                            <?php
                        break;
                        case 'select':
                        ?>
                            <select name="config[<?php echo $setting['key'];?>]">
                                <option value=""><?php _e('N/A');?></option>
                                <?php foreach($setting['options'] as $key=>$val){ ?>
                                <option value="<?php echo $key;?>"<?php echo module_config::c($setting['key'],$setting['default']) == $key ? ' selected':'' ?>><?php echo htmlspecialchars($val);?></option>
                                <?php } ?>
                            </select>
                            <?php
                        break;
                        case 'checkbox':
                        ?>
                            <input type="hidden" name="config_default[<?php echo $setting['key'];?>]" value="1">
                            <input type="checkbox" name="config[<?php echo $setting['key'];?>]" value="1" <?php if(module_config::c($setting['key'])) echo ' checked'; ?>>
                            <?php
                        break;

                        }*/

                    if(isset($setting['help'])){
                       // _h($setting['help']);
                    }
                        ?>

                </td>
            </tr>
            <?php } ?>

        </tbody>
    </table>
    <?php

    $form_actions = array(
        'class' => 'action_bar action_bar_center action_bar_single',
        'elements' => array(
            array(
                'type' => 'save_button',
                'name' => 'save',
                'value' => _l('Save settings'),
            ),
        ),
    );
    echo module_form::generate_form_actions($form_actions);

    $fieldset_data['elements_before'] = ob_get_clean();
    echo module_form::generate_fieldset($fieldset_data);
    unset($fieldset_data);



?>

</form>