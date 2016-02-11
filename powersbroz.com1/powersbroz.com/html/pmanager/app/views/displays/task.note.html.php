<?php
include_once '../../boot.php';
if (!isset($_GET['note']) || !$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
$note = StageTaskNote::find('id', $_GET['note']);
if (!$note) {
    echo 'The note passed ot the application could not be found'; exit;
}
?>
<div class="note-modal-container">
    <div class="row">
        <div class="col-md-12">
            <h2><?= $note->name();?> <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p class="subdued">Created by <?= $note->user->name();?> on <?= $note->date_created()->format('F d, Y');?> @ <?= readable_time($note->date_created());?></p>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">

            <p><?= $note->description();?></p>

        </div>
    </div>
    <script>
        $('#closeThis').click(function() {
            closeForm();
        });
    </script>
</div>