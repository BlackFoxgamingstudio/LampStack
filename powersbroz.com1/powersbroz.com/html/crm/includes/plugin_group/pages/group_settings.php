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

if(isset($_REQUEST['group_id'])){
    include('group_edit.php');
}else{

    $search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
    $groups = $module->get_groups($search);
    ?>


    <h2>
        <!--<span class="button">
            <?php /*echo create_link("Add New","add",module_group::link_open('new')); */?>
        </span>-->
        <?php echo _l('Groups'); ?>
    </h2>


    <form action="" method="post">

    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
        <thead>
        <tr class="title">
            <th><?php echo _l('Group Name'); ?></th>
            <th><?php echo _l('Available to'); ?></th>
            <th><?php echo _l('Group Members'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $c=0;
        foreach($groups as $group){ ?>
            <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
                <td class="row_action">
                    <?php echo module_group::link_open($group['group_id'],true);?>
                </td>
                <td>
                    <?php echo $group['owner_table'];?>
                </td>
                <td>
                    <?php echo $group['count']; ?>
                </td>
            </tr>
        <?php } ?>
      </tbody>
    </table>
    </form>
<?php } ?>