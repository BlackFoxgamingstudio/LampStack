<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createUser" action="./" method="post">
<input type="hidden" name="create" value="user"/>
<div class="row">
    <div class="col-md-12">
        <h2>Create a New User <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-6">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" placeholder="First name..."/>
    </div>
    <div class="col-md-6">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" placeholder="Last name..."/>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label for="useremail">Email Address</label>
        <input type="email" name="useremail" id="useremail" placeholder="Email address..."/>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-6">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Username..."/>
    </div>
    <div class="col-md-6">
        <label for="repassword">Password</label>
        <input type="password" name="password" id="password" placeholder="Password..."/>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label for="userrole">Access Role</label>
        <?= Role::html_select('userrole');?>
    </div>
</div>
<div class="row push-vertical">
    <div class="col-md-12">
        <p>You can edit the details of this user's account after you have created it.</p>
        <p><button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Make it</button> </p>
    </div>
</div>
</form>
<script>
    var usersCreated = 0;
    $('#closeThis').click(function() {
        if (usersCreated > 0) {
            window.location.reload();
        } else {
            closeForm();
        }
    });
    $('#createUser').on('submit', function(e) {
        e.preventDefault();
        var createUser = $('#createUser').serialize();
        $.post('./', createUser, function(data) {
            if (data == 'success') {
                entityError('Successful', 'User was successfully created!');
                document.getElementById('createUser').reset();
                usersCreated++;
            } else {
                entityError('Error Creating User', data);
            }
        });
    });
</script>

