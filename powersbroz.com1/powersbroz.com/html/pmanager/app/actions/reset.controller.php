<?php
if (isset($_POST['reset'])) {

    switch($_POST['reset']) {

        // RESET PASSWORD
        case 'password':
            if (trim($_POST['femail']) == '') {
                echo 'You must provide your registered email address'; exit;
            }
            $email = mysqli_real_escape_string($con->gate, trim($_POST['femail']));

            $user = User::find('sql', "SELECT * FROM users WHERE email = '".$email."'");
            if (!$user) {
                echo 'The email you provided is not associated with any user account. Please contact an administrator or try again.'; exit;
            }

            // Generate new password
            $new_password   = random_string_gen();
            $new_hash       = do_get_hash($new_password);

            $sql    = "UPDATE users SET pass = '".$new_hash."' WHERE id = ".$user[0]->id();
            $exec   = $con->gate->query($sql);
            if ($exec) {
                if (APPROPRIATE) {
                    $to         = $user->email();
                    $subject    = 'Password Reset Request';

                    $headers    = "From: " . strip_tags($_POST['req-email']) . "\r\n";
                    $headers   .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
                    $headers   .= "MIME-Version: 1.0\r\n";
                    $headers   .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    $message    = "<p>Someone, hopefully you, requested a password reset for your account. The new password is:</p>";
                    $message   .= '<h2>'.$new_password.'</h2>';
                    $message   .= "<p>Please be sure to change it when you log in!</p>";
                    $email      = new EmailTemplate('basic', array('title' => 'Password Reset Request', 'message' => $message));

                    $send       = mail($to, $subject, $message, $headers);
                    if ($send) {
                        ActivityLog::log_action($user, "pw-reset", $user->name().' reset their password');
                        echo 'success'; exit;
                    } else {
                        echo 'Our mail server was unable to send your password reset. Please let support know so we can fix this.'; exit;
                    }
                } else {
                    ActivityLog::log_action($user, "pw-reset", $user->name().' reset their password');
                    echo 'success'; exit;
                }
            } else {
                ActivityLog::log_action(
                    $user,
                    "system-error",
                    $user->name().' tried to reset their password, but the application failed'
                );
                echo 'Unable to reset your password'; exit;
            }

            break;

    }

}
// END POST RESET IF STATEMENT