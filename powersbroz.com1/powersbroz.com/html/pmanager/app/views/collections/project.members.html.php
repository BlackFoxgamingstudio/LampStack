<?php
require_once '../../boot.php';
if (!isset($_GET['project'])) {
    echo 'No direct access'; exit;
} else {
    $project = Project::find('id', $_GET['project']);
    $members = $project->members();
}
?>

<h3>Team Members</h3>

<?php if ($members) { ?>
    <ul class="list-unstyled list-inline">
        <?php for ($m = 0; $m < count($members);$m++) { ?>
            <?php if (get_class($members[$m]) == 'Group') { ?>
                <li><span class="label label-primary"><i class="fa fa-users"></i> <?= $members[$m]->name();?></span></li>
            <?php } else { ?>
                <?php if ($members[$m]->is_active()) { ?>
                    <li><span class="label label-primary"><?= $members[$m]->name();?></span></li>
                <?php } else { ?>
                    <li><span class="label label-danger"><?= $members[$m]->name();?></span></li>
                <?php } ?>
            <?php } ?>

        <?php } // End members for loop ?>
    </ul>
<?php } else { ?>
    <p>This project has no users assigned to it yet</p>
<?php } ?>
