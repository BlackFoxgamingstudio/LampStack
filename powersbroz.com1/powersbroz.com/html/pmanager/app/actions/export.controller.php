<?php

if (isset($_POST['export'])) {

    switch ($_POST['export']) {

        case 'activity-text':
            $activity = ActivityLog::find('sql', "SELECT * FROM app_activity WHERE visible = 1 ORDER BY created DESC");
            if (!$activity) {
                $system_notification->queue('No Activity', 'There is no activity to export and download');
                break;
            }
            // Check if log exists currently
            $path = ROOT_PATH.'logs/activity-'.$now->format('Y-m-d').'.txt';
            $resource = fopen($path, 'w');
            $text = '';
            foreach ($activity as $a) {
                $text .= $a->created()->format('F d, Y') .' - ' . $a->get('user')->name().' : ' . $a->message() ."\n";
            }
            fwrite($resource, $text);
            fclose($resource);
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Pragma: public");
            header("Content-Length: " . filesize($path));
            readfile($path);
            $system_notification->queue('Activity Export Complete', 'A copy of the exported log is available in your root logs directory');
            break;

        case 'activity-csv':
            $activity = ActivityLog::find('sql', "SELECT * FROM app_activity WHERE visible = 1 ORDER BY created DESC");
            if (!$activity) {
                $system_notification->queue('No Activity', 'There is no activity to export and download');
                break;
            }
            // Check if log exists currently
            $path = ROOT_PATH.'logs/activity-'.$now->format('Y-m-d').'.csv';
            $resource = fopen($path, 'w');
            $list = array (array("Date", "User", "Message"));

            foreach ($activity as $a) {
                $list[] = array($a->created()->format('F d, Y'), $a->get('user')->name(), $a->message());
            }

            foreach ($list as $fields) {
                fputcsv($resource, $fields);
            }

            fclose($resource);
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Pragma: public");
            header("Content-Length: " . filesize($path));
            readfile($path);
            $system_notification->queue('Activity Export Complete', 'A copy of the exported log is available in your root logs directory');
            break;

        case 'project-history-csv':
            $projectid  = mysqli_real_escape_string($con->gate, $_POST['projectid']);
            $project    = Project::find('id', $projectid);
            if (!$project) {
                $system_notification->queue('Project Deleted', 'Between making the request the project in question was deleted');
                break;
            }
            $history = ProjectHistory::find('sql', "SELECT * FROM project_history WHERE projectid = ".$project->id()." ORDER BY created DESC");
            if (!$history) {
                $system_notification->queue('No Project History', 'There is no project history to export and download');
                break;
            }
            // Check if log exists currently
            $safeFileName   = remove_special_chars(str_replace(" ", "-", $project->name())).'-history-'.$now->format('Y-m-d');
            $path           = ROOT_PATH.'logs/'.$safeFileName.'.csv';
            $resource       = fopen($path, 'w');
            $list           = array (array("Date", "User", "Message"));

            foreach ($history as $h) {
                $list[] = array($h->created(), $h->user(), $h->description());
            }

            foreach ($list as $fields) {
                fputcsv($resource, $fields);
            }

            fclose($resource);
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Pragma: public");
            header("Content-Length: " . filesize($path));
            readfile($path);
            $system_notification->queue('Project History Complete', 'A copy of the exported project history is available in your root logs directory');
            break;

    }

}