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
if(!$note_list_safe)die('fail');
//print_r($note_items);exit;
if(!isset($popup_links)){
    $popup_links= true;
}
$link_options = $options;
if(isset($link_options['summary_owners']))unset($link_options['summary_owners']);
if(isset($link_options['display_summary']))unset($link_options['display_summary']);
//if(isset($link_options['title']))unset($link_options['title']);
$fieldset_data = array();
if (isset($options['title']) && $options['title']){

    $fieldset_data['heading'] = array(
        'title' => $options['title'],
        'type' => 'h3',
    );
    if($can_create){
        $fieldset_data['heading']['button'] = array(
            'title' => _l('Add New Note'),
            'url' => module_note::link_open('new',false,$link_options),
            'class' => 'note_add no_permissions',
	        'id' => 'add_new_note_button',
        );
    }
	/*?>
	<h3>
        <?php if($can_create){
            ?>
		<span class="button no_permissions">
			<a href="<?php echo module_note::link_open('new',false,$link_options);?>" class="uibutton note_add no_permissions"><?php _e('Add New Note');?></a>
		</span>
        <?php } ?>
		<?php echo _l($options['title']);?>
	</h3>
    <div class="content_box_wheader">
	<?php*/
}else if($can_create){
	?>
	<a href="<?php echo module_note::link_open('new',false,$link_options);?>" data-options="<?php echo htmlspecialchars(base64_encode(serialize($link_options)));?>"  class="uibutton note_options_link note_add no_permissions"><?php _e('Add New Note');?></a>
	<?php
    //<div class="content_box_wheader">
}

if(get_display_mode()!='mobile'){
    $pagination = process_pagination($note_items,module_config::c('notes_per_page',20),0,'note'.md5(serialize($link_options)));
}else{
    $pagination = array(
        'rows' => $note_items,
        'links' => '',
        'page_numbers' => 1,
    );
}
ob_start();

/** START TABLE LAYOUT **/
$table_manager = module_theme::new_table_manager();
$columns = array();
$columns['date'] = array(
    'title' => 'Date',
    'width' => 60,
    'callback' => function($note_item){
        if($note_item['reminder'])echo '<strong>';
        echo print_date($note_item['note_time']);
        if($note_item['reminder'])echo '</strong>';
    }
);
$columns['description'] = array(
    'title' => 'Description',
    'callback' => function($note_item){
        if(isset($note_item['public']) && $note_item['public'])echo '* ';
        if($note_item['can_edit']){
	        if(function_exists('mb_substr') && function_exists('mb_strlen')){
		        $note_text = nl2br(htmlspecialchars(mb_substr($note_item['note'],0,module_config::c('note_trim_length',35))));
		        $note_text .= mb_strlen($note_item['note']) > module_config::c('note_trim_length',35) ? '...' : '';
	        }else{
		        $note_text = nl2br(htmlspecialchars(substr($note_item['note'],0,module_config::c('note_trim_length',35))));
		        $note_text .= strlen($note_item['note']) > module_config::c('note_trim_length',35) ? '...' : '';
	        }
            ?>
        <a href="<?php echo module_note::link_open($note_item['note_id'],false,$note_item['options']);?>" data-options="<?php echo htmlspecialchars(base64_encode(serialize($note_item['options'])));?>" class="note_edit note_options_link" rel="<?php echo $note_item['note_id'];?>"> <?php echo $note_text; ?> </a>
        <?php }else{
            echo forum_text($note_item['note']);
        }
    }
);
$columns['info'] = array(
    'title' => 'Info',
    'width' => 40,
    'callback' => function($note_item){
	    if(module_config::c('note_show_creator',1)) {
		    $user_data = module_user::get_user( $note_item['create_user_id'] );
		    echo $user_data['name'];
	    }
        if($note_item['display_summary'] && $note_item['rel_data'] && $note_item['owner_id']){
            global $plugins;
	        if(module_config::c('note_show_creator',1)) {
		        echo ' / ';
	        }
            echo $plugins[$note_item['owner_table']]->link_open($note_item['owner_id'],true);
        }
    }
);
if($can_delete){
    $columns['del'] = array(
        'title' => ' ',
        'callback' => function($note_item){
            if($note_item['can_delete']){
                ?> <a href="<?php echo module_note::link_open($note_item['note_id'],false,array_merge($note_item['options'],array('do_delete'=>'yes','note_id'=>$note_item['note_id'])));?>" data-options="<?php echo htmlspecialchars(base64_encode(serialize(array_merge($note_item['options'],array('do_delete'=>'yes','note_id'=>$note_item['note_id'])))));?>" rel="<?php echo $note_item['note_id'];?>" onclick="if(confirm('<?php _e('Really Delete Note?');?>'))return true; else return false;" class="note_delete note_options_link delete ui-state-default ui-corner-all ui-icon ui-icon-trash">[x]</a> <?php
            }
        }
    );
}
$table_manager->set_columns($columns);
$table_manager->set_rows($note_items);
$table_manager->table_id = 'note_'.$owner_table.'_'.$owner_id;
$table_manager->inline_table = true;
$table_manager->pagination = true;
$table_manager->pagination_per_page = module_config::c('notes_per_page',20);
$table_manager->pagination_hide_single_page = true;
$table_manager->row_callback = function($row_data, &$row_object) use($display_summary,$can_edit,$can_delete,&$options){
    $row_data['display_summary'] = $display_summary;
    $row_data['can_edit'] = $can_edit;
    $row_data['can_delete'] = $can_delete;
    $row_data['options'] = $options;
    $row_object->row_id = 'note_'.$row_data['note_id'];
    return $row_data;
};
$table_manager->print_table();
//if(!count($note_items))echo ' display:none; '; todo
if(!count($note_items)){
?>
<style type="text/css">
    #<?php echo $table_manager->table_id;?>{ display:none; }
</style>
<?php
}
/*
?>
<table class="tableclass tableclass_rows notes" width="100%" id="note_<?php echo $owner_table;?>_<?php echo $owner_id;?>" style="<?php if(!count($note_items))echo ' display:none; '; ?>">
	<thead>
		<tr>
			<th width="60"><?php _e('Date');?></th>
			<th><?php _e('Description');?></th>
			<th width="40"><?php _e('Info');?></th>
            <?php if($can_delete){ ?>
            <th width="10">&nbsp;</th>
            <?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php
        foreach($pagination['rows'] as $n){
        //foreach($note_items as $n){
			echo module_note::print_note($n['note_id'],$n,$display_summary,$can_edit,$can_delete,$options);
		}
		?>
	</tbody>
</table>
<div style="min-height: 10px;">
    <?php
        echo $pagination['page_numbers']>1 ? $pagination['links'] : '';
    ?>
</div>
<?php
*/
$fieldset_data['elements_before'] = ob_get_clean();
echo module_form::generate_fieldset($fieldset_data);

