<?php
if (isset($_POST['complete'])) {

    switch($_POST['complete']) {

        case 'project':
            if (!isset($_POST['project'])) {
                echo 'No project was passed to the application for processing'; exit;
            }
            $projectid = mysqli_real_escape_string($con->gate, trim($_POST['project']));
            $project = Project::find('id', $projectid);
            if (!$project) {
                echo 'Uh oh, this project could not be located in the database.'; exit;
            }
            $sql = "UPDATE projects SET active = 0, complete = 1, updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$project->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'There was a problem connecting to the database'; exit;
            }
            break;

        case 'stage':
            if (trim($_POST['stage']) == '') {
                echo 'No stage was was passed to the application for processing'; exit;
            }
            $stage = ProjectStage::find('id', mysqli_real_escape_string($con->gate, trim($_POST['stage'])));
            if (!$stage) {
                echo 'The stage you are trying to mark complete could not be found. It may have already been deleted by another user.'; exit;
            } else {
                // Process completion
                $sql = "UPDATE project_stages SET complete = 1, updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$stage->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    ActivityLog::log_action(
                        $current_user,
                        'complete',
                        $current_user->name() . ' marked stage: ' . $stage->name() . ' complete'
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $stage->project()->id(),
                        'complete',
                        'Stage: ' . $stage->name() . ' was completed!'
                    );
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was an error marking this stage as complete. Please try again. If this error continually occurs, please contact support.'; exit;
                }
            }
            break;

        case 'stage-task':
            if (trim($_POST['task']) == '') {
                echo 'No stage task id was passed to the application for processing'; exit;
            }
            $stageTask = StageTask::find('id', mysqli_real_escape_string($con->gate, trim($_POST['task'])));
            if (!$stageTask) {
                echo 'The staged task you are trying to mark complete could not be found. It may have already been deleted by another user.'; exit;
            } else {
                // Process completion
                $sql = "UPDATE project_staged_tasks SET complete = 1, updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$stageTask->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    ActivityLog::log_action(
                        $current_user,
                        'complete',
                        $current_user->name() . ' marked stage task: ' . $stageTask->name() . ' for stage: '.$stageTask->stage()->name().'complete'
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $stageTask->stage()->project()->id(),
                        'complete',
                        'Stage: ' . $stageTask->stage()->name() . ' task: '.$stageTask->name().' was completed!'
                    );
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was an error marking this staged task as complete. Please try again. If this error continually occurs, please contact support.'; exit;
                }

            }
            break;


    }

}
// END POST COMPLETE IF STATEMENT