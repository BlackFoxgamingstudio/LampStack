<?php
if (isset($_POST['delete'])) {

    switch($_POST['delete']) {

        // DELETE USER
        case 'user':
            if (!isset($_POST['userid'])) {
                echo 'No user was passed to the application for deletion'; exit;
            }
            $userid = mysqli_real_escape_string($con->gate, trim($_POST['userid']));
            $user   = User::find('id', $userid);
            if (!$user) {
                echo 'The user account you are trying to delete could not be located.'; exit;
            }
            $sql = "DELETE FROM users WHERE id = ".$user->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'delete',
                    'Deleted user: '.$user->name().' from the application'
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was a problem communicating to the database.'; exit;
            }
            break;

        // DELETE PROJECT
        case 'project':
            if (!isset($_POST['project'])) {
                echo 'Uh oh, there was a problem with your request. Please try again.'; exit;
            }
            $project = Project::find('id', mysqli_real_escape_string($con->gate, $_POST['project']));
            if (!$project) {
                echo 'The project could not be located.'; exit;
            }
            $sql = "DELETE FROM projects WHERE id = ".$project->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'delete',
                    'Project: ' .$project->name() . ' was deleted'
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error deleting this project. Please try again. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // DELETE PROJECT STAGE
        case 'stage':
            if (trim($_POST['stage']) == '') {
                echo 'There was no stage passed to the application for processing'; exit;
            }
            $stage = ProjectStage::find('id', mysqli_real_escape_string($con->gate, trim($_POST['stage'])));
            if (!$stage) {
                echo 'Stage could not be found. It may have already been deleted by another user'; exit;
            } else {
                $sql = "DELETE FROM project_stages WHERE id = ".$stage->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    ActivityLog::log_action(
                        $current_user,
                        'delete',
                        'Stage: ' . $stage->name() . ' was deleted from the project: ' . $stage->project()->name()
                    );
                    ProjectHistory::log_action(
                        $current_user,
                        $stage->project()->id(),
                        'delete',
                        'Stage: ' . $stage->name() . ' was deleted from the project'
                    );
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! There was an error deleting this stage. Please try again. If this error continually occurs, please contact support.'; exit;
                }
            }
            break;

        // DELETE PROJECT STAGE BRIEF
        case 'stage-brief':
            if (!isset($_POST['stage'])) {
                echo 'No stage was passed to the application for processing'; exit;
            }
            $stage = ProjectStage::find('id', $_POST['stage']);
            if (!$stage) {
                echo 'The stage provided could not be found'; exit;
            }
            $file = FILES_PATH.$stage->get_brief();
            $delete = unlink($file);

            $sql = "UPDATE project_stages SET stagebrief = '' WHERE id = ".$stage->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                $html = 'success';
                if (!$delete) {
                    $html .= 'Unable to remove the file from the server'; exit;
                }
                echo $html; exit;
            } else {
                echo 'Oh no! The stage brief could not be removed from the database';exit;
            }

            break;

        // DELETE PROJECT STAGE TASK
        case 'staged-task':
            if (trim($_POST['task']) == '') {
                echo 'No staged task was passed to the application for processing'; exit;
            }
            $task = mysqli_real_escape_string($con->gate, trim($_POST['task']));
            $stageTask = StageTask::find('id', $task);
            $sql = "DELETE FROM project_staged_tasks WHERE id = ".$task;
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'delete',
                    $stageTask->name() . ' was deleted from project '.$stageTask->stage()->project()->name() .' stage: '.$stageTask->stage()->name()
                );
                ProjectHistory::log_action(
                    $current_user,
                    $stageTask->stage()->project()->id(),
                    'delete',
                    $stageTask->name() . ' was deleted from stage: '.$stageTask->stage()->name()
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error deleting this staged task. Please try again. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // DELETE GROUP
        case 'group':
            if (trim($_POST['group']) == '') {
                echo 'No group was passed to the application for processing'; exit;
            }
            $groupID  = mysqli_real_escape_string($con->gate, trim($_POST['group']));

            $group    = Group::find('id', $groupID);
            if (!$group) {
                echo 'Group could not be found. It may have already been deleted by another user.'; exit;
            }
            $sql    = "DELETE FROM groups WHERE id = ".$group->id();
            $exec   = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action($current_user, 'delete', $group->name() . ' was deleted');
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was error deleting this group from the database. Please try again. If this error continually occurs, please contact support.'; exit;
            }
            break;

        // DELETE MESSAGE
        case 'message':
            if ($_POST['message'] == '') {
                echo 'No message was passed to the application for processing'; exit;
            }
            $messageid = mysqli_real_escape_string($con->gate, trim($_POST['message']));
            $message = Message::find('id', $messageid);
            if (!$message) {
                echo 'The message you are trying to delete could not be located'; exit;
            } else {
                $sql = "UPDATE messages SET trashcan = 1 WHERE id = ".$message->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    echo 'success'; exit;
                } else {
                    echo 'Oh no! The message could not be moved to the trashcan. Please try again. If this error continually occurs, please contact support.'; exit;
                }
            }
            break;

        // DELETE FILE
        case 'file':
            if (!isset($_POST['file'])) {
                echo 'No file was passed to the application for processing'; exit;
            }
            $file = File::find('id', mysqli_real_escape_string($con->gate, $_POST['file']));
            if (!$file) {
                echo 'Uh oh, the file could not be located.'; exit;
            }
            // Delete record
            $sql = "DELETE FROM files WHERE id = ".$file->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                $delete = unlink(FILES_PATH.$file->real_name());
                if ($delete) {
                    echo 'success'; exit;
                } else {
                    echo 'File was successfully removed from the database, but the file itself could not be removed from the server.'; exit;
                }
            } else {
                echo 'Unable to remove file record from database'; exit;
            }

            break;

        // DELETE FILE BY NAME
        case 'file-by-name':
            //var_dump($_POST['path']);exit;
            if (!isset($_POST['file'])) {
                echo 'No file was passed to application for processing'; exit;
            }
            $fileDir        = $con->secure($_POST['path']);
            $fileName       = $con->secure($_POST['file']);
            if ($fileDir == '') {
                $fileLocation   = FILES_PATH.'users/'.$current_user->id().'/'.$fileName;
            } else {
                $fileDir = trim($fileDir, '/');
                $fileLocation   = FILES_PATH.'users/'.$current_user->id().'/'.$fileDir.'/'.$fileName;
            }
            //var_dump($fileLocation);exit;
            // Find file by name and current user
            $file = File::find('sql', "SELECT * FROM files WHERE location = '".$fileLocation."' AND uploadedby = ".$current_user->id());
            //var_dump($file);exit;
            $file = array_shift($file);
            if (!$file) {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application tried to locate a file for deletion at: '.$fileLocation.' - however, the file could not be located'
                );
                echo 'No record of this file could be located in the database'; exit;
            } else {
                // Remove file then remove record
                $deletion = unlink($file->path());
                if (!$deletion) {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        'Failed attempt to delete file: '.$file->name().' from server location: '.$file->path()
                    );
                    echo 'File could not be deleted from the server'; exit;
                } else {
                    // Remove from db
                    $sql = "DELETE FROM files WHERE id = ".$file->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        ActivityLog::log_action(
                            $current_user,
                            'delete',
                            'Deleted file '. $file->name().' from server location: '.$file->path().' and database'
                        );
                        echo 'success'; exit;
                    } else {
                        ActivityLog::log_action(
                            $current_user,
                            'system-error',
                            'Could not delete file '.$file->name().' from database'
                        );
                        echo 'Could not remove file from database'; exit;
                    }
                }
            }
            break;

        // DELETE INVOICE
        case 'invoice':
            if (!isset($_POST['invoice'])) {
                echo 'No invoice was passed to the application for processing'; exit;
            }
            $invoice = Invoice::find('id', mysqli_real_escape_string($con->gate, $_POST['invoice']));
            if (!$invoice) {
                echo 'Uh oh, the invoice could not be located.'; exit;
            }
            $sql = "DELETE FROM invoices WHERE id = ".$invoice->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Oh no, there was error removing this invoice from the database'; exit;
            }
            break;

        // DELETE INVOICE CHARGE
        case 'invoice-charge':
            $charge = InvoiceCharge::find('id', $_POST['charge']);
            $invoice = $charge->invoice();
            if ($invoice) {
                $message = 'Deleted invoice charge from invoice: ' .$invoice->name().' - ' .$invoice->number();
            } else {
                $message = 'Deleted an invoice charge.';
            }
            if (!$charge) {
                echo 'The charge you are trying to delete could not be found in the database'; exit;
            }
            $sql    = "DELETE FROM invoice_charges WHERE id = ".$charge->id();
            $exec   = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'delete',
                    $message
                );
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was a problem communicating to the database'; exit;
            }
            break;

        // DELETE FORM
        case 'form':
            if (!isset($_POST['form'])) {
                echo 'No form was passed to the application for processing'; exit;
            }
            $form = Form::find('id', $con->secure($_POST['form']));
            if (!$form) {
                echo 'The form you are trying to delete no longer exists'; exit;
            }

            $sql = "DELETE FROM forms WHERE id = ".$form->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error deleting this form'; exit;
            }
            break;

        // DELETE FORM FIELD
        case 'form-field':
            if (!isset($_POST['field'])) {
                echo 'No form field was passed to the application for processing'; exit;
            }
            $field = FormField::find('id', $con->secure($_POST['field']));
            if (!$field) {
                echo '<script>window.location.reload()</script>'; exit;
            }
            $sql    = "DELETE FROM form_fields WHERE id = ".$field->id();
            $exec   = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Oh no! There was an error deleting this form field'; exit;
            }
            break;

        // DELETE ACTIVITY LOG RECORDS
        case 'activity':
            ActivityLog::erase_log();
            echo 'success'; exit;
            break;

        // DELETE WAGE
        case 'wage':
            if (trim($_POST['wage']) == '') {
                echo 'No wage was passed to the application for processing'; exit;
            }
            $wage = Wage::find('id', $con->secure($_POST['wage']));
            if (!$wage) {
                echo 'Wage could not be located in database'; exit;
            }
            $sql    = "DELETE FROM wages WHERE id = ".$wage->id();
            $exec   = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'delete',
                    'Deleted wage: '.$wage->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application attempted to delete wage: '.$wage->name().' but failed'
                );
                echo 'Unable to delete wage'; exit;
            }
            break;

        // DELETE TAX
        case 'tax':
            if (trim($_POST['tax']) == '') {
                echo 'No tax was passed to the application for processing'; exit;
            }
            $tax = Tax::find('id', $con->secure($_POST['tax']));
            if (!$tax) {
                echo 'Tax could not be located in database'; exit;
            }
            $sql    = "DELETE FROM taxes WHERE id = ".$tax->id();
            $exec   = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'delete',
                    'Deleted tax: '.$tax->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Application attempted to delete tax: '.$tax->name().' but failed'
                );
                echo 'Unable to delete tax'; exit;
            }
            break;

        // DELETE TAX ASSIGNMENTS
        case 'tax-assignments':
            $invoice = Invoice::find('id', $con->secure($_POST['invoice']));
            if (!$invoice) {
                echo 'The invoice you are trying to remove tax assignments from no longer exists'; exit;
            }
            // DELETE FROM invoices
            $sql = "DELETE FROM invoice_tax_assignments WHERE invoiceid = ".$invoice->id();
            $con->gate->query($sql);
            echo 'success';exit;
            break;
    }
}

// END POST DELETE IF STATEMENT

if (isset($_POST['clean'])) {

    switch ($_POST['clean']) {

        case 'activity':
            ActivityLog::clean_logs();
            echo 'success'; exit;
            break;

    }

}