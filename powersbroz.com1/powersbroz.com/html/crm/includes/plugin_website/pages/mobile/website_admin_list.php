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

$search = (isset($_REQUEST['search']) && is_array($_REQUEST['search'])) ? $_REQUEST['search'] : array();
if(isset($_REQUEST['customer_id'])){
    $search['customer_id'] = $_REQUEST['customer_id'];
}
$websites = module_website::get_websites($search);

?>

<h2>
    <?php if(module_website::can_i('create','Websites')){ ?>
	<span class="button">
		<?php echo create_link("Add New ".module_config::c('project_name_single','Website'),"add",module_website::link_open('new')); ?>
	</span>
    <?php } ?>
	<?php echo _l('Customer '.module_config::c('project_name_plural','Websites')); ?>
</h2>

<form action="" method="post">


<table class="search_bar" width="100%">
	<tr>
        <th width="70"><?php _e('Filter By:'); ?></th>
        <td width="40">
            <?php _e('Name/URL:');?>
        </td>
        <td>
            <input type="text" name="search[generic]" value="<?php echo isset($search['generic'])?htmlspecialchars($search['generic']):''; ?>" size="30">
        </td>
		<td width="30">
        <?php _e('Status:');?>
        </td>
        <td>
        <?php echo print_select_box(module_website::get_statuses(),'search[status]',isset($search['status'])?$search['status']:''); ?>
        </td>
        <td class="search_action">
			<?php echo create_link("Reset","reset",module_website::link_open(false)); ?>
			<?php echo create_link("Search","submit"); ?>
		</td>
	</tr>
</table>

<?php
$pagination = process_pagination($websites);
$colspan = 4;
?>

<?php echo $pagination['summary'];?>

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
	<thead>
	<tr class="title">
		<th id="website_name"><?php echo _l('Name'); ?></th>
        <?php if(module_config::c('project_display_url',1)){ ?>
		<th id="website_url"><?php echo _l('URL'); ?></th>
        <?php } ?>
        <?php if(!isset($_REQUEST['customer_id'])){ ?>
		<th id="website_customer"><?php echo _l('Customer'); ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
		<?php
		$c=0;
		foreach($pagination['rows'] as $website){
            ?>
		<tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
			<td class="row_action">
				<?php echo module_website::link_open($website['website_id'],true);?>
			</td>
            <?php if(module_config::c('project_display_url',1)){ ?>
            <td>
                <?php if(strlen(trim($website['url']))>0){ ?>
                <a href="http://<?php echo htmlspecialchars($website['url']);?>" target="_blank">http://<?php echo htmlspecialchars($website['url']);?></a>
                <?php } ?>
            </td>
            <?php } ?>
            <?php if(!isset($_REQUEST['customer_id'])){ ?>
            <td>
                <?php echo module_customer::link_open($website['customer_id'],true);?>
            </td>
            <?php } ?>
		</tr>
		<?php } ?>
	</tbody>
</table>
    <?php echo $pagination['links'];?>
</form>