<?php
if (isset($_POST['save-settings'])) {

    switch ($_POST['save-settings']) {

        case 'company-information':
            // Setup update clause
            $errors = 0;
            $open   = "UPDATE app_settings SET setting_value = '";
            $where  = "' WHERE setting_name = '";
            $close  = "'";

            if (trim($_POST['company-name']) == '') {
                $company_name = ($app_settings->get('company_name') == '') ? '' : $app_settings->get('company_name');
            } else {
                $company_name = $con->secure($_POST['company-name']);
            }
            $exec = $con->gate->query($open.$company_name.$where."company_name".$close);
            ($exec) ? null : $errors++;

            $company_owner = User::find('id', $con->secure($_POST['company-owner']));

            if (!$company_owner) {
                echo 'The user you requested be set as owner no longer exists'; exit;
            }
            $exec = $con->gate->query($open.$company_owner->id().$where."owner_account".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-addone']) == '') {
                $company_addressone = ($app_settings->get('company_address_one') == '') ? '' : $app_settings->get('company_address_one');
            } else {
                $company_addressone = $con->secure($_POST['company-addone']);
            }
            $exec = $con->gate->query($open.$company_addressone.$where."company_address_one".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-addtwo']) == '') {
                $company_addresstwo = ($app_settings->get('company_address_two') == '') ? '' : $app_settings->get('company_address_two');
            } else {
                $company_addresstwo = $con->secure($_POST['company-addtwo']);
            }
            $exec = $con->gate->query($open.$company_addresstwo.$where."company_address_two".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-city']) == '') {
                $company_city = ($app_settings->get('company_city') == '') ? '' : $app_settings->get('company_city');
            } else {
                $company_city = $con->secure($_POST['company-city']);
            }
            $exec = $con->gate->query($open.$company_city.$where."company_city".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-state']) == '') {
                $company_state = ($app_settings->get('company_state') == '') ? '' : $app_settings->get('company_state');
            } else {
                $company_state = $con->secure($_POST['company-state']);
            }
            $exec = $con->gate->query($open.$company_state.$where."company_state".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-zip']) == '') {
                $company_zip = ($app_settings->get('company_zip_code') == '') ? '' : $app_settings->get('company_zip_code');
            } else {
                $company_zip = $con->secure($_POST['company-zip']);
            }
            $exec = $con->gate->query($open.$company_zip.$where."company_zip_code".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-country']) == '') {
                $company_country = ($app_settings->get('company_country') == '') ? '' : $app_settings->get('company_country');
            } else {
                $company_country = $con->secure($_POST['company-country']);
            }
            $exec = $con->gate->query($open.$company_country.$where."company_country".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-phone']) == '') {
                $company_phone_main = ($app_settings->get('company_phone_main') == '') ? '' : $app_settings->get('company_phone_main');
            } else {
                $company_phone_main = $con->secure($_POST['company-phone']);
            }
            $exec = $con->gate->query($open.$company_phone_main.$where."company_phone_main".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-support-phone']) == '') {
                $company_phone_support = ($app_settings->get('company_phone_support') == '') ? '' : $app_settings->get('company_phone_support');
            } else {
                $company_phone_support = $con->secure($_POST['company-support-phone']);
            }
            $exec = $con->gate->query($open.$company_phone_support.$where."company_phone_support".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-fax']) == '') {
                $company_fax = ($app_settings->get('company_fax') == '') ? '' : $app_settings->get('company_fax');
            } else {
                $company_fax = $con->secure($_POST['company-fax']);
            }
            $exec = $con->gate->query($open.$company_fax.$where."company_fax".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-email']) == '') {
                $company_email = ($app_settings->get('company_email_main') == '') ? '' : $app_settings->get('company_email_main');
            } else {
                $company_email = $con->secure($_POST['company-email']);
            }
            $exec = $con->gate->query($open.$company_email.$where."company_email_main".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['company-support-email']) == '') {
                $company_email_support = ($app_settings->get('company_email_support') == '') ? '' : $app_settings->get('company_email_support');
            } else {
                $company_email_support = $con->secure($_POST['company-support-email']);
            }
            $exec = $con->gate->query($open.$company_email_support.$where."company_email_support".$close);
            ($exec) ? null : $errors++;

            if (isset($_POST['company-pay'])) {
                $company_pay = 1;
            } else {
                $company_pay = 0;
            }
            $exec = $con->gate->query($open.$company_pay.$where."company_payments".$close);
            ($exec) ? null : $errors++;

            if (isset($_POST['paypal-on'])) {
                if ($_POST['paypal-email'] == '' && $app_settings->get('company_paypal_address') == '') {
                    echo 'You must have a company PayPal email address configured to use PayPal for company payments'; exit;
                } else {
                    $company_paypal = 1;
                }
            } else {
                $company_paypal = 0;
            }
            $exec = $con->gate->query($open.$company_paypal.$where."company_use_paypal".$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['paypal-email']) == '') {
                $company_paypal_address = ($app_settings->get('company_paypal_address') == '') ? '' : $app_settings->get('company_paypal_address');
            } else {
                $company_paypal_address = $con->secure($_POST['paypal-email']);
            }
            $exec = $con->gate->query($open.$company_paypal_address.$where."company_paypal_address".$close);
            ($exec) ? null : $errors++;

            // Get stripe values
            $stripe_on      = (isset($_POST['stripe-on'])) ? 1 : 0;
            $stripe_mode    = $con->secure($_POST['stripe-mode']);
            $test_pub       = $con->secure($_POST['stripe-test-publish']);
            $test_secret    = $con->secure($_POST['stripe-test-secret']);
            $live_pub       = $con->secure($_POST['stripe-live-publish']);
            $live_secret    = $con->secure($_POST['stripe-live-secret']);

            if ($stripe_on == 1 || $app_settings->get('company_use_stripe') == 1) {

                switch ($stripe_mode) {

                    case 'test':
                        // Check test keys for current or pushed values
                        if (trim($app_settings->get('stripe_test_publishable') == '') || trim($app_settings->get('stripe_test_secret')) == '') {

                            if ($test_pub == '' || $test_secret == '') {
                                $exec = $con->gate->query($open."0".$where."company_use_stripe".$close);
                                ($exec) ? null : $errors++;
                                echo 'You must specify a valid value for both Stripe test keys if you want to turn on stripe in test mode'; exit;
                            }

                        }
                        break;

                    case 'live':
                        // Check live keys for current or pushed values
                        if ($app_settings->get('stripe_live_publishable' == '') || $app_settings->get('stripe_live_secret' == '')) {

                            if ($live_pub == '' || $live_secret == '') {
                                $exec = $con->gate->query($open."0".$where."company_use_stripe".$close);
                                ($exec) ? null : $errors++;
                                echo 'You must specify a valid value for both Stripe test keys if you want to turn on stripe in live mode'; exit;
                            }

                        }
                        break;

                    default:
                        echo 'There was an error configuring your stripe mode and validating your keys'; exit;

                }

            }

            $exec = $con->gate->query($open.$stripe_on.$where."company_use_stripe".$close);
            ($exec) ? null : $errors++;
            $exec = $con->gate->query($open.$stripe_mode.$where."stripe_mode".$close);
            ($exec) ? null : $errors++;
            $exec = $con->gate->query($open.$test_pub.$where."stripe_test_publishable".$close);
            ($exec) ? null : $errors++;
            $exec = $con->gate->query($open.$test_secret.$where."stripe_test_secret".$close);
            ($exec) ? null : $errors++;
            $exec = $con->gate->query($open.$live_pub.$where."stripe_live_publishable".$close);
            ($exec) ? null : $errors++;
            $exec = $con->gate->query($open.$live_secret.$where."stripe_live_secret".$close);
            ($exec) ? null : $errors++;

            if ($errors > 0) {
                echo 'One or more updates were unsuccessful'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'update',
                    'Updated Application Company Information Settings'
                );
                echo 'success'; exit;
            }

            break;

        case 'login-configuration':
            // Setup update clause
            $errors = 0;
            $open   = "UPDATE app_settings SET setting_value = '";
            $where  = "' WHERE setting_name = '";
            $close  = "'";

            if (isset($_POST['login-email'])) {
                $login_email = 1;
            } else {
                $login_email = 0;
            }
            $exec = $con->gate->query($open.$login_email.$where.'login_require_email'.$close);
            ($exec) ? null : $errors++;

            if (isset($_POST['login-register'])) {
                $login_register = 1;
            } else {
                $login_register = 0;
            }
            $exec = $con->gate->query($open.$login_register.$where.'login_allow_registration'.$close);
            ($exec) ? null : $errors++;

            if (trim($_POST['register-role']) == '') {
                $register_role = 5;
            } else {
                $role = Role::find('id', $con->secure($_POST['register-role']));
                if (!$role) {
                    echo 'The default registration role no longer exists'; exit;
                } else {
                    $register_role = $con->secure($_POST['register-role']);
                }
            }
            $exec = $con->gate->query($open.$register_role.$where."registration_default_role".$close);
            ($exec) ? null : $errors++;

            if (isset($_POST['login-quotes'])) {
                $login_quotes = 1;
            } else {
                $login_quotes = 0;
            }
            $exec = $con->gate->query($open.$login_quotes.$where.'allow_anonymous_quotes'.$close);
            ($exec) ? null : $errors++;

            if (!isset($_POST['quote-form'])) {
                $quote_form = 0;
            } else {
                $form = Form::find('id', $con->secure($_POST['quote-form']));
                if (!$form) {
                    echo 'The default quote form no longer exists'; exit;
                } else {
                    $quote_form = $con->secure($_POST['quote-form']);
                }
            }
            $exec = $con->gate->query($open.$quote_form.$where."login_quotes_default_form".$close);
            ($exec) ? null : $errors++;

            if ($errors > 0) {
                echo 'One or more updates were unsuccessful'; exit;
            } else {
                ActivityLog::log_action(
                    $current_user,
                    'update',
                    'Updated Application Login Configuration Settings'
                );
                echo 'success'; exit;
            }

            break;

        case 'email-notification':
            if (isset($_POST['email-user-reg'])) {
                $email_user_reg = 1;
            } else {
                $email_user_reg = 0;
            }
            $exec    = $con->gate->query("UPDATE app_settings SET setting_value = '{$email_user_reg}' WHERE setting_name = 'notify_when_user_registers'");
            if (isset($_POST['email-user-quote'])) {
                $email_user_quote = 1;
            } else {
                $email_user_quote = 0;
            }
            $exec   = $con->gate->query("UPDATE app_settings SET setting_value = '{$email_user_quote}' WHERE setting_name = 'notify_when_user_quote'");
            ActivityLog::log_action(
                $current_user,
                'update',
                'Updated Application Email Notification Settings'
            );
            echo 'success';exit;
            break;

        case 'application-general':
            //var_dump($_POST);exit;
            if (isset($_POST['maintenance-mode'])) {
                $maintenance_mode = 1;
            } else {
                $maintenance_mode = 0;
            }
            $exec   = $con->gate->query("UPDATE app_settings SET setting_value = '".$maintenance_mode."' WHERE setting_name = 'maintenance_mode'");

            if (trim($_POST['maintenance-key']) == '') {
                $maintenance_key = '';
            } else {
                $maintenance_key = $con->secure($_POST['maintenance-key']);
            }
            $exec = $con->gate->query("UPDATE app_settings SET setting_value = '".$maintenance_key."' WHERE setting_name = 'maintenance_key'");

            if (isset($_POST['google-analytics'])) {
                $google_analytics = 1;
            } else {
                $google_analytics = 0;
            }
            $exec = $con->gate->query("UPDATE app_settings SET setting_value = '".$google_analytics."' WHERE setting_name = 'google_analytics'");

            if (trim($_POST['google-analytics-code']) == '') {
                $google_analytics_code = '';
            } else {
                $google_analytics_code = $con->secure($_POST['google-analytics-code']);
            }
            $exec = $con->gate->query("UPDATE app_settings SET setting_value = '".$google_analytics_code."' WHERE setting_name = 'google_analytics_code'");

            ActivityLog::log_action(
                $current_user,
                'update',
                'Updated Application General and Maintenance Settings'
            );

            echo 'success';exit;
            break;

        default:
            exit;

    }

}