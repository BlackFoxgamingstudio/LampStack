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
            <h2>User Assignment <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p>What would you like to do?</p>
            <ul class="list-inline list-unstyled">
                <?php if ($current_user->role()->can('project', 'assign')) { ?>
                <li><span class="btn btn-primary assignToggle" data="userToProjectContainer">Assign a user to a project</span></li>
                <?php } ?>
                <li><span class="btn btn-primary assignToggle" data="userProjectLeadContainer">Assign a project lead</span></li>
                <?php if ($current_user->role()->can('group', 'assign')) { ?>
                <li><span class="btn btn-primary assignToggle" data="userToGroupContainer">Assign a user to a group</span></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">

            <?php if ($current_user->role()->can('project', 'assign')) { ?>
            <div id="userToProjectContainer" class="subForm">
                <form id="userToProject" action="./" method="post">
                    <input type="hidden" name="assign" value="user-to-project"/>
                    <label for="assigneduser">Assign User</label>
                    <?= User::html_select('assigneduser', array('user' => 'no-admin'));?>
                    <label for="assignedproject">To Project</label>
                    <?= Project::html_select('assignedproject', array('project' => 'not-complete'));?>
                    <button id="userToProjectSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>
            <?php } ?>

            <div id="userProjectLeadContainer" class="subForm">
                <form id="userProjectLead" action="./" method="post">
                <input type="hidden" name="assign" value="user-projectlead"/>
                    <label for="leaduser">Assign User:</label>
                    <?= User::html_select('leaduser', array('user' => 'no-admin'));?>
                    <label for="assignedproject">As Project Lead for</label>
                    <?= Project::html_select('assignedproject', array('project' => 'no-lead not-complete'));?>
                    <button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

            <?php if ($current_user->role()->can('group', 'assign')) { ?>
            <div id="userToGroupContainer" class="subForm">
                <form id="userToGroup" action="./" method="post">
                    <input type="hidden" name="assign" value="user-to-group"/>
                    <label for="groupuser">Assign User:</label>
                    <?= User::html_select('groupuser', array('user' => 'no-admin'));?>
                    <label for="assignedgroup">To Group</label>
                    <?= Group::html_select('assignedgroup');?>
                    <button id="userToGroupSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>
            <?php } ?>

        </div>
    </div>

    <script>
        $('#userToProject').on('submit', function(e) {
            e.preventDefault();
            $('#userToProjectSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
            var userToProject = $('#userToProject').serialize();
            $.post('./', userToProject, function(data) {
                if (data == 'success') {
                    entityError('Successful', 'User was successfully assigned to project!');
                    document.getElementById('userToProject').reset();
                    $('#userToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                } else {
                    entityError('Error assigning to project', data);
                    $('#userToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                }
            });
        });
    </script>



<?php } elseif ($_GET['scope'] == 'group') { ?>



    <div class="row">
        <div class="col-md-12">
            <h2>Group Assignment <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p>What would you like to do?</p>
            <ul class="list-inline list-unstyled">
                <li><span class="btn btn-primary assignToggle" data="userToGroupContainer">Assign a user to a group</span></li>
                <li><span class="btn btn-primary assignToggle" data="groupLeadContainer">Assign a group owner</span></li>
                <li><span class="btn btn-primary assignToggle" data="groupToProjectContainer">Assign a group to a project</span></li>
            </ul>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">

            <div id="userToGroupContainer" class="subForm">
                <form id="userToGroup" action="./" method="post">
                    <input type="hidden" name="assign" value="user-to-group"/>
                    <label for="groupuser">Assign User</label>
                    <?= User::html_select('groupuser', array('user' => 'no-admin'));?>
                    <label for="assignedgroup">To Group</label>
                    <?= Group::html_select('assignedgroup');?>
                    <button type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

            <div id="groupLeadContainer" class="subForm">
                <form id="groupLead" action="./" method="post">
                    <input type="hidden" name="assign" value="user-groupowner"/>
                    <label for="leaduser">Assign User</label>
                    <?= User::html_select('leaduser', array('user' => 'no-admin'));?>
                    <label for="ownedgroup">As Group Owner for</label>
                    <?= Group::html_select('ownedgroup', array('group' => 'no-owner'));?>
                    <button id="groupToLeadSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

            <div id="groupToProjectContainer" class="subForm">
                <form id="groupToProject" action="./" method="post">
                    <input type="hidden" name="assign" value="group-to-project"/>
                    <label for="assigngroup">Assign Group</label>
                    <?= Group::html_select('assigngroup');?>
                    <label for="assignedproject">To Project</label>
                    <?= Project::html_select('assignedproject', array('project' => 'not-complete'));?>
                    <button id="groupToProjectSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        $('#groupToProject').on('submit', function(e) {
            e.preventDefault();
            $('#groupToProjectSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
            var groupToProject = $('#groupToProject').serialize();
            $.post('./', groupToProject, function(data) {
                if (data == 'success') {
                    entityError('Successful', 'Group was successfully assigned to project!');
                    document.getElementById('groupToProject').reset();
                    $('#groupToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                } else {
                    entityError('Error assigning to project', data);
                    $('#groupToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                }
            });
        });
        $('#groupLead').on('submit', function(e) {
            e.preventDefault();
            $('#groupToLeadSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
            var groupLead = $('#groupLead').serialize();
            $.post('./', groupLead, function(data) {
                if (data == 'success') {
                    entityError('Successful', 'User was successfully assigned as group owner!');
                    document.getElementById('groupLead').reset();
                    $('#groupToLeadSubmit').html('<i class="fa fa-check"></i> Do it!');
                } else {
                    entityError('Error assigning group owner', data);
                    $('#groupToLeadSubmit').html('<i class="fa fa-check"></i> Do it!');
                }
            });
        });
    </script>

<?php } elseif ($_GET['scope'] == 'project') { ?>


    <div class="row">
        <div class="col-md-12">
            <h2>Project Assignment <span id="closeThis" class="btn btn-danger pull-right"><i class="fa fa-times-circle"></i> Close</span></h2>
            <p>What would you like to do?</p>
            <ul class="list-inline list-unstyled">
                <li><span class="btn btn-primary assignToggle" data="userToProjectContainer">Assign a user to a project</span></li>
                <li><span class="btn btn-primary assignToggle" data="groupToProjectContainer">Assign a group to a project</span></li>
                <li><span class="btn btn-primary assignToggle" data="userToProjectLeadContainer">Assign a project lead</span></li>
            </ul>
        </div>
    </div>

    <div class="row push-vertical">
        <div class="col-md-12">

            <div id="userToProjectContainer" class="subForm">
                <form id="userToProject" action="./" method="post">
                    <input type="hidden" name="assign" value="user-to-project"/>
                    <label for="assigneduser">Assign User:</label>
                    <?= User::html_select('assigneduser', array('user' => 'no-admin'));?>
                    <label for="assignedproject">To Project:</label>
                    <?= Project::html_select('assignedproject', array('project' => 'not-complete'));?>
                    <button id="userToProjectSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

            <div id="groupToProjectContainer" class="subForm">
                <form id="groupToProject" action="./" method="post">
                    <input type="hidden" name="assign" value="group-to-project"/>
                    <label for="assigngroup">Assign Group:</label>
                    <?= Group::html_select('assigngroup');?>
                    <label for="assignedproject">To Project:</label>
                    <?= Project::html_select('assignedproject', array('project' => 'not-complete'));?>
                    <button id="groupToProjectSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

            <div  id="userToProjectLeadContainer" class="subForm">
                <form id="userToProjectLead" action="./" method="post">
                    <input type="hidden" name="assign" value="user-projectlead"/>
                    <label for="leaduser">Assign User:</label>
                    <?= User::html_select('leaduser', array('user' => 'only-staff'));?>
                    <label for="assignedproject">As Project Lead For:</label>
                    <?= Project::html_select('assignedproject', array('project' => 'no-lead not-complete'));?>
                    <button id="userToProjectLeadSubmit" type="submit" class="btn btn-success push-vertical"><i class="fa fa-check"></i> Do it!</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        $('#userToProject').on('submit', function(e) {
            e.preventDefault();
            $('#userToProjectSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
            var userToProject = $('#userToProject').serialize();
            $.post('./', userToProject, function(data) {
                if (data == 'success') {
                    entityError('Successful', 'User was successfully assigned to project!');
                    document.getElementById('userToProject').reset();
                    $('#userToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                } else {
                    entityError('Error assigning to project', data);
                    $('#userToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                }
            });
        });
        $('#groupToProject').on('submit', function(e) {
            e.preventDefault();
            $('#groupToProjectSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
            var groupToProject = $('#groupToProject').serialize();
            $.post('./', groupToProject, function(data) {
                if (data == 'success') {
                    entityError('Successful', 'Group was successfully assigned to project!');
                    document.getElementById('groupToProject').reset();
                    $('#groupToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                } else {
                    entityError('Error assigning to project', data);
                    $('#groupToProjectSubmit').html('<i class="fa fa-check"></i> Do it!');
                }
            });
        });
        $('#userToProjectLead').on('submit', function(e) {
            e.preventDefault();
            $('#userToProjectLeadSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
            var userToProjectLead = $('#userToProjectLead').serialize();
            $.post('./', userToProjectLead, function(data) {
                if (data == 'success') {
                    entityError('Successful', 'User was successfully assigned as the Project Lead!');
                    document.getElementById('userToProjectLead').reset();
                    $('#userToProjectLeadSubmit').html('<i class="fa fa-check"></i> Do it!');
                } else {
                    entityError('Error assigning to project', data);
                    $('#userToProjectLeadSubmit').html('<i class="fa fa-check"></i> Do it!');
                }
            });
        });
    </script>


<?php } else { ?>

    <div class="row">
        <div class="col-md-12">
            <h2>Unrecognized Request</h2>
            <p>Something went wrong with your request. Entity does not know how to handle this assignment request. Please try again. If this error occurs again, please contact support.</p>
        </div>
    </div>

<?php } ?>

<script>
    $('.subForm').hide();
    $('.assignToggle').click(function() {
        var div = $(this).attr('data');
        $('.subForm').attr('class', 'subForm animated boundeOutRight').hide();
        $('#'+ div).attr('class', 'subForm animated bounceInLeft').show();
    });
    $('#closeThis').click(function() {
        closeForm();
    });
    // Common forms
    $('#userToGroup').on('submit', function(e) {
        e.preventDefault();
        $('#userToGroupSubmit').html('<i class="fa fa-cog fa-spin"></i> Working');
        var userToGroup = $('#userToGroup').serialize();
        $.post('./', userToGroup, function(data) {
            if (data == 'success') {
                entityError('Successful', 'User was successfully assigned to group!');
                document.getElementById('userToGroup').reset();
                $('#userToGroupSubmit').html('<i class="fa fa-check"></i> Do it!');
            } else {
                entityError('Error assigning to group', data);
                $('#userToGroupSubmit').html('<i class="fa fa-check"></i> Do it!');
            }
        });
    });
</script>
