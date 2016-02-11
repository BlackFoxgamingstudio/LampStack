<?php
if (isset($_POST['access'])) {

    switch($_POST['access']) {

        case 'login':

            if (trim($_POST['loginusername']) == '') {
                echo 'Please provide your username'; exit;
            }

            if (trim($_POST['loginpassword']) == '') {
                echo 'Please provide your password'; exit;
            }

            $username = trim($_POST['loginusername']);
            $password = trim($_POST['loginpassword']);

            if ($app_settings->get('login_require_email')) {
                if (trim($_POST['loginemail']) == '') {
                    echo 'Please provide your account email address'; exit;
                } else {
                    $email = trim($_POST['loginemail']);
                }

                $account = User::authenticate($username, $password, $email);

            } else {
                $account = User::authenticate($username, $password);
            }

            if ($account) {
                if (!$account->is_active()) {
                    echo 'Your account has been deactivated. Please contact a member of our staff to reactivate your account.';exit;
                }
                $session->log_in($account);
                ActivityLog::log_action($current_user, "login", $current_user->name().' logged in');
                echo 'success'; exit;
            } else {
                echo 'No account with that information was located'; exit;
            }

            break;

        case 'logout':
            ActivityLog::log_action($current_user, "logout", $current_user->name().' logged out');
            $session->log_out();
            echo 'success';exit;
            break;

    }

}
// END POST ACCESS IF STATEMENT