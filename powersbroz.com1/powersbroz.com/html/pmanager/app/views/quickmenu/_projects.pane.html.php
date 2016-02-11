<?php
require_once '../../boot.php';
$projects = Project::find('all');

?>

<?php if ($projects) { ?>
    <ul class="quick-menu-pane-list">
        <?php foreach ($projects as $p) { ?>
            <li>
                <i class="fa fa-users"></i>
                <a href="<?= BASE_URL;?>projects/view/<?= $p->id();?>" title="View <?= $p->name();?>">
                    <?= $p->name();?>
                </a>
            </li>
        <?php } ?>
    </ul>


<?php } else { ?>
    <div class="quick-menu-nothing-full">
        <h3>No projects?!</h3>
        <p>That's what this application is for!</p>
    </div>
<?php } ?>