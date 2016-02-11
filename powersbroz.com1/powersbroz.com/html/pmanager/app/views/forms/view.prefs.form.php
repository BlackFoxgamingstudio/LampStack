<?php
include_once '../../boot.php';
if (!$session->logged_in()) {
    echo 'Direct access blocked';exit;
}
if (!isset($_GET['scope'])) {
    echo 'You must define a scope for assignment'; exit;
}
?>

<?php if ($_GET['scope'] == 'user') { ?>

    <div class="row">
        <div class="col-md-12">
            <h2>User View Preferences <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <?= $app_preferences->debug_line();?>
            <form id="updateUserViewPrefsFrm" action="" method="post">
                <input type="hidden" name="update" value="user-view-prefs"/>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Layout</td>
                        <td>
                            <?php if ($app_preferences->get_layout('users') == 'grid') { ?>
                            <i class="fa fa-th"></i>
                            <input type="radio" name="layout" value="grid" checked> Grid<br/>
                            <i class="fa fa-list"></i>
                            <input type="radio" name="layout" value="list"> List
                            <?php } else { ?>
                                <i class="fa fa-th"></i>
                                <input type="radio" name="layout" value="grid">Grid<br/>
                                <i class="fa fa-list"></i>
                                <input type="radio" name="layout" value="list" checked> List
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Order</td>
                        <td>
                            <i class="fa fa-sort-alpha-asc"></i>
                            <input
                                type="radio"
                                name="order"
                                value="alpha"
                                <?= ($app_preferences->get_order('users') == 'alpha') ? 'checked' : '';?>/>
                            Alphabetically<br/>
                            <i class="fa fa-calendar"></i>
                            <input
                                type="radio"
                                name="order"
                                value="newest"
                                <?= ($app_preferences->get_order('users') == 'newest') ? 'checked' : '';?>/>
                            Newest on top<br/>
                        </td>
                    </tr>
                    <tr>
                        <td>Hide</td>
                        <td>
                            <i class="fa fa-lock"></i>
                            <input
                                type="checkbox"
                                name="hide"
                                value="lockedusers"
                                <?= ($app_preferences->display_locked() == true) ? 'checked' : '';?>/>
                            Locked accounts
                        </td>
                    </tr>
                    </tbody>
                </table>
                <span id="userViewPrefsFrmBtn" class="btn btn-success"><i class="fa fa-save"></i> Save</span>
            </form>
        </div>
    </div>

    <script>
        $('#userViewPrefsFrmBtn').on('click', function() {
            var formData = $('#userViewPrefsFrm').serialize();
            var frmBtn = $(this);
            frmBtn.html('<i class="fa fa-cog fa-spin"></i> Saving');
            $.post('./', formData, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    frmBtn.html('<i class="fa fa-save"></i> Save');
                    alert(data);
                }
            });
        });
    </script>


<?php } elseif ($_GET['scope'] == 'group') { ?>

    <div class="row">
        <div class="col-md-12">
            <h2>Group View Preferences <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Layout</td>
                        <td>
                            <i class="fa fa-th"></i> <input type="radio" name="layout" value="grid" checked> Grid<br/>
                            <i class="fa fa-list"></i> <input type="radio" name="layout" value="list"> List
                        </td>
                    </tr>
                    <tr>
                        <td>Order</td>
                        <td>
                            <i class="fa fa-sort-alpha-asc"></i> <input type="radio" name="order" value="alpha" checked/> Alphabetically<br/>
                            <i class="fa fa-calendar"></i> <input type="radio" name="order" value="newest"/> Newest on top<br/>
                            <i class="fa fa-sort-amount-desc"></i> <input type="radio" name="order" value="members"/> Amount of Members
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="alert alert-info">Layout settings are stored as cookies.</p>
        </div>
    </div>

<?php } elseif ($_GET['scope'] == 'project') { ?>

    <div class="row">
        <div class="col-md-12">
            <h2>Project View Preferences <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
        </div>
    </div>
    <div class="row push-vertical">
        <div class="col-md-12">
            <table class="table">
                <tbody>
                <tr>
                    <td>Layout</td>
                    <td>
                        <i class="fa fa-th"></i> <input type="radio" name="layout" value="grid" checked> Grid<br/>
                        <i class="fa fa-list"></i> <input type="radio" name="layout" value="list"> List
                    </td>
                </tr>
                <tr>
                    <td>Order</td>
                    <td>
                        <i class="fa fa-sort-alpha-asc"></i> <input type="radio" name="order" value="alpha" checked/> Alphabetically<br/>
                        <i class="fa fa-calendar"></i> <input type="radio" name="order" value="newest"/> Newest on top<br/>
                        <i class="fa fa-calendar"></i> <input type="radio" name="order" value="newest"/> Closest Due Date<br/>
                    </td>
                </tr>
                <tr>
                    <td>Notices</td>
                    <td>
                        <i class="fa fa-calendar"></i> <input type="radio" name="notice" value="1" checked/> Show Notices<br/>
                        <select name="notice-period" class="push-vertical">
                            <option value="7">One week out</option>
                            <option value="30">30 days out</option>
                            <option value="60">60 days out</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="alert alert-info">Layout settings are stored as cookies.</p>
        </div>
    </div>

<?php } else { ?>


<?php } ?>

<script>
    $('#closeThis').click(function() {
        closeForm();
    });
</script>