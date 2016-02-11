<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createTax" action="./" method="post" >
    <input type="hidden" name="create" value="tax"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Tax <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="taxname">Name for Tax:</label>
            <input type="text" name="taxname" id="taxname"/>
            <label for="taxdesc">Tax Description:</label>
            <textarea name="taxdesc" id="taxdesc" rows="4"></textarea>
            <label for="taxrate">Tax Rate (must be greater than 0) i.e. 32</label>
            <input type="text" name="taxrate" id="taxrate"/>
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
    $('#createTax').on('submit', function(e) {
        e.preventDefault();
        var createTax = $('#createTax').serialize();
        $.post('./', createTax, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Tax was successfully created!');
                document.getElementById('createTax').reset();
                needsRefresh = true;
            } else {
                entityError('Error Creating Tax', data);
            }
        });
    });
</script>