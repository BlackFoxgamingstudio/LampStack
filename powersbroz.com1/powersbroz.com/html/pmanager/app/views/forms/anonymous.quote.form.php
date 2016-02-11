<?php
include_once '../../boot.php';

$notfound       = false;
$notset         = false;
$unauthorized   = false;


if ($app_settings->get('allow_anonymous_quotes') != 1) {
    $unauthorized = true;
}

if ($app_settings->get('login_quotes_default_form') != 0) {
    $form = Form::find('id', $app_settings->get('login_quotes_default_form'));
    if (!$form) {
        $notfound = true;
    }
} else {
    $notset = true;
}



?>

<div class="row">
    <div class="col-md-12">
        <?php if ($unauthorized) { ?>
        <h2>Anonymous Quotes Disabled <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        <?php } elseif ($notset) { ?>
        <h2>Anonymous Quotes Not Configured <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        <?php } elseif ($notfound) { ?>
        <h2>Form Not Found <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        <?php } else { ?>
        <h2><?= $form->name();?> <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12 paper paper-curve">

        <?php if ($unauthorized) { ?>

            <div class="nothing-full">
                <h2>Unauthorized Access</h2>
                <p>The owner of this site has not authorized anonymous quotes. Please contact support for further assistance.</p>
            </div>

        <?php } elseif ($notset) { ?>

            <div class="nothing-full">
                <h2>No Form to Handle Request</h2>
                <p>The owner of this site has not configured a form to deal with anonymous quotes. Please contact support for further assistance.</p>
            </div>

        <?php } elseif ($notfound) { ?>

            <div class="nothing-full">
                <h2>Error loading form</h2>
                <p>The form configured to receive anonymous quotes could not be found. Please contact support for further assistance.</p>
            </div>

        <?php } else { ?>


            <form id="form-<?= $form->id();?>" name="<?= $form->slug();?>" action="./" method="post" enctype="multipart/form-data">

                <?php $fields = $form->get_fields();?>

                <?php if ($fields) { ?>

                    <input type="hidden" name="create" value="anon-quote"/>
                    <input type="hidden" name="form" value="<?= $form->id();?>"/>

                    <?php foreach ($fields as $field) { ?>
                        <div class="bump-vertical">
                            <?= $field->render();?>
                        </div>
                    <?php } ?>
                    <p>
                        <button type="submit" id="submitQuoteBtn" class="btn btn-success"><i class="fa fa-paper-plane"></i> Send</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                    </p>
                <?php } else { ?>

                    <div class="nothing-full">
                        <h2>No fields to display</h2>
                    </div>

                <?php } ?>

            </form>
            <?php if (!$form->has_file_input()) { ?>
            <script>

                $('#submitQuoteBtn').click('submit', function(event) {
                    event.preventDefault();
                    $('#submitQuoteBtn').html('<i class="fa fa-cog fa-spin"></i> Working...');
                    var submissionData = $('#form-<?= $form->id();?>').serialize();
                    $.post('./', submissionData, function(data) {
                        if (data == 'success') {
                            document.getElementById('form-<?= $form->id();?>').reset();
                            $('#submitQuoteBtn').html('<i class="fa fa-paper-plane"></i> Send');
                            entityError('Thanks!', 'Your quote submission has been received and will be review promptly');
                        } else {
                            entityError('Error', data);
                            $('#submitQuoteBtn').html('<i class="fa fa-paper-plane"></i> Send');
                        }
                    });

                });
            </script>
            <?php } // End if has file field ?>

        <?php } ?>


    </div>
</div>


<script>
    $('#closeThis').click(function() {
        closeForm();
    });
</script>