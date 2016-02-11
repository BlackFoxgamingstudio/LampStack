<?php
if (isset($_POST['download'])) {

    switch($_POST['download']) {

        case 'file':
            if (trim($_POST['fileid']) == '') {
                ActivityLog::log_action($current_user, 'system-error', 'No file id was provided to the application for download');
                $system_notification->queue('Download Error', 'No file id was provided to the application for download'); break;
            }
            $fileid = mysqli_real_escape_string($con->gate, $_POST['fileid']);
            $file   = File::find('id', $fileid);
            if (!$file) {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    $current_user->name() .' tried to download a copy of '.$file->name().' from location: '.
                    $file->real_name().' but the reference in the database was removed'
                );
                $system_notification->queue('Download Error', 'Unable to locate file');
            }

            $path = $file->real_name();

            if (file_exists($path)) {
                ActivityLog::log_action($current_user, 'download', $current_user->name() .' downloaded a copy of '.$file->name().'');
                header("Content-Description: File Transfer");
                header("Content-Type: application/octet-stream");
                header('Content-Disposition: attachment; filename="'.basename($path).'"');
                header("Expires: 0");
                header("Cache-Control: must-revalidate");
                header("Pragma: public");
                header("Content-Length: " . filesize($path));
                readfile($path);
                break;
            } else {
                ActivityLog::log_action($current_user, 'system-error', $current_user->name() .' tried to download a copy of '.$file->name().' from location: '.$file->real_name());
                $system_notification->queue('Download Error', 'File doesn\'t exist on the server anymore');
                break;
            }

            break;

        case 'document':

            break;

        case 'stage-brief':
            if (trim($_POST['stage']) == '') {
                ActivityLog::log_action($current_user, 'system-error', 'No stage was passed to the application to retrieve the stage brief');
                $system_notification->queue('Download Error', 'No stage was passed to the application to retrieve the stage brief'); break;
            }
            $stage = ProjectStage::find('id', $_POST['stage']);
            if (!$stage) {
                ActivityLog::log_action($current_user, 'system-error', $current_user->name() . ' tried obtaining a copy of a stage-brief for a stage that no longer exists');
                $system_notification->queue('Download Error', 'The stage you are trying to access could not be found. It may have been deleted already.'); break;
            }

            $path = FILES_PATH.$stage->get_brief();

            if (file_exists($path)) {
                header("Content-Description: File Transfer");
                header("Content-Type: application/octet-stream");
                header('Content-Disposition: attachment; filename="'.basename($path).'"');
                header("Expires: 0");
                header("Cache-Control: must-revalidate");
                header("Pragma: public");
                header("Content-Length: " . filesize($path));
                readfile($path);
                break;
            } else {
                ActivityLog::log_action($current_user, 'system-error', $current_user->name() . ' tried downloading a copy of a file which is still in the database, but no longer on the server');
                $system_notification->queue('Download Error', 'File doesn\'t exist on the server anymore');
                break;
            }
            break;

    }

}