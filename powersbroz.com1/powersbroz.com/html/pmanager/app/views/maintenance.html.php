<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $app_settings->get('company_name');?> Portal down for maintenance</title>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/normalize.css">
    <link rel="stylesheet" href="<?= BASE_URL;?>css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/font-awesome.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/beemuse.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/animate.css"/>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/entity.css">
    <link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Average+Sans' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
</head>
<body>

<div class="login-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <img alt="Logo" src="<?= BASE_URL;?>img/logo.png"/>
                <h1><?= $app_settings->get('company_name');?> Portal down for maintenance</h1>
                <p>Our portal is currently down for maintenance. Please check back in a little while.</p>
            </div>
        </div>
    </div>
</div>


</body>
</html>