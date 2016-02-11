<?php
if (isset($_POST['edit'])) {

    switch($_POST['edit']) {

        // EDIT USER
        case 'user':

            break;

        // EDIT PROJECT
        case 'project':
            if (!isset($_POST['project'])) {
                echo 'No project was passed to the application'; exit;
            } else {
                $project = Project::find('id', $_POST['project']);
                if (!$project) {
                    echo 'Uh oh, the project you passed to the application could not be found'; exit;
                }
            }
            if (trim($_POST['projectname']) == '') {
                $name = $project->name();
            } else {
                $name = mysqli_real_escape_string($con->gate, trim($_POST['projectname']));
            }
            if (trim($_POST['projectdesc']) == '') {
                $description = $project->description();
            } else {
                $description = mysqli_real_escape_string($con->gate, trim($_POST['projectdesc']));
            }
            if (trim($_POST['startdate']) == '') {
                $start = $project->get('startdate');
            } else {
                $start = new DateTime(trim($_POST['startdate'])) or die("Date could not be created from the provided start date");
            }
            if (trim($_POST['enddate']) == '') {
                $end = $project->get('enddate');
            } else {
                $end = new DateTime(trim($_POST['enddate'])) or die("Date could not be created from the provided end date");
            }
            $sql = "UPDATE projects SET pname = '".$name."', description = '".$description."', startdate = '".$start->format('Y-m-d')."', enddate = '".$end->format('Y-m-d')."' WHERE id = ".$project->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'edit',
                    $current_user->name() . " edited " . $project->name() . " project details"
                );
                ProjectHistory::log_action(
                    $current_user,
                    $project->id(),
                    'edit',
                    $current_user->name() . " edited project details"
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error editing this project\'s record. Please try again. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // EDIT PROJECT STAGE
        case 'stage':
            if (trim($_POST['stage']) == '') {
                echo 'There was no stage passed to the application for editing'; exit;
            } else {
                if (!is_numeric($_POST['stage'])) {
                    echo 'Stage passed to the application was not passed in the correct format'; exit;
                } else {
                    $stageid = mysqli_real_escape_string($con->gate, trim($_POST['stage']));
                }
                $stage = ProjectStage::find('id', $stageid);
                if (!$stage) {
                    echo 'Stage could not be found for editing'; exit;
                } else {

                    // Process form and compare to database
                    if (trim($_POST['stagename']) == '') {
                        $name = $stage->name();
                    } else {
                        $name = mysqli_real_escape_string($con->gate, trim($_POST['stagename']));
                    }
                    if (trim($_POST['stagedesc']) == '') {
                        $desc = $stage->description();
                    } else {
                        $desc = mysqli_real_escape_string($con->gate, trim($_POST['stagedesc']));
                    }
                    if (trim($_POST['duedate']) == '') {
                        if ($stage->has_duedate()) {
                            $due = $stage->has_duedate()->format('Y-m-d');
                        } else {
                            $due = '0000-00-00';
                        }
                    } else {
                        try {
                            $date = new DateTime(trim($_POST['duedate']));
                            $due = $date->format('Y-m-d');
                        } catch (Exception $e) {
                            echo 'Could not parse date, please follow formatting rule'; exit(1);
                        }
                    }
                    $sql = "
                    UPDATE project_stages
                    SET stagename = '".$name."', description = '".$desc."', duedate = '".$due."'
                    WHERE id = ".$stage->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        ActivityLog::log_action(
                            $current_user,
                            'edit',
                            'Edited details for stage: '.$stage->name()
                        );
                        ProjectHistory::log_action(
                            $current_user,
                            $stage->project()->id(),
                            'edit',
                            'Edited details for stage: '.$stage->name()
                        );
                        echo 'success'; exit;
                    } else {
                        echo 'Oh no! There was an error editing this stages\'s record. Please try again. If this error continually occurs, please contact support.'; exit;
                    }
                }
            }
            break;

        // EDIT PROJECT STAGETASK
        case 'stage-task':
            if (trim($_POST['stagetask']) == '') {
                echo 'There was no staged task passed to the application for editing'; exit;
            } else {
                if (!is_numeric($_POST['stagetask'])) {
                    echo 'Stage task passed to the application was not passed in the correct format'; exit;
                } else {
                    $stageTaskId = mysqli_real_escape_string($con->gate, trim($_POST['stagetask']));
                }
                $stageTask = StageTask::find('id', $stageTaskId);
                if (!$stageTask) {
                    echo 'Staged task could not be found for editing'; exit;
                } else {

                    // Process form and compare to database
                    if (trim($_POST['stagetaskname']) == '') {
                        $name = $stageTask->name();
                    } else {
                        $name = mysqli_real_escape_string($con->gate, trim($_POST['stagetaskname']));
                    }
                    if (trim($_POST['stagetaskdesc']) == '') {
                        $desc = $stageTask->description();
                    } else {
                        $desc = mysqli_real_escape_string($con->gate, trim($_POST['stagetaskdesc']));
                    }
                    if (trim($_POST['duedate']) == '') {
                        if ($stageTask->has_duedate()) {
                            $due = $stageTask->due();
                        } else {
                            $due = '0000-00-00';
                        }
                    } else {
                        try {
                            $date = new DateTime(trim($_POST['duedate']));
                            $due = $date->format('Y-m-d');
                        } catch (Exception $e) {
                            echo 'Could not parse date, please follow formatting rule'; exit(1);
                        }
                    }
                    $sql = "
                    UPDATE project_staged_tasks
                    SET stagetaskname = '".$name."', description = '".$desc."', duedate = '".$due."'
                    WHERE id = ".$stageTask->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        ActivityLog::log_action(
                            $current_user,
                            'edit',
                            'Edited stage task: '.$stageTask->name()
                        );
                        ProjectHistory::log_action(
                            $current_user,
                            $stageTask->stage()->project()->id(),
                            'edit',
                            'Edited stage task: '.$stageTask->name()
                        );
                        echo 'success'; exit;
                    } else {
                        echo 'Oh no! There was an error editing this staged task\'s record. Please try again. If this error continually occurs, please contact support.'; exit;
                    }
                }
            }
            break;

        // EDIT WAGE
        case 'wage':
            $wage = Wage::find('id', $con->secure($_POST['wage']));
            if (!$wage) {
                echo 'Wage passed to the application for processing could not be located in the database'; exit;
            }
            if (trim($_POST['billingcode']) == '') {
                $billingCode = $wage->billing_code();
            } else {
                $billingCode = $con->secure($_POST['billingcode']);
            }
            if (trim($_POST['wname']) == '') {
                $wageName = $wage->name();
            } else {
                $wageName = $con->secure($_POST['wname']);
            }
            if (trim($_POST['wdesc']) == '') {
                $wageDesc = $wage->description();
            } else {
                $wageDesc = $con->secure($_POST['wdesc']);
            }
            if (trim($_POST['wrate']) == '') {
                $wageRate = $wage->unformed_rate();
            } else {
                $wageRate = $con->secure($_POST['wrate']);
            }
            $sql = "UPDATE wages SET billingcode = '".$billingCode."', wname = '".$wageName."', wdesc = '".$wageDesc."', wrate = ".$wageRate.", updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$wage->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'edit',
                    'Edited '.$wage->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application attempted to update wage: '.$wage->name().' but failed'
                );
                echo 'Could not edit wage'; exit;
            }
            break;

        // EDIT TAX
        case 'tax':
            $tax = Tax::find('id', $con->secure($_POST['tax']));
            if (!$tax) {
                echo 'Tax passed to the application could not be located in the database'; exit;
            }
            if (trim($_POST['taxname']) == '') {
                $taxName = $tax->name();
            } else {
                $taxName = $con->secure($_POST['taxname']);
            }
            if (trim($_POST['taxdesc']) == '') {
                $taxDesc = $tax->description();
            } else {
                $taxDesc = $con->secure($_POST['taxdesc']);
            }
            if (trim($_POST['taxrate']) == '') {
                $taxRate = $tax->rate();
            } else {
                $taxRate = $con->secure($_POST['taxrate']);
            }
            $sql = "UPDATE taxes SET tname = '".$taxName."', tdesc = '".$taxDesc."', tpercent = ".$taxRate.", updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$tax->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'edit',
                    'Edited '.$tax->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application attempted to update tax: '.$tax->name().' but failed'
                );
                echo 'Could not edit tax'; exit;
            }
            break;

    }

}
// END POST EDIT IF STATEMENT