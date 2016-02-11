<div class="quick-menu">
    <div class="quick-menu-content">
        <h3>Quick Menu <span id="quickMenuCloseBtn" class="pull-right"><i class="fa fa-times"></i></span></h3>
        <hr/>
        <div class="row">
            <div class="col-md-3">
                <ul class="quick-menu-navigation">
                    <li><span id="quickShowUsers">Users</span></li>
                    <li><span id="quickShowGroups">Groups</span></li>
                    <li><span id="quickShowProjects">Projects</span></li>
                    <li><span id="quickShowInvoices">Invoices</span></li>
                </ul>
                <hr/>
                <ul class="quick-menu-navigation">
                    <li><span id="quickShowHealth">App Health</span></li>
                    <li><span id="quickShowActivity">App Activity</span></li>
                    <li><span id="quickShowSettings">Settings</span></li>
                    <li><span id="quickShowDocs">Documentation</span></li>
                </ul>
            </div>
            <div class="col-md-9">
                <div id="quick-menu-content">
                    <div class="quick-menu-nothing-full">
                        <h3>What are you looking for?</h3>
                        <p>You can get to most anywhere in the application from this quick menu.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr/>
                <p>This menu is only available to staff members. More features to follow!</p>
            </div>
        </div>
    </div>
</div>

<script>
    $('#quickShowUsers').click(function() {
        var spanSelected = $(this);
        spanSelected.html('<i class="fa fa-cog fa-spin"></i> Looking...');
        $.get('<?= BASE_URL;?>app/views/quickmenu/_users.pane.html.php', function(data) {
            $('#quick-menu-content').html(data);
            spanSelected.html('Users');
        });
    });
    $('#quickShowGroups').click(function() {
        var spanSelected = $(this);
        spanSelected.html('<i class="fa fa-cog fa-spin"></i> Looking...');
        $.get('<?= BASE_URL;?>app/views/quickmenu/_groups.pane.html.php', function(data) {
            $('#quick-menu-content').html(data);
            spanSelected.html('Groups');
        });
    });
    $('#quickShowProjects').click(function() {
        var spanSelected = $(this);
        spanSelected.html('<i class="fa fa-cog fa-spin"></i> Looking...');
        $.get('<?= BASE_URL;?>app/views/quickmenu/_projects.pane.html.php', function(data) {
            $('#quick-menu-content').html(data);
            spanSelected.html('Projects');
        });
    });
    $('#quickShowInvoices').click(function() {
        var spanSelected = $(this);
        spanSelected.html('<i class="fa fa-cog fa-spin"></i> Looking...');
        $.get('<?= BASE_URL;?>app/views/quickmenu/_invoices.pane.html.php', function(data) {
            $('#quick-menu-content').html(data);
            spanSelected.html('Invoices');
        });
    });
    $('#quickShowHealth').click(function() {
        window.location = "<?= BASE_URL;?>monitor";
    });
    $('#quickShowActivity').click(function() {
        window.location = "<?= BASE_URL;?>activity";
    });
    $('#quickShowSettings').click(function() {
        window.location = "<?= BASE_URL;?>settings";
    });
    $('#quickShowDocs').click(function() {
        window.location = "<? BASE_URL;?>docs";
    });
</script>