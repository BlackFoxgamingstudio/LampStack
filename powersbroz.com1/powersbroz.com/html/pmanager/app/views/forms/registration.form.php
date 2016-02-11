<?php
include_once '../../boot.php';
?>
<div class="row">
    <div class="col-md-12">
        <h2>Register for an account <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12 paper paper-curve">
        <form id="userRegistration" method="post">
            <input type="hidden" name="create" value="registration"/>
            <div class="row push-vertical">
                <div class="col-md-6">
                    <label for="rfname">First Name:</label>
                    <input type="text" name="rfname" id="rfname"/>
                </div>
                <div class="col-md-6">
                    <label for="rlname">Last Name:</label>
                    <input type="text" name="rlname" id="rlname"/>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="remail">Email Address:</label>
                    <input type="email" name="remail" id="remail"/>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="runame">Username:</label>
                    <input type="text" name="runame" id="runame"/>
                </div>
                <div class="col-md-6">
                    <label for="rpass">Password:</label>
                    <input type="password" name="rpass" id="rpass"/>
                </div>
            </div>

            <div class="row push-vertical">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Register</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $('#closeThis').click(function() {
        closeForm();
    });
    $('#userRegistration').on('submit', function(e) {
        e.preventDefault();
        var userRegistration = $('#userRegistration').serialize();
        $.post('./', userRegistration, function(data) {
            if (data == 'success') {
                entityError('Thanks', 'You have successfully registered for an account and can now login');
                document.getElementById('userRegistration').reset();
            } else {
                entityError('Registration Error', data);
            }
        });
    });
</script>