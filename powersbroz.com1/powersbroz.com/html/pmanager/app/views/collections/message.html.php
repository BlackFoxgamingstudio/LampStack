<?php
require_once '../../boot.php';
if (!isset($_GET['message']) && !isset($_GET['scope'])) {
    echo 'Improper Access'; exit;
} else {
    $message    = Message::find('id', $_GET['message']);
    $scope      = $_GET['scope'];
    if ($scope == 'inbox') {
        if (!$message->viewed()) {
            $message->mark_viewed();
        }
    }
}
?>
<h3><?= $message->subject();?></h3>
<p class="subdued">Sent: <?= $message->senddate()->format('F d, Y');?></p>
<hr/>
<p>
    <?= $message->body();?>
</p>
<?php if ($scope == 'sendbox' && $message->viewed()) { ?>
<p class="alert alert-success"><i class="fa fa-check-circle"></i> Message was opened on <?= $message->readdate()->format('F d, Y H:i');?></p>
<?php } elseif ($scope == 'sendbox' && !$message->viewed()) { ?>
<p class="alert alert-info"><i class="fa fa-times-circle"></i> Message has not been opened yet</p>
<?php } else { ?>

<?php } ?>
<hr/>
<?php if ($scope == 'inbox') { ?>

<ul class="list-unstyled list-inline">
    <li><a class="btn btn-success" href="#"><i class="fa fa-refresh"></i> Reply</a></li>
    <li><a id="deleteMessageBtn" class="btn btn-danger" href="#"><i class="fa fa-times-circle"></i> Delete</a></li>
</ul>

<script>
    $('#deleteMessageBtn').click(function() {
        $.post('./', {delete: "message", message: "<?= $message->id();?>"}, function(data) {
            if (data == 'success') {
                // Do remove

            } else {
                entityError('Unable to Trash Message', data);
            }
        });
    });
</script>
<?php } ?>