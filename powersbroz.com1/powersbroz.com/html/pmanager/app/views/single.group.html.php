<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?= $group->name();?> Group Page</h2>
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
                    <?php if ($current_user->role()->can('group', 'assign') || $current_user->role()->can('group', 'delete')) { ?>
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <?php if ($current_user->role()->can('group', 'assign')) { ?>
                            <li>
                                <span title="Assign a group owner" class="btn btn-success groupAssignmentBtn">
                                    <i class="fa fa-link"></i>
                                </span>
                            </li>
                            <?php } ?>
                            <?php if ($current_user->role()->can('group', 'delete')) { ?>
                            <li>
                                <span title="Delete group" class="btn btn-danger deleteGroupBtn">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="pull-right"><a href="<?= BASE_URL;?>groups/" title="All groups" class="btn btn-primary"><i class="fa fa-refresh"></i> All Groups</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Description <span class="pull-right">Owner: <?= ($group->owner()) ? $group->owner()->name() : 'None';?></span></h3>
                <div class="glass">
                    <div class="row">
                        <div class="col-md-8">
                            <blockquote>
                                <p><?= $group->description();?></p>
                            </blockquote>
                        </div>
                        <div class="col-md-4">
                            <img src="<?= $group->image();?>" alt="Group image" class="img img-responsive" width="256"/>
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
            <div class="col-md-12 text-center">
                <p>Created on <i class="fa fa-calendar"></i> <?= $group->get('created')->format('d F Y');?> &infin; <?= $group->total_days();?> days old</p>
            </div>
        </div>
    </div>
</div>

<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="box">
                    <h3>Members</h3>
                    <p><span class="stat"><?= $group->total_members();?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>Members</h3>

                <?php if ($group->has_members()) { ?>
                    <ul class="list-unstyled list-inline">
                        <?php for ($m = 0; $m < count($group->members()); $m++) { ;?>

                            <li><?= ($group->members()[$m]['user']->is_active()) ? '<span class="label label-primary">' : '<span class="label label-danger">';?><?= $group->members()[$m]['user']->name();?> <i class="fa fa-times-circle"></i></span></li>

                        <?php } // End member for loop ?>
                    </ul>

                <?php } else { ?>

                    <p>This group has no members</p>

                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div id="forum-content" class="col-md-12">

            </div>
        </div>
    </div>
</div>

<script>
    $('#forum-content').html('<div class="ajax-loading"><i class="fa fa-cog fa-spin"></i> Loading group forum posts...</div>');
    $.get('<?= VIEW_COLLECTIONS;?>group.forum.html.php', {group: "<?= $group->id();?>"}, function(data) {
        $('#forum-content').html(data);
    });
    $('.groupAssignmentBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/assign.form.php', {scope: "group"});
    });
    $('.deleteGroupBtn').click(function() {
        var groupID = <?= $group->id();?>;
        var confirmation = confirm('Are you sure you want to delete this group?');
        if (confirmation) {
            $.post('./', {delete: "group", group: groupID}, function(data) {
                if (data == 'success') {
                    window.location = "<?= BASE_URL;?>groups/";
                } else {
                    alert(data);
                }
            });
        }
    });
</script>