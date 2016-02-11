<section class="navigation">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <h1><a href="<?= BASE_URL;?>" title="Homepage"><i class="fa fa-home"></i></a></h1>
            </div>
            <div class="col-md-6">
                <ul class="navigation-taskbar">
                    <li><a href="<?= AccountController::url();?>" title="My account"><i class="glyphicon glyphicon-user"></i></a></li>

                    <li><a href="<?= MessagesController::url();?>" title="Check your messages"><i class="glyphicon glyphicon-envelope"></i></a></li>
                    <?php if ($current_user->role()->can('file', 'create')) { ?>
                    <li><a href="<?= UploadsController::url();?>" title="My files"><i class="glyphicon glyphicon-file"></i></a></li>
                    <?php } ?>
                    <!--
                    <li><a href="<?= BASE_URL;?>calendar/" title="Look at the calendar"><i class="fa fa-calendar"></i></a></li>
                    -->
                    <?php if ($current_user->role()->can_access('settings', 'system')) { ?>
                    <li><a href="<?= SettingsController::url();?>" title="View and Edit Entity's Settings"><i class="fa fa-sliders"></i></a></li>
                    <?php } ?>
                    <?php if ($current_user->role()->can_access('docs', 'system')) { ?>
                    <li><a href="<?= DocsController::url();?>" title="Read the documentation"><i class="fa fa-support"></i></a></li>
                    <?php } ?>
                    <?php if ($current_user->role()->is_staff()) { ?>
                    <li><a href="<?= HealthMonitorController::url();?>" title="Application health monitor"><i class="fa fa-heartbeat"></i></a> </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="navigation-taskbar pull-right">
                    <li><a title="Logout of Entity" id="logout-btn" href="#"><i class="fa fa-power-off"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<script>
$('#logout-btn').click(function() {
    $(this).html('<i class="fa fa-cog fa-spin"></i>');
    $.post('./', {access: "logout"}, function(data) {
        if (data == "success") {
            window.location = "<?= BASE_URL;?>";
        }
    });
});
</script>
