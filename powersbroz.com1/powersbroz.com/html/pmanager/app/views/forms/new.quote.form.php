<?php
include_once '../../boot.php';
?>
<form id="createForm" action="./" method="post">
    <input type="hidden" name="create" value="form"/>
    <input type="hidden" name="project" value="<?= $_GET['project'];?>"
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Form <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="form-name">Name of Form:</label>
            <input type="text" name="form-name" id="form-name" />
            <label for="form-desc">Description:</label>
            <textarea name="form-desc" id="form-desc" rows="5"></textarea>
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
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        var createForm = $('#createForm').serialize();
        $.post('./', createForm, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Form was successfully created!');
                document.getElementById('createForm').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Form', data);
            }
        });
    });
</script>

