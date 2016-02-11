<?php
include_once '../../boot.php';
if (!isset($_GET['scope']) || !$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createWage" action="./" method="post" >
<input type="hidden" name="create" value="wage"/>
<?php $scope = ($_GET['scope'] == 'flat') ? 1 : 0;?>
<input type="hidden" name="flat" value="<?= $scope;?>"/>
<div class="row">
    <div class="col-md-12">
        <h2>Create a New Wage <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">

        <label for="billingcode">Unique Billing Code: (Max 50 Characters)</label>
        <input type="text" id="billingcode" name="billingcode" />
        <label for="wname">Easy Name: i.e. PHP Development - Basic</label>
        <input type="text" id="wname" name="wname"/>
        <label for="wdesc">Description:</label>
        <textarea id="wdesc" name="wdesc" rows="5"></textarea>
        <label for="wrate">Numerical Rate:</label>
        <input type="text" name="wrate" id="wrate"/>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">
        <p><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Make it</button> </p>
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
$('#createWage').on('submit', function(e) {
    e.preventDefault();
    var createWage = $('#createWage').serialize();
    $.post('./', createWage, function(data) {
        if (data == 'success') {
            entityError('Successful', 'Wage was successfully created!');
            document.getElementById('createWage').reset();
            needsRefresh = true;
        } else {
            entityError('Error Creating Wage', data);
        }
    });
});
</script>