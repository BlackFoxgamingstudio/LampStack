<?php
// Check install
$users = User::find('all');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $this->title;?></title>
    <meta name="description" content="<?= $this->description;?>">
    <meta name="keywords" content="<?= $this->keywords;?>" >
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php require_once INCLUDES . 'css.html.php';?>

    <script src="<?= BASE_URL;?>js/modernizr-2.6.2.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?= BASE_URL;?>js/jquery-1.10.2.min.js"><\/script>')</script>
</head>
<body>
<?php include_once INCLUDES . 'entity.alert.html.php';?>
<div class="login-container">
    <div class="login animated zoomIn">
        <div class="row">
            <div class="col-md-6">
                <img class="img img-responsive" src="<?= BASE_URL;?>img/logo.png" alt="Logo"/>
            </div>
            <div class="col-md-6">
                <?php if ($users) { ?>
                <form id="login-frm" action="./" method="post">
                <input type="hidden" name="access" value="login"/>

                    <h2>Account Login</h2>
                    <input class="push-down" type="text" name="loginusername" id="loginusername" placeholder="Username..."/>
                    <?php if ($app_settings->get('login_require_email')) { ?>
                    <input class="push-down" type="email" name="loginemail" id="loginemail" placeholder="Email address..."/>
                    <?php } ?>
                    <input class="push-down" type="password" name="loginpassword" id="loginpassword" placeholder="Password..."/>
                    <div>
                        <button id="login-btn" type="submit" class="btn btn-primary">Login <i class="fa fa-lock"></i></button>

                        <?php if ($app_settings->get('login_allow_registration')) { ?>
                            <a href="#" id="registerUserBtn" class="btn btn-success">Register <i class="fa fa-plus"></i></a>
                        <?php } ?>

                        <?php if ($app_settings->get('allow_anonymous_quotes')) { ?>
                            <a href="#" id="anonymousQuoteBtn" class="btn btn-success">Get Quote</a>
                        <?php } ?>
                    </div>


                </form>
                <p class="subdued">
                    <a id="forgotPasswordBtn" href="#" title="Forgot password">Forgot password?</a>
                </p>
                <script>
                    $('#login-frm').on('submit', function(e) {
                        e.preventDefault();
                        $('#login-btn').html('<i class="fa fa-cog fa-spin"></i> Working...');
                        var loginData = $('#login-frm').serialize();
                        $.post('./', loginData, function(data) {
                            if (data == "success") {
                                location.reload();
                            } else {
                                $('#login-btn').html('Login <i class="fa fa-lock"></i>');
                                entityError('Failed Login', data);
                                document.getElementById('login-frm').reset();
                            }
                        });
                    });
                </script>
                <?php } else {  ?>
                    <h2>Ready to Install?</h2>
                    <p>Thanks for buying Entity {CC}. Click on the button below to start being amazed!</p>
                    <p><a href="<?= BASE_URL;?>app/install" class="btn btn-success"><i class="glyphicon glyphicon-check"></i> Start Installation</a></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row push-vertical">
        <div class="col-md-12 text-center">
            <p class="subdued">Entity {CC} created with <abbr title="LOVE"><i class="fa fa-heart"></i></abbr> by Travis Coats, <a href="http://www.zenperfectdesign.com" title="Zen Perfect Design Homepage" target="_blank">Zen Perfect Design</a></p>
        </div>
    </div>
</div>

<script src="<?= BASE_URL;?>js/entity.js"></script>
<script src="<?= BASE_URL;?>js/bootstrap.min.js"></script>

<script>
    $('#registerUserBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/registration.form.php')
    });
    $('#forgotPasswordBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/forgot.pass.form.php')
    });
    $('#anonymousQuoteBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/anonymous.quote.form.php');
    });
</script>
<?php $system_notification->render(); ?>
<?php (ENVIRONMENT == 'live') ? include_once INCLUDES . 'analytics.html.php' : '';?>
<?php (DEBUG) ? require_once INCLUDES . 'debug.html.php' : '';?>
</body>
</html>