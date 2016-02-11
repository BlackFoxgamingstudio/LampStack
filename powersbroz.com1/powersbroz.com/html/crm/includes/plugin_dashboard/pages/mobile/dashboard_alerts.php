<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */ foreach($dashboard_alerts as $key=>$alerts){ ?>

            <?php print_heading(array('title'=>$key.' ('.count($alerts).')','type'=>'h3'));?>
            <mobile>
            <table class="tableclass tableclass_rows tableclass_full tbl_fixed">
                <tbody>
                <?php
                if (count($alerts)) {
                    $y = 0;
                    foreach ($alerts as $alert) {
                        ?>
                        <tr class="<?php echo ($y++ % 2) ? 'even' : 'odd'; ?>">
                            <td class="row_action">
                                <a href="<?php echo $alert['link']; ?>"><?php echo htmlspecialchars($alert['item']); ?></a>
                            </td>
                            <td width="16%">
                                <?php echo ($alert['warning']) ? '<span class="important">' : ''; ?>
                                <?php echo print_date($alert['date']); ?>
                                <?php echo ($alert['warning']) ? '</span>' : ''; ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td class="odd" colspan="4"><?php _e('Yay! No alerts!');?></td>
                    </tr>
                <?php  } ?>
                </tbody>
            </table>
        </mobile>
        <?php
        } ?>