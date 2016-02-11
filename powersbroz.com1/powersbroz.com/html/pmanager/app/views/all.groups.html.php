<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Groups</h2>
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
                                <?php if ($app_preferences->get_layout('groups') == 'grid') { ?>
                                <span id="groupGridBtn" title="Viewing as grid" class="btn btn-info">
                                <?php } else { ?>
                                    <span id="groupGridBtn" title="View as grid" class="btn btn-primary">
                                <?php } ?>
                                    <i class="fa fa-th"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_layout('groups') == 'list') { ?>
                                <span id="groupListBtn" title="Viewing as a list" class="btn btn-info">
                                <?php } else { ?>
                                <span id="groupListBtn" title="View as a list" class="btn btn-primary">
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
                                <?php if ($app_preferences->get_order('groups') == 'alpha') { ?>
                                <span id="groupAlphaBtn" title="Viewing by group name alphabetically" class="btn btn-info">
                                <?php } else { ?>
                                <span id="groupAlphaBtn" title="View by group name alphabetically" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-sort-by-alphabet"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_order('groups') == 'newest') { ?>
                                <span id="groupNewestBtn" title="Viewing by most recent" class="btn btn-info">
                                <?php } else { ?>
                                <span id="groupNewestBtn" title="View by most recent" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_order('groups') == 'size') { ?>
                                <span id="groupSizeBtn" title="Viewing by amount of members" class="btn btn-info">
                                <?php } else { ?>
                                <span id="groupSizeBtn" title="View by amount of members" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                                </span>
                            </li>
                        </ul>
                    </li>
                    <?php if (
                        $current_user->role()->can('group', 'create') ||
                        $current_user->role()->can('group', 'assign') ||
                        $current_user->role()->can('user', 'assign')
                    ) { ?>
                    <li class="toolbar-options-wrap">

                        <span class="subdued-caps">Actions</span>
                        <ul>
                        <?php if ($current_user->role()->can('group', 'create')) { ?>
                            <li>
                                <span title="Create a new group" class="btn btn-success newGroupBtn">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </li>
                        <?php } ?>
                        <?php if ($groups && $current_user->role()->can('group', 'assign') && $current_user->role()->can('user', 'assign')) { ?>
                            <li>
                                <span title="Assign a user to a group" class="btn btn-success assignGroupBtn">
                                    <i class="fa fa-link"></i>
                                </span>
                            </li>
                        <?php } ?>
                        </ul>
                    <?php } // End check permission if block ?>
                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-home"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <?= (DEBUG) ? $app_preferences->debug_line('groups'): null;?>

        <?php if ($groups) { ?>


            <?php if ($app_preferences->get_layout('groups') == 'grid') { ?>



                <!-- Grid layout -->

                <?php for ($g = 0; $g < count($groups);$g++) { ?>

                    <div class="row push-down">
                        <div class="col-md-4 text-center">
                            <img class="img img-responsive push-down" src="<?= $groups[$g]->image();?>" alt="Group image"/>
                            <a href="<?= BASE_URL;?>groups/view/<?= $groups[$g]->id();?>" class="btn btn-primary"><i class="fa fa-check"></i> View</a>&nbsp;
                            <?php if ($current_user->role()->can('group', 'delete')) { ?>
                            <a href="#" class="btn btn-danger deleteGroupBtn" data-group="<?= $groups[$g]->id();?>"><i class="fa fa-times"></i> Delete</a>
                            <?php } ?>
                        </div>
                        <div class="col-md-8">
                            <h3>
                                <?= $groups[$g]->name();?> <?= ($groups[$g]->owner()) ? '<span class="label label-primary pull-right">Owner: '.$groups[$g]->owner()->name().'</span>' : ''?>
                            </h3>
                            <p><?= $groups[$g]->description();?></p>

                            <!-- Group Members in Grid Format -->
                            <div class="well well-sm">

                                <h4>Members</h4>
                                <?php if ($groups[$g]->has_members()) { ?>
                                    <ul class="list-unstyled list-inline">
                                        <?php for ($m = 0; $m < count($groups[$g]->members()); $m++) { ;?>

                                        <li>
                                            <a href="<?= BASE_URL;?>users/view/<?= $groups[$g]->members()[$m]['user']->id();?>" title="View <?= $groups[$g]->members()[$m]['user']->name();?>"><?= ($groups[$g]->members()[$m]['user']->is_active()) ? '<span class="label label-primary">' : '<span class="label label-danger">';?><?= $groups[$g]->members()[$m]['user']->name();?></span></a>
                                        </li>

                                        <?php } // End member for loop ?>
                                    </ul>

                                <?php } else { ?>

                                <p>This group has no members</p>

                                <?php } ?>
                            </div>
                            <!-- End Group Members in Grid Format -->
                            <p class="push-vertical"><abbr title="Created on"><i class="fa fa-calendar"></i></abbr> <?= $groups[$g]->get('created')->format('d F Y');?></p>
                        </div>
                    </div>

                    <?= (($g + 1) < count($groups)) ? '<hr/>' : '';?>

                <?php } // End grid layout for loop ?>



            <?php } else { ?>



                <!-- List layout -->

                <table class="table">

                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Created</th>
                            <th>Members</th>
                            <th>Options</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php for ($g = 0; $g < count($groups);$g++) { ?>
                        <tr>
                            <td>
                                <img
                                    src="<?= $groups[$g]->image();?>"
                                    class="img img-thumbnail"
                                    width="64"
                                    alt="<?= $groups[$g]->name() .' group image';?>"
                                />
                            </td>
                            <td><?= $groups[$g]->name();?></td>
                            <td><?= $groups[$g]->get('created')->format('F d, Y');?></td>
                            <td><?= $groups[$g]->total_members();?></td>
                            <td>
                                <a href="<?= BASE_URL;?>groups/view/<?= $groups[$g]->id();?>" class="btn btn-primary"><i class="fa fa-eye"></i> View</a>
                                <a href="#" class="btn btn-danger deleteGroupBtn" data-group="<?= $groups[$g]->id();?>"><i class="fa fa-times-circle"></i> Delete</a>
                            </td>
                        </tr>
                        <?php } // End list layout for loop ?>

                    </tbody>

                </table>


            <?php } // End App preferences IF block ?>



        <?php } else { // End if group statement ?>



            <?php if ($current_user->role()->can('group', 'create')) { ?>

            <div class="nothing-full">
                <h2>Uh oh! You don't have any groups yet!<br/>Let's make one!</h2>
                <p><span class="btn btn-success newGroupBtn"><i class="fa fa-plus"></i> New</span></p>
            </div>

            <?php } else { ?>

            <div class="nothing-full">
                <h2>You have not been assigned to any groups. Please contact support if you need further assistance.</h2>
            </div>

            <?php } ?>



        <?php } ?>
    </div>
</div>

<?php if ($current_user->role()->classification() == 'Staff') { ?>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h3>Total</h3>
                    <p><span class="stat"><?= Group::total();?></span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3>User Population in Groups</h3>
                    <p><span class="stat"><?= User::percentage_grouped();?>%</span></p>
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
            <p>This page lists all groups which you are currently assigned with or have access to. Please contact one of our staff if you need additional access.</p>
        </div>
    </div>
</div>

<?php } ?>

<script>
    $('.newGroupBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.group.form.php');
    });
    $('.assignGroupBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/assign.form.php', {scope: "group"});
    });
    $('.deleteGroupBtn').click(function() {
        var groupID = $(this).attr('data-group');
        var confirmation = confirm('Are you sure you want to delete this group?');
        if (confirmation) {
            $.post('./', {delete: "group", group: groupID}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    alert(data);
                }
            });
        }
    });
    $('#groupGridBtn').click(function() {
        document.cookie="Entity-AGL=grid";
        window.location.reload();
    });
    $('#groupListBtn').click(function() {
        document.cookie="Entity-AGL=list";
        window.location.reload();
    });
    $('#groupAlphaBtn').click(function() {
        document.cookie="Entity-AGO=alpha";
        window.location.reload();
    });
    $('#groupNewestBtn').click(function() {
        document.cookie="Entity-AGO=newest";
        window.location.reload();
    });
    $('#groupSizeBtn').click(function() {
        document.cookie="Entity-AGO=size";
        window.location.reload();
    });
</script>