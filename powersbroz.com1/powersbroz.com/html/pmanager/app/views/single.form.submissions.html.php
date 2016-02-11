<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?= $form->name();?></h2>
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
                    <li class="pull-right">
                        <a href="<?= BASE_URL;?>forms/edit/<?= $form->slug();?>" title="Go back to <?= $form->name();?>" class="btn btn-primary">
                            <i class="fa fa-refresh"></i> <?= $form->name();?>
                        </a>
                        <a href="<?= BASE_URL;?>forms/" title="All forms" class="btn btn-primary">
                            <i class="fa fa-refresh"></i> All Forms
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="highlight-dark-recessed">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <blockquote>
                    <p><?= $form->desc();?></p>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><a id="submissions"></a> Submissions</h2>
                <hr/>

                <?php if ($submissions) { ?>
                    <?php for ($s = 0;$s < count($submissions);$s++) { ?>
                        <?php $sf = FormSubmissionField::find('sql', "SELECT * FROM form_submission_fields WHERE submission_id = ".$submissions[$s]->id());?>

                        <div class="well well-lg">
                            <h3>
                                Submitted from IP: <span class="label label-primary"><?= $submissions[$s]->ip();?></span>
                                on <?= $submissions[$s]->created()->format('F d, Y'). ' <i class="fa fa-clock-o"></i> ' .readable_time($submissions[$s]->created());?>
                            </h3>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="50%">Field</th>
                                    <th>Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($sf as $field) { ?>
                                    <tr>
                                        <td><?= $field->field()->label();?></td>
                                        <?php if ($field->type() == 'input-file') { ?>
                                        <td><span class="btn btn-primary"><i class="fa fa-download"></i> <?= $field->value();?></span></td>
                                        <?php } else { ?>
                                        <td><?= $field->value();?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- TODO: Implement tracking capability
                            <hr/>
                            <p>Status: <?= $submissions[$s]->status();?></p>
                            -->
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="nothing-full">
                        <h2>No Submissions to display</h2>
                        <p>Unfortunately, you do not have any submissions of this form yet.</p>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>