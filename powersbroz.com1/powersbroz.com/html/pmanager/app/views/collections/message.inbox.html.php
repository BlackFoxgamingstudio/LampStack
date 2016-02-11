<?php require_once '../../boot.php';?>

<?php $messages = Message::find('sql', "SELECT * FROM messages WHERE recipient = ".$current_user->id()." AND trashcan = 0 ORDER BY created DESC");?>
<?php if ($messages) { ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <ul class="message-inbox">
                        <?php for ($m = 0; $m < count($messages);$m++) { ?>
                            <li class="messageItem" message="<?= $messages[$m]->id();?>">
                                <h4><?= $messages[$m]->subject();?></h4>
                                <h5><?= $messages[$m]->from()->name();?></h5>
                                <p class="subdued">
                                    <i class="fa fa-calendar"></i> Received <?= $messages[$m]->senddate()->format('F d, Y H:i');?>
                                </p>
                            </li>
                        <?php } // End message Inbox FOR loop ?>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="paper paper-curve">
                        <div id="inbox-message-body">
                            <p>Select a message to view it's contents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.messageItem').click(function() {
            $('#inbox-message-body').html('<h2><i class="fa fa-cog fa-spin"></i> Loading message content...</h2>');
            var message = $(this).attr('message');
            $.get('<?= BASE_URL;?>app/views/collections/message.html.php', {message: message, scope: "inbox"}, function(data) {
                $('#inbox-message-body').html(data);
            });
        });
    </script>

<?php } else { ?>

    <div class="section min-height-500">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>You have no messages in your inbox</h2>
                </div>
            </div>
        </div>
    </div>

<?php } ?>