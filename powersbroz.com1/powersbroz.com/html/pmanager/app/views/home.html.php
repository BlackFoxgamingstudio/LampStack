<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Users, Groups, &amp; Access</h1>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Users</h2>
                            <p>
                                <?php if ($current_user->role()->can('user', 'view')) { ?>
                                <a href="<?= UsersController::url();?>" title="All users" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php } else { ?>
                                <?= restricted_button();?>
                                <?php } ?>
                                <?php if ($current_user->role()->can('user', 'create')) { ?>
                                <a id="newUserBtn" href="#" title="New user" class="btn btn-success"><i class="fa fa-plus"></i> New</a>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Groups</h2>
                            <p>
                                <a href="<?= GroupsController::url();?>" title="All projects" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php if ($current_user->role()->can('group', 'create')) { ?>
                                <a id="newGroupBtn" href="#" title="New group" class="btn btn-success"><i class="fa fa-plus"></i> New</a>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Access Roles</h2>
                            <p>
                                <?php if ($current_user->role()->can_access('roles', 'system')) { ?>
                                <a href="<?= AccessController::url();?>" title="All user roles" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php } else { ?>
                                <?= restricted_button(); ?>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Projects & Finance</h1>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Projects</h2>
                            <p>
                                <a href="<?= ProjectsController::url();?>" title="All users" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php if ($current_user->role()->can('project', 'create')) { ?>
                                <a id="newProjectBtn" href="#" title="New project" class="btn btn-success"><i class="fa fa-plus"></i> New</a>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Invoices</h2>
                            <p>
                                <a href="<?= InvoicesController::url();?>" title="All users" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php if ($current_user->role()->can('invoice', 'create')) { ?>
                                <a id="newInvoiceBtn" href="#" title="New invoice" class="btn btn-success"><i class="fa fa-plus"></i> New</a>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Rates &amp; Taxes</h2>
                            <p>
                                <?php if ($current_user->role()->can_access('wages', 'system')) { ?>
                                <a href="<?= RatesController::url();?>" title="All wages" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php } else { ?>
                                <?= restricted_button();?>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($current_user->role()->classification() == 'Staff') { ?>

<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h1>The Application</h1>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Settings</h2>
                            <p>
                                <a href="<?= SettingsController::url();?>" title="View application settings" class="btn btn-success"><i class="fa fa-check"></i> View</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Activity</h2>
                            <p>
                                <?php if ($current_user->role()->can_access('activity', 'system')) { ?>
                                <a href="<?= ActivityController::url();?>" title="View application activity log" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <?php } else { ?>
                                <?= restricted_button();?>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box">
                            <h2>Forms</h2>
                            <p>
                                <a href="<?= FormsController::url();?>" title="All custom forms" class="btn btn-primary"><i class="fa fa-list"></i> All</a>
                                <!--
                                <a href="#" title="New custom form" class="btn btn-success"><i class="fa fa-plus"></i> New</a>
                                -->
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Support Links and Operations -->
<div class="container">
    <div class="row push-vertical">
        <div class="col-md-4 text-center">
            <div class="btn-documentation">
                <p><i class="fa fa-support"></i></p>
                <ul>
                    <li><a href="<?= DocsController::url();?>" class="btn btn-primary">Read the Documentation</a></li>
                    <li><a href="http://codecanyon.net/user/zenperfect/portfolio" target="_blank" class="btn btn-primary">CodeCanyon Product Support</a></li>
                    <li><a href="http://www.zenperfectdesign.com/products/entity/" target="_blank" class="btn btn-primary">Entity Product Page</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-8">

            <h2>Entity {CC} <span class="subdued">version</span> <?= $app->version;?></h2>
            <p>Build date: <?= $app->vdate;?></p>
            <hr/>
            <p>Entity {CC} is exclusively licensed, released, and supported on the CodeCanyon Marketplace. This application was designed and developed by Travis Coats, Zen Perfect Design. You can view and feed into the product's development by visiting the <a href="https://trello.com/b/BlIwId84" target="_blank" title="Entity {CC} 2.0.x Trello board">Trello development board</a>.</p>
        </div>
    </div>
</div>
<?php } else { ?>

    <?php require_once INCLUDES . 'user.support.section.html.php';?>

<?php } ?>

<script>
    <?php if ($current_user->role()->can('user', 'create')) { ?>
    $('#newUserBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.user.form.php');
    });
    <?php } ?>

    <?php if ($current_user->role()->can('group', 'create')) { ?>
    $('#newGroupBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.group.form.php');
    });
    <?php } ?>

    <?php if ($current_user->role()->can('project', 'create')) { ?>
    $('#newProjectBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.project.form.php');
    });
    <?php } ?>

    <?php if ($current_user->role()->can('invoice', 'create')) { ?>
    $('#newInvoiceBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.invoice.form.php');
    });
    <?php } ?>

    $('#newFileBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.file.form.php');
    });
</script>