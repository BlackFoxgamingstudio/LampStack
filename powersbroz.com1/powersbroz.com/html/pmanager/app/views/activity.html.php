<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>System Activity Log</h2>
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
                            <li><span class="btn btn-primary cleanActivityBtn" title="Clean log"><i class="fa fa-paint-brush"></i> Clean Log</span></li>
                            <li><span class="btn btn-primary deleteActivityBtn" title="Erase log"><i class="fa fa-eraser"></i> Erase Log</span></li>
                            <li><a class="btn btn-help" href="<?= BASE_URL;?>docs/#activity-log" title="Documentation"><i class="fa fa-support"></i>  Log Documentation</a></li>
                        </ul>
                    </li>
                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-home"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <?php if ($activity) { ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">All system activity</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-activity">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Date and time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $results_per_page   = 15;
                            $page               = 1;
                            $result             = 1;
                            ?>
                            <?php for ($a = 0; $a < count($activity);$a++) { ?>
                                <?php

                                if ($result > $results_per_page) {
                                    $page++;
                                    $result = 1;
                                }

                                ?>
                                <tr class="activityPage" page="<?= $page;?>">
                                    <td><a href="<?= BASE_URL;?>users/view/<?=$activity[$a]->get('user')->id();?>" title="View <?= $activity[$a]->get('user')->name();?> profile"><?= $activity[$a]->get('user')->name();?></a></td>
                                    <td><?= $activity[$a]->action_icon() . ' ' .$activity[$a]->message();?></td>
                                    <td><?= $activity[$a]->get('created')->format('F d, Y') . '@' .readable_time($activity[$a]->get('created'));?></td>
                                </tr>
                                <?php $result++;?>
                            <?php } // End activity log FOR loop ?>
                            </tbody>
                        </table>

                        <?php if ($page > 1) { ?>
                            <!-- Activity Table Pagination -->
                            <nav>
                                <ul class="pagination">
                                    <?php for ($i = 1;$i <= $page;$i++) { ?>
                                        <li><span style="cursor: pointer;" class="activityPageBtn" page="<?= $i;?>"><?= $i;?></span></li>
                                    <?php } ?>
                                </ul>
                            </nav>
                            <p class="subdued">Current Page: <span class="activityCurrentPage">1</span></p>
                            <script>
                                $('.activityPage').hide();
                                $('.activityPage[page="1"]').show();
                                $('.activityPageBtn').click(function() {
                                    $('.activityPage').hide();
                                    var page = $(this).attr('page');
                                    $('.activityCurrentPage').html(page);
                                    $('.activityPage[page="'+ page +'"]').show();
                                });
                            </script>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>


        <?php } else { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-lg">
                        <h3>There is currently no activity in the database</h3>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>Export Application Activity</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <form action="./" method="post">
                        <input type="hidden" name="export" value="activity-text" />
                        <span class="stat"><i class="fa fa-file"></i></span>
                        <p><button type="submit" class="btn btn-primary">Text</button></p>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <form action="./" method="post">
                        <input type="hidden" name="export" value="activity-csv"/>
                        <span class="stat"><i class="fa fa-file-excel-o"></i></span>
                        <p><button class="btn btn-primary">Excel (CSV)</button></p>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <span class="stat"><i class="fa fa-print"></i></span>
                    <p><a href="<?= BASE_URL;?>activity/printer/" title="Print all activity" class="btn btn-primary">Print</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.deleteActivityBtn').click(function() {
        var confirmation = confirm('Are you sure you want to completely erase your activity log?');
        if (confirmation) {
            $.post('./', {delete: 'activity'}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Unable to Erase Log', 'There was an error erasing your activity log entries from the database.');
                }
            });
        }
    });
    $('.cleanActivityBtn').click(function() {
        $.post('./', {clean: 'activity'}, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Clean Log', 'There was an error cleaning out the activity log in the database.');
            }
        });
    });
</script>