<?php
include_once '../../boot.php';
if (!isset($_GET['project']) || !$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
$project = Project::find('id', $_GET['project']);
if (!$project) {
    echo 'Error locating the project. Contact Support.'; exit;
}
?>
<form id="createStage" action="./" method="post">
    <input type="hidden" name="create" value="stage"/>
    <input type="hidden" name="project" value="<?= $_GET['project'];?>"
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Stage <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="stagename">Stage Name:</label>
            <input type="text" name="stagename" id="stagename"/>
            <label for="stagedesc">Quick Overview:</label>
            <textarea name="stagedesc" id="stagedesc" rows="5"></textarea>
            <label for="duedate">Due Date (Leave if there is no due date):</label>
            <input type="date" name="duedate" id="duedate"/>
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
    $('#createStage').on('submit', function(e) {
        e.preventDefault();
        var createStage = $('#createStage').serialize();
        $.post('./', createStage, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Stage was successfully created!');
                document.getElementById('createStage').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Stage', data);
            }
        });
    });
</script>

