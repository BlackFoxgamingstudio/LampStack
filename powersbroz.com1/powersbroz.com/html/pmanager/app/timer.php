<?php require_once 'boot.php';
if (isset($_GET['project'])) {
    $projectid = mysqli_real_escape_string($con->gate, $_GET['project']);
    $project = Project::find('id', $projectid);
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Entity Active Project Timer</title>
    <meta name="description" content="Click to start timer and be sure to leave this window open. If you close the window, the timer will be stopped and recorded as is.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php require_once INCLUDES.'css.html.php';?>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
</head>
<body>


<div id="timer-container" class="timer-container">
    <div class="timer-content">
        <form id="createTimerItem" action="./" method="post">
            <div class="row">
                <?php if ($project) { ?>
                <div class="col-sm-12 text-center">
                    <span id="timer">
                        <span id="hours">0</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label for="billableYes">Is this billable time?</label>
                    <p class="well well-sm">Yes <input id="billableYes" type="radio" name="billable" value="1"/> | No <input type="radio" name="billable" value="0" checked /></p>
                    <?php $hourlywages = Wage::find('sql', "SELECT * FROM wages WHERE is_flat = 0 ORDER BY wname ASC");?>
                    <?php if ($hourlywages) { ?>
                        <div style="display:none;" id="billable-rates" class="push-vertical">
                            <label for="charge">Which rate?</label>
                            <select id="charge">
                                <option value="0">I will select this later</option>
                                <?php for ($hw = 0; $hw < count($hourlywages);$hw++) { ?>
                                    <option value="<?= $hourlywages[$hw]->id();?>"><?= $hourlywages[$hw]->name();?> $<?= $hourlywages[$hw]->rate();?>/hr</option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-6">
                    <label for="timenote">Notes:</label>
                    <textarea name="timenote" id="timenote" rows="4"></textarea>
                </div>
                <?php } else { ?>
                <div class="col-md-12">
                    <p>No project was passed or you do not have access to this project</p>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p><span id="timer-btn" class="btn btn-primary"><i class="fa fa-clock-o"></i>Start Timer</span> <button type="submit" id="timer-submit-btn" class="btn btn-success"><i class="fa fa-check-circle"></i> Submit Time</button></p>
                    <p class="subdued">Leave this window open and be sure to submit your time before closing it! You can always go back and edit your time manually later from the project or invoice pages.</p>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    var billable = 0;
    $('[name="billable"]').on('change', function() {
        if ($(this).val() == 1) {
            $('#billable-rates').show();
            billable = 1;
        } else {
            $('#billable-rates').hide();
            billable = 0;
        }
    });
    $('#timer-submit-btn').hide();
    function updateBorder(timer) {
        if (timer) {
            $('#timer-container').attr('style', 'border: 5px solid #ba5e00;');
        } else {
            $('#timer-container').attr('style', 'border: 5px solid #05ba0a;');
        }
    }
    var timer = false;
    var seconds = 0;
    var minutes = 0;
    var hours   = 0;
    var timerInstance;
    var confirmationMessage;
    $('#timer-btn').click(function() {
        if (timer == false) {
            $('#timer-submit-btn').hide();
            timer = true;
            updateBorder(timer);
            $('#timer-btn').attr('class', 'btn btn-danger').html('<i class="fa fa-clock-o fa-spin"></i> Stop Timer');
            timerInstance = setInterval(function() {
                seconds++;
                if (seconds >= 60) {
                    minutes++;
                    if (minutes >= 60) {
                        hours++;
                        minutes = 0;
                    }
                    seconds = 0;
                }
                $('#hours').html(hours);
                if (minutes >= 10) {
                    $('#minutes').html(minutes);
                } else {
                    $('#minutes').html('0' + minutes);
                }
                if (seconds >= 10) {
                    $('#seconds').html(seconds);
                } else {
                    $('#seconds').html('0' + seconds);
                }

            }, 1000);
        } else {
            $('#timer-btn').attr('class', 'btn btn-primary').html('<i class="fa fa-clock-o"></i> Resume Timer');
            timer = false;
            updateBorder(timer);
            clearInterval(timerInstance);
            $('#timer-submit-btn').show();
        }

    });
    window.addEventListener("beforeunload", function (e) {
        if (timer) {
            confirmationMessage = "If you leave this page, your time will not be recorded and will have to be entered manually!";

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            return confirmationMessage;                            //Webkit, Safari, Chrome
        } else {
            if (hours > 0 || minutes > 0 || seconds > 0) {
                confirmationMessage = "If you have recorded time, please submit it before leaving this page!";

                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage;
            }
        }
    });
    $('#createTimerItem').on('submit', function(e) {
        e.preventDefault();
        var submission      = {};
        submission.billable = billable;
        submission.rate     = $('#charge').val();
        submission.project  = '<?= $project->id();?>';
        submission.notes    = $('#timenote').val();
        submission.hours    = hours;
        submission.minutes  = minutes;
        submission.seconds  = seconds;
        submission.create   = "timer-item";

        $.post('timer.php', submission, function(data) {
            if (data == "success") {
                document.getElementById('createTimerItem').reset();
                alert("Your time was logged!");
                window.close();
            } else {
                alert(data);
            }
        });
    });
</script>


</body>
</html>