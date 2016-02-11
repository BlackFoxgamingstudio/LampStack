<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>$_SESSION Global Array</h2>
                <pre>
                    <?= var_dump($_SESSION);?>
                </pre>
            </div>
            <div class="col-md-6">
                <h2>$session object</h2>
                <pre>
                    <?= var_dump($session);?>
                </pre>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h2>Current User</h2>
                <pre>
                    <?= var_dump($current_user);?>
                </pre>
            </div>
            <div class="col-md-6">
                <h2>Current User Preferences</h2>
                <pre>
                    <?= var_dump($app_preferences);?>
                </pre>
            </div>
        </div>

    </div>
</div>