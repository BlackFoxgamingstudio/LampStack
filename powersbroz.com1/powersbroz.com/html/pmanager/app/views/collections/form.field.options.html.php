<?php

require_once '../../boot.php';

if (!isset($_GET['field'])) {
    echo 'Improper access'; exit;
}

$field = FormField::find('id', $con->secure($_GET['field']));

if (!$field) {
    echo 'Field passed to the application could not be located in the database'; exit;
}

if (!$field->options_allowed()) {
    echo '<p class="alert alert-warning">Unable to add option elements to this field.</p>'; exit;
}

?>
<p>Options for "<strong><?= $field->label();?></strong>":</p>
<hr/>
<?php if ($field->has_options()) { ?>



    <? $options = $field->get_options();?>

    <ul class="form-field-options-list">
        <?php foreach ($options as $o) { ?>
        <li>
            <span title="Delete this option" class="label label-danger">
                <i class="fa fa-times"></i>
            </span>
            <span title="What your user sees" class="label label-success">
                <i class="fa fa-eye"></i> <?= $o->label();?>
            </span>
            <span title="The data you receive when the form is submitted" class="label label-success">
                <i class="fa fa-signal"></i> <?= $o->value();?>
            </span>
        </li>
        <?php } ?>
    </ul>



<?php } else { ?>
<p class="alert alert-info">
    This field does not currently have any options associated with it
</p>
<?php } ?>


<form id="addOptionFrm-<?= $field->id();?>" action="" method="post">
    <input type="hidden" name="create" value="form-field-option"/>
    <input type="hidden" name="field" value="<?= $field->id();?>"/>
    <table class="table">
        <thead>
            <tr>
                <th>Label</th>
                <th>Value</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="option-label" placeholder="Label"/></td>
                <td><input type="text" name="option-value" placeholder="Value"/></td>
                <td><span class="btn btn-success addOptionBtn<?= $field->id();?>"><i class="fa fa-plus-circle"></i></span></td>
            </tr>
        </tbody>
    </table>

</form>

<script>
    function addOption($id) {
        var formData = $('#addOptionFrm-<?= $field->id();?>').serialize();
        $.post('./', formData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Create Option', data);
            }
        });
    }

    $('.addOptionBtn<?= $field->id();?>').click(function() {
        addOption(<?= $field->id();?>);
    });

</script>

