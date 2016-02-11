<?php
// Currencies
$baseCurrency   = Currency::find('id',$app_settings->get('wage_currency'));
$currencies     = Currency::find('sql', "SELECT * FROM app_currencies WHERE NOT id = ".$baseCurrency->id());
?>
<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Wages and Taxes</h2>
            </div>
        </div>
    </div>
</div>
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled list-inline">

                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-home"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Wages &amp; Taxes</h2>
                <hr/>
                <p>All of your wages are charged based on the <?= $baseCurrency->name();?>. This was configured during installation and cannot be changed.</p>
                <div class="blue-box">
                    <h3>Current Rates for Available Currencies</h3>
                    <ul class="wage-currency-list">
                        <?php foreach ($currencies as $currency) { ?>
                        <li><?= $currency->name() .'('.$currency->rate().')';?></li>
                        <?php } ?>
                    </ul>
                    <p><span class="btn btn-primary updateExchangeRatesBtn">Update Rates</span></p>
                    <p class="small">Last updated <?= Currency::last_updated();?></p>
                </div>
                <h3 class="setting-group">Hourly Wages <span class="btn btn-success pull-right createHourlyWageBtn"><i class="fa fa-plus"></i> New</span></h3>
                <?php $hourly = Wage::find('sql', "SELECT * FROM wages WHERE is_flat = 0 ORDER BY wname ASC");?>
                <?php if ($hourly) { ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th width="25%">Wage Name</th>
                        <th width="50%">Short Description</th>
                        <th width="10%">Hourly Rate</th>
                        <th width="15%">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php for ($h = 0;$h < count($hourly);$h++) { ?>
                        <tr>
                            <td><?= $hourly[$h]->name();?></td>
                            <td><?= $hourly[$h]->description();?></td>
                            <td><?= $hourly[$h]->formatted_rate();?></td>
                            <td>
                                <span data-wage="<?= $hourly[$h]->id();?>" class="editWageBtn btn btn-warning"><i class="fa fa-edit"></i></span>
                                <span data-wage="<?= $hourly[$h]->id();?>" class="deleteWageBtn btn btn-danger"><i class="fa fa-times-circle"></i></span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                <div class="nothing-full">
                    <h4>No hourly wages have been set. Would you like to create one?</h4>
                </div>
                <?php } ?>
                <h3 class="setting-group">Flat Rate Wages <span class="btn btn-success pull-right createFlatWageBtn"><i class="fa fa-plus"></i> New</span></h3>
                <?php $flat = Wage::find('sql', "SELECT * FROM wages WHERE is_flat = 1 ORDER BY wname ASC");?>
                <?php if ($flat) { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="25%">Rate Name</th>
                            <th width="50%">Short Description</th>
                            <th width="10%">Flat Rate</th>
                            <th width="15%">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($f = 0;$f < count($flat);$f++) { ?>
                        <tr>
                            <td><?= $flat[$f]->name();?></td>
                            <td><?= $flat[$f]->description();?></td>
                            <td><?= $flat[$f]->formatted_rate();?></td>
                            <td>
                                <span data-wage="<?= $flat[$f]->id();?>" class="editWageBtn btn btn-warning"><i class="fa fa-edit"></i></span>
                                <span data-wage="<?= $flat[$f]->id();?>" class="deleteWageBtn btn btn-danger"><i class="fa fa-times-circle"></i></span>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    <div class="nothing-full">
                        <h4>No flat-rate wages have been set. Would you like to create one?</h4>
                    </div>
                <?php } ?>
                <h3 class="setting-group">Taxes <span class="btn btn-success pull-right createTaxBtn"><i class="fa fa-plus"></i> New</span></h3>
                <?php $taxes = Tax::find('sql', "SELECT * FROM taxes ORDER BY tname ASC");?>
                <?php if ($taxes) { ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th width="25%">Tax Name</th>
                        <th width="50%">Short Description</th>
                        <th width="10%">Pecentage</th>
                        <th width="15%">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php for ($t = 0;$t < count($taxes);$t++) { ?>
                        <tr>
                            <td><?= $taxes[$t]->name();?></td>
                            <td><?= $taxes[$t]->description();?></td>
                            <td><?= $taxes[$t]->rate();?>%</td>
                            <td>
                                <span data-tax="<?= $taxes[$t]->id();?>" class="editTaxBtn btn btn-warning"><i class="fa fa-edit"></i></span>
                                <span data-tax="<?= $taxes[$t]->id();?>" class="deleteTaxBtn btn btn-danger"><i class="fa fa-times-circle"></i></span>
                            </td>
                        </tr>
                        <?php } // End all taxes FOR loop ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    <div class="nothing-full">
                        <h4>No taxes have been set. Would you like to create one?</h4>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('.createHourlyWageBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/new.wage.form.php', {scope: 'hourly'});
    });
    $('.createFlatWageBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/new.wage.form.php', {scope: 'flat'});
    });
    $('.editWageBtn').click(function() {
        var wageID = $(this).attr('data-wage');
        openForm('<?= BASE_URL;?>app/views/forms/edit.wage.form.php', {wage: wageID});
    });
    $('.deleteWageBtn').click(function() {
        var confirmation = confirm('Are you sure you want to delete this wage?');
        if (confirmation) {
            var wageID = $(this).attr('data-wage');
            $.post('./', {delete: "wage", wage: wageID}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Unable to Delete Wage', data);
                }
            });
        }
    });
    $('.createTaxBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/new.tax.form.php');
    });
    $('.deleteTaxBtn').click(function() {
        var confirmation = confirm('Are you sure you want to delete this tax?');
        if (confirmation) {
            var taxID = $(this).attr('data-tax');
            $.post('./', {delete: "tax", tax: taxID}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Unable to Delete Tax', data);
                }
            });
        }
    });
    $('.editTaxBtn').click(function() {
        var taxID = $(this).attr('data-tax');
        openForm('<?= BASE_URL;?>app/views/forms/edit.tax.html.php', {tax: taxID});
    });
    $('.updateExchangeRatesBtn').click(function() {
        $(this).html('<i class="fa fa-cog fa-spin"></i> Updating');
        $.post('./', {update: "exchange-rates"}, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                $('.updateExchangeRatesBtn').html('Update Rates');
                entityError('Unable to Update Rates', data);
            }
        });
    });
</script>