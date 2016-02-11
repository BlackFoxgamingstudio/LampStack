<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Invoice History</h2>
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
                    <!-- TODO: Add Filter
                    <li>Filter</li>
                    <li>
                        <select style="display:inline;" id="filterList">
                            <option value="All">Unpaid</option>
                            <option value="Sixty">Past Due</option>
                            <option value="Thirty">Paid</option>
                        </select>
                    </li>
                    <li><span id="filterBtn" class="btn btn-primary"><i class="fa fa-filter"></i></span></li>
                    -->
                    <li class="pull-right"><a href="<?= BASE_URL;?>invoices/" title="All invoices" class="btn btn-primary"><i class="fa fa-refresh"></i> Finances</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="col-md-12">
            <?php if ($invoices) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice No.</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Paid By</th>
                        <th>Paid To</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($invoices);$i++) { ?>
                        <?php
                        $class = '';
                        if ($invoices[$i]->paid()) {
                            $class = ' class="alert alert-success"';
                        } else {
                            if ($invoices[$i]->due()) {
                                $class = ($invoices[$i]->due() < $now) ? ' class="alert alert-danger"': '';
                            }
                        }
                        ?>

                    <tr<?= $class;?>>
                        <td><?= $invoices[$i]->number();?></td>
                        <td><?= $invoices[$i]->name();?></td>
                        <td><?= $invoices[$i]->amount();?></td>
                        <td><?= $invoices[$i]->payor()->name();?></td>
                        <td><?= $invoices[$i]->payee()->name();?></td>
                        <td><a href="<?= BASE_URL;?>invoices/view/<?= $invoices[$i]->number();?>" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="nothing-full">
                    <h2>No Invoices are stored in your records</h2>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if ($current_user->role()->is_staff()) { ?>
<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h3>All Time Income</h3>
                    <p><span class="stat"><?= Invoice::format(Invoice::total_income());?></span></p>
                    <p class="subdued-caps">Tax not included</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3>All Time Payments</h3>
                    <p><span class="stat"><?= Invoice::format(Invoice::total_payments());?></span></p>
                    <p class="subdued-caps">Tax not included</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<?php require_once INCLUDES . 'user.support.section.html.php';?>
<?Php } ?>