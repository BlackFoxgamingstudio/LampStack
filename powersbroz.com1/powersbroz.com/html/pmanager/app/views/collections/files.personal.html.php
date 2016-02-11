<?php
require_once '../../boot.php';
if (!isset($_GET['scope'])) {
    echo 'Improper Access'; exit;
} else {
    if (!isset($_GET['scope'])) {
        $scope = 'all';
    } else {
        $scope = $_GET['scope'];
    }

    switch($scope) {

        case 'all':
            $sql = "SELECT * FROM files WHERE uploadedby = ".$current_user->id()." ORDER BY created DESC";
            break;
        case 'search':
            $search = mysqli_real_escape_string($con->gate, trim($_GET['search']));
            $sql = "SELECT * FROM files WHERE uploadedby = ".$current_user->id()." AND filename LIKE '%".$search."%' ORDER BY created DESC";
            break;
    }
    $personalFiles = File::find('sql', $sql);

    if ($personalFiles) {
        $total  = count($personalFiles);
        $rows   = ceil($total / 3);
    }

}
?>

<div class="section">
    <div class="container">
        <?php if ($personalFiles) { ?>
            <?php
            $i      = 0;
            $cell   = 1;
            ?>
            <?php for ($row = 0; $row < $rows;$row++) { ?>
            <div class="row">

                    <?php do { ?>

                    <div class="col-md-4">
                        <div class="box">
                            <?= $personalFiles[$i]->demo();?>
                            <h3><?= $personalFiles[$i]->name();?></h3>
                            <ul class="list-unstyled">
                                <li><?= $personalFiles[$i]->type();?></li>
                                <li><?= $personalFiles[$i]->size();?></li>
                            </ul>
                            <div class="file-box-options">
                                <ul>
                                    <li><i class="fa fa-download"></i></li>
                                    <li class="deleteFileBtn" file="<?= $personalFiles[$i]->id();?>"><i class="fa fa-times-circle"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php

                    if ($cell < 3 ) {
                        $cell++;
                        if ($i + 1 == $total) {
                            break;
                        }
                        $i++;
                    } else {
                        $i++;
                        $cell = 1;
                        break;

                    }

                    ?>

                    <?php } while($cell <= 3); // End Do while loop ?>

            </div>
            <?php } // End personal files FOR Loop ?>

            <script>
                $('.deleteFileBtn').click(function() {
                    var selectedFile = $(this).attr('file');
                    $.post('./', {delete: 'file', file: selectedFile}, function(data) {
                        if (data == 'success') {
                            window.location.reload();
                        } else {
                            entityError('Unable to Delete File', data);
                        }
                    });
                });
            </script>

        <?php } else { ?>

        <div class="row">
            <div class="col-md-12">
                <h3>No files to display</h3>
            </div>
        </div>

        <?php } ?>
    </div>
</div>

