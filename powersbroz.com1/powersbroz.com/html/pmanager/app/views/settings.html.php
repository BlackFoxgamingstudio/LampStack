<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Entity Settings</h2>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">

        <!-- Company Information Form -->
        <form id="updateCompanyInfoFrm" action="" method="post">
        <input type="hidden" name="save-settings" value="company-information"/>
        <div class="row">
            <div class="col-md-12">
                <h2 class="setting-group">
                    Company Information <span id="updateCompanyInfoBtn" class="pull-right btn btn-success"><i class="fa fa-save"></i> Save</span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3><label for="company-name">Name of your Company:</label></h3>
                <input type="text" name="company-name" placeholder="Company name" id="company-name" value="<?= $app_settings->get('company_name');?>"/>
            </div>
            <div class="col-md-6">
                <h3><label for="company-owner">Owner Account:</label><span class="pull-right"><a href="<?= BASE_URL;?>docs/#owner-account" title="About the owner account"><i class="fa fa-support"></i></a></span></h3>
                <?= User::html_select('company-owner', array('user' => 'only-staff'), $app_settings->get('owner_account'));?>
            </div>
        </div>
        <hr/>
        <div class="row push-vertical">
            <div class="col-md-6">
                <h3>Company Address</h3>
                <p>Will be shown for all official correspondence and invoices</p>
            </div>
            <div class="col-md-6">
                <table>
                    <tbody>
                        <tr>
                            <td colspan="3"><input class="push-down" type="text" name="company-addone" placeholder="Address line one" value="<?= $app_settings->get('company_address_one');?>"/></td>
                        </tr>
                        <tr>
                            <td colspan="3"><input class="push-down" type="text" name="company-addtwo" placeholder="Address line two" value="<?= $app_settings->get('company_address_two');?>"/></td>
                        </tr>
                        <tr>
                            <td><input class="push-down" type="text" name="company-city" placeholder="City" value="<?= $app_settings->get('company_city');?>"/></td>
                            <td><input class="push-down" type="text" name="company-state" placeholder="State or region" value="<?= $app_settings->get('company_state');?>"/></td>
                            <td><input class="push-down" type="text" name="company-zip" placeholder="Zip code" value="<?= $app_settings->get('company_zip_code');?>"/></td>
                        </tr>
                        <tr>
                            <td colspan="3"><?= get_countries('company-country', 'push-down', $app_settings->get('company_country'));?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row push-vertical">
            <div class="col-md-6">
                <h3>Company Phone Numbers</h3>
            </div>
            <div class="col-md-6">
                <table class="setting-table">
                    <tbody>
                        <tr>
                            <td><p><i class="fa fa-phone"></i> Main Phone:</p></td>
                            <td><input type="tel" name="company-phone" id="company-phone" placeholder="Main phone number" value="<?= $app_settings->get('company_phone_main');?>"/></td>
                        </tr>
                        <tr>
                            <td><p><i class="fa fa-phone"></i> Support Phone:</p></td>
                            <td><input type="tel" name="company-support-phone" id="company-support-phone" placeholder="Support phone number" value="<?= $app_settings->get('company_phone_support');?>"/></td>
                        </tr>
                        <tr>
                            <td><p><i class="fa fa-print"></i> Fax:</p></td>
                            <td><input type="tel" name="company-fax" id="company-fax" placeholder="Fax number" value="<?= $app_settings->get('company_fax');?>"/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row push-vertical">
            <div class="col-md-6">
                <h3>Company Email</h3>
            </div>
            <div class="col-md-6">
                <table class="setting-table">
                    <tbody>
                    <tr>
                        <td><p><i class="fa fa-envelope"></i> Main Email:</p></td>
                        <td><input type="email" name="company-email" id="company-email" placeholder="Main email address" value="<?= $app_settings->get('company_email_main');?>"/></td>
                    </tr>
                    <tr>
                        <td><p><i class="fa fa-envelope"></i> Support Email:</p></td>
                        <td><input type="tel" name="company-support-email" id="company-support-email" placeholder="Support email address" value="<?= $app_settings->get('company_email_support');?>"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row push-vertical">
            <div class="col-md-6">
                <h3>Company Payment Account</h3>
            </div>
            <div class="col-md-6">
                <table class="setting-table">
                    <tbody>
                    <tr>
                        <td><p>Accept Company Payments</p></td>
                        <td>
                            <div class="switch">
                                <input class="img-switch" type="checkbox" name="company-pay" id="company-pay" <?= ($app_settings->get('company_payments') == 1) ? 'checked': ''?> />
                                <label for="company-pay"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="alert alert-info">By turning on accept company payments, an option will show up when creating a new invoice to collect the payment into your company account. This is convenient if you want to direct funds directly to a central account.</p>
                        </td>
                    </tr>
                    <tr>
                        <td><img class="img" src="<?= BASE_URL;?>img/ico/paypal.png" height="32" alt="PayPal Logo"/></td>
                        <td>
                            <div class="switch">
                                <input class="img-switch" type="checkbox" name="paypal-on" id="paypal-on" <?= ($app_settings->get('company_use_paypal') == 1) ? 'checked': ''?>/>
                                <label for="paypal-on"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><p><i class="fa fa-bank"></i> PayPal Email:</p></td>
                        <td><input type="email" name="paypal-email" id="paypal-email" placeholder="PayPal Account Email" value="<?= $app_settings->get('company_paypal_address');?>"/></td>
                    </tr>
                    <tr>
                        <td><img class="img" src="<?= BASE_URL;?>img/ico/stripepay.png" height="32" alt="PayPal Logo"/></td>
                        <td>
                            <div class="switch">
                                <input class="img-switch" type="checkbox" name="stripe-on" id="stripe-on" <?= ($app_settings->get('company_use_stripe') == 1) ? 'checked': ''?>/>
                                <label for="stripe-on"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Stripe Mode</td>
                        <?php if ($app_settings->get('stripe_mode') == 'test') { ?>
                        <td>Test <input type="radio" name="stripe-mode" value="test" checked/> Live <input type="radio" name="stripe-mode" value="live"/></td>
                        <?php } else { ?>
                            <td>Test <input type="radio" name="stripe-mode" value="test" /> Live <input type="radio" name="stripe-mode" value="live" checked/></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>Test Publishable Key</td>
                        <td><input type="text" name="stripe-test-publish" placeholder="Stripe test publishable key" value="<?= $app_settings->get('stripe_test_publishable');?>"/></td>
                    </tr>
                    <tr>
                        <td>Test Secret Key</td>
                        <td><input type="text" name="stripe-test-secret" placeholder="Stripe test secret key" value="<?= $app_settings->get('stripe_test_secret');?>"/></td>
                    </tr>
                    <tr>
                        <td>Live Publishable Key</td>
                        <td><input type="text" name="stripe-live-publish" placeholder="Stripe live publishable key" value="<?= $app_settings->get('stripe_live_publishable');?>"/></td>
                    </tr>
                    <tr>
                        <td>Live Secret Key</td>
                        <td><input type="text" name="stripe-live-secret" placeholder="Stripe live secret key" value="<?= $app_settings->get('stripe_live_secret');?>"/></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="alert alert-warning">
                                This set of Stripe keys is specifically for collecting payments in your company account
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </form>
        <script>
            $('#updateCompanyInfoBtn').click(function() {
                $('#updateCompanyInfoBtn').html('<i class="fa fa-cog fa-spin"></i> Saving');
                var companyInformation = $('#updateCompanyInfoFrm').serialize();
                $.post('./', companyInformation, function(data) {
                    if (data == 'success') {
                        window.location.reload();
                    } else {
                        $('#updateCompanyInfoBtn').html('<i class="fa fa-save"></i> Save');
                        entityError('Unable to update settings', data);
                    }
                });
            });
        </script>
        <!-- End Company Information Form -->



        <!-- Login Configuration Form -->
        <form id="updateLoginConfigFrm" action="" method="post">
        <input type="hidden" name="save-settings" value="login-configuration"/>
        <div class="row">
            <div class="col-md-12">
                <h2 class="setting-group">Login Configuration <span id="updateLoginConfigBtn" class="pull-right btn btn-success"><i class="fa fa-save"></i> Save</span></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Require Email</h3>
                <p>In addition to a username and password, users will be required to provide an email to login</p>
            </div>
            <div class="col-md-6">
                <div class="switch">
                    <input class="img-switch" type="checkbox" name="login-email" id="login-email" <?= ($app_settings->get('login_require_email') == 1) ? 'checked': ''?>/>
                    <label for="login-email"></label>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <h3>Allow Registration</h3>
                <p>Users can manually register for an account</p>
            </div>
            <div class="col-md-6">
                <div class="switch">
                    <input class="img-switch" type="checkbox" name="login-register" id="login-register" <?= ($app_settings->get('login_allow_registration') == 1) ? 'checked': ''?>/>
                    <label for="login-register"></label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Registration Default Role</h3>
            </div>
            <div class="col-md-6">
                <?php $roles = Role::find('all');?>
                <select name="register-role">
                    <?php foreach ($roles as $role) { ?>

                        <option value="<?= $role->id();?>" <?= ($app_settings->get('registration_default_role') == $role->id()) ? 'selected': '';?>>
                            <?= $role->name();?>
                        </option>

                    <?php } ?>
                </select>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <h3>Allow Quotes</h3>
                <p>Users can get a quote without having to login</p>
            </div>
            <div class="col-md-6">
                <div class="switch">
                    <input class="img-switch" type="checkbox" name="login-quotes" id="login-quotes" <?= ($app_settings->get('allow_anonymous_quotes') == 1) ? 'checked': ''?>/>
                    <label for="login-quotes"></label>
                </div>
            </div>
        </div>
        <?php $forms = Form::find('all');?>
        <?php if ($forms) { ?>
        <div class="row">
            <div class="col-md-6">
                <h3>Quote Default Form</h3>
            </div>
            <div class="col-md-6">
                <select name="quote-form">
                    <?php foreach ($forms as $form) { ?>
                        <option value="<?= $form->id();?>" <?= ($app_settings->get('login_quotes_default_form') == $form->id()) ? 'selected': '';?>>
                            <?= $form->name();?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <?php } // End if forms block ?>
        </form>
        <script>
            $('#updateLoginConfigBtn').click(function() {
                $('#updateLoginConfigBtn').html('<i class="fa fa-cog fa-spin"></i> Saving');
                var loginConfig = $('#updateLoginConfigFrm').serialize();
                $.post('./', loginConfig, function(data) {
                    if (data == 'success') {
                        window.location.reload();
                    } else {
                        $('#updateLoginConfigBtn').html('<i class="fa fa-save"></i> Save');
                        entityError('Unable to update settings', data);
                    }
                });
            });
        </script>
        <!-- End Login Configuration Form -->

        <!-- Email Notifications Form -->
        <form id="updateEmailNotificationsFrm" action="" method="post">
            <input type="hidden" name="save-settings" value="email-notification"/>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="setting-group">Email Notification Settings <span id="updateEmailNotificationsBtn" class="pull-right btn btn-success"><i class="fa fa-save"></i> Save</span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Notification when User Registers</h3>
                    <p>Email application <em>Owner</em> when a user registers for an account</p>
                </div>
                <div class="col-md-6">
                    <div class="switch">
                        <?php if ($app_settings->get('notify_when_user_registers') == 1) { ?>
                        <input class="img-switch" type="checkbox" name="email-user-reg" id="email-user-reg" checked />
                        <?php } else { ?>
                        <input class="img-switch" type="checkbox" name="email-user-reg" id="email-user-reg" />
                        <?php } ?>
                        <label for="email-user-reg"></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Notification when User Submits Quote</h3>
                    <p>Email application <em>Owner</em> when a user submits a quote request</p>
                </div>
                <div class="col-md-6">
                    <div class="switch">
                        <?php if ($app_settings->get('notify_when_user_quote')) { ?>
                        <input class="img-switch" type="checkbox" name="email-user-quote" id="email-user-quote" checked />
                        <?php } else { ?>
                        <input class="img-switch" type="checkbox" name="email-user-quote" id="email-user-quote" />
                        <?php } ?>
                        <label for="email-user-quote"></label>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $('#updateEmailNotificationsBtn').click(function() {
                $(this).html('<i class="fa fa-cog fa-spin"></i> Saving');
                var notificationsFormData = $('#updateEmailNotificationsFrm').serialize();
                $.post('./', notificationsFormData, function(data) {
                    if (data == 'success') {
                        window.location.reload();
                    } else {
                        $('#updateEmailNotificationsBtn').html('<i class="fa fa-save"></i> Save');
                        entityError('Unable to update settings', data);
                    }
                });
            });
        </script>
        <!-- End Email Notifications Form -->

        <!-- Application General Settings -->
        <form id="updateGeneralSettingsFrm" action="" method="post">
            <input type="hidden" name="save-settings" value="application-general"/>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="setting-group">Application General Settings &amp; Maintenance <span id="updateApplicationGeneralBtn" class="pull-right btn btn-success"><i class="fa fa-save"></i> Save</span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Turn On Maintenance Mode</h3>
                    <p>Toggle whether or not the system is offline to test customizations, make upgrades, and more.</p>
                </div>
                <div class="col-md-6">
                    <div class="switch">
                        <?php if ($app_settings->get('maintenance_mode') == 1) { ?>
                        <input class="img-switch" type="checkbox" name="maintenance-mode" id="maintenance-mode" checked />
                        <?php } else { ?>
                        <input class="img-switch" type="checkbox" name="maintenance-mode" id="maintenance-mode" />
                        <?php } ?>
                        <label for="maintenance-mode"></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Access Key</h3>
                    <p>Use this key to access Entity {CC} during maintenance mode. It will be stored as a Cookie so you don't have to enter it on each screen</p>
                </div>
                <div class="col-md-6">
                    <?php if ($app_settings->get('maintenance_key') != '') { ?>
                    <input class="push-vertical" type="text" name="maintenance-key" id="maintenance-key" value="<?= $app_settings->get('maintenance_key');?>" />
                    <? } else { ?>
                        <input class="push-vertical" type="text" name="maintenance-key" id="maintenance-key" placeholder="No key stored" />
                    <?php } ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3>Google Analytics</h3>
                    <p>Would you like to use Google Analytics?</p>
                </div>
                <div class="col-md-6">
                    <div class="switch">
                        <?php if ($app_settings->get('google_analytics') == 1) { ?>
                        <input class="img-switch" type="checkbox" name="google-analytics" id="google-analytics" checked />
                        <?php } else { ?>
                        <input class="img-switch" type="checkbox" name="google-analytics" id="google-analytics" />
                        <?php } ?>
                        <label for="google-analytics"></label>
                    </div>
                    <label for="google-analytics-code">Google Analytics Tracking Account Number (UA-XXXXX-X)</label>
                    <?php if ($app_settings->get('google_analytics_code') != '') { ?>
                    <input class="push-vertical" type="text" name="google-analytics-code" id="google-analytics-code" value="<?= $app_settings->get('google_analytics_code');?>"/>
                    <?php } else { ?>
                    <input class="push-vertical" type="text" name="google-analytics-code" id="google-analytics-code" placeholder="UA-XXXXX-X"/>
                    <?php } ?>
                </div>
            </div>
        </form>
        <script>
            $('#updateApplicationGeneralBtn').click(function() {
                $(this).html('<i class="fa fa-cog fa-spin"></i> Saving');
                var generalSettingsFormData = $('#updateGeneralSettingsFrm').serialize();
                $.post('./',generalSettingsFormData, function(data) {
                    if (data == 'success') {
                        window.location.reload();
                    } else {
                        $('#updateApplicationGeneralBtn').html('<i class="fa fa-save"></i> Save');
                        entityError('Unable to update settings', data);
                    }
                });
            });
        </script>
    </div>
</div>