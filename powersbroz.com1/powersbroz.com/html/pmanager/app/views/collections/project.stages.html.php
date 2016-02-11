<?php
require_once '../../boot.php';
if (!isset($_GET['project'])) {
    echo 'Improper Access'; exit;
} else {
    $project = Project::find('id', $_GET['project']);
}

// Helper vars for readable checks
$IS_OWNER       = $project->is_owner($current_user->id());
$CREATE_PROJECT = $current_user->role()->can('project', 'create');
$CREATE_STAGE   = $current_user->role()->can('stage', 'create');
$CREATE_STASK   = $current_user->role()->can('stage-task', 'create');

?>
<div class="container">

    <?php if ($project->has_stages()) { ?>
    <?php $stages = $project->get_stages();?>

    <ul class="stage-list push-vertical">
        <li>Stages:</li>
        <?php for($s = 0; $s < count($stages);$s++) { ?>
        <li><span class="btn btn-primary stage-btn" stageTarget="<?= $s;?>"><?= $stages[$s]->name();?></span></li>
        <?php } ?>
    </ul>

    <?php for($s = 0; $s < count($stages);$s++) { ?>

    <div class="normal-box" stage="<?= $s;?>">
        <div class="row">
            <div class="col-md-12">
                <h3 class="stage-title">
                <?= $stages[$s]->name();?>
                <span class="pull-right">
                <span class="label label-primary"><i class="fa fa-tasks"></i> <?=$stages[$s]->number_of_tasks();?></span>
                <span class="label label-primary"><i class="fa fa-bar-chart"></i> <?= $stages[$s]->completion();?>%</span></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="stage-info">
                    <!-- Stage description -->
                    <p><?= nl2br(htmlspecialchars($stages[$s]->description()));?></p>

                    <!-- Stage Brief -->
                    <?php if ($stages[$s]->has_brief()) { ?>

                        <ul class="list-unstyled list-inline">
                            <li><img src="<?= BASE_URL;?>img/ico/compressed-32.png" alt="Stage brief"/> Stage Brief</li>
                            <li>
                                <form id="downloadStagebrief<?=$stages[$s]->id();?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="download" value="stage-brief"/>
                                    <input type="hidden" name="stage" value="<?=$stages[$s]->id();?>"/>
                                    <button title="Download stage brief" type="submit" class="btn btn-success"><i class="fa fa-download"></i></button>
                                </form>
                            </li>

                            <li>
                                <span title="Delete stage brief" id="deleteStagebrief<?=$stages[$s]->id();?>" class="btn btn-danger">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                            </li>

                        </ul>

                        <script>
                            $('#deleteStagebrief<?=$stages[$s]->id();?>').click(function() {
                                $.post('./', {delete: "stage-brief", stage: "<?=$stages[$s]->id();?>"}, function(data) {
                                    if (data == 'success') {
                                        window.location.reload();
                                    } else {
                                        entityError('Unable to Delete Stage Brief', data);
                                    }
                                });
                            });
                        </script>

                    <?php } else { ?>

                    <?php if (!$project->is_complete()) { ?>

                    <form name="createStageBrief" id="createStageBrief-<?=$stages[$s]->id();?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="create" value="stage-brief"/>
                        <input type="hidden" name="stage" value="<?= $stages[$s]->id();?>"/>

                        <p class="push-vertical well well-sm">
                            No Stage Brief
                            <?php if ($current_user->role()->can('stage-task', 'create') || $current_user->is_lead($project->id(), 'project')) { ?>
                            <label class="floated-label pull-right" for="stagebrief-<?=$stages[$s]->id();?>">
                                <span class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </label>
                            <?php } ?>
                        </p>
                        <input class="hidden" type="file" name="stagebrief" id="stagebrief-<?=$stages[$s]->id();?>"/>

                    </form>

                    <script>
                        $('#stagebrief-<?=$stages[$s]->id();?>').change(function() {
                            $('#createStageBrief-<?=$stages[$s]->id();?>').submit();
                        });
                    </script>

                    <?php } else { ?>
                    <p class="push-vertical well well-sm">No Stage Brief</p>
                    <?php } ?>

                    <?php } // End has stage brief IF block ?>






                    <!-- Assignment Workload Statistics-->
                    <?php if ($stages[$s]->has_task_assignments()) { ?>
                    <h4>Workload Statistics</h4>
                    <ul class="list-unstyled">
                        <?php $workload = $stages[$s]->assignment_workload();?>
                        <?php foreach ($workload as $statistic) { ?>
                        <li><span class="label label-primary"><?= $statistic['name'];?></span> <span class="label label-default pull-right"><?= $statistic['stat'];?>%</span></li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                    <?php if ($stages[$s]->is_complete()) { ?>
                    <img src="<?= BASE_URL;?>img/complete.png" class="img img-responsive" alt="Stage complete"/>
                    <?php } else { ?>
                    <?php if (!$project->is_complete()) { ?>
                    <h4>Options</h4>
                    <ul class="list-unstyled stage-options">
                        <?php if ($current_user->role()->can('stage-task', 'create') || $current_user->is_lead($project->id(), 'project')) { ?>
                        <li>
                            <span stage="<?= $stages[$s]->id();?>" class="btn btn-success createStageTaskBtn">
                                <i class="fa fa-plus"></i>
                            </span>
                            New Task
                        </li>
                        <?php } ?>
                        <?php if ($current_user->role()->can('stage', 'create') || $current_user->is_lead($project->id(), 'project')) { ?>
                        <li>
                            <span class="btn btn-warning completeStageBtn" stage="<?= $stages[$s]->id();?>">
                                <i class="fa fa-check-circle"></i>
                            </span>
                            Close Stage
                        </li>
                        <li>
                            <span class="btn btn-warning editStageBtn" stage="<?= $stages[$s]->id();?>">
                                <i class="fa fa-edit"></i>
                            </span>
                            Edit Stage
                        </li>
                        <li>
                            <span class="btn btn-danger deleteStageBtn" stage="<?= $stages[$s]->id();?>">
                                <i class="fa fa-times-circle"></i>
                            </span>
                            Remove Stage
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } // End if project is not complete IF Block ?>
                    <?php } // End if stage is complete IF block for options and graphic ?>
                </div>
            </div>
            <div class="col-md-8">
                <?php if ($stages[$s]->has_tasks()) { ?>
                <?php $tasks = $stages[$s]->get_tasks();?>
                <?php for ($t = 0;$t < count($tasks);$t++) { ?>
                <?php
                // Setup task variables foreach task
                $taskClass = ($tasks[$t]->is_complete() || $stages[$s]->is_complete()) ? 'task-complete' : 'task';
                ?>
                <div id="stagetask-<?= $tasks[$t]->id();?>" class="<?= $taskClass;?>">
                    <h5>
                        <?= ($tasks[$t]->is_complete()) ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-clock-o"></i>';?>
                        <?= $tasks[$t]->name();?>
                        <?php
                        if ($tasks[$t]->is_complete()) {
                            echo '<span class="label label-success pull-right">Complete</span>';
                        } else{
                            echo '<span class="label label-warning pull-right">'.$tasks[$t]->due().'</span>';
                        }
                        ?>
                    </h5>
                    <p><?= $tasks[$t]->description();?></p>

                    <!-- Assigned Users -->
                    <ul class="list-unstyled list-inline">

                        <?php if ($tasks[$t]->get_assignments()) { ?>

                            <?php $assignedusers = $tasks[$t]->get_assignments();?>

                            <li><i class="fa fa-user"></i></li>

                            <?php for ($a = 0; $a < count($assignedusers);$a++) { ?>
                            <li>
                                <a class="btn btn-primary btn-xs" href="<?= BASE_URL;?>users/view/<?=$assignedusers[$a]->id();?>">
                                    <?= $assignedusers[$a]->name();?>
                                </a>
                            </li>
                            <?php } // End FOR loop for assigned users ?>

                            <?php if (!$tasks[$t]->is_complete() && !$stages[$s]->is_complete() && !$project->is_complete()) { ?>
                            <li class="pull-right">
                                <span class="btn btn-success btn-sm assignUserStageTaskBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-link"></i> Assign User
                                </span>
                            </li>

                            <?php } // End if task not complete IF Block for assign button ?>

                        <?php } else { ?>

                            <li><i class="fa fa-user"></i></li>
                            <li>No users assigned</li>
                            <?php if ($project->members() && !$stages[$s]->is_complete() && !$tasks[$t]->is_complete() && !$project->is_complete()) { ?>
                            <?php $individuals = $project->get_team_unique();?>

                            <?php if ($current_user->role()->can('project', 'assign') || $current_user->is_lead($project->id())) { ?>
                            <li class="pull-right">
                                <span class="btn btn-success assignUserStageTaskBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-paperclip"></i> Assign
                                </span>
                            </li>
                            <?php } ?>

                            <?php } // End is assigned user to PROJECT ?>

                        <?php } // End if has assigned user to task IF block?>
                    </ul>

                    <hr/>

                    <div class="task-attachments">
                        <ul>
                            <?php if ($tasks[$t]->has_notes()) { ?>
                            <?php $notes = $tasks[$t]->get_notes(); ?>
                            <?php for ($n = 0; $n < count($notes);$n++) { ?>
                            <li>
                                <img style="cursor: pointer;" class="callNoteBtn" note="<?= $notes[$n]->id();?>" src="<?= BASE_URL;?>img/ico/notepad-32.png" alt="<?= $notes[$n]->name();?>" />
                                <?= $notes[$n]->name();?>
                                <span class="pull-right">
                                    <?= $notes[$n]->user->name();?>
                                    <img class="img img-circle" src="<?= $notes[$n]->user->img();?>" alt="Creator " height="32" width="32" />
                                </span>
                            </li>
                            <?php } // End stage task note FOR block?>
                            <?php } // End stage task note IF block ?>

                            <?php if ($tasks[$t]->has_attachments()) { ?>
                            <?php $attachments = $tasks[$t]->attachments();?>
                            <?php for ($a = 0;$a < count($attachments);$a++) { ?>
                            <li>
                                <form method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="download" value="file"/>
                                    <input type="hidden" name="fileid" value="<?= $attachments[$a]->id();?>"/>
                                    <button title="Download <?= $attachments[$a]->name();?>" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></button>
                                    <img src="<?= BASE_URL;?>/img/ico/compressed-32.png" alt="<?= $attachments[$a]->name();?> Uploaded by <?= $attachments[$a]->creator()->name();?>"/> <?= $attachments[$a]->name();?>

                                    <span class="pull-right">
                                        <?= $attachments[$a]->creator()->name();?>
                                        <img class="img img-circle" src="<?= $attachments[$a]->creator()->img();?>" alt="<?= $attachments[$a]->creator()->name();?>" height="32" width="32" />
                                    </span>
                                </form>
                            </li>
                            <?php } // End stage task attachments FOR block ?>
                            <?php } // End stage task attachments IF Block ?>

                            <!-- Task notes and attachment controls -->
                            <?php if (!$stages[$s]->is_complete() && !$tasks[$t]->is_complete() && !$project->is_complete() && ($current_user->role()->can('project', 'assign') || $current_user->is_lead($project->id()))) { ?>
                            <li>
                                <span class="btn btn-success btn-sm createStageTaskAttachmentBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-paperclip"></i> Attach New
                                </span>
                                <span class="btn btn-success btn-sm createStageTaskAttachmentRelationBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-paperclip"></i> Attach Uploaded
                                </span>
                                <span class="btn btn-success btn-sm createStageTaskNoteBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-plus"></i> New Note
                                </span>
                            </li>
                            <?php } ?>

                            <?php if ($tasks[$t]->is_complete() && !$tasks[$t]->has_attachments() && !$tasks[$t]->has_notes()) { ?>
                            <li>
                                This task had no attachments or notes associated with it
                            </li>
                            <?php } ?>
                            <!-- End Task notes and attachment controls -->
                        </ul>
                    </div>

                    <?php if (!$project->is_complete()) { ?>
                    <hr>

                    <!-- Task Controls -->
                    <div class="task-controls">
                        <ul class="list-unstyled list-inline">
                            <?php if (StageTask::is_assigned($tasks[$t], $current_user->id()) || $project->is_owner($current_user->id())) { ?>
                            <li><span class="btn btn-success completeStageTaskBtn" stagetask="<?= $tasks[$t]->id();?>"><i class="fa fa-check"></i> Mark Complete</span></li>
                            <?php } // End if project lead or assigned ?>
                            <?php if ($current_user->is_lead($project->id(), 'project') || $current_user->role()->can('stage-task', 'create')) { ?>
                            <li>
                                <span class="btn btn-warning editStageTaskBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-edit"></i> Edit
                                </span>
                            </li>
                            <li>
                                <span class="btn btn-danger deleteStageTaskBtn" stagetask="<?= $tasks[$t]->id();?>">
                                    <i class="fa fa-times-circle"></i> Delete
                                </span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <?php } ?>
                </div>
                <?php } // End Staged Tasks FOR block?>

                <?php } else { ?>

                    <?php if (!$project->is_complete() && !$stages[$s]->is_complete()) { ?>
                    <h4>Get started by making some tasks!</h4>
                    <p>This stage currently has no tasks associated with it. Why don't you make one?</p>
                    <p><span class="btn btn-success createStageTaskBtn" stage="<?= $stages[$s]->id();?>"><i class="fa fa-plus"></i> Create Task</span></p>
                    <?php } else { ?>

                    <h4>Project or stage is complete</h4>
                    <p>No further edits can be performed</p>

                    <?php } ?>

                <?php } // End Staged Tasks IF block ?>

            </div>
        </div>

    </div>
    <?php } // End Stage For Loop ?>

    <?php if (!$project->is_complete() && ($current_user->role()->can('stage', 'create') || $current_user->is_lead($project->id(), 'project'))) { ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>Add a new stage to <?= $project->name();?></h3>
            <p><span id="createStageBtn" class="btn btn-primary"><i class="fa fa-plus"></i> Create stage</span></p>
        </div>
    </div>
    <?php } ?>

    <!-- Script to hide and toggle stages -->
    <script>
        function moveStageCarousel(id) {
            $(".normal-box").hide();
            $("[stage='" + id + "']").show();
        }

        $('.stage-btn').click(function() {
            var stage = $(this).attr('stageTarget');
            moveStageCarousel(stage);
        });

        <? if ($project->has_stages()) { ?>
        $(".normal-box").hide();
        $('[stage="0"]').show();
        <?php } ?>
    </script>


    <?php } else { ?>
    <?php if (!$project->is_complete()) { ?>


    <div class="row">
        <div class="col-md-12 text-center">
            <?php if ($current_user->role()->can('stage', 'create')) { ?>
            <h3><?= $project->name();?> has no stages. Would you like to make one?</h3>
            <p><span id="createStageBtn" class="btn btn-primary"><i class="fa fa-plus"></i> Create stage</span></p>
            <?php } else { ?>
            <h3><?= $project->name();?> currently has no stages</h3>
            <?php } ?>
        </div>
    </div>


    <?php } ?>
    <?php } // End Project Stages IF Block ?>

</div>

<script>

    <?php if ($current_user->role()->can('stage', 'create') || $current_user->is_lead($project->id(), 'project')) { ?>
    $('#createStageBtn').click(function() {
        openForm("<?= BASE_URL;?>app/views/forms/new.stage.form.php", {project: "<?= $project->id();?>"});
    });
    <?php } ?>

    <?php if ($current_user->role()->can('stage-task', 'create') || $current_user->is_lead($project->id(), 'project')) { ?>
    $('.createStageTaskBtn').click(function() {
        var stageVal = $(this).attr('stage');
        openForm("<?= BASE_URL;?>app/views/forms/new.stagetask.form.php", {stage: stageVal});
    });
    <?php } ?>

    $('.createStageTaskNoteBtn').click(function() {
        var taskval = $(this).attr('stagetask');
        openForm("<?= BASE_URL;?>app/views/forms/new.stagetask.note.form.php", {stagetask: taskval});
    });
    $('.callNoteBtn').click(function() {
        var noteVal = $(this).attr('note');
        openForm("<?= BASE_URL;?>app/views/displays/task.note.html.php", {note: noteVal});
    });
    $('.assignUserStageTaskBtn').click(function() {
        var stageTaskVal = $(this).attr('stagetask');
        openForm("<?= BASE_URL;?>app/views/forms/assign.user.task.form.php", {scope: "stage-task", taskid: stageTaskVal, project: <?= $project->id();?>});
    });
    $(".editStageBtn").click(function() {
        var affectedStage = $(this).attr("stage");
        openForm('<?=BASE_URL;?>app/views/forms/edit.stage.form.php', {stage: affectedStage});
    });

    $('.createStageTaskAttachmentBtn').click(function() {
        var attachmentStageTask = $(this).attr("stagetask");
        openForm('<?= BASE_URL;?>app/views/forms/new.file.form.php', {modifier: "attachment", stagetask: attachmentStageTask});
    });

    $('.createStageTaskAttachmentRelationBtn').click(function() {
        var attachmentStageTask = $(this).attr("stagetask");
        openForm('<?= BASE_URL;?>app/views/collections/files.personal.list.html.php', {stagetask: attachmentStageTask});
    });

    <?php if ($current_user->role()->can('stage', 'create') || $current_user->is_lead($project->id(), 'project')) { ?>
    $('.completeStageBtn').click(function() {
        $(this).html('<i class="fa fa-cog fa-spin"></i>');
        var affectedStage = $(this).attr("stage");
        var confirmation = confirm('Are you sure you want to close out this stage?');
        if (confirmation) {
            $.post('./', {complete: "stage", stage: affectedStage}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Unable to Close Out Stage', data);
                    $('.completeStageBtn').html('<i class="fa fa-check-circle"></i>');
                }
            });
        } else {
            $('.completeStageBtn').html('<i class="fa fa-check-circle"></i>');
        }
    });
    $('.deleteStageBtn').click(function() {
        $(this).html('<i class="fa fa-cog fa-spin"></i>');
        var affectedStage = $(this).attr("stage");
        var confirmation = confirm('Are you sure you want to delete this stage and all associated tasks?');
        if (confirmation) {
            $.post('./', {delete: "stage", stage: affectedStage}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Unable to Delete Stage', data);
                    $('.deleteStageBtn').html('<i class="fa fa-times-circle"></i>');
                }
            });
        } else {
            $('.deleteStageBtn').html('<i class="fa fa-times-circle"></i>');
        }
    });
    <?php } ?>

    $(".completeStageTaskBtn").click(function() {
        var stageTask = $(this).attr('stagetask');
        $.post('./', {complete: "stage-task", task: stageTask}, function(data) {
            if (data == 'success') {
                var div = $('#stagetask-' + stageTask);
                div.attr('class', 'task-complete');
            } else {
                entityError('Unable to Mark Staged Task Complete', data);
            }
        });
    });
    $('.editStageTaskBtn').click(function() {
        var affectedStageTask = $(this).attr("stagetask");
        openForm('<?=BASE_URL;?>app/views/forms/edit.stagetask.form.php', {stagetask: affectedStageTask});
    });
    $('.deleteStageTaskBtn').click(function() {
        $(this).html('<i class="fa fa-cog fa-spin"></i>');
        var stageTask = $(this).attr('stagetask');
        $.post('./', {delete: "staged-task", task: stageTask}, function(data) {
            if (data == 'success') {
                var div = $('#stagetask-' + stageTask);
                div.attr('class', 'animated zoomOutDown');
            } else {
                entityError('Unable to Delete Staged Task', data);
            }
        });
    });

</script>