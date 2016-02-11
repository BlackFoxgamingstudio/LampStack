<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createMessage" action="./" method="post">
    <input type="hidden" name="create" value="message"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Send a new Message <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <hr/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php $addresses = User::find('sql', "SELECT * FROM users WHERE NOT id = ".$current_user->id());?>
            <?php if ($addresses) { ?>
            <label for="addressbook">Send to:</label>
            <select id="addressbook" name="addressbook">
                <?php for ($u = 0; $u < count($addresses);$u++) { ?>
                <option value="<?= $addresses[$u]->id();?>"><?= $addresses[$u]->name();?></option>
                <?php } ?>
            </select>
            <label for="messagesubject">Subject of Message:</label>
            <input type="text" id="messagesubject" name="messagesubject"/>
            <label for="messagebody">Message:</label>
            <textarea class="push-vertical" id="messagebody" name="messagebody" rows="8"></textarea>

            <?php } else { ?>
            <h3>Your address book is empty. This could be due to restrictions placed on your account by an administrator. Please contact support if you feel this is an error.</h3>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p><button type="submit" class="btn btn-success push-vertical"><i class="fa fa-envelope"></i> Send</button></p>
        </div>
    </div>
</form>
<script>
    $('#closeThis').click(function() {
        closeForm();
    });
    $('#createMessage').on('submit', function(e) {
        e.preventDefault();
        var createMessage = $('#createMessage').serialize();
        $.post('./', createMessage, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Message was successfully sent!');
                document.getElementById('createMessage').reset();
            } else {
                entityError('Error Sending Message', data);
            }
        });
    });
</script>