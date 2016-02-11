<?php
if (isset($_POST['toggle'])) {

    switch ($_POST['toggle']) {

        case 'user-account':
            if (!isset($_POST['user'])) {
                echo 'No user was passed to the application for processing'; exit;
            }
            $user = User::find('id', $con->secure($_POST['user']));
            if (!$user) {
                echo 'The user account could not be found'; exit;
            }
            if ($user->is_active()) {
                $sql  = "UPDATE users SET active = 0 WHERE id = ".$user->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    $code = "0";
                }
            } else {
                $sql  = "UPDATE users SET active = 1 WHERE id = ".$user->id();
                $exec = $con->gate->query($sql);
                if ($exec) {
                    $code = "1";
                }
            }
            echo $code; exit;
            break;

    }

}