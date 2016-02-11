<?php
require_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Improper Access'; exit;
} else {
    if (!isset($_GET['stagetask'])) {
        echo 'Improper access'; exit;
    }
    $stagetaskid = mysqli_real_escape_string($con->gate, $_GET['stagetask']);
    $stagetask = StageTask::find('id', $stagetaskid);
    if (!$stagetask) {
        echo 'Improper access'; exit;
    }
    $sql = "SELECT * FROM files WHERE uploadedby = ".$current_user->id()." ORDER BY created DESC";
    $personalFiles = File::find('sql', $sql);
}
?>

<div class="row">
    <div class="col-md-12">
        <h2>Attach Uploaded File(s) <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        <p class="subdued">You can select multiple files to assign to this staged task</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form id="attachUpload" method="post">
            <input type="hidden" name="create" value="stage-task-attachment"/>
            <input type="hidden" name="stagetask" value="<?= $stagetask->id();?>"/>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Your Files</h3>
                </div>
                <div class="panel-body">

                    <?php if ($personalFiles) { ?>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>File name</th>
                                <th>Preview / Type</th>
                                <th>Select</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($f = 0; $f < count($personalFiles);$f++) { ?>
                                <tr>
                                    <td><?= $personalFiles[$f]->name();?></td>
                                    <td>
                                        <?= $personalFiles[$f]->demo($array = ['width' => '128']);?>
                                        <br/>
                                        <span class="label label-primary"><?= $personalFiles[$f]->type();?></span>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="selectedFiles[]" value="<?= $personalFiles[$f]->id();?>"/>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Attach to task</button>

                    <?php } else { ?>
                        <p class="alert alert-warning">You have no personal files to attach</p>
                    <?php } // End if have person files IF Block ?>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $('#attachUpload').on('submit', function(e) {
        e.preventDefault();
        var attachUpload = $('#attachUpload').serialize();
        $.post('./', attachUpload, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Error Making Attachment', data);
            }
        });
    });
    $('#closeThis').click(function() {
        closeForm();
    });
</script>