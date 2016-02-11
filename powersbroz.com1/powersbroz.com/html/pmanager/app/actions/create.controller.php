<?php
if (isset($_POST['create'])) {

    switch($_POST['create']) {

        // CREATE ADMIN
        case 'admin':
            demo_die();
            if (trim($_POST['firstname']) == '') {
                echo 'Please a first name';exit;
            }
            $fname = mysqli_real_escape_string($con->gate, trim($_POST['firstname']));
            if (trim($_POST['lastname']) == '') {
                echo 'Please a last name';exit;
            }
            $lname = mysqli_real_escape_string($con->gate, trim($_POST['lastname']));
            if (trim($_POST['username']) == '') {
                echo 'You need to enter your username'; exit;
            }
            $uname = mysqli_real_escape_string($con->gate, trim($_POST['username']));
            if (trim($_POST['email']) == '') {
                echo 'Please enter your email address'; exit;
            }
            $email = mysqli_real_escape_string($con->gate, trim($_POST['email']));
            if (trim($_POST['password']) == '') {
                echo 'Please enter a password'; exit;
            }
            $password = mysqli_real_escape_string($con->gate, trim($_POST['password']));
            $password = do_get_hash($password);

            $sql = "INSERT INTO users (uname, pass, fname, lname, email, role, active, created, updated) VALUES ('".$uname."', '".$password."', '".$fname."', '".$lname."', '".$email."', 1, 1, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'There was a problem while creating this administrator account'; exit;
            }

            break;

        // CREATE USER
        case 'user':
            if (trim($_POST['firstname']) == '') {
                echo "You forgot to enter the user's first name"; exit;
            }
            $fname = mysqli_real_escape_string($con->gate, trim($_POST['firstname']));
            if (trim($_POST['lastname']) == '') {
                echo "You forgot to enter the user's last name"; exit;
            }
            $lname = mysqli_real_escape_string($con->gate, trim($_POST['lastname']));
            if (trim($_POST['useremail']) == '') {
                echo "You forgot to enter the user's email address"; exit;
            }
            $email = mysqli_real_escape_string($con->gate, trim($_POST['useremail']));
            // Check for duplicate
            if (User::is_duplicate($email, 'email')) {
                echo 'The email you entered is not available'; exit;
            }
            if (trim($_POST['username']) == '') {
                echo "You forgot to enter the user's username"; exit;
            }
            $uname = mysqli_real_escape_string($con->gate, trim($_POST['username']));
            // Check for duplicate
            if (User::is_duplicate($uname, 'uname')) {
                echo 'The username you have chosen is not available'; exit;
            }
            if (trim($_POST['password']) == '') {
                echo "You forgot to enter the user's password"; exit;
            }
            $pass = mysqli_real_escape_string($con->gate, trim($_POST['password']));
            $pass = do_get_hash($pass);

            $role = mysqli_real_escape_string($con->gate, trim($_POST['userrole']));

            $sql = "INSERT INTO
            users (uname, pass, fname, lname, email, role, active, created, updated)
            VALUES ('".$uname."', '".$pass."', '".$fname."', '".$lname."', '".$email."', ".$role.", 1, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec = $con->gate->query($sql);
            $userid = $con->insert_id();
            // Create user's personal files area
            mkdir(ROOT_PATH . 'files/users/'.$userid, 0755, true);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    "create",
                    $current_user->name().' created a new user: '.$fname.' '.$lname.', '.$email
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    "Application attempted to create a new user: ".$fname.' '.$lname." but failed"
                );
                echo 'Uh oh, there was an error creating the user account in the database.'; exit;
            }
            break;

        // CREATE USER IMAGE
        case 'user-image':

            if (trim($_POST['user']) == '') {
                $system_notification->queue('Upload Error', 'no user was passed to the application for processing');
                break;
            }
            $user = User::find('id', trim($_POST['user']));

            if (!$user) {
                $system_notification->queue('Upload Error', 'The user you are uploading this image for has been removed or could not be found');
                break;
            }

            if (empty($_FILES)) {
                $system_notification->queue('Upload Error', 'No file was selected to be uploaded'); break;
            }

            $upload = File::upload(
                'user',
                $_FILES,
                array(
                   'target_user'    => $user,
                   'current_user'   => $current_user
                )
            );

            if (empty($upload)) {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Unable to upload file due to unknown error'
                );
                $system_notification->queue('Unable to process Request', 'Something went wrong on the inside!');
            } else {
                $system_notification->queue($upload['title'], $upload['message']);
            }

            break;

        // CREATE REGISTRATION
        case 'registration':
            if (trim($_POST['rfname']) == '') {
                echo 'Please enter your first name'; exit;
            }
            $fname = mysqli_real_escape_string($con->gate, trim($_POST['rfname']));
            if (trim($_POST['rlname']) == '') {
                echo 'Please enter your last name'; exit;
            }
            $lname = mysqli_real_escape_string($con->gate, trim($_POST['rlname']));
            if (trim($_POST['remail']) == '') {
                echo 'Please enter your email address'; exit;
            }
            $email = mysqli_real_escape_string($con->gate, trim($_POST['remail']));
            if (User::is_duplicate($email, 'email')) {
                echo 'Email address is unavailable. Have you already registered? Try resetting your password.'; exit;
            }
            if (trim($_POST['runame']) == '') {
                echo 'Please enter a username'; exit;
            }
            $uname = mysqli_real_escape_string($con->gate, trim($_POST['runame']));
            if (User::is_duplicate($uname, 'uname')) {
                echo 'Username is unavailable. Select another one.'; exit;
            }
            if (trim($_POST['rpass']) == '') {
                echo 'Please enter a password'; exit;
            }
            $plainpass = mysqli_real_escape_string($con->gate, trim($_POST['rpass']));
            $hashpass  = sha1($plainpass);

            if ($app_settings->get('registration_default_role') != 0) {
                $default_role = $app_settings->get('registration_default_role');
            }

            $sql = "
            INSERT INTO
            users (uname, pass, fname, lname, email, role, active, created, updated)
            VALUES ('".$uname."', '".$hashpass."', '".$fname."', '".$lname."', '".$email."', ".$default_role.", 1, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
            $exec = $con->gate->query($sql);
            $userid = $con->insert_id();
            // Create user's personal files area
            mkdir(ROOT_PATH . 'files/users/'.$userid, 0755, true);
            $user = User::find('id', $userid);
            if ($exec) {
                $lastid = $con->insert_id();
                if (APPROPRIATE && $app_owner && $app_settings->get('notify_when_user_registers') == 1) {
                    $to         = $app_owner->email();
                    $subject    = 'Password Reset Request';

                    $headers    = "From: " . $email . "\r\n";
                    $headers   .= "Reply-To: ". $email . "\r\n";
                    $headers   .= "MIME-Version: 1.0\r\n";
                    $headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    $message    = "<p>".$fname . " " . $lname . " registered for an account. You can email him/her at: ".$email."</p>";
                    $email      = new EmailTemplate('basic', array('title' => 'You have a new user!', 'message' => $message));

                    $send       = mail($to, $subject, $email->render(), $headers);
                }

                // Insert into unique user registrations table for record
                $sql = "
                INSERT INTO
                user_registrations (userid, registeredrole, created)
                VALUES (".$user->id().", 5, '".$now->format('Y-m-d H:i:s')."')";
                $exec = $con->gate->query($sql);
                ActivityLog::log_action(
                    $user,
                    "register",
                    $user->name().' successfully registered for an account'
                );
                echo 'success'; exit;
            } else {
                echo 'Uh oh! There was a problem completing your registration. Please contact support if this continues.'; exit;
            }

            break;

        // CREATE USER ROLE
        case 'access-role':

            // Debug
            // echo '<pre>'; var_dump($_POST);exit;

            if (trim($_POST['rolename']) == '') {
                echo 'You must give this access role a name'; exit;
            }
            $role_name = mysqli_real_escape_string($con->gate, trim($_POST['rolename']));

            if (trim($_POST['roledesc']) == '') {
                echo 'You must give this access role a short description'; exit;
            }
            $role_description = mysqli_real_escape_string($con->gate, trim($_POST['roledesc']));

            $error_message = 'There was problem creating this access role. Make sure you filled out the form correctly.';

            // Initialize Perms string
            $perms = '';
            // Process Basic [Basic]
            if (!isset($_POST['basic']) || $_POST['basic'] == '') {
                echo 'You must select a primary role for this access role'; exit;
            }

            switch ($_POST['basic']) {

                case 'staff':
                    $classification = 1;
                    break;

                case 'contractor':
                    $classification = 2;
                    break;

                case 'client':
                    $classification = 3;
                    break;

                default:
                    $classification = 3;

            }


            // Process System Actions [SysX]
            if (!isset($_POST['system-settings']) || trim($_POST['system-settings']) == '') {
                echo $error_message; exit;
            }
            $system_settings = mysqli_real_escape_string($con->gate, trim($_POST['system-settings']));

            if (!isset($_POST['system-docs']) || trim($_POST['system-docs']) == '') {
                echo $error_message; exit;
            }
            $system_docs = mysqli_real_escape_string($con->gate, trim($_POST['system-docs']));

            if (!isset($_POST['system-activity']) || trim($_POST['system-activity']) == '') {
                echo $error_message; exit;
            }
            $system_activity = mysqli_real_escape_string($con->gate, trim($_POST['system-activity']));

            if (!isset($_POST['system-maintenance']) || trim($_POST['system-maintenance']) == '') {
                echo $error_message; exit;
            }
            $system_maintenance = mysqli_real_escape_string($con->gate, trim($_POST['system-maintenance']));

            if (!isset($_POST['system-roles']) || trim($_POST['system-roles']) == '') {
                echo $error_message; exit;
            }
            $system_roles = mysqli_real_escape_string($con->gate, trim($_POST['system-roles']));

            if (!isset($_POST['system-wages']) || trim($_POST['system-wages']) == '') {
                echo $error_message; exit;
            }
            $system_wages = mysqli_real_escape_string($con->gate, trim($_POST['system-wages']));

            // Build System Actions
            $SysX = '[SysX(';

            $SysX .= 'settings-'.$system_settings.';';
            $SysX .= 'docs-'.$system_docs.';';
            $SysX .= 'activity-'.$system_activity.';';
            $SysX .= 'maintenance-'.$system_maintenance.';';
            $SysX .= 'roles-'.$system_roles.';';
            $SysX .= 'wages-'.$system_wages;

            $SysX .= ')]';

            $perms .= $SysX.',';

            // Process User Actions [UX]
            if (!isset($_POST['user-create']) || trim($_POST['user-create']) == '') {
                echo $error_message; exit;
            }
            $user_create = mysqli_real_escape_string($con->gate, trim($_POST['user-create']));

            if (!isset($_POST['user-edit']) || trim($_POST['user-edit']) == '') {
                echo $error_message; exit;
            }
            $user_edit = mysqli_real_escape_string($con->gate, trim($_POST['user-edit']));

            if (!isset($_POST['user-delete']) || trim($_POST['user-delete']) == '') {
                echo $error_message; exit;
            }
            $user_delete = mysqli_real_escape_string($con->gate, trim($_POST['user-delete']));

            if (!isset($_POST['user-assign-project']) || trim($_POST['user-assign-project']) == '') {
                echo $error_message; exit;
            }
            $user_assign_project = mysqli_real_escape_string($con->gate, trim($_POST['user-assign-project']));

            if (!isset($_POST['user-assign-group']) || trim($_POST['user-assign-group']) == '') {
                echo $error_message; exit;
            }
            $user_assign_group = mysqli_real_escape_string($con->gate, trim($_POST['user-assign-group']));

            // Build User Actions
            $UX = '[UX(';

            $UX .= 'create-'.$user_create.';';
            $UX .= 'edit-'.$user_edit.';';
            $UX .= 'delete-'.$user_delete.';';
            $UX .= 'assignProject-'.$user_assign_project.';';
            $UX .= 'assignGroup-'.$user_assign_group;

            $UX .= ')]';

            $perms .= $UX.',';

            // Process Group Actions [GX]
            if (!isset($_POST['group-create']) || trim($_POST['group-create']) == '') {
                echo $error_message; exit;
            }
            $group_create = mysqli_real_escape_string($con->gate, trim($_POST['group-create']));

            if (!isset($_POST['group-edit']) || trim($_POST['group-edit']) == '') {
                echo $error_message; exit;
            }
            $group_edit = mysqli_real_escape_string($con->gate, trim($_POST['group-edit']));

            if (!isset($_POST['group-delete']) || trim($_POST['group-delete']) == '') {
                echo $error_message; exit;
            }
            $group_delete = mysqli_real_escape_string($con->gate, trim($_POST['group-delete']));

            // Build Group Actions
            $GX = '[GX(';

            $GX .= 'create-'.$group_create.';';
            $GX .= 'edit-'.$group_edit.';';
            $GX .= 'delete-'.$group_delete;

            $GX .= ')]';

            $perms .= $GX.',';

            // Process Group Access Actions [GaX]
            if (!isset($_POST['group-access']) || trim($_POST['group-access']) == '') {
                echo $error_message; exit;
            }
            $group_access = mysqli_real_escape_string($con->gate, trim($_POST['group-access']));

            // Build Group Access Actions
            $GaX = '[GaX(';

            $GaX .= 'access-'.$group_access;

            $GaX .= ')]';

            $perms .= $GaX.',';

            // Process Project Actions [PX]
            if (!isset($_POST['project-create']) || trim($_POST['project-create']) == '') {
                echo $error_message; exit;
            }
            $project_create = mysqli_real_escape_string($con->gate, trim($_POST['project-create']));

            if (!isset($_POST['project-edit']) || trim($_POST['project-edit']) == '') {
                echo $error_message; exit;
            }
            $project_edit = mysqli_real_escape_string($con->gate, trim($_POST['project-edit']));

            if (!isset($_POST['project-delete']) || trim($_POST['project-delete']) == '') {
                echo $error_message; exit;
            }
            $project_delete = mysqli_real_escape_string($con->gate, trim($_POST['project-delete']));

            if (!isset($_POST['create-project-stages']) || trim($_POST['create-project-stages']) == '') {
                echo $error_message; exit;
            }
            $project_create_stages = mysqli_real_escape_string($con->gate, trim($_POST['create-project-stages']));

            if (!isset($_POST['stage-delete']) || trim($_POST['stage-delete']) == '') {
                echo $error_message; exit;
            }
            $project_delete_stages = mysqli_real_escape_string($con->gate, trim($_POST['stage-delete']));

            if (!isset($_POST['stage-brief-access']) || trim($_POST['stage-brief-access']) == '') {
                echo $error_message; exit;
            }
            $project_stage_brief = mysqli_real_escape_string($con->gate, trim($_POST['stage-brief-access']));

            if (!isset($_POST['create-project-stage-task']) || trim($_POST['create-project-stage-task']) == '') {
                echo $error_message; exit;
            }
            $project_create_stage_tasks = mysqli_real_escape_string($con->gate, trim($_POST['create-project-stage-task']));

            if (!isset($_POST['stage-task-delete']) || trim($_POST['stage-task-delete']) == '') {
                echo $error_message; exit;
            }
            $project_delete_stage_tasks = mysqli_real_escape_string($con->gate, trim($_POST['stage-task-delete']));

            if (!isset($_POST['create-project-task']) || trim($_POST['create-project-task']) == '') {
                echo $error_message; exit;
            }
            $project_tasks = mysqli_real_escape_string($con->gate, trim($_POST['create-project-task']));

            if (!isset($_POST['delete-project-task']) || trim($_POST['delete-project-task']) == '') {
                echo $error_message; exit;
            }
            $project_tasks_delete = mysqli_real_escape_string($con->gate, trim($_POST['delete-project-task']));

            // Build Project Actions
            $PX = '[PX(';

            $PX .= 'create-'.$project_create.';';
            $PX .= 'edit-'.$project_edit.';';
            $PX .= 'delete-'.$project_delete.';';
            $PX .= 'createStage-'.$project_create_stages.';';
            $PX .= 'deleteStage-'.$project_delete_stages.';';
            $PX .= 'stageBrief-'.$project_stage_brief.';';
            $PX .= 'createStageTask-'.$project_create_stage_tasks.';';
            $PX .= 'deleteStageTask-'.$project_delete_stage_tasks.';';
            $PX .= 'createTask-'.$project_tasks.';';
            $PX .= 'deleteTask-'.$project_tasks_delete;

            $PX .= ')]';

            $perms .= $PX.',';

            // Process Project Access Actions [PaX]
            if (!isset($_POST['project-access']) || trim($_POST['project-access']) == '') {
                echo $error_message; exit;
            }
            $project_access = mysqli_real_escape_string($con->gate, trim($_POST['project-access']));

            // Build Project Access Actions
            $PaX = '[PaX(';

            $PaX .= 'access-'.$project_access;

            $PaX .= ')]';

            $perms .= $PaX.',';

            // Process Invoicing [InvX]
            if (!isset($_POST['inv-create']) || trim($_POST['inv-create']) == '') {
                echo $error_message; exit;
            }
            $inv_create = mysqli_real_escape_string($con->gate, trim($_POST['inv-create']));

            if (!isset($_POST['inv-edit']) || trim($_POST['inv-edit']) == '') {
                echo $error_message; exit;
            }
            $inv_edit = mysqli_real_escape_string($con->gate, trim($_POST['inv-edit']));

            if (!isset($_POST['inv-delete']) || trim($_POST['inv-delete']) == '') {
                echo $error_message; exit;
            }
            $inv_delete = mysqli_real_escape_string($con->gate, trim($_POST['inv-delete']));

            if (!isset($_POST['inv-payment']) || trim($_POST['inv-payment']) == '') {
                echo $error_message; exit;
            }
            $inv_payment = mysqli_real_escape_string($con->gate, trim($_POST['inv-payment']));

            if (!isset($_POST['inv-chargeto']) || trim($_POST['inv-chargeto']) == '') {
                echo $error_message; exit;
            }
            $inv_chargeto = mysqli_real_escape_string($con->gate, trim($_POST['inv-chargeto']));

            // Build Invoicing Actions
            $InvX = '[InvX(';

            $InvX .= 'create-'.$inv_create.';';
            $InvX .= 'edit-'.$inv_edit.';';
            $InvX .= 'delete-'.$inv_delete.';';
            $InvX .= 'getPaid-'.$inv_payment.';';
            $InvX .= 'charge-'.$inv_chargeto;

            $InvX .= ')]';

            $perms .= $InvX.',';

            // Process File System Operations [FSX]
            if (!isset($_POST['upload-file']) || trim($_POST['upload-file']) == '') {
                echo $error_message; exit;
            }
            $file_upload = mysqli_real_escape_string($con->gate, trim($_POST['upload-file']));

            if (!isset($_POST['upload-max-space']) || trim($_POST['upload-max-space']) == '') {
                echo $error_message; exit;
            }
            $file_upload_max_space = mysqli_real_escape_string($con->gate, trim($_POST['upload-max-space']));

            if (!isset($_POST['upload-max-space-unit']) || trim($_POST['upload-max-space-unit']) == '') {
                echo $error_message; exit;
            }
            $file_upload_max_space_unit = mysqli_real_escape_string($con->gate, trim($_POST['upload-max-space-unit']));

            if (!isset($_POST['upload-max-space-override'])) {
                $file_upload_max_override = 0;
            } else {
                $file_upload_max_override = 1;
            }

            if (!isset($_POST['admin-file']) || trim($_POST['admin-file']) == '') {
                echo $error_message; exit;
            }
            $file_admin = mysqli_real_escape_string($con->gate, trim($_POST['admin-file']));

            // Build File System Actions
            $FSX = '[FSX(';

            $FSX .= 'upload-'.$file_upload.';';
            $FSX .= 'maxSpace-'.$file_upload_max_space.';';
            $FSX .= 'maxSpaceUnit-'.$file_upload_max_space_unit.';';
            $FSX .= 'maxSpaceOverride-'.$file_upload_max_override.';';
            $FSX .= 'admin-'.$file_admin;

            $FSX .= ')]';

            $perms .= $FSX.',';

            // Process Visibility Scope and Communication [VisX]
            if (!isset($_POST['system-addressbook']) || trim($_POST['system-addressbook']) == '') {
                echo $error_message; exit;
            }
            $address_book = mysqli_real_escape_string($con->gate, trim($_POST['system-addressbook']));

            if (!isset($_POST['cal-view']) || trim($_POST['cal-view']) == '') {
                echo $error_message; exit;
            }
            $cal_view = mysqli_real_escape_string($con->gate, trim($_POST['cal-view']));

            // Build VisX Actions
            $VisX = '[VisX(';

            $VisX .= 'addressBook-'.$address_book.';';
            $VisX .= 'calendar-'.$cal_view;

            $VisX .= ')]';

            $perms .= $VisX;

            $sql = "
            INSERT INTO
            user_roles (rolename, perms, description, custom, classification, created, updated)
            VALUES ('".$role_name."', '".$perms."', '".$role_description."', 1, ".$classification.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action($current_user, 'create', 'Created access role: '.$role_name);
                echo 'success';exit;
            } else {
                echo 'There was an error creating this new access role. Please try again.'; exit;
            }

            break;

        // CREATE GROUP
        case 'group':
            if (trim($_POST['groupname']) == '') {
                echo 'You forgot to give this group a name!'; exit;
            }
            $gname = mysqli_real_escape_string($con->gate, trim($_POST['groupname']));
            if (trim($_POST['groupdesc']) == '') {
                echo 'Just a short description...come on!'; exit;
            }
            $gdesc = mysqli_real_escape_string($con->gate, trim($_POST['groupdesc']));

            $sql = "INSERT INTO
            groups (gname, description, image, owner, created, updated)
            VALUES ('".$gname."', '".$gdesc."', '', 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action($current_user, "create", $current_user->name().' created a new group: '.$gname);
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application failed to create group: '.$gname
                );
                echo 'Uh oh, there was an error inserting the group into the database.'; exit;
            }
            break;

        // CREATE GROUP IMAGE
        case 'group-image':
            // TODO: Create group image avatar
            break;

        // CREATE PROJECT
        case 'project':
            if (trim($_POST['projectname']) == '') {
                echo 'Uh oh, you forgot to name your project!'; exit;
            }
            $pname = mysqli_real_escape_string($con->gate, trim($_POST['projectname']));
            if (trim($_POST['projectdesc']) == '') {
                echo 'Uh oh, you forgot to give your project a description!'; exit;
            }
            $pdesc = mysqli_real_escape_string($con->gate, trim($_POST['projectdesc']));
            if (trim($_POST['startdate']) == '') {
                echo 'Please enter an approximate start date. You can always change it later.'; exit;
            }
            $start = new DateTime(trim($_POST['startdate']));
            if (trim($_POST['enddate']) == '') {
                echo 'Please enter an approximate end date. You can always change it later.'; exit;
            }
            $end = new DateTime(trim($_POST['enddate']));

            $sql = "INSERT INTO
            projects (owner, pname, description, startdate, enddate, active, complete, created, updated)
            VALUES (0, '".$pname."', '".$pdesc."', '".$start->format('Y-m-d')."', '".$end->format('Y-m-d')."', 1, 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec       = $con->gate->query($sql);
            $insertid   = $con->insert_id();
            if ($exec) {
                ProjectHistory::log_action(
                    $current_user,
                    $insertid,
                    'create',
                    'Project created'
                );
                ActivityLog::log_action(
                    $current_user,
                    "create",
                    $current_user->name().' created a new project: '.$pname.' that starts on '.$start->format('F d, Y').' and ends on '.$end->format('F d, Y')
                );
                // Check to see if user selected to assign their self to the project at creation time
                if (isset($_POST['assignMe'])) {
                    $sql = "INSERT INTO project_members (memberid, projectid, is_group, created, updated) VALUES (".$current_user->id().", ".$insertid.", 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        ProjectHistory::log_action(
                            $current_user,
                            $insertid,
                            'assign',
                            $current_user->name() . " assigned to this project by ".$current_user->name()
                        );
                    }
                }
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application failed to create new project: '.$pname
                );
                echo 'Oh no! There was an error entering this project into your database. Please try again. If this error continually occurs, please contact support.'; exit;
            }

            break;

        // CREATE PROJECT IMAGE
        case 'project-image':

            if (trim($_POST['project']) == '') {
                $system_notification->queue('Upload Error', 'No project was passed to the application for processing'); break;
            }
            $project = Project::find('id', trim($_POST['project']));

            if (!$project) {
                $system_notification->queue('Upload Error','The project you are uploading this image for has been removed or could not be found'); break;
            }

            if (empty($_FILES)) {
                $system_notification->queue('Upload Error', 'No file was selected to be uploaded'); break;
            }

            $upload = File::upload(
                'project',
                $_FILES,
                array(
                    'project'   => $project,
                    'user'      => $current_user
                )
            );

            if (empty($upload)) {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application failed to upload a project image for project: '.$project->name()
                );
                $system_notification->queue('Unable to process Request', 'Something went wrong on the inside!');
            } else {
                $system_notification->queue($upload['title'], $upload['message']);
            }

            break;

        // CREATE FILE
        case 'file':
            demo_die();
            // Check for modifier submission first
            if (isset($_POST['modifier'])) {
                // Check for the stagetask
                if (!isset($_POST['stagetask'])) {
                    $system_notification->queue('Upload Error', 'There was a problem with the way this form was submitted'); break;
                } else {
                    $stagetaskid = mysqli_real_escape_string($con->gate, trim($_POST['stagetask']));
                    $stagetask = StageTask::find('id', $stagetaskid);
                    if (!$stagetask) {
                        $system_notification->queue('Upload Error', 'The staged task this attachment was intended for could not be located'); break;
                    }
                }
                $modifier = true;
            } else {
                $modifier = false;
            }

            if (trim($_POST['directory']) == '') {
                $directory = '';
            } else {
                $directory = $con->secure($_POST['directory']);
                $directory = trim($directory, '/');
            }

            $destination = FILES_PATH .'users/'.$current_user->id().'/'.$directory;

            if (trim($_POST['filename']) == '') {
                $system_notification->queue('Upload Error', 'You must give the file a name');
                break;
            }
            $filename = mysqli_real_escape_string($con->gate, trim($_POST['filename']));
            if (strlen($filename) > 50) {
                $system_notification->queue('Upload Error', 'The name of the file is greater than 50 characters');
                break;
            }
            if (trim($_POST['filedesc']) == '') {
                $system_notification->queue('Upload Error', 'You must give the file a brief description');
                break;
            }
            $filedesc = mysqli_real_escape_string($con->gate, trim($_POST['filedesc']));

            $isdocument = mysqli_real_escape_string($con->gate, $_POST['isdoc']);
            if ($isdocument == 1) {
                $document = true;
            } else {
                $document = false;
            }

            if (empty($_FILES)) {
                $system_notification->queue('Upload Error', 'No file was selected to be uploaded'); break;
            }

            if (!File::acceptable_upload($_FILES['upload']['name'])) {
                ActivityLog::log_action(
                    $current_user,
                    "security-warning",
                    $current_user->name().' tried uploading a file type that is currently not allowed by the application. Filename: '.$_FILES['upload']['name']
                );
                $system_notification->queue('Upload Error', 'The file type of the uploaded file is not allowed'); break;
            }

            if ($document) {
                // If this is a document then find out if it is an acceptable document
                if (!File::acceptable_upload($_FILES['upload']['name'], 'document')) {
                    ActivityLog::log_action(
                        $current_user,
                        "security-warning",
                        $current_user->name().' tried uploading a document file type that is currently not allowed by the application. Filename: '.$_FILES['upload']['name']
                    );
                    $system_notification->queue('Upload Error', 'The document file type of the uploaded file is not allowed'); break;
                }
            }

            $file           = $_FILES['upload']['name'];
            $type           = $_FILES['upload']['type'];
            $tmpname        = $_FILES['upload']['tmp_name'];
            $size           = $_FILES['upload']['size']; // Integer in bytes

            // Create User Folder if it isn't created already

            move_uploaded_file($tmpname, $destination.$file);

            // Double check that file was moved
            if (file_exists($destination.$file)) {

                $sql = "
                INSERT INTO
                files (filename, description, type, filesize, location, trashcan, uploadedby, isdocument, created, updated)
                VALUES ('".$filename."', '".$filedesc."', '".$type."', ".$size.", '".$destination.$file."', 0, ".$current_user->id().", ".$isdocument.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                $exec = $con->gate->query($sql);
                if ($exec) {
                    // Get insert id of last file
                    $lastid = $con->insert_id();
                    // Check divergent attachment modifier path
                    if ($modifier) {
                        // Update stage task attachments
                        $sql    = "INSERT INTO project_staged_task_attachments (stage_task_id, file_id, created, updated) VALUES (".$stagetask->id().", ".$lastid.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                        $exec   = $con->gate->query($sql);
                        if ($exec) {
                            $system_notification->queue('Attachment Successful', 'You have successfully uploaded the file and attached it to the task'); break;
                        } else {
                            $system_notification->queue('Attachment Unsuccessful', 'The file was uploaded but could not be attached. Please manually attach from the files menu.'); break;
                        }
                    } else {
                        if (!DEMO) {
                            ActivityLog::log_action(
                                $current_user,
                                "upload",
                                $current_user->name().' successfully uploaded a new file: '.$file
                            );
                        } else {
                            ActivityLog::log_action(
                                $current_user,
                                "upload",
                                $current_user->name().' successfully uploaded a new file: '.$file.' stored in:'.$destination.$file
                            );
                        }
                        $system_notification->queue('Upload Successful', 'You have successfully uploaded the file');
                        break;
                    }
                } else {
                    $system_notification->queue('Upload Error', 'Oh no! The file may have been uploaded but the application was unable to update the database'); break;
                }
            } else {
                $system_notification->queue('Upload Error', 'Oh no! The file could not be moved to the new location. Please ensure your files folder is writable'); break;
            }


            break;

        // CREATE DIRECTORY
        case 'directory':

            if (trim($_POST['current_directory']) == '') {
                $current_directory = ROOT_PATH . 'files/users/'.$current_user->id().'/';
            } else {
                $current_directory = ROOT_PATH . 'files/users/'.$current_user->id().'/' . ltrim($_POST['current_directory']).'/';
            }
            if (trim($_POST['dir_name']) == '') {
                echo 'You must specify a name for this directory'; exit;
            }
            $dir_name = $con->secure(trim($_POST['dir_name']));
            $do = mkdir($current_directory.$dir_name, 0755, true);
            if ($do) {
                if (!DEMO) {
                    ActivityLog::log_action(
                        $current_user,
                        'create',
                        $current_user->name() . ' created a new directory in their personal file folder:'.$current_directory.$dir_name
                    );
                } else {
                    ActivityLog::log_action(
                        $current_user,
                        'create',
                        $current_user->name() . ' created a new directory in their personal file folder.'
                    );
                }

                echo 'success'; exit;
            } else {
                if (!DEMO) {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        $current_user->name() . ' was unable to create a new directory in their personal file folder:'.$current_directory.$dir_name
                    );
                } else {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        $current_user->name() . ' was unable to create a new directory in their personal file folder.'
                    );
                }

                echo 'Directory could not be created'; exit;
            }
            break;

        // CREATE GROUP FORUM POST
        case 'group-forum-post':
            if (!isset($_POST['group'])) {
                echo 'Form was improperly submitted'; exit;
            }
            $groupid = mysqli_real_escape_string($con->gate, trim($_POST['group']));
            $group = Group::find('id', $groupid);
            if (!$group) {
                echo 'The Group you are trying to post this to could not be located'; exit;
            }
            if (trim($_POST['postsubject']) == '') {
                echo 'You must provide a subject for your post'; exit;
            }
            $subject    = mysqli_real_escape_string($con->gate, trim($_POST['postsubject']));

            if (trim($_POST['postbody']) == '') {
                echo 'You must enter a message'; exit;
            }
            $body       = mysqli_real_escape_string($con->gate, trim($_POST['postbody']));

            if (isset($_POST['sticky'])) {
                if (trim($_POST['sticky']) == 1) {
                    $sticky = 1;
                } else {
                    $sticky = 0;
                }
            } else {
                $sticky = 0;
            }

            if (isset($_POST['reply'])) {
                $reply      = 1;
                $replyto    = mysqli_real_escape_string($con->gate, trim($_POST['post']));
            } else {
                $reply = 0;
                $replyto = 0;
            }

            $author = $current_user->id();
            $posted = $now->format('Y-m-d H:i:s');

            $sql = "
            INSERT INTO
            group_forum_posts (groupid, postsubject, postbody, author, isreply, replyto, sticky, created, updated)
            VALUES (".$group->id().", '".$subject."', '".$body."', ".$author.", ".$reply.", ".$replyto.", ".$sticky.", '".$posted."', '".$posted."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    $current_user->name() .' created a post: '.$subject.' in the '.$group->name().' forum'
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was a problem communicating with the database. Please try again. If this error persists, please contact support.'; exit;
            }
            break;

        // CREATE PROJECT FORUM POST
        case 'project-forum-post':
            if (!isset($_POST['project'])) {
                echo 'Form was improperly submitted'; exit;
            }
            $projectid = mysqli_real_escape_string($con->gate, trim($_POST['project']));
            $project = Project::find('id', $projectid);
            if (!$project) {
                echo 'The project you are trying to post this to could not be located'; exit;
            }
            if (trim($_POST['postsubject']) == '') {
                echo 'You must provide a subject for your post'; exit;
            }
            $subject    = mysqli_real_escape_string($con->gate, trim($_POST['postsubject']));

            if (trim($_POST['postbody']) == '') {
                echo 'You must enter a message'; exit;
            }
            $body       = mysqli_real_escape_string($con->gate, trim($_POST['postbody']));

            if (isset($_POST['sticky'])) {
                if (trim($_POST['sticky']) == 1) {
                    $sticky = 1;
                } else {
                    $sticky = 0;
                }
            } else {
                $sticky = 0;
            }

            if (isset($_POST['reply'])) {
                $reply      = 1;
                $replyto    = mysqli_real_escape_string($con->gate, trim($_POST['post']));
            } else {
                $reply = 0;
                $replyto = 0;
            }

            $author = $current_user->id();
            $posted = $now->format('Y-m-d H:i:s');

            $sql = "
            INSERT INTO
            project_forum_posts (projectid, postsubject, postbody, author, isreply, replyto, sticky, created, updated)
            VALUES (".$project->id().", '".$subject."', '".$body."', ".$author.", ".$reply.", ".$replyto.", ".$sticky.", '".$posted."', '".$posted."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'post',
                    $current_user->name() .' created a post: '.$subject.' in the '.$project->name().' forum'
                );
                ProjectHistory::log_action(
                    $current_user,
                    $project->id(),
                    'post',
                    "Posted a message '".$subject."' to the forum"
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application failed to process user\'s request to create a new post in the '.$project->name().' project forum'
                );
                echo 'Oh no! There was a problem communicating with the database. Please try again. If this error persists, please contact support.'; exit;
            }
            break;

        // CREATE PROJECT STAGE
        case 'stage':
            if (!isset($_POST['project'])) {
                echo 'Oh no! There was no project submitted to add this stage to!'; exit;
            }
            $project = Project::find('id', mysqli_real_escape_string($con->gate, trim($_POST['project'])));
            if (!$project) {
                echo 'Oh no! The project you are trying to add this stage to does not exist!'; exit;
            }
            if (trim($_POST['stagename']) == '') {
                echo 'Please enter a name for this stage.';exit;
            }
            $stagename = mysqli_real_escape_string($con->gate, trim($_POST['stagename']));
            if (trim($_POST['stagedesc']) == '') {
                echo 'Please enter an overview for this stage.';exit;
            }
            $stagedesc = mysqli_real_escape_string($con->gate, trim($_POST['stagedesc']));
            if (trim($_POST['duedate']) == '') {
                $duedate = '0000-00-00';
            } else {
                $duedateobject = new DateTime(trim($_POST['duedate']));
                $duedate = $duedateobject->format('Y-m-d');
            }
            $sql = "INSERT INTO
            project_stages (projectid, stagename, description, stagebrief, duedate, complete, created, updated)
            VALUES (".$project->id().", '".$stagename."', '".$stagedesc."', '', '".$duedate."', 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    $current_user->name() . ' created a new stage named "'.$stagename.'" for the project: '.$project->name()
                );
                ProjectHistory::log_action(
                    $current_user,
                    $project->id(),
                    'create',
                    $current_user->name() . ' created a new stage named "'.$stagename.'"'
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application failed to create a new stage in project: '.$project->name()
                );
                echo 'Oh no! There was an error entering this stage into your database. Please try again. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // CREATE PROJECT STAGE BRIEF
        // TODO: Still points to older date-based file system
        case 'stage-brief':

            demo_die();

            if (trim($_POST['stage']) == '') {
                $system_notification->queue('Upload Error', 'No project stage was passed to the application for processing');
                break;
            }
            $stage = ProjectStage::find('id', trim($_POST['stage']));
            if (!$stage) {
                $system_notification->queue('Upload Error','The project stage you are uploading this brief for has been removed or could not be found');
                break;
            }
            if (empty($_FILES)) {
                $system_notification->queue('Upload Error', 'No file was selected to be uploaded');
                break;
            }

            if (!File::acceptable_upload($_FILES['stagebrief']['name'], 'document')) {
                ActivityLog::log_action(
                    $current_user,
                    'security-warning',
                    'Tried to upload a file-type that is not allowed: '.$_FILES['stagebrief']['name']
                );
                $system_notification->queue('Upload Error', 'The document type you have selected is not allowed');
                break;
            }

            $name_array  = explode('.', $_FILES['stagebrief']['name']);
            $extension   = array_pop($name_array);


            $name       = str_replace(' ', '-', $stage->name());
            $file       = $name.'-Stage-Brief.'.$extension;
            $type       = $_FILES['stagebrief']['type'];
            $tmpname    = $_FILES['stagebrief']['tmp_name'];
            $size       = $_FILES['stagebrief']['size']; // Integer in bytes

            // Create Folder if it isn't created already i.e. 01-Feb-2015
            $date_folder = $now->format('d-M-Y').'/';
            if (file_exists(FILES_PATH.$date_folder)) {
                // Move file and check that it exists
                move_uploaded_file($tmpname, FILES_PATH.$date_folder.$file);
            } else {
                $new_dir = mkdir(FILES_PATH.$date_folder, 0755);
                if ($new_dir) {
                    move_uploaded_file($tmpname, FILES_PATH.$date_folder.$file);
                } else {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        'Upload Error: Unable to create directory while uploading stage brief file'
                    );
                    $system_notification->queue('Upload Error', 'Entity was unable to create a directory to store your stage brief. Please check that the files folder is writable.');
                    break;
                }
            }
            // Double check that file was moved
            if (file_exists(FILES_PATH.$date_folder.$file)) {

                $sql = "UPDATE project_stages SET stagebrief = '".$date_folder.$file."' WHERE id = ".$stage->id();

                $exec = $con->gate->query($sql);
                if ($exec) {
                    $system_notification->queue('Upload Successful', 'You have successfully uploaded / replaced the stage brief');
                    ActivityLog::log_action(
                        $current_user,
                        'create',
                        $current_user->name() . ' created a new stage brief file for ' . $stage->name()
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $stage->project->id(),
                        'create',
                        $current_user->name() . ' created a new stage brief file for ' . $stage->name()
                    );
                } else {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        'Upload Error: Database error while logging file record into database'
                    );
                    $system_notification->queue(
                        'Upload Error',
                        'Oh no! The stage brief may have been uploaded but the application was unable to update the database'
                    );
                }
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Upload Error: Unable to move file to default file location'
                );
                $system_notification->queue('Upload Error', 'Oh no! The file could not be moved to the new location. Please ensure your files folder is writable');

            }

            break;

        // CREATE PROJECT STAGE TASK
        case 'stage-task':
            if (trim($_POST['stage']) == '') {
                echo 'No stage was passed to the application for processing'; exit;
            }
            if (ProjectStage::find('id', $_POST['stage'])) {
                $stage = ProjectStage::find('id', trim($_POST['stage']));
            } else {
                echo 'The stage passed to the application does not exist!'; exit;
            }
            if (trim($_POST['stagetaskname'] == '')) {
                echo 'You must give the task a name!'; exit;
            }
            $stname = mysqli_real_escape_string($con->gate, trim($_POST['stagetaskname']));
            if (trim($_POST['stagetaskdesc']) == '') {
                echo 'Please give this task a short description. What needs to get done?'; exit;
            }
            $description = mysqli_real_escape_string($con->gate, trim($_POST['stagetaskdesc']));
            if (trim($_POST['duedate']) == '') {
                $duedatestring = '0000-00-00';
            } else {
                $duedate        = new DateTime(trim($_POST['duedate']));
                $duedatestring  = $duedate->format('Y-m-d');
            }

            $sql = "INSERT INTO
            project_staged_tasks (stageid, stagetaskname, description, duedate, complete, created, updated)
            VALUES (".$stage->id().", '".$stname."', '".$description."', '".$duedatestring."', 0,'".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                $stagetaskid = $con->insert_id();
                if (isset($_POST['user'])) {
                    foreach ($_POST['user'] as $user) {
                        $con->gate->query("
                        INSERT INTO project_staged_task_assignments (stagetaskid, userid, created, updated)
                        VALUES (".$stagetaskid.", ".$user.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')
                        ");
                    }
                }

                ActivityLog::log_action(
                    $current_user,
                    'create',
                    $current_user->name() . ' created a new task: '.$stname.' for stage: '.$stage->name() .' for project: '.$stage->project()->name()
                );
                ProjectHistory::log_action(
                    $current_user,
                    $stage->project->id(),
                    'create',
                    $current_user->name() . ' created a new task: '.$stname.' for stage: '.$stage->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application unable to create stage task for '.$stage->name().' in project '.$stage->project()->name()
                );
                echo 'Oh no! There was a problem entering this task into the stage. Please try again. If this error continues to occur please contact support.'; exit;
            }
            break;

        // CREATE PROJECT STAGE TASK NOTE
        case 'stage-task-note':
            if (trim($_POST['stagetask']) == '') {
                echo 'No task was passed to the application for processing'; exit;
            }
            $task = mysqli_real_escape_string($con->gate, trim($_POST['stagetask']));
            if (trim($_POST['notesubject']) == '') {
                echo 'You must enter a title / subject for this note'; exit;
            }
            $subject = mysqli_real_escape_string($con->gate, trim($_POST['notesubject']));
            if (trim($_POST['notebody']) == '') {
                echo 'You cannot create an empty note'; exit;
            }
            $body = mysqli_real_escape_string($con->gate, trim($_POST['notebody']));
            $user = $current_user->id();
            $created = $now->format('Y-m-d H:i:s');

            $sql = "INSERT INTO
            project_staged_task_notes (taskid, notesubject, notebody, userid, created, updated)
            VALUES (".$task.", '".$subject."', '".$body."', ".$user.", '".$created."', '".$created."')";

            $exec = $con->gate->query($sql);
            if ($exec) {
                $task = StageTask::find('id', $task);
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    'Created a stage task note for task: '.$task->name().' in the stage: '.$task->stage()->name()
                );
                ProjectHistory::log_action(
                    $current_user,
                    $task->stage()->project()->id(),
                    'create',
                    'Created a stage task note for task: '.$task->name().' in the stage: '.$task->stage()->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application unable to create a tage task note for '.$task->name().' in the stage '.$task->stage()->name().' for project '.$task->stage()->project()->name()
                );
                echo 'Oh no! There was a problem creating your note. Please try again. If this error continues to occur please contact support.'; exit;
            }
            break;

        // CREATE PROJECT STAGE TASK ATTACHMENT
        case 'stage-task-attachment':
            if (!isset($_POST['selectedFiles'])) {
                echo 'No files were selected'; exit;
            }
            $selectedfiles = $_POST['selectedFiles'];

            if (!isset($_POST['stagetask'])) {
                echo 'No staged task was selected to attach these files to'; exit;
            }
            $stagetaskid    = mysqli_real_escape_string($con->gate, $_POST['stagetask']);
            $stagetask      = StageTask::find('id', $stagetaskid);
            if (!$stagetask) {
                echo 'The staged task you are trying to attach these items to could not be found'; exit;
            }

            foreach ($selectedfiles as $fileid) {
                $file = File::find('id', $fileid);
                if (!$file) {
                    echo 'One or more of the files you have selected could not be found for attachment'; exit;
                }
                unset($file);
            }

            // Update stage task attachments
            foreach ($selectedfiles as $fileID) {
                // Check for duplicate
                $duplicates = gen_query("SELECT file_id FROM project_staged_task_attachments WHERE file_id = ".$fileID." AND stage_task_id = ".$stagetask->id());
                if ($duplicates['count'] > 0) {
                    continue;
                } else {
                    $sql = "INSERT INTO project_staged_task_attachments (stage_task_id, file_id, created, updated) VALUES (".$stagetask->id().", ".$fileID.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        continue;
                    } else {
                        echo 'Unable to attach one or more files'; exit;
                    }
                }
            }
            echo 'success'; exit;
            break;

        // CREATE PROJECT TASK
        case 'project-task':

            break;

        // CREATE WAGE
        case 'wage':
            if (trim($_POST['billingcode']) == '') {
                echo 'You must enter a unique billing code for this wage'; exit;
            }
            $billing = mysqli_real_escape_string($con->gate, trim($_POST['billingcode']));
            $duplicate_billing = Wage::find('sql', "SELECT * FROM wages WHERE billingcode = '".$billing."'");
            if ($duplicate_billing) {
                echo 'This billing code is already in use!'; exit;
            }
            if (trim($_POST['wname']) == '') {
                echo 'You must enter a name for this wage'; exit;
            }
            $name = mysqli_real_escape_string($con->gate, trim($_POST['wname']));
            if (trim($_POST['wdesc']) == '') {
                echo 'You must enter a short description for this wage'; exit;
            }
            $description = mysqli_real_escape_string($con->gate, trim($_POST['wdesc']));
            if (trim($_POST['wrate'] == '' && !is_numeric(trim($_POST['wrate'])))) {
                echo 'Please enter a numeric value for this wage\'s rate'; exit;
            }
            $rate = mysqli_real_escape_string($con->gate, trim($_POST['wrate']));
            if (!isset($_POST['flat']) || trim($_POST['flat']) == 0) {
                $flat = 0;
            } else {
                $flat = 1;
            }

            $sql = "
            INSERT INTO
            wages (billingcode, wname, wdesc, is_flat, wrate, created, updated)
            VALUES ('".$billing."', '".$name."', '".$description."', ".$flat.", ".$rate.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                switch($flat) {

                    case 1:
                        ActivityLog::log_action($current_user, 'create', $current_user->name() .' created flat-rate wage: '.$name.' @ '.number_format($rate,2));
                        break;

                    case 0:
                        ActivityLog::log_action($current_user, 'create', $current_user->name() .' created hourly wage: '.$name.' @ '.number_format($rate,2));
                        break;

                }
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was a problem creating the new wage. Please try again. If this error continues to occur please contact support.'; exit;
            }
            break;

        // CREATE TAX
        case 'tax':
            if (trim($_POST['taxname']) == '') {
                echo 'Please enter a name for this tax'; exit;
            }
            $name = mysqli_real_escape_string($con->gate, trim($_POST['taxname']));
            if (trim($_POST['taxdesc']) == '') {
                echo 'Please enter a short description for this task'; exit;
            }
            $description = mysqli_real_escape_string($con->gate, trim($_POST['taxdesc']));
            if (!is_numeric(trim($_POST['taxrate'])) || trim($_POST['taxrate']) == '') {
                echo 'Please ensure you entered a tax rate that is numerical'; exit;
            }
            $rate = mysqli_real_escape_string($con->gate, trim($_POST['taxrate']));
            $date = $now->format('Y-m-d H:i:s');
            $sql = "
            INSERT INTO
            taxes (tname, tdesc, tpercent, created, updated)
            VALUES ('".$name."', '".$description."', ".$rate.", '".$date."', '".$date."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action($current_user, 'create', $current_user->name().' created tax: '.$name.' @ '.$rate.'%');
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was a problem creating the new tax. Please try again. If this error continues to occur please contact support.'; exit;
            }
            break;

        // CREATE INVOICE
        case 'invoice':
            if (trim($_POST['invname']) == '') {
                echo 'Uh oh, you forgot to name your invoice!'; exit;
            }
            $name = mysqli_real_escape_string($con->gate, trim($_POST['invname']));
            if (trim($_POST['invdesc']) == '') {
                $description = '';
            } else {
                $description = mysqli_real_escape_string($con->gate, trim($_POST['invdesc']));
            }
            if (trim($_POST['paidby']) == '') {
                echo 'There was no one selected to pay this invoice!'; exit;
            } else {
                $paidby = mysqli_real_escape_string($con->gate, trim($_POST['paidby']));
            }
            if (trim($_POST['paidto']) == '') {
                echo 'There was no one selected to get paid for this invoice!'; exit;
            } else {
                $paidto = mysqli_real_escape_string($con->gate, trim($_POST['paidto']));
            }

            if ($current_user->role()->is_staff()) {
                if (trim($_POST['for-company']) == 1) {
                    $display_company = 1;
                } else {
                    $display_company = 0;
                }
            } else {
                $display_company = 0;
            }

            if (trim($_POST['currency']) == '') {
                $currency = '1';
            } else {
                $currency = mysqli_real_escape_string($con->gate, trim($_POST['currency']));
            }
            if (trim($_POST['duedate']) == '') {
                $duedate = '0000-00-00';
            } else {
                try {
                    $duedate = new DateTime(trim($_POST['duedate']));
                } catch (Exception $e) {
                    echo 'The date you provided was not recognized by the application'; exit;
                }
                if ($duedate < $now) {
                    echo "The due date of the invoice must fall after today's date!"; exit;
                }
                $duedate = $duedate->format('Y-m-d');
            }
            $project = mysqli_real_escape_string($con->gate, trim($_POST['iproject']));
            if ($project != 0) {
                // An actual project was passed and needs further validation
                $projectvalidation = Project::find('id', $project);
                if (!$projectvalidation) {
                    echo 'The project you are trying to associate this invoice to no longer exists'; exit;
                }
                if ($projectvalidation->is_complete()) {
                    echo 'The project you are trying to associate this invoice is already complete'; exit;
                }
                if (!$projectvalidation->is_user_assigned($paidby)) {
                    echo 'The paying user is not assigned to the project you are trying to associate this invoice with'; exit;
                }
            }

            // Generate invoice number
            $date   = $now->format('Ymd-');
            $number = 1;
            $string = $date.$number;

            $record = gen_query("SELECT id FROM invoices WHERE inumber = '".$string."'");
            if ($record['count'] > 0) {
                do {
                    $number++;
                    $string = $date.$number;
                    $record = gen_query("SELECT id FROM invoices WHERE inumber = '".$string."'");
                } while($record['count'] > 0);
            }
            $invoice_number = $date.$number;
            $sql = "INSERT INTO
            invoices (inumber, iproject, iname, inotes, currencyid, displaycompany, paidby, paidto, duedate, created_by, paid, created, updated)
            VALUES ('".$invoice_number."', ".$project.", '".$name."', '".$description."', ".$currency.", ".$display_company.", ".$paidby.", ".$paidto.", '".$duedate."', ".$current_user->id().", 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                $payor = User::find('id', $paidby);
                $payee = User::find('id', $paidto);
                if (!$payor || !$payee) {
                    $message = 'Created an invoice: '.$invoice_number;
                } else {
                    $message = 'Created an invoice: '.$invoice_number.' paid by: '.$payor->name().' and paid to: '.$payee->name();
                }
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    $message
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error entering this invoice into your database. Please try again. If this error continually occurs, please contact support. '.$con->gate->error; exit;
            }
            break;

        // CREATE INVOICE CHARGE
        case 'invoice-charge':
            $invoice = Invoice::find('id', trim($_POST['invoice']));
            if (!$invoice) {
                echo 'Invoice provided could not be found'; exit;
            }
            if (trim($_POST['chargename']) == '') {
                echo 'You must give the charge a name'; exit;
            }
            $name = mysqli_real_escape_string($con->gate, trim($_POST['chargename']));
            if (trim($_POST['chargedesc']) == '') {
                echo 'Give the charge a quick and to the point description'; exit;
            }
            $desc = mysqli_real_escape_string($con->gate, trim($_POST['chargedesc']));

            if ($_POST['manualToggle'] == 1) {
                if (!isset($_POST['wage'])) {
                    echo 'There are no wages to create this charge'; exit;
                }
                // Manually entered pathway
                $wageid   = mysqli_real_escape_string($con->gate, trim($_POST['wage']));
                $units  = round(mysqli_real_escape_string($con->gate, trim($_POST['units'])), 2);
                if ($units <= 0) {
                    echo 'You must provide a positive value for units'; exit;
                }
                if (!is_numeric($units)) {
                    echo 'Units provided must be numeric';exit;
                }
                $wage = Wage::find('id', $wageid);
                if (!$wage) {
                    echo 'Unable to locate the selected wage in the database'; exit;
                }

                // Conversion
                $amount = Currency::convert($units, $wage->unformed_rate(), $invoice->currency()->rate());

                $sql = "
                INSERT INTO
                invoice_charges (invoiceid, cname, cdesc, is_manual, attachedtime, amount, created, updated)
                VALUES (".$invoice->id().", '".$name."', '".$desc."', 1, '', ".$amount.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            } else {
                // Using time entries
                if (!isset($_POST['timerItem'])) {
                    echo 'No time was passed to create this charge'; exit;
                }

                if (empty($_POST['timerItem'])) {
                    echo 'No time was passed to create this charge'; exit;
                }

                $timers = array();
                foreach ($_POST['timerItem'] as $timer) {
                    $check = Timer::find('id', $timer);
                    if (!$check) {
                        echo 'One or more of the time entries you selected could not be found in the database'; exit;
                    } else {
                        $timers[] = $check;
                    }
                }
                $amount = 0;
                foreach ($timers as $timer) {
                    $amount += $timer->calculate_rate($timer->wage()->unformed_rate());
                    $con->gate->query("UPDATE timer_items SET closed = 1 WHERE id = ".$timer->id());
                }

                $timers_string = implode(',', $_POST['timerItem']);

                $sql = "
                INSERT INTO
                invoice_charges (invoiceid, cname, cdesc, is_manual, attachedtime, amount, created, updated)
                VALUES (".$invoice->id().", '".$name."', '".$desc."', 0, '".$timers_string."', ".$amount.", '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            }

            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Oh no! Something went wrong while trying to communicate with the database'; exit;
            }
            break;

        // CREATE TIMER ITEM
        case 'timer-item':
            $user       = $current_user->id();
            $billable   = mysqli_real_escape_string($con->gate, trim($_POST['billable']));
            $rate       = mysqli_real_escape_string($con->gate, trim($_POST['rate']));
            $project    = mysqli_real_escape_string($con->gate, trim($_POST['project']));
            if (!Project::find('id', $project)) {
                echo 'The project you submitted your time for no longer exists or could not be found'; exit;
            }
            $note = mysqli_real_escape_string($con->gate, trim($_POST['notes']));
            if (!is_numeric(trim($_POST['hours']))) {
                echo 'The hours submitted must be in the form of a number'; exit;
            } else {
                $hours = (int) mysqli_real_escape_string($con->gate, trim($_POST['hours']));
            }
            if (!is_numeric(trim($_POST['minutes']))) {
                echo 'The minutes submitted must be in the form of a number'; exit;
            } else {
                $minutes = (int) mysqli_real_escape_string($con->gate, trim($_POST['minutes']));
            }
            if (!is_numeric(trim($_POST['seconds']))) {
                echo 'The seconds submitted must be in the form of a number'; exit;
            } else {
                $seconds = (int) mysqli_real_escape_string($con->gate, trim($_POST['seconds']));
            }
            $date = $now->format('Y-m-d H:i:s');

            $sql = "
            INSERT INTO
            timer_items(tuser, billable, trate, tproject, tnote, thours, tminutes, tseconds, created, updated)
            VALUES (".$user.", ".$billable.", ".$rate.", ".$project.", '".$note."', ".$hours.", ".$minutes.", ".$seconds.", '".$date."', '".$date."')";
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    'Recorded '.$hours.' hours and '.$minutes.' minutes of work'
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error entering your time. Please try entering it manually. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // CREATE MESSAGE
        case 'message':
            if (trim($_POST['addressbook']) == '') {
                echo 'You must select a user to send your message to'; exit;
            }
            $to = User::find('id', trim($_POST['addressbook']));
            if (!$to) {
                echo 'Uh oh, the user you have selected to send a message to could not be located in the database'; exit;
            }
            if (trim($_POST['messagesubject']) == '') {
                echo 'You must enter a subject for your message'; exit;
            }
            $subject = mysqli_real_escape_string($con->gate, trim($_POST['messagesubject']));
            if (trim($_POST['messagebody']) == '') {
                echo 'You cannot send a message without content'; exit;
            }
            $body = mysqli_real_escape_string($con->gate, trim($_POST['messagebody']));

            $sql = "
            INSERT INTO
            messages (msubject, mbody, sender, recipient, viewed, trashcan, isreply, replyto, created, updated)
            VALUES ('".$subject."', '".$body."', ".$current_user->id().", ".$to->id().", 0, 0, 0, 0, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error sending your message. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // CREATE FORM
        case 'form':
            if (trim($_POST['form-name']) == '') {
                echo 'You must give your form a name'; exit;
            }
            $form_name = $con->secure($_POST['form-name']);
            if (trim($_POST['form-desc']) == '') {
                echo 'You must give this form a description'; exit;
            }
            $form_desc = $con->secure($_POST['form-desc']);

            // Create form slug
            $slug = strtolower(trim($form_name));
            $slug = str_replace(" ", '-', $slug);
            $slug = preg_replace("/[^-A-Za-z0-9]/", "", $slug);
            $slug = trim($slug, '-');

            // TODO: Create folder in files/forms based on slug

            $sql = "INSERT INTO forms (name, slug, description, published, created, updated) VALUES ('".$form_name."', '".$slug."', '".$form_desc."', 1, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    'Created form: '.$form_name
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no, there was an error creating your form'; exit;
            }

            break;

        // CREATE FORM FIELD
        case 'form-field':
            $form = Form::find('id', $con->secure($_POST['parent']));
            if (!$form) {
                echo 'The form you are trying to submit this field to could not be located'; exit;
            }
            if (trim($_POST['field-label']) == '') {
                echo 'You must give this field a label'; exit;
            }
            $label = $con->secure($_POST['field-label']);
            if (trim($_POST['field-name']) == '') {
                echo 'You must give the field a name that contains only letters and dashes'; exit;
            }
            $name = strtolower($con->secure($_POST['field-name']));
            if (trim($_POST['field-type']) == '') {
                echo 'Please select a field type for your new field'; exit;
            }
            $type = $con->secure($_POST['field-type']);

            if (trim($_POST['field-placeholder']) == '') {
                $nullPlaceholder = true;
                $placeholder     = "";
            } else {
                $nullPlaceholder = false;
                $placeholder = $con->secure($_POST['field-placeholder']);
            }


            if (!isset($_POST['field-required'])) {
                echo 'Is this field required'; exit;
            }
            $required = $con->secure($_POST['field-required']);

            if ($nullPlaceholder) {
                $sql = "INSERT INTO
            form_fields (field_parent, field_label, field_name, field_type, field_required, field_position)
            VALUES (".$form->id().", '".$label."', '".$name."', '".$type."', ".$required.", 1)";
            } else {
                $sql = "INSERT INTO
            form_fields (field_parent, field_label, field_name, field_type, field_placeholder, field_required, field_position)
            VALUES (".$form->id().", '".$label."', '".$name."', '".$type."', '".$placeholder."', ".$required.", 1)";
            }

            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    'Created a new '.$type.' field for form: '.$form->name()
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error adding this field ot the desired form'; exit;
            }
            break;

        // CREATE FORM FIELD OPTION
        case 'form-field-option':
            if (!isset($_POST['field'])) {
                echo 'No field was passed to the application for processing'; exit;
            }
            $field  = FormField::find('id', $con->secure($_POST['field']));

            if (!$field) {
                echo 'The field passed to the application could not be found in the database'; exit;
            }

            if (trim($_POST['option-label']) == '') {
                echo 'The option must have a human readable label'; exit;
            }
            $label = $con->secure($_POST['option-label']);
            if (trim($_POST['option-value']) == '') {
                echo 'The option must have a value'; exit;
            }
            $value = $con->secure($_POST['option-value']);

            $sql = "INSERT INTO
            form_field_options (option_parent, option_label, option_value, selected, option_position, created, updated)
            VALUES (".$field->id().", '".$label."', '".$value."', 0, 1, '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')";

            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'create',
                    'Added field option '.$label.' to field: '.$field->name()
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! The option could not be added to this form field. Please try again.'; exit;
            }
            break;

        // CREATE FORM SUBMISSION
        case 'anon-quote':

            // TODO: Implement debugging option
            //echo 'Form: <pre>';var_dump($_POST).'</pre><pre>'.var_dump($_FILES); exit;

            if (!isset($_POST['form'])) {
                echo 'Form could not be processed'; exit;
            }
            $form = Form::find('id', $con->secure($_POST['form']));
            if (!$form) {
                echo 'Form could not be located to process your request'; exit;
            }

            $fields = $form->get_fields();

            if (!$fields) {
                echo 'The form you are trying to submit has no fields associated with it'; exit;
            }

            $field_data = array();

            foreach ($fields as $field) {
                $fieldID    = $field->id();
                $name       = $field->name();
                $label      = $field->label();

                if ($field->required()) {
                    if (!isset($_POST[$name]) || trim($_POST[$name]) == '') {

                        switch ($field->type()) {

                            case 'input-file':
                                echo 'You must upload a file for '.$label; exit;
                                break;

                            case 'input-radio':
                                echo 'You must select one of the values for '.$label; exit;
                                break;

                            case 'input-checkbox':
                                echo 'You must select one of the values for '.$label; exit;
                                break;

                            default:
                                echo 'You must enter a value into '.$label; exit;

                        }

                    }
                }

                if ($field->type() == 'input-date') {
                    try {

                        $field_data[$fieldID] = new DateTime($con->secure($_POST[$name]));
                        $field_data[$fieldID] = $field_data[$fieldID]->format('F d, Y');

                    } catch (Exception $e) {

                        echo 'The date you provided for '.$label.' was not recognized'; exit;

                    }

                } elseif ($field->type() == 'input-email') {

                    if (!filter_var($_POST[$name], FILTER_VALIDATE_EMAIL)) {
                        echo "This ($_POST[$name]) email address is not a valid email address"; exit;
                    }

                    $field_data[$fieldID] = $con->secure($_POST[$name]);

                } elseif ($field->type() == 'input-file') {

                    if (isset($_FILES[$name])) {
                        $uploadAction = File::upload('form', $_FILES, array('user'  => $current_user, 'form' => $form->id(), 'field-name' => $name));
                        if (!empty($uploadAction)) {
                            $field_data[$fieldID] = $uploadAction['saved-name'];
                        }
                    } else {
                        $field_data[$fieldID] = "File not uploaded";
                    }


                } elseif (is_array($_POST[$name])) {

                    // Turn to comma separated values
                    $toString = implode(', ', $_POST[$name]);
                    $field_data[$fieldID] = $toString;

                } else {

                    $field_data[$fieldID] = $con->secure($_POST[$name]);

                }

            }

            // echo '<pre>';var_dump($field_data);exit;

            // Insert Submission record first
            $ip         = $_SERVER['REMOTE_ADDR'];
            $time       = new DateTime('Now');
            $sql_time   = $time->format('Y-m-d H:i:s');

            $sql            = "INSERT INTO form_submissions (ip_address, parent_form, status, created, updated) VALUES ('".$ip."', ".$form->id().", 0, '".$sql_time."', '".$sql_time."')";
            $exec           = $con->gate->query($sql);
            $submission_id  = $con->insert_id();

            foreach ($field_data as $key => $value) {

                $exec = $con->gate->query("
                INSERT INTO
                form_submission_fields (submission_id, parent_form, parent_field, submission_value, created, updated)
                VALUES ('".$submission_id."', ".$form->id().", ".$key.", '".$value."', '".$now->format('Y-m-d H:i:s')."', '".$now->format('Y-m-d H:i:s')."')"
                );

            }
            if ($exec) {
                if (APPROPRIATE && $app_owner && $app_settings->get('notify_when_user_quote') == 1) {
                    $to         = $app_owner->email();
                    $subject    = 'New Form Submission';

                    $headers    = "From: " . $app_owner->email() . "\r\n";
                    $headers   .= "Reply-To: ". $app_owner->email() . "\r\n";
                    $headers   .= "MIME-Version: 1.0\r\n";
                    $headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    $message    = "<p>Someone just filled out the form: ".$form->name()."! Log into your account to view the details of this submission.</p>";
                    $email      = new EmailTemplate('basic', array('title' => 'Someone just submitted a form!', 'message' => $message));

                    $send       = mail($to, $subject, $email->render(), $headers);
                }
            }
            if ($exec && $form->has_file_input()) {
                $system_notification->queue('Thank you', 'Your submission has been received!');
                break;
            } else {
                echo 'success'; exit;
            }
            unset($_POST);
            break;
    }

}
// END POST CREATE IF STATEMENT