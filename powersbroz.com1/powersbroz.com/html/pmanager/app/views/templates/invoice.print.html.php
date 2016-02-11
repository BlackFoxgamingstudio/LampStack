<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Entity {CC} - Print Invoice <?= $invoice->number();?></title>
    <style type="text/css">
        body {
            position: relative;
            height: 100%;
            padding: 0;
            margin: 0;
            background: white;
            font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-weight: 300;
        }

        .toolbar {
            background: black;
            color: #AAA;
            padding: 15px 0;
            text-align: center;
        }

        .toolbar a, .toolbar a:visited, .toolbar a:hover {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: 90%;
            height: 100%;
            margin-left:auto;
            margin-right: auto;
            padding: 25px;
        }
        hr {
            border: none;
            border-bottom: 1px solid #CCC;
            margin: 25px 0px;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        table {
            width: 100%;
            margin: auto 0;
        }

        table.charges-table {
            text-align: left;
            padding: 25px 0;
        }

        table.charges-table tr th {
            border-bottom: 1px solid #CCC;
            padding: 10px 0;
        }

        table.charges-table tr td {
            padding: 15px 0;
        }
    </style>
</head>
<body>

<div class="toolbar">
    <ul>
        <li><a href="<?= BASE_URL;?>invoices/view/<?= $invoice->number();?>" title="Go back to view the invoice">Back to Application</a></li>
    </ul>
</div>

<div class="container">
    <table>
        <tr>
            <td>
                <h1>Paidby:</h1>
                <ul>
                    <li><?= $invoice->payor()->name();?></li>
                </ul>
            </td>
            <td>
                <h1>Paidto:</h1>
                <ul class="list-unstyled">
                    <?php if ($invoice->is_company_invoice()) { ?>
                        <li><?= $invoice->show_company();?></li>
                        <li><span class="label label-primary">Created by <?= $invoice->creator()->name();?></span></li>
                    <?php } else { ?>
                        <li><?= $invoice->payee()->name();?></li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h3>Special Instructions</h3>
                <p><?= $invoice->notes();?></p>
                <hr/>
                <?php if ($invoice->paid()) { ?>
                    <p><span class="label label-success">Paid</span> on <?= $invoice->updated()->format('F d, Y');?></p>
                <?php } else { ?>
                    <p>Due by: <?= ($invoice->due()) ? $invoice->due()->format('F d, Y'): 'No Due Date';?></p>
                <?php } ?>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <h4>Individual Charges</h4>
            <?php if ($invoice->has_charges()) { ?>
                <?php $charges = $invoice->get_charges();?>
                <table class="charges-table">
                    <thead>
                    <tr>
                        <th>Charge</th>
                        <th>Description of charge</th>
                        <th width="10%" class="text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for ($c = 0;$c < count($charges);$c++) { ?>
                        <tr>
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
                        <td></td>
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
                                <td></td>
                                <td class="text-right"><?= $tax->name();?></td>
                                <td class="text-right"><?= $tax->rate();?>%</td>
                            </tr>
                        <?php } // End foreach loop ?>
                        <tr>
                            <td></td>
                            <td class="text-right">Total:</td>
                            <td class="text-right"><?= $invoice->currency()->symbol() . ' ' .number_format((($invoice->get_tax_total() / 100 ) * $invoice->raw_amount()) + $invoice->raw_amount(), 2);?></td>
                        </tr>
                    <?php } // End has taxes IF block ?>
                    </tfoot>
                </table>

            <?php } else { ?>

                <p>No charges are associated with this invoice.</p>

            <?php } ?>
        </tr>
    </table>
</div>

</body>
</html>