<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Entity {CC} - Activity Log</title>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/normalize.css">
    <link rel="stylesheet" href="<?= BASE_URL;?>css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL;?>css/font-awesome.min.css"/>
    <style type="text/css">
        body {
            height: 100%;
            background: white;
        }
        .toolbar {
            background: black;
            color: #AAA;
            padding: 15px 0;
            text-align: center;
        }

        .toolbar a, .toolbar a:visited, .toolbar a:hover {
            color: inherit;
            text-decoration: none;
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<div class="toolbar">
    <ul>
        <li><a href="<?= BASE_URL;?>activity" title="Go back to activity log">Back to Application</a></li>
    </ul>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?= $app_settings->get('company_name');?> Activity Log</h1>
            <p>Printed on <?= $now->format('F d, Y');?> @ <?= readable_time($now);?> by <?= $current_user->name();?></p>
            <table class="table">
                <tr>
                    <td>User</td>
                    <td>Action</td>
                    <td>Date and Time</td>
                </tr>
                <?php foreach ($activity as $a) { ?>
                <tr>
                    <td><?= $a->get('user')->name();?></td>
                    <td><?= $a->action_icon() . ' ' .$a->message();?></td>
                    <td><?= $a->get('created')->format('F d, Y') . '@' .readable_time($a->get('created'));?></td>
                </tr>
                <?php } ?>
            </table>
            <p>End of Log.</p>
        </div>
    </div>
</div>

</body>
</html>