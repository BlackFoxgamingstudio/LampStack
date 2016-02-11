<?php

/*

VideoEditor

*/

session_start();

if( !empty( $_GET['logout'] ) ){
    $_SESSION['user_id'] = '';
    $_SESSION['is_admin'] = '';
    unset( $_SESSION['user_id'] );
    unset( $_SESSION['is_admin'] );
}

//load config
$root_path = dirname( __FILE__ );
require $root_path . '/config.php';

//debug mode
if( !empty( $config['debug'] ) ){
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
}

//authorization
if( empty( $_SESSION['user_id'] ) ){
    header( "Location: auth.php" );
    exit;
}else{
    if( !empty( $_GET['uid'] ) && is_numeric( $_GET['uid'] ) &&  $_SESSION['is_admin'] ){
        $_SESSION['user_id'] = intval( $_GET['uid'] );
    }
    $config['user_id'] = $_SESSION['user_id'];
    $config['is_admin'] = $_SESSION['is_admin'];
}

//load language file
if( file_exists( $root_path . '/languages/' . $config['lang'] . '.php' ) ){
    $lang_file = $root_path . '/languages/' . $config['lang'] . '.php';
}else{
    $lang_file = $root_path . '/languages/en.php';
}
require $lang_file;

//get users
$users_arr = array();
//users from DB
if( !empty( $config['db']['user'] ) && !empty( $config['db']['db_name'] ) ){
    
    $db = mysqli_connect( $config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name'] ) or die("MySQL Error " . mysqli_error( $connect ) ); 
    $sql = "SELECT `id`, `username`, `type` FROM `{$config['db']['users_table']}`"; 
    
    if( !$result = $db->query( $sql ) ){
        die( 'There was an error running the query [' . $db->error . ']' );
    }
    
    if( $result->num_rows > 0 ){
        
        while( $row = $result->fetch_assoc() ){
            
            $users_arr[] = $row;
            
        }
        
    }
    
}
//users from config file
else{
    
    $users_arr = $config['users'];
    
}

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
    
    <script src="js/flowplayer/html5/flowplayer.min.js"></script>
    <script src="js/video-js/video.js"></script>
    <script src="js/codoplayer/CodoPlayer.js"></script>
    
    <script src="js/videoeditor.js"></script>
    <script src="js/videoeditor_langs.js"></script>
    <script>
        videoEditor.options.player_type = '<?php echo $config['player']; ?>';
        videoEditor.options.lang = '<?php echo $config['lang']; ?>';
        $(document).bind("ready",function(){
            $("body").tooltip(
                {
                    selector: "[data-toggle=tooltip],[data-toggle-tooltip]",
                    placement: "bottom",
                    viewport: { selector: "body", padding: 0 },
                    container: "body"
                }
            );
        });
    </script>
    
</head>
<body role="document">

<div class="logout_block">
    <div class="btn-group">
        
        <button type="button" id="btnLog" class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo LANG_VIEW_LOG; ?>">
            <span class="glyphicon glyphicon-list-alt"></span>
        </button>
        
        <?php if( $config['is_admin'] ): ?>
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" data-toggle-tooltip="1" title="<?php echo LANG_USERS; ?>">
                <span class="glyphicon glyphicon-user"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <?php foreach( $users_arr as $usr ): ?>
                <li><a href="<?php echo $_SERVER['PHP_SELF'] ?>?uid=<?php echo $usr['id']; ?>"><?php echo $usr['username']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <a class="btn btn-default btn-sm" href="index.php?logout=1" data-toggle="tooltip" title="<?php echo LANG_LOGOUT; ?>">
            <span class="glyphicon glyphicon-log-out"></span>
        </a>
        
    </div>
</div>

<div class="container editor-container" role="main">

<div class="jumbotron editor-wrapper">
    
    <div class="header row">
        <div class="pull-right">
            <button type="button" class="btn btn-info btn-lg" id="btnUpload">
                <span class="glyphicon glyphicon-upload"></span>
                <?php echo LANG_UPLOAD_VIDEO; ?>
            </button>
        </div>
        
        <h1>
            <span class="glyphicon glyphicon-film"></span>
            <?php echo LANG_VIDEOEDITOR; ?>
        </h1>
        
        <button type="button" class="btn btn-default btn-xs btn-block visible-xs-block visible-sm-block" data-toggle="collapse" data-target="#input-list">
            <span class="glyphicon glyphicon-list"></span>
        </button>
        
    </div>
    
    <div id="editor" class="row">
        
        <!-- input-list -->
        <div id="input-list" class="col-md-3 col-sm-12 col-xs-12 pull-right collapse">
            
            <div class="row">
                <div class="list-group col-md-offset-1" id="listInput">
                    
                </div>
            </div>
            
        </div>
        <!-- /input-list -->
        
        <!-- video-preview -->
        <div id="video-preview" class="col-md-9 col-sm-12 col-xs-12 pull-right">
            <div class="row video-preview-inner">
                <div class="col-md-6 col-sm-6"><div class="row left"></div></div>
                <div class="col-md-6 col-sd-6"><div class="row right"></div></div>
            </div>
        </div>
        <!-- /video-preview -->
        
    </div>
    
    <div class="clearfix"></div>
    
    <div class="row">
        
        <div class="time-line well">
            <div class="time-line-b">
                <span class="label label-default pull-left">00:00:00</span>
                <span class="label label-default pull-right">00:00:00</span>
                <div class="clearfix"></div>
            </div>
            <div id="time-range"></div>
            <div id="segments" style="display:none;"></div>
        </div>
        
        <div class="actions">
            
            <div class="row">
                
                <div class="col-md-2">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="btnStepBackward" data-toggle="tooltip" title="<?php echo LANG_FRAME_BACK; ?>">
                                <span class="glyphicon glyphicon-step-backward"></span>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="btnStepForward" data-toggle="tooltip" title="<?php echo LANG_FRAME_FORWARD; ?>">
                                <span class="glyphicon glyphicon-step-forward"></span>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="btnPlay" data-toggle="tooltip" title="<?php echo LANG_PLAY; ?>">
                                <span class="glyphicon glyphicon-play"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /col-md-2 -->
                
                <div class="col-md-5">
                    
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="text" id="v_time_in" class="form-control" readonly>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" id="v_time_out" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            
                            <div class="btn-group btn-group-justified">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-block" id="btnGetSegmet" data-toggle="tooltip" title="<?php echo LANG_ADD_SEGMENT; ?>">
                                        <span class="glyphicon glyphicon-save"></span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-block" id="btnRemoveSegmet" data-toggle="tooltip" title="<?php echo LANG_DELETE_SEGMENT; ?>">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <!-- /col-md-5 -->
                
                <div class="col-md-5">
                    <div class="btn-group btn-group-justified">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#createVideoModal">
                                <span class="glyphicon glyphicon-ok"></span>
                                <?php echo LANG_CREATE; ?>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-block" id="btnJoin">
                                <span class="glyphicon glyphicon-resize-small"></span>
                                <?php echo LANG_JOIN; ?>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-block" id="btnRemove">
                                <span class="glyphicon glyphicon-remove"></span>
                                <?php echo LANG_DELETE; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /col-md-5 -->
                
            </div>
            <!-- /row -->
            
        </div>
        <!-- /actions -->
        
        <div id="listOutput"></div>
    </div>
    <!-- /row -->
    
</div>
<!-- /editor-container -->

</div>
<!-- /editor-wrapper -->


<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo LANG_INFORMATION; ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="display:none;"><?php echo LANG_SAVE; ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo LANG_CLOSE; ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="action.php" enctype="multipart/form-data" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo LANG_UPLOAD_VIDEO; ?></h4>
                </div>
                <div class="modal-body">
                    
                    <input type="hidden" name="action" value="upload">
                    
                    <div class="form-group">
                        <label for="fieldUploadLink"><?php echo LANG_FILE_LINK; ?></label>
                        <input type="text" class="form-control" id="fieldUploadLink" name="link" placeholder="<?php echo LANG_FILE_LINK; ?>">
                    </div>
                    <div class="form-group">
                        <label for="fieldUploadFile"><?php echo LANG_FILE; ?></label>
                        <input type="file" name="file" id="fieldUploadFile">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo LANG_UPLOAD; ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo LANG_CLOSE; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="createVideoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="action.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo LANG_CREATE_VIDEO; ?></h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="opt_quality"><?php echo LANG_VIDEO_QUALITY; ?>:</label>
                        <select name="quality" id="opt_quality" class="form-control">
                            <option value="0"><?php echo LANG_WITHOUT_CONVERT; ?></option>
                            <option value="low"><?php echo LANG_QUALITY_LOW; ?></option>
                            <option value="medium"><?php echo LANG_QUALITY_MEDIUM; ?></option>
                            <option value="high"><?php echo LANG_QUALITY_HIGH; ?></option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label for="opt_size"><?php echo LANG_VIDEO_SIZE; ?>:</label>
                                <select name="quality" id="opt_size" class="form-control" disabled="disabled">
                                    <option value="360">360p</option>
                                    <option value="480">480p</option>
                                    <option value="576">576p</option>
                                    <option value="720">720p</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label for="opt_format"><?php echo LANG_VIDEO_FORMAT; ?>:</label>
                                <select name="format" id="opt_format" class="form-control" disabled="disabled">
                                    <option value="mp4">mp4</option>
                                    <option value="flv">flv</option>
                                    <option value="webm">webm</option>
                                    <option value="ogv">ogv</option>
                                    <option value="mp3">mp3</option>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit"><?php echo LANG_CREATE; ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo LANG_CLOSE; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>