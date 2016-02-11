<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['scope'])) {
    echo 'You must define a scope for assignment'; exit;
}
?>

<?php if ($_GET['scope'] == 'stage-task') {
    $project = Project::find('id', $_GET['project']);
    if (!$project) {
        echo 'Failed to locate the project'; exit;
    } else {
        $individuals = $project->get_team_unique();
        $task = StageTask::find('id', $_GET['taskid']);
    }
?>
    <form id="userToStageTask" action="./" method="post">
    <input type="hidden" name="assign" value="user-to-stagetask" />
    <input type="hidden" name="stagetaskid" value="<?= $task->id();?>"/>
        <div class="row">
            <div class="col-md-12">
                <h2>Assign a user to <span class="label label-primary"><?= $task->name();?></span> <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            </div>
        </div>
        <div class="row push-vertical">
            <div class="col-md-12">
                <?php if ($individuals) { ?>
                    <p>Below are the users currently in this project's pool. You can only assign tasks to users assigned to this project. User's currently assigned are already checked. Unchecking an assigned user will remove them from the task.</p>
                    <ul class="list-unstyled">
                        <?php for ($i = 0;$i < count($individuals);$i++) { ?>
                            <li>
                                <?php if ($individuals[$i]->is_assigned('stagetask', $task->id())) { ?>
                                <input type="checkbox" name="attach[]" value="<?= $individuals[$i]->id();?>" checked />
                                <?php } else { ?>
                                <input type="checkbox" name="attach[]" value="<?= $individuals[$i]->id();?>" />
                                <?php } ?>
                                <?= $individuals[$i]->name();?>
                            </li>
                        <?php } ?>
                    </ul>
                    <button class="btn btn-success push-vertical" type="submit"><i class="fa fa-check"></i> Make it</button>
                <?php } else { ?>
                    <h3>You need to assign users to this project before you can make an assignment to a task</h3>
                <?php } ?>
            </div>
        </div>
    </form>

<?php } ?>
<script>
    var needsRefresh = false;
    $('#closeThis').click(function() {
        if (needsRefresh) {
            window.location.reload();
        }
        closeForm();
    });
    $('#userToStageTask').on('submit', function(e) {
        e.preventDefault();
        var userToStageTask = $('#userToStageTask').serialize();
        $.post('./', userToStageTask, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Assignment was successfully created!');
                needsRefresh = true;
            } else {
                entityError('Error Assigning User', data);
            }
        });
    });
</script>