<?php
// Helper variables
$CAN_ALTER = ($current_user->role()->is_staff() && $invoice->payor()->id() != $current_user->id()) || $invoice->payee()->id() == $current_user->id();
$PAID = $invoice->paid()

?>
<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?= $invoice->name() .', '.$invoice->number();?></h2>
            </div>
        </div>
    </div>
</div>

<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="toolbar-options">
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <li>
                                <a href="<?= BASE_URL;?>invoices/printer/<?= $invoice->number();?>" class="btn btn-primary">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </li>

                            <?php if ($invoice->payor()->id() == $current_user->id() && !$invoice->paid()) { ?>
                                <li>
                                    <?php if ($stripeReady) { ?>
                                    <a id="payStripeInvoiceBtn" class="btn btn-success">
                                        <i class="fa fa-cc-stripe"></i> Pay
                                    </a>
                                    <?php } else { ?>
                                    <span class="btn btn-default">
                                        <i class="fa fa-cc-stripe"></i> Payment Unavailable
                                    </span>
                                    <?php } ?>
                                </li>
                            <?php } ?>

                            <?php if ($CAN_ALTER) { ?>
                                <!-- TODO: Enter edit invoice functionality
                                <li>
                                    <span id="editInvoiceBtn" class="btn btn-warning">
                                        <i class="fa fa-chain"></i> Edit
                                    </span>
                                </li>
                                -->
                                <li>
                                    <span id="deleteInvoiceBtn" class="btn btn-danger">
                                        <i class="fa fa-times-circle"></i> Delete
                                    </span>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>


                    <li class="pull-right">
                        <a href="<?= BASE_URL;?>invoices/" title="All invoices" class="btn btn-primary">
                            <i class="fa fa-refresh"></i> Finances
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-invoice">
                    <div class="single-invoice-head">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Paid By:</h3>
                                <ul class="list-unstyled">
                                    <li><?= $invoice->payor()->name();?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h3>Paid To:</h3>
                                <ul class="list-unstyled">
                                    <?php if ($invoice->is_company_invoice()) { ?>
                                    <li><?= $invoice->show_company();?></li>
                                    <li><span class="label label-primary">Created by <?= $invoice->creator()->name();?></span></li>
                                    <?php } else { ?>
                                    <li><?= $invoice->payee()->name();?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Special Instructions</h3>
                            <p><?= $invoice->notes();?></p>
                            <hr/>
                            <?php if ($invoice->paid()) { ?>
                            <p><span class="label label-success">Paid</span> on <?= $invoice->updated()->format('F d, Y');?></p>
                            <?php } else { ?>
                            <p>Due by: <?= ($invoice->due()) ? $invoice->due()->format('F d, Y'): 'No Due Date';?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row push-vertical">
                        <div class="col-md-12">
                            <h4>Individual Charges</h4>
                            <?php if ($invoice->has_charges()) { ?>
                            <?php $charges = $invoice->get_charges();?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <?php if ($CAN_ALTER) { ?>
                                        <th></th>
                                        <?php } ?>
                                        <th>Charge</th>
                                        <th>Description of charge</th>
                                        <th width="10%" class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($c = 0;$c < count($charges);$c++) { ?>
                                    <tr>
                                        <?php if ($CAN_ALTER) { ?>
                                        <td><span class="btn btn-danger btn-sm deleteChargeBtn" charge="<?= $charges[$c]->id();?>"><i class="fa fa-times"></i></span></td>
                                        <?php } ?>
                                        <td><?= $charges[$c]->name();?></td>
                                        <td>
                                            <?= $charges[$c]->description();?><br/>
                                            <?php if ($charges[$c]->has_taxes()) { ?>
                                                <ul class="invoice-tax-list">
                                                    <li>Taxes Applied: </li>
                                                    <?php $tax = $charges[$c]->get_taxes();?>
                                                    <?php foreach ($tax as $key => $value) { ?>
                                                        <li><span class="label label-primary">+ <?= $value->rate() . '% Tax'; ?></span></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } // End charge tax IF block ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $invoice->currency()->symbol();?> <?= $charges[$c]->amount();?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <?php if ($CAN_ALTER) { ?>
                                        <td colspan="2"></td>
                                        <?php } else { ?>
                                        <td></td>
                                        <?php } ?>

                                        <?php if ($invoice->has_taxes()) { ?>
                                        <td class="text-right"><strong>Subtotal:</strong></td>
                                        <td class="text-right"><strong><?= $invoice->amount();?></strong></td>
                                        <?php } else { ?>
                                        <td class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong><?= $invoice->amount();?></strong></td>
                                        <?php } ?>
                                    </tr>
                                    <?php if ($invoice->has_taxes()) { ?>
                                    <?php $taxes = $invoice->get_taxes();?>
                                    <?php foreach ($taxes as $tax) { ?>
                                    <tr class="tax-table-row">
                                        <?php if ($CAN_ALTER) { ?>
                                            <td colspan="2"></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td class="text-right"><?= $tax->name();?></td>
                                        <td class="text-right"><?= $tax->rate();?>%</td>
                                    </tr>
                                    <?php } // End foreach loop ?>
                                    <tr>
                                        <?php if ($CAN_ALTER) { ?>
                                            <td colspan="2"></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td class="text-right">Total:</td>
                                        <td class="text-right"><?= $invoice->currency()->symbol() . ' ' .number_format((($invoice->get_tax_total() / 100 ) * $invoice->raw_amount()) + $invoice->raw_amount(), 2);?></td>
                                    </tr>
                                    <?php } // End has taxes IF block ?>
                                </tfoot>
                            </table>

                            <?php if (!$PAID && $CAN_ALTER) { ?>
                            <p>
                                <span class="btn btn-success createInvoiceChargeBtn">
                                    <i class="fa fa-plus"></i> New Charge
                                </span>
                                <span class="btn btn-success applyTaxToInvoiceBtn">
                                    <i class="fa fa-plus"></i> Apply Tax
                                </span>
                                <span class="btn btn-danger removeTaxesBtn">
                                    <i class="fa fa-times-circle-o"></i> Remove all Taxes
                                </span>
                            </p>
                            <?php } ?>

                            <?php } else { ?>

                            <?php if ($invoice->payor()->id() != $current_user->id() || $invoice->payee()->id() == $current_user->id()) { ?>
                                <p>No charges are associated with this invoice. Would you like to add one?</p>
                                <p><span class="btn btn-success createInvoiceChargeBtn"><i class="fa fa-plus"></i> New Charge</span></p>
                            <?php } else { ?>
                                <p>No charges are associated with this invoice.</p>
                            <?php } ?>

                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($invoice->has_project()) { ?>
                        <hr/>
                        <div class="row push-vertical">
                            <div class="col-md-4 text-center">
                                <img src="<?= $invoice->has_project()->image();?>" alt="<?= $invoice->has_project()->name();?> job image" class="img img-responsive" width="50%"/>
                            </div>
                            <div class="col-md-8">
                                <p class="subdued">Charges on this invoices are related to work performed on:</p>
                                <h3><?= $invoice->has_project()->name();?></h3>
                                <p><?= $invoice->has_project()->description();?></p>
                                <p class="subdued">Project Start Date: <?= $invoice->has_project()->startdate();?> &amp; End Date: <?= $invoice->has_project()->enddate();?></p>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    <?php if ($CAN_ALTER) { ?>
    $('.createInvoiceChargeBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/new.charge.form.php', {invoice: "<?= $invoice->id();?>"});
    });
    $('.applyTaxToInvoiceBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/apply.tax.form.php', {invoice: "<?= $invoice->id();?>"});
    });
    $('.removeTaxesBtn').click(function() {
        $.post('./', {delete: "tax-assignments", invoice: <?= $invoice->id();?>}, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Remove Taxes', data);
            }
        });
    });
    $('#deleteInvoiceBtn').click(function() {
        var confirmDeletion = confirm('Are you sure you want to delete this invoice?');
        if (confirmDeletion) {
            $.post('./', {delete: "invoice", invoice: "<?= $invoice->id();?>"}, function(data) {
                if (data == "success") {
                    window.location = "<?= BASE_URL;?>invoices";
                } else {
                    entityError('Unable to Delete Invoice', data);
                }
            });
        }
    });
    $('.deleteChargeBtn').click(function() {
        var chargeId = $(this).attr('charge');
        $.post('./', {delete: "invoice-charge", charge: chargeId}, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Delete Charge', data);
            }
        });
    });
    <?php } ?>

    <?php if ($stripeReady) { ?>
    // Stripe Payment script
    var handler = StripeCheckout.configure({
        <?php if ($invoice->is_company_invoice()) { ?>
        key: '<?= $app_settings->appStripePublishableModeKey();?>',
        <?php } else { ?>
        key: '<?= $invoice->payee()->stripePublishKey();?>',
        <?php } ?>
        image: '<?= BASE_URL;?>img/logo.png',
        locale: 'auto',
        currency: '<?= $invoice->currency()->code();?>',
        email: '<?= $invoice->payor()->email();?>',
        token: function(token) {
            $.post('./', {payment: "invoice-stripe", invoice: "<?= $invoice->id();?>", stripeToken: token.id}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Payment Error', data);
                }
            });
        }
    });
    $('#payStripeInvoiceBtn').on('click', function(e) {
        // Open Checkout with further options
        handler.open({
            <?php if ($invoice->is_company_invoice()) { ?>
            name: '<?= $invoice->show_company();?>',
            <?php } else { ?>
            name: '<?= $invoice->payee()->name();?>',
            <?php } ?>
            description: '<?= $invoice->number().' : '.$invoice->name();?>',
            <?php if ($invoice->has_taxes()) { ?>
            amount: <?= $invoice->taxed_amount_pennies();?>
            <?php } else { ?>
            amount: <?= $invoice->raw_amount_pennies();?>
            <?php } ?>
        });
        e.preventDefault();
    });

    // Close Checkout on page navigation
    $(window).on('popstate', function() {
        handler.close();
    });
    <?php } else { ?>
    $('#payStripeInvoiceBtn').click(function() {
        entityError('Stripe Not Configured', 'Stripe is not configured properly to pay this invoice using Stripe Checkout');
    });
    <?php } ?>

</script>