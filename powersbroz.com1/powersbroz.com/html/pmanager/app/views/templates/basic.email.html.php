<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Entity {CC} - Automated Message</title>
    <style type="text/css">
    body {
        height: 100%;
        background: #f5f5f5;
        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
        font-weight: 300;
    }
    .container {
        width: 600px;
        margin-left:auto;
        margin-right: auto;
        padding: 25px;
        text-align: center;
    }
    hr {
        border: none;
        border-bottom: 1px solid #CCC;
        margin: 25px 0px;
    }
    .small {
        font-size: 12px;
    }
    .subdued {
        color: #CCC;
    }
    ul.inline {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    ul.inline li {
        display: inline-block;
        margin-right: 25px;
        margin-left: 25px;
    }
    a, a:visited, a:hover {
        text-decoration: none;
        color: #2bc9ff;
    }

    </style>
</head>
<body>

<div class="container">
    <img alt="Logo" src="<?= BASE_URL;?>img/logo.png"/>
    <h1><?= $this->parameters['title'];?></h1>
    <hr/>
    <?= $this->parameters['message'];?>
    <hr/>
    <p class="small subdued">This is an automated email message from the Entity Project Management Web Application<br/> on behalf of <?= $app_settings->get('company_name');?></p>
    <ul class="inline">
        <li><a class="small" href="<?= WEBSITE;?>" title="Visit our website">Visit Website</a></li>
        <li><a class="small" href="<?= BASE_URL;?>" title="Login to Entity">Login</a></li>
    </ul>
</div>

</body>
</html>