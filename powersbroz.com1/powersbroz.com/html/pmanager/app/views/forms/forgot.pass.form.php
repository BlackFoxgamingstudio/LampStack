<?php
include_once '../../boot.php';
?>
<div class="row">
    <div class="col-md-12">
        <h2>Reset your password <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12 paper paper-curve">
        <form id="forgotPassword" method="post">
            <input type="hidden" name="reset" value="password"/>
            <div class="row push-vertical">
                <div class="col-md-12">
                    <label for="rfname">Please enter your email address:</label>
                    <input type="email" name="femail" id="femail"/>
                    <p class="subdued">Your password will be reset and a new password will be emailed to you.<br/>If you need to retrieve your username, please email support.</p>
                </div>
            </div>

            <div class="row push-vertical">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Send it</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $('#closeThis').click(function() {
        closeForm();
    });
    $('#forgotPassword').on('submit', function(e) {
        e.preventDefault();
        var forgotPassword = $('#forgotPassword').serialize();
        $.post('./', forgotPassword, function(data) {
            if (data == 'success') {
                entityError('Ok', 'Your password has been reset and a new one has been emailed to you');
                document.getElementById('forgotPassword').reset();
            } else {
                entityError('Application Error', data);
            }
        });
    });
</script>