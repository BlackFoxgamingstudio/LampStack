<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['tax'])) {
    echo 'No tax was passed to this form for processing'; exit;
} else {
    $tax = Tax::find('id', $_GET['tax']);
    if (!$tax) {
        echo 'Tax could not be located'; exit;
    }
}
?>
<form id="editTax" action="./" method="post">
    <input type="hidden" name="edit" value="tax"/>
    <input type="hidden" name="tax" value="<?= $tax->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Edit <span class="label label-primary"><?= $tax->name();?></span><span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="taxname">Name for Tax:</label>
            <input type="text" name="taxname" id="taxname" value="<?= $tax->name();?>"/>
            <label for="taxdesc">Tax Description:</label>
            <textarea name="taxdesc" id="taxdesc" rows="4"><?= $tax->description();?></textarea>
            <label for="taxrate">Tax Rate (must be greater than 0) i.e. 32</label>
            <input type="text" name="taxrate" id="taxrate" value="<?= $tax->rate();?>"/>
            <button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Change it!</button>
        </div>
    </div>
</form>
<script>
    var needsRefresh = false;
    $('#closeThis').click(function() {
        if (needsRefresh) {
            window.location.reload();
        }
        closeForm();
    });
    $('#editTax').on('submit', function(e) {
        e.preventDefault();
        var editTax = $('#editTax').serialize();
        $.post('./', editTax, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Tax was successfully edited!');
                document.getElementById('editTax').reset();
                needsRefresh = true;
            } else {
                entityError('Error Editing Tax', data);
            }
        });
    });
</script>