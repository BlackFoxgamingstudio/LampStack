<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['invoice'])) {
    echo 'No invoice passed to the application for processing';exit;
}
$invoice = Invoice::find('id', $con->secure($_GET['invoice']));
if (!$invoice) {
    echo 'Invoice passed to the application could not be found'; exit;
}
$taxes = Tax::find('all');
?>
<?php if ($taxes) { ?>
<form id="applyTax" action="./" method="post">
    <input type="hidden" name="assign" value="tax"/>
    <input type="hidden" name="invoice" value="<?= $invoice->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Apply Tax Options <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="tax">Select a Tax to Apply:</label>
            <select class="push-vertical" id="tax" name="tax">
                <?php foreach($taxes as $tax) { ?>
                <option value="<?= $tax->id();?>"><?= $tax->name();?></option>
                <?php } ?>
            </select>
            <div class="well well-lg">
                <p>Would you like to apply this tax to the entire invoice or a specific set of charges?</p>
                <p><input type="radio" name="application" value="invoice" checked/> Whole Invoice<br/><input type="radio" name="application" value="charges"/> Charge(s)</p>
                <div style="display: none;" id="chargesAppliedTo" class="normal-box">
                    <?php if ($invoice->has_charges()) { ?>
                        <?php $charges = $invoice->get_charges();?>
                        <ul class="list-unstyled">
                        <?php foreach ($charges as $charge) { ?>
                            <li><input type="checkbox" name="charges[]" value="<?= $charge->id();?>"/> <?= $charge->name();?></li>
                        <?php } ?>
                        </ul>
                    <?php } else { ?>
                    <p>This invoice does not currently have any charges to apply tax to</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <p><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Apply</button> </p>
        </div>
    </div>
</form>
<script>
    $('input[name="application"]').on('change', function() {
        if ($(this).val() == 'charges') {
            $('#chargesAppliedTo').show();
        } else {
            $('#chargesAppliedTo').hide();
        }
    });
    $('#closeThis').click(function() {
        closeForm();
    });
    $('#applyTax').on('submit', function(e) {
        e.preventDefault();
        var applyTax = $('#applyTax').serialize();
        $.post('./', applyTax, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Tax Assignment Error', data);
            }
        });
    });
</script>
<?php } else { ?>

    <div class="row">
        <div class="col-md-12">
            <h2>Apply Tax Options <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <p class="alert alert-danger">No taxes have been configured to be applied</p>
        </div>
    </div>

<?php } ?>