<?php

/*

VideoEditor

*/

ini_set( 'display_errors', 1 );
error_reporting(E_ALL);

$root_path = dirname( __FILE__ );
$action = !empty($_GET['act']) ? trim($_GET['act']) : 'login';
define( 'REQUIRE_AUTH', false );

require $root_path . '/action.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title><?php echo LANG_VIDEOEDITOR; ?></title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/ui-lightness/jquery-ui-1.10.4.css" rel="stylesheet">
    <link href="css/videoeditor.css" rel="stylesheet">
    <link href="js/flowplayer/html5/skin/minimalist.css" rel="stylesheet">
    <link href="js/video-js/video-js.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery-ui-1.10.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
</head>
<body role="document">
    
    <div class="container editor-container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-default">
                <div class="panel-body">
                    
                    <div class="well well-sm">
                        <h1>
                            <span class="glyphicon glyphicon-film"></span>
                            <span><?php echo LANG_VIDEOEDITOR; ?></span>
                        </h1>
                    </div>
                    
                    <?php if( $message_error ): ?>
                        <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php echo $message_error; ?></div>
                    <?php endif; ?>
                    
                    <?php if( $message_info ): ?>
                        <div class="alert alert-success"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $message_info; ?></div>
                    <?php endif; ?>
                    
                    <form id="loginform" role="form" action="<?php echo $_SERVER['PHP_SELF'] . '?act=' . $action; ?>" method="post">
                        
                        <input type="hidden" name="action" value="<?php echo $action; ?>">
                        
                        <!-- panel -->
                        <div class="panel panel-default custom no-margin">
                            <div class="panel-heading">
                                <h3 class="panel-title text-bold">
                                    <?php echo ( $action == 'register' ? LANG_REGISTRATION : ( $action == 'recovery' ? LANG_RECOVERY : LANG_LOGIN ) ); ?>
                                </h3>
                            </div>
                            <!-- /panel-heading -->
                            <div class="panel-body">
                                
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="username" value="<?php if( !empty( $field_username ) ){ echo  $field_username; } ?>" placeholder="<?php echo $action == 'register' ? LANG_USERNAME : LANG_USERNAME_OR_EMAIL; ?>" required>                                        
                                    </div>
                                </div>
                                
                                <?php if( $action == 'register' ): ?>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="email" value="<?php if( !empty( $field_email ) ){ echo $field_email; } ?>" placeholder="<?php echo LANG_EMAIL; ?>" required>                                        
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if( $action != 'recovery' ): ?>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="password" placeholder="<?php echo LANG_PASSWORD; ?>" required>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if( $action == 'register' ): ?>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="password_re" placeholder="<?php echo LANG_PASSWORD_RE; ?>" required>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="form-controls">
                                    <div class="controls">
                                        <button class="btn btn-default btn-block">
                                            <span class="glyphicon glyphicon-ok"></span>
                                            <?php echo ( $action == 'register' ? LANG_REGISTRATION_SUBMIT : ( $action == 'recovery' ? LANG_RECOVERY_SUBMIT : LANG_LOGIN ) ); ?>
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- /panel-body -->
                            <?php if( $is_db_auth ): ?>
                            <div class="panel-footer">
                                <div class="text-right">
                                    <?php if( $action != 'recovery' ): ?>
                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?act=recovery"><?php echo LANG_RECOVERY; ?></a>
                                    <?php endif; ?>
                                    <?php if( $action != 'register' ): ?>
                                        <?php if( $action != 'recovery' ): ?>&nbsp;::&nbsp;<?php endif; ?>
                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?act=register"><?php echo LANG_REGISTRATION; ?></a>
                                    <?php endif; ?>
                                    <?php if( $action != 'login' ): ?>
                                        &nbsp;::&nbsp;
                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?act=login"><?php echo LANG_LOGIN; ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- /panel-footer -->
                            <?php endif; ?>
                        </div>
                        <!-- /panel -->
                    
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>