<?php if ($system_notification->hasFlashMessages() && $current_user->role()->is_staff()) { ?>
<div class="error-flash-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p id="errorFlashMessages">
                    <?= $system_notification->showFlashMessages();?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php } ?>