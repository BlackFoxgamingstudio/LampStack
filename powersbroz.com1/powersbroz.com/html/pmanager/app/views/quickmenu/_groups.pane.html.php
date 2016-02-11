<?php
require_once '../../boot.php';
$groups = Group::find('all');

?>

<?php if ($groups) { ?>
    <ul class="quick-menu-pane-list">
        <?php foreach ($groups as $g) { ?>
            <li>
                <i class="fa fa-users"></i>
                <a href="<?= BASE_URL;?>groups/view/<?= $g->id();?>" title="View <?= $g->name();?>">
                    <?= $g->name();?>
                </a>
            </li>
        <?php } ?>
    </ul>


<?php } else { ?>
    <div class="quick-menu-nothing-full">
        <h3>Got groups?</h3>
        <p>There is nothing to show...let's make some!</p>
    </div>
<?php } ?>