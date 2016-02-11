<?php
if (isset($_POST['mass'])) {

    switch($_POST['mass']) {

        case 'reassign-user-role':
            if (!isset($_POST['initial-role'])) {
                echo 'Unable to complete request'; exit;
            }
            $initial_role = Role::find('id', $con->secure($_POST['initial-role']));
            if (!$initial_role) {
                echo 'The initial role has already been removed from the database'; exit;
            }
            if (!isset($_POST['new-role'])) {
                echo 'Unable to complete request'; exit;
            }
            $new_role = Role::find('id', $con->secure($_POST['new-role']));
            if (!$new_role) {
                echo 'The new role has already been removed from the database'; exit;
            }
            if ($initial_role->id() == $new_role->id()) {
                echo 'The new and old role are the same!'; exit;
            }
            $sql = "UPDATE users SET role = ".$new_role->id()." WHERE role = ".$initial_role->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                if ($_POST['delete-old'] == 1 && $initial_role->is_custom()) {
                    $sql = "DELETE FROM user_roles WHERE id = ".$initial_role->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        echo 'success'; exit;
                    } else {
                        echo 'The new role has been assigned, but there was a problem deleting the old role from the database.';exit;
                    }
                }
                echo 'success'; exit;
            } else {
                echo 'There was a problem communicating your intent to the database. No changes have been made.'; exit;
            }

            break;

    }
}