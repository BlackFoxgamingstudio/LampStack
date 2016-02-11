<?php
if (isset($_POST['assign'])) {

    switch ($_POST['assign']) {

        // ASSIGN USER TO GROUP
        case 'user-to-group':
            //echo '<pre>';var_dump($_POST);echo '</pre>';exit;
            if (trim($_POST['groupuser']) == '') {
                echo 'Uh oh, there was no user passed to the application for assignment'; exit;
            }
            $user = User::find('id', $_POST['groupuser']);
            if (!$user) {
                echo "Uh oh, the user's account could not be found";exit;
            }
            if (trim($_POST['assignedgroup']) == '') {
                echo 'Uh oh, there was no group passed to the application for this assignment'; exit;
            }
            $group = Group::find('id', $_POST['assignedgroup']);
            if (!$group) {
                echo 'Uh oh, the Group information could not be found'; exit;
            }
            // Check for current assignment
            $assigned = $user->is_assigned('group', $group->id());
            if ($assigned) {
                echo 'This is user is already a member of '.$group->name(); exit;
            } else {
                $sql = "INSERT INTO
                group_members (userid, groupid, created, updated)
                VALUES (".$user->id().", ".$group->id().", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                $exec = $con->gate->query($sql);
                if ($exec) {
                    ActivityLog::log_action($current_user, 'assign', $current_user->name() . ' assigned ' . $user->name() . ' to group:'.$group->name());
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was an error entering this assignment into your database. Please try again. If this error continually occurs, please contact support.'; exit;
                }
            }
            break;

        // ASSIGN USER TO PROJECT
        case 'user-to-project':
            if (trim($_POST['assigneduser']) == '') {
                echo 'Uh oh, there was no user passed to the application for assignment'; exit;
            }
            $user = User::find('id', $_POST['assigneduser']);
            if (!$user) {
                echo "Uh oh, the user's account could not be found";exit;
            }
            if (trim($_POST['assignedproject']) == '') {
                echo 'Uh oh, there was no project was passed to the application for this assignment'; exit;
            }
            $project = Project::find('id', $_POST['assignedproject']);
            if (!$project) {
                echo 'Uh oh, the Project information could not be found'; exit;
            }
            // Check for current assignment - will also check by group...
            // TODO: Improve how system handles an already existing direct assignment when assigning a group
            $assigned = $project->is_user_assigned($user->id());
            if ($assigned) {
                echo 'This is user is already assigned to '.$project->name(); exit;
            } else {
                $sql = "INSERT INTO
                project_members (memberid, projectid, is_group, created, updated)
                VALUES (".$user->id().", ".$project->id().",0,'".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                $exec = $con->gate->query($sql);
                if ($exec) {
                    ActivityLog::log_action(
                        $current_user,
                        'assign',
                        $current_user->name() . " assigned " . $user->name() .' to project: '.$project->name()
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $project->id(),
                        'assign',
                        $user->name() . " was assigned to this project by ".$current_user->name()
                    );
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was an error entering this assignment into your database. Please try again. If this error continually occurs, please contact support.'; exit;
                }
            }
            break;

        // ASSIGN USERS TO PROJECT
        case 'users-to-project':
            if (!isset($_POST['assignees'])) {
                echo 'Please select the users you want to assign to this project'; exit;
            } else {
                if (!isset($_POST['project'])) {
                    echo 'No project was passed to the application for processing'; exit;
                }
                $project = Project::find('id', mysqli_real_escape_string($con->gate, $_POST['project']));
                if (!$project) {
                    echo 'The project you are trying to assign these users to no longer exists'; exit;
                }

                foreach($_POST['assignees'] as $userid) {

                    if ($project->is_user_assigned($userid)) {
                        continue;
                    } else {
                        $con->gate->query("
                        INSERT INTO
                        project_members (memberid, projectid, is_group, created, updated)
                        VALUES (".$userid.", ".$project->id().", 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')");
                    }

                }
                echo 'success'; exit;
            }


            break;

        // ASSIGN PROJECT LEAD
        case 'user-projectlead':
            if (trim($_POST['leaduser']) == '') {
                echo 'No user was passed to the application for processing'; exit;
            }
            $userid = mysqli_real_escape_string($con->gate, trim($_POST['leaduser']));
            $user = User::find('id', $userid);
            if (!$user) {
                echo 'The user account could not be located'; exit;
            }
            if (trim($_POST['assignedproject']) == '') {
                echo 'No project was passed to the application for processing'; exit;
            }
            $projectid = mysqli_real_escape_string($con->gate, trim($_POST['assignedproject']));
            $project = Project::find('id', $projectid);
            if (!$project) {
                echo 'The project record could not be located'; exit;
            }
            $sql = "UPDATE projects SET owner = ".$userid." WHERE id = ".$projectid;
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'assign',
                    $current_user->name() . " assigned " . $user->name() .' as project lead for project: '.$project->name()
                );
                ProjectHistory::log_action(
                    $current_user,
                    $project->id(),
                    'assign',
                    $user->name() . " was assigned as project lead this project by ".$current_user->name()
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error assigning this user as a project lead. Please try again. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // ASSIGN GROUP OWNER
        case 'user-groupowner':
            if (!isset($_POST['leaduser'])) {
                echo 'No user was passed to the application for processing'; exit;
            }
            $ownerid = mysqli_real_escape_string($con->gate, $_POST['leaduser']);
            $owner = User::find('id', $ownerid);
            if (!$owner) {
                echo 'The user you are trying to assign as this group owner could not be found'; exit;
            }
            if (!isset($_POST['ownedgroup'])) {
                echo 'No group was passed to the application for processing'; exit;
            }
            $groupid = mysqli_real_escape_string($con->gate, $_POST['ownedgroup']);
            $group = Group::find('id', $groupid);
            if (!$group) {
                echo 'The group you are trying to assign this user as owner could not be found'; exit;
            }

            // Check to see if the group has owner already
            if ($group->owner()) {
                echo 'You already have '.$group->owner()->name().' assigned as this group\'s owner. Please <a href="'.BASE_URL.'groups/'.$group->id().'" title="Visit group page to remove this user as owner">remove</a> them first.'; exit;
            } else {

                $sql = "UPDATE groups SET owner = ".$owner->id()." WHERE id = ".$group->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was a problem communicating to the database'; exit;
                }
            }
            break;

        // ASSIGN GROUP TO PROJECT
        case 'group-to-project':
            if (trim($_POST['assigngroup']) == '') {
                echo 'Uh oh, there was no group passed to the application for assignment'; exit;
            }
            $group = Group::find('id', $_POST['assigngroup']);
            if (!$group) {
                echo "Uh oh, the group's account could not be found";exit;
            }
            if (trim($_POST['assignedproject']) == '') {
                echo 'Uh oh, there was no project passed to the application for assignment'; exit;
            }
            $project = Project::find('id', $_POST['assignedproject']);
            if (!$project) {
                echo 'Uh oh, the Project information could not be found'; exit;
            }
            // Check for current assignment
            $assigned = $group->is_assigned($project->id());
            if ($assigned) {
                echo 'This is group is already assigned to '.$project->name(); exit;
            } else {
                $sql = "INSERT INTO
                project_members (memberid, projectid, is_group, created, updated)
                VALUES (".$group->id().", ".$project->id().",1,'".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                $exec = $con->gate->query($sql);
                if ($exec) {
                    ActivityLog::log_action(
                        $current_user,
                        'assign',
                        $current_user->name() . " assigned the group " . $group->name() .' to project: '.$project->name()
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $project->id(),
                        'assign',
                        "Group: " . $group->name() . " was assigned to this project by ".$current_user->name()
                    );
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was an error entering this assignment into your database. Please try again. If this error continually occurs, please contact support.'; exit;
                }
            }
            break;

        // ASSIGN USER TO STAGE TASK
        case 'user-to-stagetask':
            if (!isset($_POST['stagetaskid'])) {
                echo 'No task was passed to the application to make this assignment'; exit;
            }
            $stagetask              = StageTask::find('id', trim($_POST['stagetaskid']));
            $currentlyAssignedUsers = $stagetask->get_assigned_userIDs();

            if (!$stagetask) {
                echo 'The stage task you are trying to assign this user to could not be found';exit;
            }

            if (empty($_POST['attach']) && empty($currentlyAssignedUsers)) {
                echo 'No assignment changes need to be made'; exit;
            }

            // Check to see if anyone was passed for assignment
            if (empty($_POST['attach'])) {
                $checkedUsers = array();
            } else {
                $checkedUsers = $_POST['attach'];
            }

            // Compare and operate on table
            $project                = $stagetask->stage()->project();
            $cleanCheckedUsers      = array();
            // Sanitize post data
            foreach ($checkedUsers as $userID) {
                $cleanCheckedUsers[] = $con->secure($userID);
            }

            // Identify all currently assigned that were unchecked
            $removeUsers            = array_diff($currentlyAssignedUsers, $cleanCheckedUsers);
            if (!empty($removeUsers)) {
                foreach ($removeUsers as $removedUser) {
                    $user = User::find('id', $removedUser);
                    StageTask::unassign_user($stagetask->id(), $removedUser);
                    ActivityLog::log_action(
                        $current_user,
                        'unassign',
                        "Unassigned " . $user->name() .' from stage task: '.$stagetask->name() ." for stage: ".$stagetask->stage->name() . " in the project: ".$project->name()
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $project->id(),
                        'unassign',
                        "Unassigned " . $user->name() .' from stage task: '.$stagetask->name() ." for stage: ".$stagetask->stage->name()
                    );
                }
            }

            // Assignment operations
            foreach ($checkedUsers as $userID) {
                $user = User::find('id', $userID);
                if (!$user || !$user->is_assigned('project', $project->id()) || $user->is_assigned('stagetask', $stagetask->id())) {
                    continue;
                }
                StageTask::assign_user($stagetask->id(), $userID);
                ActivityLog::log_action(
                    $current_user,
                    'assign',
                    $current_user->name() . " assigned " . $user->name() .' to stage task: '.$stagetask->name() ." for stage: ".$stagetask->stage->name() . " in the project: ".$project->name()
                );
                ProjectHistory::log_action(
                    $current_user,
                    $project->id(),
                    'assign',
                    $current_user->name() . " assigned " . $user->name() .' to stage task: '.$stagetask->name() ." for stage: ".$stagetask->stage->name()
                );
            }

            echo 'success'; exit;
            break;

        case 'tax':
            if (trim($_POST['tax']) == '') {
                echo 'No tax was passed to the application for processing'; exit;
            }
            $tax = Tax::find('id', $con->secure($_POST['tax']));
            if (!$tax) {
                echo 'The tax you are trying to assign no longer exists'; exit;
            }
            $invoice = Invoice::find('id', $con->secure($_POST['invoice']));
            if (!$invoice) {
                echo 'Invoice passed to the application does not exist'; exit;
            }

            switch ($_POST['application']) {

                case 'invoice':
                    $sql = "INSERT INTO invoice_tax_assignments (taxid, invoiceid, created, updated) VALUES (".$tax->id().", ".$invoice->id().", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        echo 'success'; exit;
                    } else {
                        echo 'Oh no! There was an error applying the tax to this invoice'; exit;
                    }
                    break;

                case 'charges':
                    if (empty($_POST['charges'])) {
                        echo 'You did not select any charges to apply the tax to'; exit;
                    }
                    $insertQuery = "INSERT INTO invoice_charge_tax_assignments (chargeid, taxid, created, updated) VALUES ";
                    foreach ($_POST['charges'] as $key => $value) {
                        $insertQuery .= "(".$value.", ".$tax->id().", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."'),";
                    }
                    $insertQuery = rtrim($insertQuery, ',');
                    $exec = $con->gate->query($insertQuery);
                    if ($exec) {
                        echo 'success';exit;
                    } else {
                        echo 'Oh no! Something went wrong while trying to assign this tax to the selected charges'; exit;
                    }
                    break;

                default:
                    echo 'Unknown assignment'; exit;

            }
            break;
    }

}
// END POST ASSIGN IF STATEMENT