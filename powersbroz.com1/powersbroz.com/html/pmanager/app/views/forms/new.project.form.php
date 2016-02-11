<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createProject" action="./" method="post">
    <input type="hidden" name="create" value="project"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Project <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="projectname">Project Name</label>
            <input type="text" name="projectname" id="projectname"/>
            <label for="projectdesc">Project Description</label>
            <textarea name="projectdesc" id="projectdesc" rows="5"></textarea>
            <div class="row push-vertical">
                <div class="col-md-6">
                    <label for="startdate">Start (MM/DD/YYYY)</label>
                    <input type="date" id="startdate" name="startdate"/>
                </div>
                <div class="col-md-6">
                    <label for="enddate">End (MM/DD/YYYY)</label>
                    <input type="date" id="enddate" name="enddate"/>
                </div>
            </div>
            <div class="well well-lg">
                <input id="assignMe" type="checkbox" name="assignMe" value="1"/>
                <label for="assignMe">Assign me to this project</label>
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
    var projectsCreated = 0;
    $('#closeThis').click(function() {
        if (projectsCreated > 0) {
            window.location.reload();
        } else {
            closeForm();
        }
    });
    $('#createProject').on('submit', function(e) {
        e.preventDefault();
        var createProject = $('#createProject').serialize();
        $.post('./', createProject, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Project was successfully created!');
                document.getElementById('createProject').reset();
                projectsCreated++;
            } else {
                entityError('Error Creating Project', data);
            }
        });
    });
</script>

