<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>My Account</h2>
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
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <li>
                                <a href="<?= BASE_URL;?>account/edit/" title="Edit your account" class="btn btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            </li>
                            <?php if ($current_user->role()->can('invoice', 'create')) { ?>
                            <li>
                                <a href="<?= BASE_URL;?>account/timers/" title="View my Timer Items" class="btn btn-primary">
                                    <i class="fa fa-clock-o"></i> My Man Hours
                                </a>
                            </li>
                            <li>
                                <a href="<?= BASE_URL;?>account/payment/" title="My Payment Methods" class="btn btn-primary">
                                    <i class="fa fa-money"></i> Payment Methods
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>

                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-refresh"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2><?= $current_user->name();?></h2>
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-user"></i> <span class="label label-primary"><?= $current_user->role()->name();?></span></li>
                    <li><i class="fa fa-envelope"></i> <span class="label label-primary"><?= $current_user->email();?></span></li>
                </ul>
                <p>Account holder since <?= $current_user->member_since();?></p>
                <blockquote>
                    <p><?= $current_user->bio();?></p>
                </blockquote>
            </div>
            <div class="col-md-4 text-center">
                <img class="img img-responsive" src="<?= $current_user->img();?>" style="max-width: 256px;" alt="My avatar"/>
                <p class="push-vertical">
                    <form class="hidden-form" id="createUserImage" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="create" value="user-image"/>
                        <input type="hidden" name="user" value="<?= $current_user->id();?>"/>
                        <label class="no-space" for="user-image">
                            <span id="editUserImgBtn" class="btn btn-primary"><i class="fa fa-image"></i> New</span>
                        </label>
                        <input class="hidden" type="file" name="user-image" id="user-image"/>
                    </form>
                    <a href="#" class="btn btn-danger"><i class="fa fa-image"></i> Delete</a>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <p>You have successfully completed <?= $current_user->profile_completion();?> of your profile!</p>
                <?= ($current_user->profile_completion() != '100%') ? '<p><a href="'.BASE_URL.'account/edit" class="btn btn-primary">Complete your Info</a></p>' : ''?>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <h3>Open Invoices</h3>
                    <p><span class="stat"><?= Invoice::my_total_open();?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Current Projects</h3>
                    <p><span class="stat"><?= count(Project::user_project_list($current_user));?></span></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <h3>Groups</h3>
                    <p><span class="stat"><?= count(Group::user_group_list($current_user));?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#user-image').change(function() {
        $('#createUserImage').submit();
    });
</script>