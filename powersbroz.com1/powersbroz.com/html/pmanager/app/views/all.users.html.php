<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Users</h2>
            </div>
        </div>
    </div>
</div>
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="toolbar-options">
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Layout</span>
                        <ul>
                            <li>
                                <?php if ($app_preferences->get_layout('users') == 'grid') { ?>
                                <span id="userGridBtn" title="Viewing as grid" class="btn btn-info">
                                <?php } else { ?>
                                <span id="userGridBtn" title="View as Grid" class="btn btn-primary">
                                <?php } ?>
                                    <i class="fa fa-th"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_layout('users') == 'list') { ?>
                                <span id="userListBtn" title="Viewing as list" class="btn btn-info">
                                <?php } else { ?>
                                <span id="userListBtn" title="View as list" class="btn btn-primary">
                                <?php } ?>
                                    <i class="fa fa-list"></i>
                                </span>
                            </li>
                        </ul>
                    </li>

                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Order</span>
                        <ul>
                            <li>
                                <?php if ($app_preferences->get_order('users') == 'alpha') { ?>
                                <span id="userAlphaBtn" title="Viewing by last name alphabetically" class="btn btn-info">
                                <?php } else { ?>
                                <span id="userAlphaBtn" title="View by last name alphabetically" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-sort-by-alphabet"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_order('users') == 'newest') { ?>
                                <span id="userNewestBtn" title="Viewing by most recent" class="btn btn-info">
                                <?php } else { ?>
                                <span id="userNewestBtn" title="View by most recent" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </li>
                        </ul>
                    </li>

                    <?php if ($current_user->role()->can('user', 'create') || $current_user->role()->can('user', 'assign')) { ?>
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <?php if ($current_user->role()->can('user', 'create')) { ?>
                            <li><span id="newUserBtn" title="Create new user" class="btn btn-success"><i class="fa fa-plus"></i></span></li>
                            <?php } ?>
                            <?php if ($current_user->role()->can('user', 'assign')) { ?>
                            <li><span id="assignUserBtn" title="Assign a user" class="btn btn-success"><i class="fa fa-link"></i></span></li>
                            <?php } ?>

                        </ul>
                    </li>
                    <?php } ?>
                    <?php if ($current_user->role()->is_staff()) { ?>
                    <li>Filter</li>
                    <li>
                        <select style="display:inline;" id="filterList">
                            <option value="All">All</option>
                            <option value="Staff">Staff</option>
                            <option value="Contractor">Contractors</option>
                            <option value="Client">Clients</option>
                        </select>
                    </li>
                    <li><span id="filterBtn" title="Apply filter" class="btn btn-primary"><i class="fa fa-filter"></i></span></li>

                    <?php if ($app_preferences->display_locked()) { ?>
                        <li><span id="hideLockedBtn" title="Hide inactivated clients" class="btn btn-primary"><i class="fa fa-eye-slash"></i></span> </li>
                    <?php } else { ?>
                        <li><span id="showLockedBtn" title="Show inactivated clients" class="btn btn-primary"><i class="fa fa-eye"></i></span> </li>
                    <?php } ?>
                    <?php } // End if staff block ?>



                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-home"></i> Dashboard</a></li>
                </ul>
                <script>
                    $('#newUserBtn').click(function() {
                        openForm('<?=BASE_URL;?>app/views/forms/new.user.form.php');
                    });
                    $('#assignUserBtn').click(function() {
                        openForm('<?= BASE_URL;?>app/views/forms/assign.form.php', {scope: "user"})
                    });
                </script>
            </div>
        </div>
    </div>
</div>


<div class="section">
    <div class="container">
        <div class="row">

            <?= (DEBUG) ? $app_preferences->debug_line('users'): null;?>

            <?php if ($app_preferences->get_layout('users') == 'grid') { ?>

                <?php if (!empty($users)) { ?>

                    <?php for ($u = 0;$u < count($users);$u++) { ?>
                    <div class="col-md-4 col-sm-6 text-center userAccount role<?= $users[$u]->role()->classification();?> push-down">
                        <div class="user-box">
                            <div class="user-box-actions">
                                <ul class="list-unstyled list-inline">

                                    <li><a href="view/<?= $users[$u]->id();?>/" class="btn btn-primary"><i class="fa fa-eye"></i> View</a></li>

                                    <?php if ($users[$u]->id() != $current_user->id() && $current_user->role()->can('user', 'edit')) { ?>
                                    <?= $users[$u]->activation_btn_html();?>
                                    <?php } ?>
                                </ul>
                            </div>
                            <img src="<?= $users[$u]->img();?>" width="128" class="img img-responsive img-circle"/>
                            <h3><?= $users[$u]->name();?></h3>
                            <p><?= $users[$u]->role()->classification('format');?></p>
                        </div>
                    </div>
                    <?php } // End user grid FOR loop ?>

                <?php } else { ?>

                    <div class="col-md-12">
                        <div class="nothing-full">
                            <h2>You are currently not able to see others users in this application</h2>
                            <p>Please contact a member of our support staff if you need additional assistance</p>
                        </div>
                    </div>

                <?php } // End no users or no access if block ?>

            <?php } else { ?>

                <?php if (!empty($users)) { ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($u = 0;$u < count($users);$u++) { ?>
                            <tr class="userAccount role<?= $users[$u]->role()->classification();?>">
                                <td><img src="<?= $users[$u]->img();?>" width="64px" alt="Avatar for <?= $users[$u]->name();?>" class="img img-responsive img-thumbnail"/></td>
                                <td><?= $users[$u]->name();?></td>
                                <td><?= $users[$u]->role()->name();?></td>
                                <td><?= $users[$u]->get('created')->format('F d, Y');?></td>
                                <td>
                                    <a href="view/<?= $users[$u]->id();?>/" class="btn btn-primary"><i class="fa fa-eye"></i> View</a>
                                    <?php if ($users[$u]->id() != $current_user->id() && $current_user->role()->can('user', 'edit')) { ?>
                                    <?= $users[$u]->activation_btn_html();?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } // End user LIST FOR loop ?>
                        </tbody>
                    </table>

                <?php } else { ?>

                    <div class="col-md-12">
                        <div class="nothing-full">
                            <h2>You are currently not able to see others users in this application</h2>
                            <p>Please contact a member of our support staff if you need additional assistance</p>
                        </div>
                    </div>

                <?php } ?>

            <?php } ?>
        </div>
    </div>
</div>

<?php if ($current_user->role()->classification() == 'Staff') { ?>
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>User Pool Statistics</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <h3>Staff</h3>
                    <p><span class="stat"><?= User::total_staff();?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Contractors</h3>
                    <p><span class="stat"><?= User::total_contractors();?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Clients</h3>
                    <p><span class="stat"><?= User::total_clients();?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h3>Total</h3>
                    <p><span class="stat"><?= User::total();?></span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3>Active</h3>
                    <p><span class="stat"><?= User::total_active();?></span></p>
                </div>
            </div>
        </div>

    </div>
</div>
<?php } else { ?>

<div class="highlight">
    <div class="container">
        <div class="col-md-4 text-center">
            <img class="img img-thumbnail" src="<?= $current_user->img();?>" alt="My Avatar" width="128"/>
        </div>
        <div class="col-md-8">
            <h3>Welcome <?= $current_user->name();?></h3>
            <p>This page lists all users which you are currently assigned with or have access to. Please contact one of our staff if you need additional access.</p>
        </div>
    </div>
</div>

<?php } ?>
<script>
    $('#filterBtn').click(function() {
        var classification = $('#filterList').val();
        var numItems = $('.role'+ classification).length;
        if (classification == 'All') {
            $('.userAccount').show();
        } else {
            $('.userAccount').hide();
            if (numItems > 0) {
                $('.role'+ classification).show();
            } else {
                $('.userAccount').show();
                alert('There are no users matching that criteria');
            }

        }
    });
    $('#userGridBtn').click(function() {
        document.cookie="Entity-AUL=grid";
        window.location.reload();
    });
    $('#userListBtn').click(function() {
        document.cookie="Entity-AUL=list";
        window.location.reload();
    });
    $('#userAlphaBtn').click(function() {
        document.cookie="Entity-AUO=alpha";
        window.location.reload();
    });
    $('#userNewestBtn').click(function() {
        document.cookie="Entity-AUO=newest";
        window.location.reload();
    });
    $('#hideLockedBtn').click(function() {
        document.cookie="Entity-AUHLA=false";
        window.location.reload();
    });
    $('#showLockedBtn').click(function() {
        document.cookie="Entity-AUHLA=true";
        window.location.reload();
    });
    $('.toggleActivationUserBtn').click(function(e) {
        var userButton = $(this);
        $(this).html('<i class="fa fa-cog fa-spin"></i> Working');
        var userId = $(this).attr('user');
        $.post('./', {toggle: "user-account", user: userId}, function(data) {
            if (data == "1") {
                userButton.removeClass('btn-success').addClass('btn-warning').html('<i class="fa fa-lock"></i> Deactivate');
            } else if (data == "0") {
                userButton.removeClass('btn-warning').addClass('btn-success').html('<i class="fa fa-unlock"></i> Activate');
            } else {
                entityError('Account Error', data);
            }
        });
    });
</script>