?>

<div id="new_note_popup" title="<?php _e('Add New Note');?>">
	<div id="new_note_inner"></div>
</div>
<?php if($popup_links){ ?>
<script type="text/javascript">
	var edit_note_id = 'new';
	var edit_note_changed = false;
	function run_note_edit(){
		$('.note_edit')
		.addClass('note_edit_done')
		.removeClass('note_edit')
		.click(function(){
			edit_note_id = $(this).attr('rel');
			$('#new_note_popup').dialog('open');
			return false;
		});
	}

	$(function(){
		$("#new_note_popup").dialog({
			autoOpen: false,
			height: 400,
			width: 400,
			modal: true,
			buttons: {
				'<?php _e('Save note');?>': function() {
					$.ajax({
						type: 'POST',
                        url: '<?php echo $plugins['note']->link('note_admin',array(
                            '_process' => 'save_note',
                            'options' => base64_encode(serialize($link_options)),
                            //'owner_id' => $owner_id,
                        ));?>&note_id='+edit_note_id+'',
						data: {
							note_time: $('#form_note_time').val(),
							note: encodeURIComponent($('#form_note_data').val()),
							rel_data: $('#form_rel_data').val(),
							user_id: $('.form_user_id').val(),
							reminder: (typeof $('#form_reminder')[0] != 'undefined' && $('#form_reminder')[0].checked ? 1 : 0),
							public: (typeof $('#form_public')[0] != 'undefined' && $('#form_public')[0].checked ? 1 : 0),
							public_chk: (typeof $('#form_public')[0] != 'undefined' ? 1 : 0)
						},
						success: function(h){
							$('#note_<?php echo $owner_table;?>_<?php echo $owner_id;?>').show();
							if(edit_note_id == 'new'){
								$('#note_<?php echo $owner_table;?>_<?php echo $owner_id;?> tbody').append(h);
							}else{
								$('#note_'+edit_note_id+'').replaceWith(h);
							}
							edit_note_changed = false;
							$('#new_note_popup').dialog('close');
							run_note_edit();
						}
					});
				},
                '<?php _e('Cancel');?>': function() {
					$(this).dialog('close');
				}
			},
			open: function(){
				$.ajax({
					type: "GET",
                    url: '<?php echo $plugins['note']->link('note_admin',array(
                        'options' => base64_encode(serialize($link_options)),
                        //'owner_table' => $owner_table,
                        //'owner_id' => $owner_id,
                    ));?>&note_id='+edit_note_id+'&display_mode=ajax',
					dataType: "html",
					success: function(d){
						if($('#form_note_data',d).length < 1){
							alert('Failed to load note, please try logging in again.');
							$(this).dialog('close');
							return false;
						}
						$('#new_note_inner').html(d);
						<?php if($rel_data){ ?>
						if(edit_note_id=='new'){
							$('#form_rel_data').val('<?php echo $rel_data;?>');
						}
						<?php } ?>
						load_calendars();
						edit_note_changed = false;
						$('#form_note_data')[0].focus();
						$('#form_note_data').change(function(){
							edit_note_changed = true;
						})
					}
				});
			},
			beforeclose: function(){
				if(edit_note_changed && $('#form_note_data').val() != ''){
					return(confirm('Close without saving?'));
				}
				return true;
			},
			close: function() {
				$('#new_note_inner').html('');
			}
		});
		$('.note_add')
		.button()
		.click(function(){
			edit_note_id = 'new';
			$('#new_note_popup').dialog('open');
			return false;
		});
		run_note_edit();
	});
</script>

<?php } ?>

<a name="t_note_<?php echo $owner_table;?>_<?php echo $owner_id;?>"></a>
