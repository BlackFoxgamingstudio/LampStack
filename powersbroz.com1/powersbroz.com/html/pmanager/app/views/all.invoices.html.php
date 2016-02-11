<?php
$open_income    = array();
$open_outgoing  = array();

foreach ($invoices as $i) {
    if ($i->payee()->id() == $current_user->id() && !$i->paid()) {
        $open_income[] = $i;
    }
    if ($i->payor()->id() == $current_user->id() && !$i->paid()) {
        $open_outgoing[] = $i;
    }
}

?>
<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Invoices &amp; Finance Reports</h2>
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
                            <?php if ($current_user->role()->can('invoice', 'create')) { ?>
                            <li><span id="newInvoiceBtn" class="btn btn-success"><i class="fa fa-plus"></i> New</span></li>
                            <?php } ?>
                            <li><a class="btn btn-primary" href="history/" title="All invoices history"><i class="fa fa-list"></i> All Records</a></li>
                        </ul>
                    </li>
                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-refresh"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php if ($current_user->role()->is_contractor()) { ?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Income Invoices Awaiting Payment</h3>
                <?php if ($open_income) { ?>
                    <table id="awaitingPayment" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Paid By</th>
                            <th>Paid To</th>
                            <th>Due</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < count($open_income); $i++) { ?>

                            <tr class="invoiceRow">
                                <td><a href="<?= BASE_URL;?>invoices/view/<?= $open_income[$i]->number();?>" title="View invoice" class="btn btn-primary"><?= $open_income[$i]->number();?></a></td>
                                <td><?= $open_income[$i]->name();?></td>
                                <td><?= $open_income[$i]->amount();?></td>
                                <td><?= $open_income[$i]->payor()->name();?></td>
                                <td><?= ($open_income[$i]->is_company_invoice()) ? $open_income[$i]->show_company(): $open_income[$i]->payee()->name();?></td>
                                <td>
                                    <?= ($open_income[$i]->due()) ? $open_income[$i]->due()->format('F d, Y') : '<span class="label label-warning">No Due Date</span>';?>
                                    <?= ($open_income[$i]->overdue()) ? '<i class="fire glyphicon glyphicon-fire"></i>': '';?>
                                </td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>

                <?php } else { ?>

                    <div class="nothing-full">
                        <h2>No open invoices to display <i class="fa fa-frown-o"></i></h2>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <h3>Pending Income</h3>
                    <p><span class="stat"><?= Invoice::format(Invoice::my_pending_income());?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Past Due</h3>
                    <p><span class="stat"><?= Invoice::format(Invoice::my_overdue_income());?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Open Invoices</h3>
                    <p><span class="stat"><?= Invoice::my_total_open();?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } // End contractor if statement ?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Open Payouts</h3>
                <?php if ($open_outgoing) { ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Invoice No.</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Paid By</th>
                            <th>Paid To</th>
                            <th>Due</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < count($open_outgoing); $i++) { ?>

                            <tr class="invoiceRow">
                                <td><a href="<?= BASE_URL;?>invoices/view/<?= $open_outgoing[$i]->number();?>" title="View invoice" class="btn btn-primary"><?= $open_outgoing[$i]->number();?></a></td>
                                <td><?= $open_outgoing[$i]->name();?></td>
                                <td><?= $open_outgoing[$i]->amount();?></td>
                                <td><?= $open_outgoing[$i]->payor()->name();?></td>
                                <td><?= ($open_outgoing[$i]->is_company_invoice()) ? $open_outgoing[$i]->show_company(): $open_outgoing[$i]->payee()->name();?></td>
                                <td>
                                    <?= ($open_outgoing[$i]->due()) ? $open_outgoing[$i]->due()->format('F d, Y') : '<span class="label label-warning">No Due Date</span>';?>
                                    <?= ($open_outgoing[$i]->overdue()) ? '<i class="fire glyphicon glyphicon-fire"></i>': '';?>
                                </td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="nothing-full">
                        <h2>You don't owe anything! <i class="fa fa-smile-o"></i></h2>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <h3>Pending Payments</h3>
                    <p><span class="stat"><?= Invoice::format(Invoice::my_pending_payments());?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Past Due</h3>
                    <p><span class="stat"><?= Invoice::format(Invoice::my_overdue_payments());?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Open Payments</h3>
                    <p><span class="stat"><?= Invoice::my_total_open_payments();?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#newInvoiceBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.invoice.form.php');
    });
    $('#filterBtn').click(function() {
        var filterValue = $('#filterList').val();
        console.log(filterValue);
        switch(filterValue) {
            case 'Thirty':
                $('#awaitingPayment tr.invoiceRow').show().hide();
                $('#awaitingPayment tr.js30').show();
                break;
            case 'Overdue':
                $('#awaitingPayment tr.invoiceRow').show().hide();
                $('#awaitingPayment tr.jsOverdue').show();
                break;
            default:
                $('#awaitingPayment tr.invoiceRow').show();
        }
    });
</script>