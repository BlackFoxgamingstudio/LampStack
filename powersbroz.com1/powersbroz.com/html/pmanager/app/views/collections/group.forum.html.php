<?php
require_once '../../boot.php';
if (!isset($_GET['group'])) {
    echo 'Improper Access'; exit;
} else {
    $object = Group::find('id', $_GET['group']);
    if (!$object) {
        echo 'Improper Access'; exit;
    }
    $object_type = 'group';
}
$posts = GroupForumPost::find('sql', "SELECT * FROM group_forum_posts WHERE groupid = ".$object->id()." ORDER BY created DESC");
?>

<?php require_once __DIR__ . '/_forum.html.php';?>