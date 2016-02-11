<div class="container">
    <div class="col-md-12">
        <div class="restricted-full">
            <h1><i class="fa fa-lock"></i></h1>
            <h2>Unexpected Application Error</h2>
            <p>Uh oh! It looks like something went wrong.</p>
        </div>
    </div>
</div>

<div class="highlight-dark">
    <div class="container">
        <div class="col-md-2 text-center">
            <span class="stat"><i class="fa fa-support"></i></span>
        </div>
        <div class="col-md-10">
            <h3>Support</h3>
            <p>If you are having trouble using Entity {CC}, please contact our support team or a member of our staff</p>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="col-md-12">
            <h2>Company Contact Information</h2>
            <div class="row">
                <div class="col-md-4">
                    <?= ($app_settings->owner()) ? '<p><span class="label label-primary">Point of Contact: ' . $app_settings->owner()->name() . '</span></p>': ''; ?>
                    <div class="well well-lg">
                        <?= $app_settings->company();?>
                    </div>
                </div>
                <div class="col-md-8">
                    <?= $app_settings->company_contact(); ?>
                </div>
            </div>
        </div>
    </div>
</div>