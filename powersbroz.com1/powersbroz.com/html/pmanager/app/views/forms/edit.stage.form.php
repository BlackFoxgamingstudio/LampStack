<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['stage'])) {
    echo 'No stage was passed to this form for processing'; exit;
} else {
    $stage = ProjectStage::find('id', $_GET['stage']);
    if (!$stage) {
        echo 'Stage could not be located'; exit;
    }
}
?>
<form id="editStage" action="./" method="post">
    <input type="hidden" name="edit" value="stage"/>
    <input type="hidden" name="stage" value="<?= $stage->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Edit <span class="label label-primary"><?= $stage->name();?></span><span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p><em>Blank fields will be ignored</em></p>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="stagename">Stage Name:</label>
            <input type="text" id="stagename" name="stagename" value="<?= $stage->name();?>"/>
            <label for="stagedesc">Description:</label>
            <textarea id="stagedesc" name="stagedesc" rows="5"><?= $stage->description();?></textarea>
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
    $('#editStage').on('submit', function(e) {
        e.preventDefault();
        var editStage = $('#editStage').serialize();
        $.post('./', editStage, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Stage was successfully edited!');
                document.getElementById('editStage').reset();
                needsRefresh = true;
            } else {
                entityError('Error Editing Stage', data);
            }
        });
    });
</script>