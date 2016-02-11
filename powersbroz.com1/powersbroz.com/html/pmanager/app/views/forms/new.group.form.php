<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createGroup" action="./" method="post">
<input type="hidden" name="create" value="group"/>
<div class="row">
    <div class="col-md-12">
        <h2>Create a New Group <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-6">
        <label for="groupname">Name of Group</label>
        <input type="text" name="groupname" id="groupname" placeholder="Group name..."/>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label for="groupdesc">Description</label>
        <textarea name="groupdesc" id="groupdesc" rows="8" placeholder="A short description..."></textarea>
        <button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Make it</button>
    </div>
</div>
</form>
<script>
    var groupsCreated = 0;
    $('#closeThis').click(function() {
        if (groupsCreated > 0) {
            window.location.reload();
        } else {
            closeForm();
        }

    });
    $('#createGroup').on('submit', function(e) {
        e.preventDefault();
        var createGroup = $('#createGroup').serialize();
        $.post('./', createGroup, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Group was successfully created!');
                document.getElementById('createGroup').reset();
                groupsCreated++;
            } else {
                entityError('Error Creating Group', data);
            }
        });
    });
</script>