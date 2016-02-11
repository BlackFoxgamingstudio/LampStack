<?
$open_timers = gen_query("SELECT * FROM timer_items WHERE tuser = ".$current_user->id()." AND closed = 0");
$closed_timers = gen_query("SELECT * FROM timer_items WHERE tuser = ".$current_user->id()." AND closed = 1")
?>
<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>My Tracked Time</h2>
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
                    <?php $man_hours = Timer::get_man_hours($current_user->id());?>
                    <li>Recorded Man Hours: <span class="label label-primary"><i class="fa fa-clock-o"></i> <?= $man_hours;?> hrs</span></li>
                    <li class="pull-right"><a href="<?= BASE_URL;?>account/" title="My Account" class="btn btn-primary"><i class="fa fa-refresh"></i> My Account</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Open Tracked Time</h3>
                    </div>

                    <div class="panel-body">
                        <?php if ($open_timers['count'] > 0) { ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Note</th>
                                <th>Project</th>
                                <th>Time</th>
                                <th>Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $time_total = 0; // in seconds ?>
                            <?php for ($t = 0;$t < $open_timers['count'];$t++) { ?>
                                <?php $project = Project::find('id', $open_timers['rows'][$t]['tproject']);?>
                                <?php
                                $time_total += ($open_timers['rows'][$t]['thours'] * 60) * 60;
                                $time_total += $open_timers['rows'][$t]['tminutes'] * 60;
                                $time_total += $open_timers['rows'][$t]['tseconds'];
                                $created    = new DateTime($open_timers['rows'][$t]['created']);
                                ?>
                                <tr>
                                    <td width="50%"><?= htmlspecialchars($open_timers['rows'][$t]['tnote']);?></td>
                                    <td><?= $project->name();?></td>
                                    <td>
                                        <?= ($open_timers['rows'][$t]['thours'] > 0) ? $open_timers['rows'][$t]['thours'].' hours ' : '';?>
                                        <?= ($open_timers['rows'][$t]['tminutes'] > 0) ? $open_timers['rows'][$t]['tminutes'].' min ' : '';?>
                                        <?= ($open_timers['rows'][$t]['tseconds'] > 0) ? $open_timers['rows'][$t]['tseconds'].' sec ' : '';?>
                                    </td>
                                    <td><?= $created->format('F d, Y H:i');?></td>
                                </tr>
                            <?php } // End timer items FOR Loop ?>
                            </tbody>
                            <?php $time_total = Timer::convert_seconds($time_total);?>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td>Totals</td>
                                <td colspan="2"><?= $time_total['h'].' hours '.$time_total['m'].' min '.$time_total['s'].' sec';?></td>
                            </tr>
                            </tfoot>
                        </table>
                        <?php } else { ?>
                        <div class="nothing-full">
                            <h2>You have no assignable time entries to display</h2>
                        </div>
                        <?php } // End open timer items IF Block ?>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Closed out time entries</h3>
                    </div>
                    <div class="panel-body">
                        <?php if ($closed_timers['count'] > 0) { ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Note</th>
                                <th>Project</th>
                                <th>Time</th>
                                <th>Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $time_total = 0; // in seconds ?>
                            <?php for ($t = 0;$t < $closed_timers['count'];$t++) { ?>
                                <?php $project = Project::find('id', $closed_timers['rows'][$t]['tproject']);?>
                                <?php
                                $time_total += ($closed_timers['rows'][$t]['thours'] * 60) * 60;
                                $time_total += $closed_timers['rows'][$t]['tminutes'] * 60;
                                $time_total += $closed_timers['rows'][$t]['tseconds'];
                                $created    = new DateTime($closed_timers['rows'][$t]['created']);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($closed_timers['rows'][$t]['tnote']);?></td>
                                    <td><?= $project->name();?></td>
                                    <td>
                                        <?= ($closed_timers['rows'][$t]['thours'] > 0) ? $closed_timers['rows'][$t]['thours'].' hours ' : '';?>
                                        <?= ($closed_timers['rows'][$t]['tminutes'] > 0) ? $closed_timers['rows'][$t]['tminutes'].' min ' : '';?>
                                        <?= ($closed_timers['rows'][$t]['tseconds'] > 0) ? $closed_timers['rows'][$t]['tseconds'].' sec ' : '';?>
                                    </td>
                                    <td><?= $created->format('F d, Y H:i');?></td>
                                </tr>
                            <?php } // End timer items FOR Loop ?>
                            </tbody>
                            <?php $time_total = convert_seconds($time_total);?>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td>Totals</td>
                                <td><?= $time_total['h'].' hours '.$time_total['m'].' min '.$time_total['s'].' sec';?></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                        <?php } else { ?>
                        <div class="nothing-full">
                            <h2>You have no assigned time entries to display</h2>
                        </div>
                        <?php } // End closed timer items IF Block ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>