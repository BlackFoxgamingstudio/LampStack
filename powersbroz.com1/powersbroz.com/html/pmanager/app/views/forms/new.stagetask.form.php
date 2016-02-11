<?php
include_once '../../boot.php';
if (!isset($_GET['stage']) || !$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
$stage = ProjectStage::find('id', $_GET['stage']);
if (!$stage) {
    echo 'Error locating the stage. Contact Support.'; exit;
}
?>
<form id="createStageTask" action="./" method="post">
    <input type="hidden" name="create" value="stage-task"/>
    <input type="hidden" name="stage" value="<?= $stage->id();?>" />
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Task for <span class="label label-primary"><?= $stage->name();?></span> <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="stagetaskname">Name of Task:</label>
            <input type="text" name="stagetaskname" id="stagetaskname"/>
            <label for="stagetaskdesc">What needs to get done?</label>
            <textarea name="stagetaskdesc" id="stagetaskdesc" rows="5"></textarea>
            <label for="duedate">Due By MM/DD/YYYY (Leave blank if there is no due date):</label>
            <input type="date" name="duedate" id="duedate">
            <h4>Assignment</h4>

            <div class="row push-vertical">
                <div class="col-md-4">
                    <h4>Users</h4>
                </div>
                <div class="col-md-8">
                    <div class="selection-box">
                        <?php $assignedusers = $stage->project()->get_team('users');?>
                        <?php if ($assignedusers) { ?>
                            <ul class="list-unstyled">
                                <?php for ($u = 0; $u < count($assignedusers);$u++) { ?>
                                    <li>
                                        <input type="checkbox" name="user[]" value="<?= $assignedusers[$u]->id();?>">
                                        <?= $assignedusers[$u]->name();?>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } else { ?>
                            <p>No users are assigned to this project</p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="row push-vertical">
                <div class="col-md-4">
                    <h4>Groups</h4>
                </div>
                <div class="col-md-8">
                    <div class="selection-box">
                        <?php $assignedgroups = $stage->project()->get_team('groups');?>

                        <?php if ($assignedgroups) { ?>
                            <ul class="list-unstyled">
                                <?php for ($g = 0; $g < count($assignedgroups);$g++) { ?>
                                <li>
                                    <?= $assignedgroups[$g]->name();?>

                                    <?php if ($assignedgroups[$g]->members()) { ?>
                                        <?php $members = $assignedgroups[$g]->members();?>
                                        <ul class="list-unstyled">
                                            <?php for ($m = 0; $m < count($members);$m++) { ?>
                                            <li>
                                                <input type="checkbox" name="user[]" value="<?= $members[$m]['user']->id();?>">
                                                <?= $members[$m]['user']->name();?>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } else { ?>

                                        There are no users assigned to this group to assign to this task
                                    <?php } ?>

                                </li>
                                <?php } // End groups FOR loop ?>
                            </ul>
                        <?php } else { ?>
                            <p>No groups are assigned to this project</p>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <p><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Make it</button> </p>
        </div>
    </div>
</form>
<script>
    var needsRefresh = false;
    $('#closeThis').click(function() {
        if (needsRefresh) {
            window.location.reload();
        }
        closeForm();
    });
    $('#createStageTask').on('submit', function(e) {
        e.preventDefault();
        var createStageTask = $('#createStageTask').serialize();
        $.post('./', createStageTask, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Stage task was successfully created!');
                document.getElementById('createStageTask').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Stage Task', data);
            }
        });
    });
</script>

