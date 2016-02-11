<?php
require_once '../../boot.php';
if (!isset($_GET['project'])) {
    echo 'Improper Access'; exit;
} else {
    $object = Project::find('id', $_GET['project']);
    if (!$object) {
        echo 'Improper Access'; exit;
    }
    $object_type = 'project';
}
$posts = ProjectForumPost::find('sql', "SELECT * FROM project_forum_posts WHERE projectid = ".$object->id()." ORDER BY created DESC");

?>

<?php require_once __DIR__ . '/_forum.html.php';?>