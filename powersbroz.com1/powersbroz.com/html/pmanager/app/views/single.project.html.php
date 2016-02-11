<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?= $project->name();?></h2>
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
                            <?php if (!$project->is_complete() && ($current_user->role()->can('project', 'edit') || $current_user->is_lead($project->id(), 'project'))) { ?>
                                <li>
                                    <form class="no-space" id="createProjectImage" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="create" value="project-image"/>
                                        <input type="hidden" name="project" value="<?= $project->id();?>"/>
                                        <label class="no-space" for="project-image">
                                            <span id="editProjectImgBtn" class="btn btn-primary"><i class="fa fa-image"></i> Image</span>
                                        </label>
                                        <input class="hidden" type="file" name="project-image" id="project-image"/>
                                    </form>
                                </li>
                                <li><span id="editProjectBtn" class="btn btn-warning"><i class="fa fa-chain"></i> Edit</span></li>
                            <?php } ?>
                            <li><span class="btn btn-primary historyBtn"><i class="fa fa-clock-o"></i> History</span></li>
                            <?php if (!$project->is_complete() && $current_user->role()->can('project', 'edit') && ($project->raw_startdate() < $now)) { ?>
                                <li><span class="btn btn-success completeProjectBtn"><i class="fa fa-check"></i> Mark Complete</span></li>
                            <?php } ?>
                            <?php if ($current_user->role()->can('project', 'delete')) { ?>
                                <li><span class="btn btn-danger deleteProjectBtn"><i class="fa fa-times-circle"></i> Delete</span></li>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="pull-right"><a href="<?= BASE_URL;?>projects/" title="All projects" class="btn btn-primary"><i class="fa fa-refresh"></i> All Projects</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="project-history">
    <div class="container">
        <div class="row">
            <?php $history = ProjectHistory::get_history($project->id());?>
            <?php if ($history) { ?>
                <table class="table">
                    <tbody>
                    <?php foreach ($history as $item) { ?>
                    <tr>
                        <td><?= $item->action_icon();?></td>
                        <td><?= $item->created();?></td>
                        <td><?= $item->description();?></td>
                        <td><i class="glyphicon glyphicon-user"></i> <?= $item->user();?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <hr/>
               <form method="post" action="">
                   <input type="hidden" name="export" value="project-history-csv"/>
                   <input type="hidden" name="projectid" value="<?= $project->id();?>"/>
                   <p><button class="btn btn-primary" type="submit"><i class="fa fa-file-excel-o"></i> Export as Excel CSV</button></p>
               </form>
            <?php } else { ?>
                <div class="nothing-full">
                    <h2>No history found</h2>
                    <p>This project has not history to show</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="section" style="background-image: url('<?= $project->image();?>');background-position: bottom; background-repeat: no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Project Description:</h3>
                <div class="glass">
                    <blockquote>
                        <p><?= nl2br($project->description());?></p>
                    </blockquote>
                </div>
                <ul class="list-unstyled list-inline push-vertical">
                    <li>Project Lead(s)</li>
                    <li><?= ($project->owner) ? '<span class="label label-primary"> '.$project->owner->name() .'</spam>' : '<span class="label label-default">Unassigned</span>';?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <?php if (!$project->is_complete()) { ?>
        <div class="row">
            <div class="col-md-6 text-center">
                <p><i class="fa fa-calendar"></i> Start: <?= $project->get('startdate')->format('F d, Y');?></p>
            </div>
            <div class="col-md-6 text-center">
                <p><i class="fa fa-calendar"></i> Ends: <?= $project->get('enddate')->format('F d, Y');?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>Time Progression</h3>
                <?php if ($project->has_started()) { ?>
                <?= $project->progress('time', 'meter');?>
                <?php } else { ?>
                <p>This project has not started. The time progression meter will be available on <?= $project->get('startdate')->format('F d, Y');?></p>
                <?php } ?>
            </div>
        </div>
        <?php } else { ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="alert alert-success">
                    <h3>Project Completed <?= $project->get('updated')->format('F d, Y');?></h3>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>

<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <h3>Assigned Users</h3>
                    <p><span class="stat"><?= ($project->get_team_unique()) ? count($project->get_team_unique()) : '0';?></span></p>
                    <?php if (!$project->is_complete() && $current_user->role()->can('project', 'assign')) { ?>
                    <p><a id="assignUserBtn" href="#" class="btn btn-success"><i class="fa fa-chain"></i> Assign</a></p>
                    <?php } else { ?>
                    <?= restricted_button();?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Invoices</h3>
                    <p><span class="stat"><?= $project->total_invoices();?></span></p>
                    <?php if (!$project->is_complete() && $current_user->role()->can('invoice', 'create')) { ?>
                    <p><span class="btn btn-success newInvoiceBtn"><i class="fa fa-plus"></i> New</span></p>
                    <?php } else { ?>
                        <?= restricted_button();?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Hours</h3>
                    <p><span class="stat"><?= $project->total_hours();?></span></p>
                    <?php if (!$project->is_complete() && !$current_user->role()->is_client()) { ?>
                    <p><a href="#" onclick="return timerWindow('<?=BASE_URL;?>app/timer.php?project=<?= $project->id();?>')" class="btn btn-primary"><i class="fa fa-clock-o"></i> Open Timer</a></p>
                    <?php } else { ?>
                        <?= restricted_button();?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!$project->is_complete() && $current_user->role()->can('project', 'assign')) { ?>
<script>
    // User assignment via multiple mass select
    $('#assignUserBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/mass.assign.user.form.php', {project: <?= $project->id();?>});
    });
</script>
<?php } ?>
<div class="section">
    <div class="container">
        <div class="row">
            <div id="project-team" class="col-md-12 text-center">

            </div>
        </div>
    </div>
</div>
<script>
    $('#project-team').html('<span class="label-carolina"><i class="fa fa-cog fa-spin"></i> Finding Team Members</span>');
    $.get('<?= VIEW_COLLECTIONS;?>project.members.html.php', {project: "<?= $project->id();?>"}, function(data) {
        $('#project-team').html(data);
    });
</script>

<div id="project-stages" class="highlight">

</div>
<script>
    $('#project-stages').html('<div class="ajax-loading"><i class="fa fa-cog fa-spin"></i> Loading project stages and tasks...</div>');
    $.get('<?= VIEW_COLLECTIONS;?>project.stages.html.php', {project: "<?= $project->id();?>"}, function(data) {
        $('#project-stages').html(data);
    });
</script>

<div class="section">
    <div class="container">
        <div class="row">
            <div id="forum-content" class="col-md-12">

            </div>
        </div>
    </div>
</div>

<script>
    <?php if (!$project->is_complete()) { ?>
    $('#editProjectBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/edit.project.form.php', {project: "<?= $project->id();?>"});
    });
    $('.completeProjectBtn').click(function() {
        var completeConfirmation = confirm('Are you sure you want to mark this project as complete?');
        if (completeConfirmation) {
            $.post('./', {complete: "project", project: "<?= $project->id();?>"}, function(data) {
                if (data == 'success') {
                    window.location = "<?= BASE_URL;?>projects/";
                } else {
                    entityError('Unable to Mark Complete', data);
                }
            });
        }
    });
    $('#project-image').change(function() {
        $('#createProjectImage').submit();
    });
    <?php } // End if project is not complete statement ?>

    $('.historyBtn').click(function() {
        $('.project-history').slideToggle('fast');
    });

    $('.deleteProjectBtn').click(function() {
        var userInput = confirm('Are you sure you want to delete this project?');
        if (userInput) {
            $.post('./', {delete: "project", project: "<?= $project->id();?>"}, function(data) {
                if (data == 'success') {
                    window.location = "<?= BASE_URL;?>projects/";
                } else {
                    entityError('Unable to Delete Project', data);
                }
            });
        }
    });
    $('#forum-content').html('<div class="ajax-loading"><i class="fa fa-cog fa-spin"></i> Loading project forum posts...</div>');
    $.get('<?= VIEW_COLLECTIONS;?>project.forum.html.php', {project: "<?= $project->id();?>"}, function(data) {
        $('#forum-content').html(data);
    });
    $('.newInvoiceBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/new.project.invoice.form.php', {project: <?= $project->id();?>});
    });
</script>