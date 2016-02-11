<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Entity {CC} Health &amp; Status Monitoring</h2>
            </div>
        </div>
    </div>
</div>

<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="health-monitor-overview">
                    <li>System Information</li>
                    <li><i class="glyphicon glyphicon-qrcode"></i> <?= $app->name;?></li>
                    <li><abbr title="Version"><i class="fa fa-refresh"></i> <?= $app->version;?></abbr></li>
                    <li><abbr title="Build date"><i class="fa fa-clock-o"></i> <?= $app->vdate;?></abbr></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="normal-box">


                    <div class="health-monitor-section">
                        <h2>Flash Messages</h2>
                        <p>Flash messages are issues which need to be addressed right away.</p>
                        <?php if ($system_notification->hasFlashMessages()) { ?>
                            <?= $system_notification->showFlashMessages(); ?>
                        <?php } else { ?>
                            <div class="nothing-full">
                                <h3>There are currently no Flash Messages to display!</h3>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="health-monitor-section">
                        <h2>Captured security violations</h2>
                        <p>Entity monitors common security risks, mostly centered around file uploads and url access to users who do not have the proper privileges to see a certain page. It by no means covers all bases, but will slowly be expanded to watch for more threats.</p>
                        <?php if (HealthMonitor::securityActivity()) { ?>

                        <?php $securityWarning = HealthMonitor::securityActivity();?>
                        <p class="subdued">Total captured: <?= count($securityWarning);?></p>
                        <table class="table table-monitor">
                            <thead>
                                <tr>
                                    <th>Offender</th>
                                    <th>Action</th>
                                    <th>Date and Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($securityWarning as $warning) { ?>
                                <tr>
                                    <td>
                                        <a href="<?= BASE_URL;?>users/view/<?=$warning->get('user')->id();?>" title="View <?= $warning->get('user')->name();?> profile">
                                            <?= $warning->get('user')->name();?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $warning->action_icon() . ' ' .$warning->message();?>
                                    </td>
                                    <td>
                                        <?= $warning->get('created')->format('F d, Y') . '@' .readable_time($warning->get('created'));?>
                                    </td>
                                </tr>
                            <?php } // End foreach loop ?>
                            </tbody>
                        </table>

                        <?php } else { ?>
                        <div class="nothing-full">
                            <h3>No security warnings to report</h3>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="health-monitor-section">
                        <h2>Captured system errors</h2>
                        <p>Bugs are logged if the system failed to perform an action properly. If the bug was not the result of a fatal error, it will likely get reported. All PHP runtime fatal errors will result in a complete failure of the application and are not reportable. You need to look into your main PHP error_log file to see how your application is performing.</p>
                        <?php if (HealthMonitor::bugActivity()) { ?>

                            <?php $bugWarning = HealthMonitor::bugActivity();?>
                            <p class="subdued">Total captured: <?= count($bugWarning);?></p>
                            <table class="table table-monitor">
                                <thead>
                                <tr>
                                    <th>Victim</th>
                                    <th>Action</th>
                                    <th>Date and Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($bugWarning as $warning) { ?>
                                    <tr>
                                        <td>
                                            <a href="<?= BASE_URL;?>users/view/<?=$warning->get('user')->id();?>" title="View <?= $warning->get('user')->name();?> profile">
                                                <?= $warning->get('user')->name();?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= $warning->action_icon() . ' ' .$warning->message();?>
                                        </td>
                                        <td>
                                            <?= $warning->get('created')->format('F d, Y') . '@' .readable_time($warning->get('created'));?>
                                        </td>
                                    </tr>
                                <?php } // End foreach loop ?>
                                </tbody>
                            </table>

                        <?php } else { ?>
                            <div class="nothing-full">
                                <h3>No system errors to report</h3>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <hr/>

                <p class="alert alert-info">If your system error log is showing repeat problems with certain functionality, please take a screenshot and send it to me so I can address the problem. Entity is programmed to attempt to capture when common functions fail.</p>
            </div>
        </div>
    </div>
</div>