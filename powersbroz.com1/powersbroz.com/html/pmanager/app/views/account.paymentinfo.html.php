<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>My Payment Methods</h2>
            </div>
        </div>
    </div>
</div>
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="toolbar-options">
                    <li class="pull-right"><a href="<?= BASE_URL;?>account/" title="My Account" class="btn btn-primary"><i class="fa fa-refresh"></i> My Account</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img src="<?= BASE_URL;?>img/ico/stripe.png" class="img img-responsive" alt="Stripe Logo" />
            </div>
            <div class="col-md-8">
                <h2>Stripe Payment Configuration</h2>
                <p>Collecting payment for your services is easy. Just provide your stripe api keys and let Entity take care of the rest.</p>
                <?php if ($current_user->hasStripe()) { ?>
                    <div class="blue-settings-display">
                        <p>Current Settings:</p>
                        <ul class="list-unstyled list-inline">
                            <li>
                                <span class="blue-settings-title">
                                    Stripe Secret Key
                                </span>
                                <span class="blue-settings-content">
                                    <?= $current_user->stripeSecretKey();?>
                                </span>
                            </li>
                            <li>
                                <span class="blue-settings-title">
                                    Stripe Publish Key
                                </span>
                                <span class="blue-settings-content">
                                    <?= $current_user->stripePublishKey();?>
                                </span>
                            </li>
                        </ul>
                    </div>
                <?php } else { ?>
                <p class="alert alert-warning">You have currently not setup your Stripe payment keys</p>
                <?php } ?>
                <hr/>
                <div class="well well-lg">
                    <form id="updateStripeKeysFrm" action="" method="post">
                        <input type="hidden" name="update" value="stripe-payment"/>
                        <label for="stripeSecretKey">Stripe Secret Key (Live):</label>
                        <input class="push-down" type="text" id="stripeSecretKey" name="stripeSecretKey" placeholder="Stripe secret key"/>
                        <label for="stripePublishKey">Stripe Publishable Key (Live):</label>
                        <input class="push-down" type="text" id="stripePublishKey" name="stripePublishKey" placeholder="Stripe publishable key"/>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Save Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <img src="<?= BASE_URL;?>img/ico/paypal.png" class="img img-responsive" alt="Paypal Logo"/>
            </div>
            <div class="col-md-8">
                <h2>PayPal Payment Configuration</h2>
                <p>All you need to collect paypal payments using paypal is your paypal account email</p>
                <?php if ($current_user->paypalEmail()) { ?>
                <div class="blue-settings-display">
                    <p>Current Settings:</p>
                    <ul class="list-unstyled list-inline">
                        <li>
                            <span class="blue-settings-title">
                                PayPal Email
                            </span>
                            <span class="blue-settings-content">
                                <?= $current_user->paypalEmail();?>
                            </span>
                        </li>
                    </ul>
                </div>
                <?php } else { ?>
                <p class="alert alert-warning">PayPal email address not setup</p>
                <?php } ?>
                <hr/>
                <div class="well well-lg">
                    <form id="updatePaypalFrm" action="" method="post">
                        <input type="hidden" name="update" value="paypal-payment"/>
                        <label>PayPal Email Address</label>
                        <input class="push-down" type="text" name="paypalEmail" id="paypalEmail" placeholder="PayPal email address"/>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#updateStripeKeysFrm').on('submit', function(e) {
        e.preventDefault();
        var formData = $('#updateStripeKeysFrm').serialize();
        $.post('./', formData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to update Stripe Keys', data);
            }
        });
    });
    $('#updatePaypalFrm').on('submit', function(e) {
        e.preventDefault();
        var formData = $('#updatePaypalFrm').serialize();
        $.post('./', formData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to update PayPal Email', data);
            }
        });
    });
</script>