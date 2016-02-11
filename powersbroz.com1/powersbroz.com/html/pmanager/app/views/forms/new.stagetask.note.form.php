<?php
include_once '../../boot.php';
if (!isset($_GET['stagetask']) || !$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
$task = StageTask::find('id', $_GET['stagetask']);
if (!$task) {
    echo 'Error locating the staged task. Contact Support.'; exit;
}
?>
<form id="createStageTaskNote" action="./" method="post">
<div class="row">
    <div class="col-md-12">
        <h2>Create a New Note for <span class="label label-primary"><?= $task->name();?></span><span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">
        <input type="hidden" name="create" value="stage-task-note"/>
        <input type="hidden" name="stagetask" value="<?= $task->id();?>" />
        <label for="notesubject">Subject of Note / Title:</label>
        <input type="text" id="notesubject" name="notesubject"/>
        <label for="notebody">Body of Note:</label>
        <textarea id="notebody" name="notebody" rows="8"></textarea>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">
        <p><button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Make it</button></p>
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
    $('#createStageTaskNote').on('submit', function(e) {
        e.preventDefault();
        var createStageTaskNote = $('#createStageTaskNote').serialize();
        $.post('./', createStageTaskNote, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Note was successfully created!');
                document.getElementById('createStageTaskNote').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Note', data);
            }
        });
    });
</script>