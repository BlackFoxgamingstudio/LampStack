<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['invoice'])) {
    echo 'No invoice was passed to this form for processing'; exit;
} else {
    $invoice = Invoice::find('id', $_GET['invoice']);
    if (!$invoice) {
        echo 'Invoice could not be located'; exit;
    }
}
?>
<form id="createInvoiceCharge" action="./" method="post">
    <input type="hidden" name="create" value="invoice-charge"/>
    <input type="hidden" name="invoice" value="<?= $invoice->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Charge <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="chargename">Name of Charge</label>
            <input type="text" id="chargename" name="chargename"/>
            <label for="chargedesc">Description of Charge</label>
            <textarea id="chargedesc" name="chargedesc" rows="5"></textarea>
            <p>Is this a manual entry? <input type="radio" name="manualToggle" value="1" checked> Yes <input type="radio" name="manualToggle" value="0"/> No</p>

            <div id="manualCharge" class="well well-lg push-vertical">
                <label for="wage">Select a Wage for Itemization:</label>
                <?php $hourlywages  = Wage::find('sql', "SELECT * FROM wages WHERE is_flat = 0 ORDER BY wname ASC");?>
                <?php $flatwages    = Wage::find('sql', "SELECT * FROM wages WHERE is_flat = 1 ORDER BY wname ASC");?>
                <?php if ($hourlywages || $flatwages) { ?>

                <select id="wage" name="wage">

                    <?php if ($hourlywages) { ?>
                        <optgroup label="Hourly Rates">
                        <?php for ($hw = 0; $hw < count($hourlywages);$hw++) { ?>
                            <option value="<?= $hourlywages[$hw]->id();?>">
                                <?= $hourlywages[$hw]->name() . ' ('.$hourlywages[$hw]->rate().'/hr)';?>
                            </option>
                        <?php } // End Hourly wages FOR loop ?>
                        </optgroup>
                    <?php } // End hourly wages IF block ?>

                    <?php if ($flatwages) { ?>
                        <optgroup label="Flat Rates">
                            <?php for ($fw = 0; $fw < count($flatwages);$fw++) { ?>
                                <option value="<?= $flatwages[$fw]->id();?>">
                                    <?= $flatwages[$fw]->name() . ' ('.$flatwages[$fw]->rate().')';?>
                                </option>
                            <?php } // End Hourly wages FOR loop ?>
                        </optgroup>
                    <?php } // End hourly wages IF block ?>

                </select>

                <label for="units">Units:</label>
                <p class="subdued">Units are hours for hourly wages and multipliers for flat rates</p>
                <input type="text" id="units" name="units" />
                <?php } else { ?>
                    <p class="alert alert-danger">Cannot create a charge. The administrator of this application has not setup any wages!</p>
                <?php } ?>
            </div>

            <div id="timedCharge" class="well well-lg push-vertical">
                <?php $timers = Timer::find('sql', 'SELECT * FROM timer_items WHERE billable = 1 AND closed = 0 AND tuser = '.$current_user->id());?>
                <?php if ($timers) { ?>
                    <h4>Available Billable Time Entries</h4>
                    <p class="subdued">Select all that apply to this charge and they will be added together</p>
                    <ul class="list-unstyled">
                        <?php foreach ($timers as $timer) { ?>

                        <li><i class="fa fa-clock-o"></i> <input type="checkbox" name="timerItem[]" value="<?= $timer->id();?>"/> <?= $timer->quick_string();?>

                            <?php } // End timers for each loop?>
                    </ul>
                <?php } else { ?>
                    <p>There are no timers available to attach to this charge</p>
                <?php } ?>
            </div>

            <p class="well well-sm push-vertical">If there are no wages are found or there is no wage to match up your charge to, please contact an admin so one can be added before creating this charge</p>

        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <p><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Make it</button> </p>
        </div>
    </div>
</form>
<script>
    var manualCharge = true;
    $('#timedCharge').hide();
    $('[name="manualToggle"]').click(function() {
        if ($(this).val() == 1) {
            $('#manualCharge').show();
            $('#timedCharge').hide();
            manualCharge = true;
        } else {
            $('#manualCharge').hide();
            $('#timedCharge').show();
            manualCharge = false;
        }
        //console.log(manualCharge);
    });
    var needsRefresh = false;
    $('#closeThis').click(function() {
        if (needsRefresh) {
            window.location.reload();
        }
        closeForm();
    });
    $('#createInvoiceCharge').on('submit', function(e) {
        e.preventDefault();
        var createInvoiceCharge = $('#createInvoiceCharge').serialize();
        $.post('./', createInvoiceCharge, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Charge was successfully created!');
                document.getElementById('createInvoiceCharge').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Charge', data);
            }
        });
    });
</script>