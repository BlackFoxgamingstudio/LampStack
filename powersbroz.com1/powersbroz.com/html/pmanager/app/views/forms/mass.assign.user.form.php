<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['project'])) {
    echo 'No project was passed to this form for processing'; exit;
} else {
    $project = Project::find('id', mysqli_real_escape_string($con->gate, $_GET['project']));
    if (!$project) {
        echo 'The project you requested to assign users to no longer exists'; exit;
    }
    $users = User::find('sql', "SELECT * FROM users ORDER BY lname ASC");

}
?>

<?php if ($users) { ?>
<form id="assignUsers" name="assignUsers" action="" method="post">
    <input type="hidden" name="assign" value="users-to-project"/>
    <input type="hidden" name="project" value="<?= $project->id();?>"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Assign Users to <?= $project->name();?><span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="subdued">Select all the users you want to assign to <?= $project->name();?></p>
            <div class="push-vertical">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Name</th>
                            <th>Primary Role</th>
                            <th>Access Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($users);$i++) { ?>
                        <tr>
                            <td>
                                <?php if ($project->is_user_assigned($users[$i]->id())) { ?>
                                    <input name="assignees[]" type="checkbox" value="<?= $users[$i]->id();?>" checked />
                                <?php } else { ?>
                                    <input name="assignees[]" type="checkbox" value="<?= $users[$i]->id();?>" />
                                <?php } ?>
                            </td>
                            <td><img src="<?= $users[$i]->img();?>" alt="<?= $users[$i]->name();?> Avatar" class="img img-thumbnail" width="32" /></td>
                            <td><?= $users[$i]->name();?></td>
                            <td><?= $users[$i]->role()->classification();?></td>
                            <td><?= $users[$i]->role()->name();?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>




            </div>
            <hr/>
            <button type="submit" class="btn btn-success"><i class="fa fa-link"></i> Assign</button>

        </div>
    </div>
</form>

<?php } else { ?>

    <h2>There are no users to assign to this project!</h2>

<?php } ?>

<script>
    $('#closeThis').click(function() {
        closeForm();
    });
    $('#assignUsers').on('submit', function(e) {
        e.preventDefault();
        var assignUsers = $('#assignUsers').serialize();
        $.post('./', assignUsers, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Error Assigning Users', data);
            }
        });
    });
</script>