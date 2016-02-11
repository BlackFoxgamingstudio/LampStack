<?php
include_once '../../boot.php';
if (!isset($_GET['wage']) || !$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
$wage = Wage::find('id', $con->secure($_GET['wage']));
if (!$wage) {
    echo 'Unable to location wage in the database'; exit;
}
?>
<form id="editWage" action="./" method="post" >
<input type="hidden" name="edit" value="wage"/>
<input type="hidden" name="wage" value="<?= $wage->id();?>"/>
<div class="row">
    <div class="col-md-12">
        <h2>Edit Wage <span class="label label-primary"><?= $wage->name();?></span> <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">
        <label for="billingcode">Unique Billing Code: (Max 50 Characters)</label>
        <input type="text" id="billingcode" name="billingcode" value="<?= $wage->billing_code();?>"/>
        <label for="wname">Easy Name: i.e. PHP Development - Basic</label>
        <input type="text" id="wname" name="wname" value="<?= $wage->name();?>"/>
        <label for="wdesc">Description:</label>
        <textarea id="wdesc" name="wdesc" rows="5"><?= $wage->description();?></textarea>
        <label for="wrate">Numerical Rate:</label>
        <input type="text" name="wrate" id="wrate" value="<?= $wage->rate();?>"/>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">
        <p><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Edit Wage</button> </p>
    </div>
</div>

</form>
<script>

function cssClassOutput(number) {
    if (number > 0 && number <= 20) {
        return 'label-success';
    } else if (number > 20 && number <= 50) {
        return 'label-warning';
    } else {
        return 'label-danger';
    }
}

$('#billingcode').on('blur', function() {
    if ($(this).val() == '') {
        alert('You must enter a billing code for this wage')
    }
});

var needsRefresh = false;
$('#closeThis').click(function() {
    if (needsRefresh) {
        window.location.reload();
    }
    closeForm();
});
$('#editWage').on('submit', function(e) {
    e.preventDefault();
    var editWage = $('#editWage').serialize();
    $.post('./', editWage, function(data) {
        if (data == 'success') {
            entityError('Successful', 'Wage was successfully edited!');
            needsRefresh = true;
        } else {
            entityError('Error Editing Wage', data);
        }
    });
});
</script>