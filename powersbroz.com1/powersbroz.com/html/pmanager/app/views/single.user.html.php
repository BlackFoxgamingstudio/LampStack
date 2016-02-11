<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Account for <?= $user->name();?></h2>
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
                    <?php if ($user->id() != $current_user->id()) { ?>
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Actions</span>
                        <ul>

                            <?php if ($current_user->role()->can('user', 'edit')) { ?>

                            <?php if ($user->id() != $current_user->id()) { ?>

                                <?php if ($user->is_active()) { ?>
                                <li>
                                    <span title="Deactivate this user's account" id="toggleActiveBtn" class="btn btn-warning">
                                        <i class="fa fa-lock"></i> Deactivate
                                    </span>
                                </li>
                                <?php } else { ?>
                                <li>
                                    <span title="Activate this user's account" id="toggleActiveBtn" class="btn btn-success">
                                        <i class="fa fa-unlock"></i> Activate
                                    </span>
                                </li>
                                <?php } // End if user is active block ?>


                                <?php if ($current_user->role()->can('user', 'delete')) { ?>
                                    <li>
                                        <span title="Delete this user completely" id="deleteUserBtn" class="btn btn-danger" user="<?= $user->id();?>">
                                            <i class="fa fa-times-circle"></i>
                                        </span>
                                    </li>
                                <?php } ?>

                            <?php } // End if user is not current user block ?>

                            <?php } // End if user can edit users block ?>


                        </ul>
                    </li>
                    <?php } // End if not me if block ?>
                    <li class="pull-right"><a href="<?= BASE_URL;?>users/" title="All users" class="btn btn-primary"><i class="fa fa-refresh"></i> All Users</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <img src="<?= $user->img();?>" alt="<?= $user->name();?> Avatar" class="img img-responsive" width="128"/>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">

                <h3>Contact Information</h3>
                <ul class="info-list">
                    <li><i class="fa fa-envelope"></i> <a href="mailto:<?= $user->email();?>" title="Email <?= $user->name();?>"><?= $user->email();?></a></li>
                    <li><i class="fa fa-globe"></i> <?= ($user->get('website') == '') ? 'No website provided' : '<a href="'.$user->get('website').'" title="Visit user website" target="_blank">'.$user->get('website').'</a>';?></li>
                    <li><i class="fa fa-phone"></i> Phone Numbers
                        <ul class="list-unstyled">
                            <li>Home: <?= ($user->get('homephone') == '') ? '<span class="label label-danger pull-right">NA</span>' : format_phone($user->get('homephone'));?></li>
                            <li>Cell: <?= ($user->get('cellphone') == '') ? '<span class="label label-danger pull-right">NA</span>' : format_phone($user->get('cellphone'));?></li>
                            <li>Work: <?= ($user->get('workphone') == '') ? '<span class="label label-danger pull-right">NA</span>' : format_phone($user->get('workphone'));?></li>
                            <li>Fax: <?= ($user->get('fax') == '') ? '<span class="label label-danger pull-right">NA</span>' : format_phone($user->get('fax'));?></li>
                        </ul>
                    </li>
                    <li><i class="fa fa-home"></i> Address

                        <ul class="list-unstyled">
                            <?php if ($user->has_address()) { ?>
                            <li><?= ($user->get('addressone') != '') ? $user->get('addressone') : '';?></li>
                            <li><?= ($user->get('addresstwo') != '') ? $user->get('addresstwo') : '';?></li>
                            <li>
                                <?= ($user->get('city') != '') ? $user->get('city').', ' : '';?>
                                <?= ($user->get('state') != '') ? $user->get('state').' ' : '';?>
                                <?= ($user->get('zip') != '') ? $user->get('zip') : '';?>
                            </li>
                            <li><?= $user->get('country');?></li>
                            <?php } else { ?>
                            <li><i class="fa fa-question-circle"></i> No address info on file</li>
                            <?php } ?>
                        </ul>

                    </li>
                </ul>
                <h3>Quick Statistics</h3>
                <?php $currency = Currency::find('id', $app_settings->get('wage_currency'));?>
                <table class="table table-bordered">
                    <tr>
                        <td><i class="fa fa-briefcase"></i> Projects Total</td>
                        <td>
                            <span class="label label-primary">
                                <?= $user->project_assignment_count();?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-upload"></i> Income</td>
                        <td>
                            <span class="label label-primary">
                                <?= $currency->symbol(). ' ' . number_format(Invoice::user_total_income($user->id()), 2);?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-download"></i> Charges</td>
                        <td>
                            <span class="label label-primary">
                                <?= $currency->symbol(). ' ' . number_format(Invoice::user_total_payments($user->id()), 2);?>
                            </span>
                        </td>
                    </tr>
                </table>

                <p><span class="label label-primary">Profile Completion: <?= $user->profile_completion();?></span> <span class="label label-primary"><i class="fa fa-calendar"></i> Created <?= $user->get('created')->format('F d, Y');?></span></p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Primary Role: <?= $user->role()->classification();?></h2>
                        <h3>Access Role: <?= $user->role()->name();?></h3>
                        <p>Last logged in <?= (ActivityLog::user_last_login($user->id())) ? ActivityLog::user_last_login($user->id()) : 'Never';?></p>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Project Assignments</h3>
                            </div>
                            <div class="panel-body">
                                <?php $assignments = $user->get_assigned_projects();?>
                                <?php if ($assignments) { ?>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th width="35%">Project</th>
                                            <th>Assigned Date</th>
                                            <th>Relationship</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php for ($i = 0; $i < count($assignments);$i++) { ?>
                                            <tr>
                                                <td><a href="<?= BASE_URL;?>projects/view/<?= $assignments[$i]['project']->id();?>" title="<?= $assignments[$i]['project']->name();?>"><?= $assignments[$i]['project']->name();?></a></td>
                                                <td><?= $assignments[$i]['project']->get('created')->format('F d, Y');?></td>
                                                <td><?= $assignments[$i]['relationship'];?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <blockquote>
                                        <p>This user has yet to be assigned to any projects</p>
                                    </blockquote>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Groups</h3>
                            </div>
                            <div class="panel-body">
                                <?php $groups = $user->get_assigned_groups();?>
                                <?php if ($groups) { ?>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th width="50%">Group</th>
                                            <th>Assigned Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php for ($i = 0; $i < count($groups);$i++) { ?>
                                            <?php $group = Group::find('id', $groups[$i]['groupid']);?>
                                            <?php $groupassigndate = new DateTime($groups[$i]['created']);?>
                                            <tr>
                                                <td><a href="<?= BASE_URL;?>groups/view/<?= $group->id();?>" title="<?= $group->name();?>"><?= $group->name();?></a></td>
                                                <td><?= $groupassigndate->format('F d, Y');?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <blockquote>
                                        <p>This user is not a member of any groups</p>
                                    </blockquote>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($current_user->id() != $user->id()) { ?>
                        <div class="blue-box">
                            <form id="changeAccessFrm" action="" method="post">
                                <input type="hidden" name="update" value="access-role"/>
                                <input type="hidden" name="user" value="<?= $user->id();?>" />
                            <h3>Change Access</h3>
                            <div class="push-vertical">
                                <?= Role::html_select('new-role', array(), $user->role()->id());?>
                            </div>
                            <button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $('#deleteUserBtn').click(function() {
        var selectedUser = $(this).attr('user');
        var decisionCheck = confirm('Are you sure you want to delete this user completely from the application?');
        if (decisionCheck) {
            $.post('./', {delete: "user", userid: selectedUser}, function(data) {
                if (data == "success") {
                    window.location.href = "<?= BASE_URL;?>users/";
                } else {
                    entityError('Application Error', data);
                }
            });
        }
    });
    $('#toggleActiveBtn').click(function() {
        var toggleButton = $(this);
        toggleButton.html('<i class="fa fa-cog fa-spin"></i> Working');
        $.post('./', {toggle: "user-account", user: "<?= $user->id();?>"}, function(data) {
            if (data == "1") {
                toggleButton.removeClass('btn-success').addClass('btn-warning').html('<i class="fa fa-lock"></i> Deactivate');
            } else if (data == "0") {
                toggleButton.removeClass('btn-warning').addClass('btn-success').html('<i class="fa fa-unlock"></i> Activate');
            } else {
                entityError('Account Error', data);
            }
        });
    });
    $('#changeAccessFrm').on('submit', function(e) {
        e.preventDefault();
        var formData = $('#changeAccessFrm').serialize();
        $.post('./', formData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Update Access Role', data);
            }
        });
    });
</script>