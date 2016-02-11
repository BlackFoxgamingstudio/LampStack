<?php

if (isset($_POST['payment'])) {

    switch ($_POST['payment']) {


        case 'invoice-stripe':

            if (trim($_POST['invoice']) == '' || !Invoice::find('id', $con->secure($_POST['invoice']))) {
                echo 'The invoice passed to the application for processing could not be located'; exit;
            }
            $invoice = Invoice::find('id', $con->secure($_POST['invoice']));

            if (trim($_POST['stripeToken']) == '') {
                ActivityLog::log_action(
                    $current_user,
                    'system-error',
                    'Stripe payment token was not transferred to server for charge.'
                );
                echo 'Stripe payment failed. Please try again.'; exit;
            }
            $token = $con->secure($_POST['stripeToken']);

            // Check for stripe keys for the object being paid
            if ($invoice->is_company_invoice()) {

                // Redundant checks
                if (!$app_settings->appStripeCapable()) {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        'Application Stripe configuration failure'
                    );
                    echo 'Application / Company Stripe payment method is not configured properly.'; exit;
                }
                \Stripe\Stripe::setApiKey($app_settings->appStripeSecretModeKey());

            } else {

                // Redundant user check
                if (!$invoice->payee()->hasStripe()) {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        'User Stripe configuration failure'
                    );
                    echo 'The user you are paying has not properly set up his / her Stripe payment settings.'; exit;
                }

                \Stripe\Stripe::setApiKey($invoice->payee()->stripeSecretKey());

            }

            try {
                $amount =  ($invoice->has_taxes()) ? $invoice->taxed_amount_pennies() : $invoice->raw_amount_pennies();
                // Instantiate the charge using the supplied data
                \Stripe\Charge::create(array(
                    "amount" => $amount,
                    "currency" => $invoice->currency()->code(),
                    "source" => $token, // Token
                    "description" => $invoice->number().' : '.$invoice->name()
                ));

                // Charge successful. Mark invoice paid.
                $sql = "UPDATE invoices SET paid = 1, updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$invoice->id();
                $exec = $con->gate->query($sql);

                if ($exec) {
                    echo 'success'; exit;
                } else {
                    ActivityLog::log_action(
                        $current_user,
                        'system-error',
                        'Stripe charge created successfully. Application unable to mark invoice: '.$invoice->name() . ' - ' . $invoice->number().' as paid in the database.'
                    );
                    echo 'Payment was sent, but the application was unable to mark the invoice as paid. Please contact the payee to resolve it\'s status'; exit;
                }

            } catch (Exception $e) {

                echo 'Unable to process your payment'; exit;

            }

            break;

        case 'invoice-paypal':

            break;

        default:
            die('Payment method not recognized');


    }

}