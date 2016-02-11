<?php $messages = Message::find('sql', "SELECT * FROM messages WHERE recipient = ".$current_user->id()." AND trashcan = 0 ORDER BY created DESC");?>
<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Inbox <span class="label label-primary"><i class="fa fa-inbox"></i> <?= count($messages);?></span> </h2>
            </div>
        </div>
    </div>
</div>
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled list-inline">
                    <li>View</li>
                    <li><span class="btn btn-primary jsMailbox" index="1">Inbox</span></li>
                    <li><span class="btn btn-primary jsMailbox" index="2">Sent Messages</span></li>
                    <li><span class="btn btn-primary jsMailbox" index="3"><i class="fa fa-trash"></i> Trashcan</span></li>
                    <li>Actions</li>
                    <li><span id="newMessageBtn" class="btn btn-success"><i class="fa fa-plus"></i> New</span></li>
                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-refresh"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="inbox" class="mailbox">

</div>

<div id="sendbox" class="mailbox">

</div>

<div id="trashcan" class="mailbox">

</div>

<script>
    var mailboxIndex = 1;
    function mailboxView() {
        if (mailboxIndex == 1) {
            $('.mailbox').hide();
            $('#inbox').show();
        } else if (mailboxIndex == 2) {
            $('.mailbox').hide();
            $('#sendbox').show();
        } else {
            $('.mailbox').hide();
            $('#trashcan').show();
        }
    }
    mailboxView();

    $('.jsMailbox').click(function() {
        mailboxIndex = $(this).attr("index");
        mailboxView();
    })

    $('#newMessageBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.message.form.php');
    });
    $.get('<?= BASE_URL;?>app/views/collections/message.inbox.html.php', function(data) {
        $('#inbox').html(data);
    });
    $.get('<?= BASE_URL;?>app/views/collections/message.sendbox.html.php', function(data) {
        $('#sendbox').html(data);
    });
    $.get('<?= BASE_URL;?>app/views/collections/message.trashcan.html.php', function(data) {
        $('#trashcan').html(data);
    });

</script>

