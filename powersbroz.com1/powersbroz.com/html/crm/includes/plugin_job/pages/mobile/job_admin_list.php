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

if(!$job_safe)die('denied');

$search = (isset($_REQUEST['search']) && is_array($_REQUEST['search'])) ? $_REQUEST['search'] : array();
if(isset($_REQUEST['customer_id'])){
    $search['customer_id'] = $_REQUEST['customer_id'];
}
$jobs = module_job::get_jobs($search);


?>

<h2>
    <?php if(module_job::can_i('create','Jobs')){ ?>
	<span class="button">
		<?php echo create_link("Add New job","add",module_job::link_open('new')); ?>
	</span>
    <?php } ?>
	<?php echo _l('Customer Jobs'); ?>
</h2>

<form action="" method="post">


<table class="search_bar" width="100%">
	<tr>
		<th width="70"><?php _e('Filter By:'); ?></th>
		<td width="80">
			<?php echo _l('Job Title:');?>
		</td>
		<td>
			<input type="text" name="search[generic]" value="<?php echo isset($search['generic'])?htmlspecialchars($search['generic']):''; ?>" size="30">
		</td>
		<td width="30">
			<?php echo _l('Status:');?>
		</td>
		<td>
			<?php echo print_select_box(module_job::get_statuses(),'search[status]',isset($search['status'])?$search['status']:''); ?>
		</td>
		<td class="search_action">
			<?php echo create_link("Reset","reset",module_job::link_open(false)); ?>
			<?php echo create_link("Search","submit"); ?>
		</td>
	</tr>
</table>

<?php
$pagination = process_pagination($jobs);
$colspan = 4;
?>

<?php echo $pagination['summary'];?>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
	<thead>
	<tr class="title">
		<th id="job_title"><?php echo _l('Job Title'); ?></th>
		<th id="job_start_date"><?php echo _l('Date'); ?></th>
		<th id="job_website"><?php echo _l(module_config::c('project_name_single','Website')); ?></th>
		<th id="job_progress"><?php echo _l('Progress'); ?></th>
		<th id="job_total"><?php echo _l('Job Total'); ?></th>
		<th><?php echo _l('Invoice'); ?></th>
    </tr>
    </thead>
    <tbody>
		<?php
		$c=0;
		foreach($pagination['rows'] as $job_original){
//            print_r(array_keys($job_original));
            //echo $job_original['website_name'];
            $job = module_job::get_job($job_original['job_id']);
            ?>
            <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
                <td class="row_action">
                    <?php echo module_job::link_open($job['job_id'],true);?>
                </td>
                <td>
                    <?php
                    echo print_date($job['date_start']);
                        //is there a renewal date?
                        if(isset($job['date_renew']) && $job['date_renew'] && $job['date_renew'] != '0000-00-00'){
                            _e(' to %s',print_date($job['date_renew']));
                        }
                    ?>
                </td>
                <td>
                    <?php  echo module_website::link_open($job['website_id'],true);?>
                </td>
                <td>
                    <span class="<?php
                        echo $job['total_percent_complete'] >= 1 ? 'success_text' : '';
                        ?>">
                        <?php echo ($job['total_percent_complete']*100).'%';?>
                    </span>
                </td>
                <td>
                    <span class="currency">
                    <?php echo dollar($job['total_amount'],true,$job['currency_id']);?>
                    </span>
                </td>
                <td>
                    <?php foreach(module_invoice::get_invoices(array('job_id'=>$job['job_id'])) as $invoice){
                        $invoice = module_invoice::get_invoice($invoice['invoice_id']);
                        echo module_invoice::link_open($invoice['invoice_id'],true);
                        echo " ";
                        echo '<span class="';
                        if($invoice['total_amount_due']>0){
                            echo 'error_text';
                        }else{
                            echo 'success_text';
                        }
                        echo '">';
                        if($invoice['total_amount_due']>0){
                            echo dollar($invoice['total_amount_due'],true,$invoice['currency_id']);
                            echo ' '._l('due');
                        }else{
                            echo _l('%s paid',dollar($invoice['total_amount'],true,$invoice['currency_id']));
                        }
                        echo '</span>';
                        echo "<br>";
                    } ?>
                </td>
            </tr>
		<?php } ?>
	</tbody>
</table>
    <?php echo $pagination['links'];?>
</form>