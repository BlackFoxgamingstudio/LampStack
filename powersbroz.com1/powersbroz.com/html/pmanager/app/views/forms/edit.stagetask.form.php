<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['stagetask'])) {
    echo 'No task was passed to this form for processing'; exit;
} else {
    $stageTask = StageTask::find('id', $_GET['stagetask']);
    if (!$stageTask) {
        echo 'Stage task could not be located'; exit;
    }
}
?>
<form id="editStageTask" action="./" method="post">
    <input type="hidden" name="edit" value="stage-task"/>
    <input type="hidden" name="stagetask" value="<?= $stageTask->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Edit <span class="label label-primary"><?= $stageTask->name();?></span><span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p><em>Blank fields will be ignored</em></p>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="stagetaskname">Staged Task Name:</label>
            <input type="text" id="stagetaskname" name="stagetaskname" value="<?= $stageTask->name();?>"/>
            <label for="stagetaskdesc">Description:</label>
            <textarea id="stagetaskdesc" name="stagetaskdesc" rows="5"><?= $stageTask->description();?></textarea>
            <label for="duedate">Due (MM/DD/YYYY):</label>
            <input type="date" id="duedate" name="duedate" />
            <button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Change it!</button>
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
    $('#editStageTask').on('submit', function(e) {
        e.preventDefault();
        var editStageTask = $('#editStageTask').serialize();
        $.post('./', editStageTask, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Stage task was successfully edited!');
                document.getElementById('editStageTask').reset();
                needsRefresh = true;
            } else {
                entityError('Error Editing Stage Task', data);
            }
        });
    });
</script>