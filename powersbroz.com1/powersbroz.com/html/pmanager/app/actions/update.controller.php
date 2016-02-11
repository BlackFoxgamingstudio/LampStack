<?php
if (isset($_POST['update'])) {

    switch ($_POST['update']) {

        // UPDATE FORM FIELD POSITION
        case 'form-field-position':
            $position   = $con->secure($_POST['position']);
            $field      = $con->secure($_POST['field']);
            $sql        = "UPDATE form_fields SET field_position = ".$position." WHERE id = ".$field;
            $exec       = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'System failed to update form field position'
                );
                echo 'Unable to update this field\'s position'; exit;
            }
            break;

        // UPDATE ACCOUNT
        case 'my-account':

            $user = $current_user;

            if (trim($_POST['userfname']) == '') {
                $fname = mysqli_real_escape_string($con->gate, $user->get('fname'));
            } else {
                $fname = $con->secure($_POST['userfname']);
            }

            if (trim($_POST['userlname']) == '') {
                $lname = mysqli_real_escape_string($con->gate, $user->get('lname'));
            } else {
                $lname = $con->secure($_POST['userlname']);
            }

            if (trim($_POST['useremail']) == '') {
                $email = mysqli_real_escape_string($con->gate, $user->get('email'));
            } else {
                $email = $con->secure($_POST['useremail']);
            }

            if (trim($_POST['userpass']) == '' || trim($_POST['userpassrepeat']) == '') {
                $password_change    = false;
            } else {
                $password_change    = true;
                $pass               = $con->secure($_POST['userpass']);
                $passrepeat         = $con->secure($_POST['userpassrepeat']);

                if ($pass !== $passrepeat) {
                    echo 'Your passwords do not match'; exit;
                }
            }

            if (trim($_POST['userbio']) == '') {
                $bio = mysqli_real_escape_string($con->gate, $user->get('bio'));
            } else {
                $bio = $con->secure($_POST['userbio']);
            }

            if (trim($_POST['useraddone']) == '') {
                $addone = mysqli_real_escape_string($con->gate, $user->get('addressone'));
            } else {
                $addone = $con->secure($_POST['useraddone']);
            }

            if (trim($_POST['useraddtwo']) == '') {
                $addtwo = mysqli_real_escape_string($con->gate, $user->get('addresstwo'));
            } else {
                $addtwo = $con->secure($_POST['useraddtwo']);
            }

            if (trim($_POST['usercity']) == '') {
                $city = mysqli_real_escape_string($con->gate, $user->get('city'));
            } else {
                $city = $con->secure($_POST['usercity']);
            }

            if (trim($_POST['userstate']) == '') {
                $state = mysqli_real_escape_string($con->gate, $user->get('state'));
            } else {
                $state = $con->secure($_POST['userstate']);
            }

            if (trim($_POST['userzip']) == '') {
                $zip = mysqli_real_escape_string($con->gate, $user->get('zip'));
            } else {
                $zip = $con->secure($_POST['userzip']);
            }

            if (trim($_POST['usercountry']) == '') {
                $country = mysqli_real_escape_string($con->gate, $user->get('country'));
            } else {
                $country = $con->secure($_POST['usercountry']);
            }

            if (trim($_POST['userhomephone']) == '') {
                $homephone = mysqli_real_escape_string($con->gate, $user->get('homephone'));
            } else {
                $homephone = $con->secure($_POST['userhomephone']);
            }

            if (trim($_POST['userworkphone']) == '') {
                $workphone = mysqli_real_escape_string($con->gate, $user->get('workphone'));
            } else {
                $workphone = $con->secure($_POST['userworkphone']);
            }

            if (trim($_POST['usercellphone']) == '') {
                $cellphone = mysqli_real_escape_string($con->gate, $user->get('cellphone'));
            } else {
                $cellphone = $con->secure($_POST['usercellphone']);
            }

            if (trim($_POST['userfax']) == '') {
                $fax = mysqli_real_escape_string($con->gate, $user->get('fax'));
            } else {
                $fax = $con->secure($_POST['userfax']);
            }

            if (trim($_POST['userwebsite']) == '') {
                $website = mysqli_real_escape_string($con->gate, $user->get('website'));
            } else {
                $website = $con->secure($_POST['userwebsite']);
            }

            if (trim($_POST['userfacebook']) == '') {
                $facebook = mysqli_real_escape_string($con->gate, $user->get('facebook'));
            } else {
                $facebook = $con->secure($_POST['userfacebook']);
            }

            if (trim($_POST['usertwitter']) == '') {
                $twitter = mysqli_real_escape_string($con->gate, $user->get('twitter'));
            } else {
                $twitter = $con->secure($_POST['usertwitter']);
            }

            if (trim($_POST['usergoogle']) == '') {
                $google = mysqli_real_escape_string($con->gate, $user->get('googleplus'));
            } else {
                $google = $con->secure($_POST['usergoogle']);
            }

            if (trim($_POST['userlinkedin']) == '') {
                $linkedin = mysqli_real_escape_string($con->gate, $user->get('linkedin'));
            } else {
                $linkedin = $con->secure($_POST['userlinkedin']);
            }

            if (trim($_POST['userskype']) == '') {
                $skype = mysqli_real_escape_string($con->gate, $user->get('skype'));
            } else {
                $skype = $con->secure($_POST['userskype']);
            }

            if (trim($_POST['useryahoo']) == '') {
                $yahoo = mysqli_real_escape_string($con->gate, $user->get('yahoo'));
            } else {
                $yahoo = $con->secure($_POST['useryahoo']);
            }

            $sql = "
            UPDATE users
            SET fname= '".$fname."',
                lname = '".$lname."',
                email = '".$email."',
                bio = '".$bio."',
                website = '".$website."',
                facebook = '".$facebook."',
                twitter = '".$twitter."',
                googleplus = '".$google."',
                linkedin = '".$linkedin."',
                skype = '".$skype."',
                yahoo = '".$yahoo."',
                homephone = '".$homephone."',
                cellphone = '".$cellphone."',
                workphone = '".$workphone."',
                fax = '".$fax."',
                addressone = '".$addone."',
                addresstwo = '".$addtwo."',
                city = '".$city."',
                state = '".$state."',
                zip = '".$zip."',
                country = '".$country."',
                updated = '".$now->format('Y-m-d H:i:s')."'
                WHERE id = ".$user->id();

            $exec = $con->gate->query($sql);

            if ($exec) {

                if ($password_change) {
                    // Update password separately
                    $pass = do_get_hash($con->secure($_POST['userpass']));
                    $sql = "UPDATE users SET pass = '".$pass."' WHERE id = ".$user->id();
                    $exec = $con->gate->query($sql);
                    if ($exec) {
                        echo 'success'; exit;
                    } else {
                        ActivityLog::log_action(
                            $current_user,
                            'system-error',
                            'System failed to update user\'s password'
                        );
                        echo 'Profile was updated but password could not be.'; exit;
                    }
                }
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'System failed to update user\'s profile'
                );
                echo 'Oh no! There was an error updating your profile in the database.'; exit;
            }

            break;

        // UPDATE STRIPE PAYMENT INFORMATION
        case 'stripe-payment':
            if (trim($_POST['stripeSecretKey']) == '') {
                echo 'You must provide your Stripe secret key'; exit;
            }
            $secretKey = $con->secure($_POST['stripeSecretKey']);
            if (trim($_POST['stripePublishKey']) == '') {
                echo 'You must provide your Stripe publishable key'; exit;
            }
            $publishKey = $con->secure($_POST['stripePublishKey']);

            $sql = "UPDATE users SET stripe_secret_key = '".$secretKey."', stripe_publish_key = '".$publishKey."' WHERE id = ".$current_user->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'System failed to update Stripe payment information for this user'
                );
                echo 'Oh no! There was an error while trying to update your Stripe payment information'; exit;
            }
            break;

        // UPDATE PAYPAL PAYMENT INFORMATION
        case 'paypal-payment':
            if (trim($_POST['paypalEmail']) == '') {
                echo 'You must enter your PayPal email address'; exit;
            }
            $paypal = $con->secure($_POST['paypalEmail']);
            $sql = "UPDATE users SET paypal_email = '".$paypal."' WHERE id = ".$current_user->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'System failed to update PayPal payment information for this user'
                );
                echo 'Oh no! Something went wrong while trying to update your PayPal email address in the database'; exit;
            }
            break;

        // UPDATE ACCESS ROLE
        case 'access-role':
            $user = User::find('id', $con->secure($_POST['user']));
            if (!$user) {
                echo 'This user no longer exists'; exit;
            }
            $role = Role::find('id', $con->secure($_POST['new-role']));
            if (!$role) {
                echo 'The role you are trying to assign t his user to no longer exists'; exit;
            }
            $sql = "UPDATE users SET role = ".$role->id()." WHERE id = ".$user->id();
            $exec = $con->gate->query($sql);
            if ($exec) {
                ActivityLog::log_action(
                    $current_user,
                    'edit',
                    'Changed '.$user->name().' role to '.$role->name()
                );
                echo 'success'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Attempt failed to change '.$user->name().' role to '.$role->name()
                );
                echo 'Uh oh! There was a problem changing this user\'s role'; exit;
            }
            break;

        // UPDATE COMPANY INFORMATION
        case 'company-information':
            if (!isset($_POST['companyname'])) {
                echo 'Company name must have a value'; exit;
            }
            $name = $con->secure($_POST['companyname']);
            $sql = "UPDATE app_settings SET setting_value = '".$name."' WHERE setting_name = 'company_name'";
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Unable to update your company\'s name in the database'; exit;
            }
            break;

        // UPDATE SYSTEM CURRENCY
        case 'system-currency':
            if (!isset($_POST['app-currency'])) {
                echo 'No currency was passed to the application for processing'; exit;
            }
            $currencyID = $con->secure($_POST['app-currency']);
            $currency = Currency::find('id', $currencyID);

            if (!$currency) {
                echo 'The currency you are trying to add is not supported by this application'; exit;
            }

            $sql = "UPDATE app_settings SET setting_value = '".$currency->id()."' WHERE setting_name = 'wage_currency'";
            $exec = $con->gate->query($sql);
            if ($exec) {
                echo 'success'; exit;
            } else {
                echo 'Unable to update application currency'; exit;
            }
            break;

        // UPDATE CURRENCY EXCHANGE RATES
        case 'exchange-rates':
            $baseCurrency = Currency::find('id', $app_settings->get('wage_currency'));
            CurrencyExchange::updateRates($baseCurrency);
            ActivityLog::log_action(
                $current_user,
                'update',
                'Updated application currency exchange rates'
            );
            echo 'success'; exit;
            break;

    }

}