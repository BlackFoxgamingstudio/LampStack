<?php
require_once '../../boot.php';
if (!isset($_GET['month']) || !isset($_GET['year'])) {
    echo 'Improper Access: '.var_dump($_GET); exit;
} else {
    $month = trim($_GET['month']);
    $year = trim($_GET['year']);
    if (!is_numeric($month) || $month > 12 || $month <= 0 || !is_numeric($year)) {
        echo 'Improper date formatting'; exit;
    }
}
?>
<? $calendar = new Calendar($month, $year);?>
<?= $calendar->render_default();?>