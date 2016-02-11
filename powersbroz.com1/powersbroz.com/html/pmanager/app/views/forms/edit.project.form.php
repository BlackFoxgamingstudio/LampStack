<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['project'])) {
    echo 'No project was passed to this form for processing'; exit;
} else {
    $project = Project::find('id', $_GET['project']);
    if (!$project) {
        echo 'Project could not be located'; exit;
    }
}
?>
<form name="editProject" action="./" method="post">
<input type="hidden" name="edit" value="project"/>
<input type="hidden" name="project" value="<?= $project->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Edit <?= $project->name();?><span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p><em>Blank fields will be ignored</em></p>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="projectname">Project Name</label>
            <input class="push-down" type="text" name="projectname" id="projectname" value="<?= $project->name();?>"/>
            <label for="projectdesc">Description</label>
            <textarea name="projectdesc" id="projectdesc" rows="8"><?= $project->description();?></textarea>
            <div class="row push-vertical">
                <div class="col-md-6">
                    <label for="startdate">Start Date: <?= $project->get('startdate')->format('m/d/Y');?></label>
                    <input type="date" name="startdate" id="startdate"/>
                </div>
                <div class="col-md-6">
                    <label for="enddate">End Date: <?= $project->get('enddate')->format('m/d/Y');?></label>
                    <input type="date" name="enddate" id="enddate"/>
                </div>
            </div>
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
    $('#editProject').on('submit', function(e) {
        e.preventDefault();
        var editProject = $('#editProject').serialize();
        $.post('./', editProject, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Project was successfully edited!');
                document.getElementById('editProject').reset();
                needsRefresh = true;
            } else {
                entityError('Error Editing Project', data);
            }
        });
    });
</script>