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
                            <p>Due by: <?= ($invoice->due()) ? $invoice->due()->format('F d, Y'): 'No Due Date';?></p>
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
                                        <?php if ($current_user->role()->is_staff() || $invoice->payee()->id() == $current_user->id()) { ?>
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
                                            <?php if ($current_user->role()->is_staff() || $invoice->payee()->id() == $current_user->id()) { ?>
                                                <td><span class="btn btn-danger btn-sm deleteChargeBtn" charge="<?= $charges[$c]->id();?>"><i class="fa fa-times"></i></span></td>
                                            <?php } ?>
                                            <td><?= $charges[$c]->name();?></td>
                                            <td><?= $charges[$c]->description();?></td>
                                            <td class="text-right"><?= $invoice->currency()->symbol();?> <?= $charges[$c]->amount();?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <?php if ($current_user->role()->is_staff() || $invoice->payee()->id() == $current_user->id()) { ?>
                                            <td colspan="2"></td>
                                        <?php } else { ?>
                                            <td></td>
                                        <?php } ?>
                                        <td class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong><?= $invoice->amount();?></strong></td>
                                    </tr>
                                    </tfoot>
                                </table>

                                <?php if ($invoice->payor()->id() != $current_user->id() || $current_user->role()->is_staff()) { ?>
                                    <p><span class="btn btn-success createInvoiceChargeBtn"><i class="fa fa-plus"></i> New Charge</span></p>
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