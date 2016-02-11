<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
?>
<form id="createInvoice" action="./" method="post">
    <input type="hidden" name="create" value="invoice"/>
    <div class="row">
        <div class="col-md-12">
            <h2>Create a New Invoice <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="invname">Quick Name:</label>
            <input type="text" name="invname" id="invname"/>
            <label for="invdesc">Special instructions</label>
            <textarea rows="5" name="invdesc" id="invdesc"></textarea>
        </div>
    </div>
    <?php
    if ($current_user->role()->is_staff()) {
        $projects = Project::find('all');
    } else {
        $projects = Project::user_project_list($current_user);
    }
    ?>
    <div class="row push-vertical">
        <div class="col-md-12">
            <select name="iproject" id="iproject">
                <option value="0">No project</option>
                <?php if ($projects) { ?>
                <?php for ($p = 0; $p < count($projects);$p++) { ?>
                    <option value="<?= $projects[$p]->id();?>"><?= $projects[$p]->name();?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <div id="iprojectteam"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="paidby">Paid by:</label>
            <?php
            if ($current_user->role()->is_contractor()) {
                echo User::html_select('paidby', array('user' => 'only-staff'));
            } elseif ($current_user->role()->is_staff()) {
                echo User::html_select('paidby', array('user' => 'not-me'));
            } else {
                echo '<p class="alert alert-warning">You cannot charge invoices</p>';
            }
            ?>
        </div>
        <div class="col-md-6">
            <label for="paidto">Paid to:</label>
            <?php
            if ($current_user->role()->is_contractor()) {
                echo User::html_select('paidto', array('user' => 'me'));
            } elseif ($current_user->role()->is_staff()) {
                echo User::html_select('paidto', array('user' => 'only-staff'));
            } else {
                echo '<p class="alert alert-warning">You cannot get paid</p>';
            }
            ?>
        </div>
    </div>
    <?php if ($current_user->role()->is_staff()) { ?>
    <div class="row push-vertical">
        <div class="col-md-12 text-center">
            <div class="well well-lg">
                <p>Collect Payment in Company Account? <input type="radio" name="for-company" value="1"/> Yes <input type="radio" name="for-company" value="0" checked/> No</p>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="row push-vertical">
        <div class="col-md-12">
            <label for="currency">Currency</label>
            <?= Currency::html_select('currency', array('currency' => 'all'));?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for="duedate">Due Date: (MM/DD/YYYY)</label>
            <input type="date" name="duedate" id="duedate"/>
            <p><button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Make it</button></p>
        </div>
    </div>
</form>
<script>
    var invoicesCreated = 0;
    $('#iproject').on('change', function() {
        var project = $(this).val();
        $.get('<?= BASE_URL;?>app/collections/project.members.unique.php', {project: project}, function(data) {
            $('#iprojectteam').html(data);
        });
    });
    $('#closeThis').click(function() {
        if (invoicesCreated > 0) {
            window.location.reload();
        } else {
            closeForm();
        }

    });
    $('#createInvoice').on('submit', function(e) {
        e.preventDefault();
        var createInvoice = $('#createInvoice').serialize();
        $.post('./', createInvoice, function(data) {
            if (data == 'success') {
                entityError('Successful', 'Invoice was successfully created!');
                document.getElementById('createInvoice').reset();
                invoicesCreated++;
            } else {
                entityError('Error Creating Invoice', data);
            }
        });
    });
</script>