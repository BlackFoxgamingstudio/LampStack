<?php
require_once '../../boot.php';
$users = User::find('all');

?>

<?php if ($users) { ?>
    <ul class="quick-menu-pane-list">
        <?php foreach ($users as $u) { ?>
        <li>
            <i class="glyphicon glyphicon-user"></i>
            <a href="<?= BASE_URL;?>users/view/<?= $u->id();?>" title="View <?= $u->name();?>">
                <?= $u->name();?>
            </a>
        </li>
        <?php } ?>
    </ul>


<?php } else { ?>
    <div class="quick-menu-nothing-full">
        <h3>Got users?</h3>
        <p>There is nothing to show...let's make some accounts!</p>
    </div>
<?php } ?>