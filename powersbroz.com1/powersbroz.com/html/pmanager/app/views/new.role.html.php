<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Access Roles</h2>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>Define custom access privileges</h3>
                <p>Define your own unique user roles and assign privileges to your new role. By default, Entity offers five default templates:</p>
            </div>
            <div class="col-md-6">
                <h3>Defaults</h3>
                <ul class="list-unstyled list-inline">
                    <li><span class="label label-primary"><i class="fa fa-user"></i> The System Administrator</span></li>
                    <li><span class="label label-primary"><i class="fa fa-user"></i> The Project Lead</span></li>
                    <li><span class="label label-primary"><i class="fa fa-user"></i> The Employee</span></li>
                    <li><span class="label label-primary"><i class="fa fa-user"></i> The Contractor</span></li>
                    <li><span class="label label-primary"><i class="fa fa-user"></i> The Client / Customer</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
<div class="container">
<div class="row push-vertical">
<!-- User privelages selection -->
<div class="col-md-12">
<form id="access-permissions" name="access-permissions" method="post" action="./">
<input type="hidden" name="create" value="access-role"/>
<label for="rolename">Name of New Role:</label>
<input id="rolename" class="push-down" type="text" name="rolename" placeholder="Role name..."/>
<label for="roledesc">Short Description:</label>
<textarea id="roledesc" class="push-down" rows="5" name="roledesc" placeholder="Short description for you to remember..."></textarea>
<p>Begin creating your new role by selecting the individual permissions you want your new role to have. </p>
<h3 class="setting-group">Primary Role <a class="btn btn-primary pull-right" href="<?= BASE_URL;?>docs/#primary-roles" title="Learn more about primary roles"><i class="fa fa-question-circle"></i></a></h3>
<table class="table">
    <tbody>
    <tr>
        <td>
            Staff <input type="radio" name="basic" value="staff" checked/> &infin; Contractor / Business <input type="radio" name="basic" value="contractor" /> &infin; Client or Customer <input type="radio" name="basic" value="client"/></td>
    </tr>

    </tbody>
</table>
<h3 class="setting-group">System Actions [SysX]</h3>
<table class="table">
    <thead>
    <tr>
        <th width="75%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-primary">access</span> and <span class="label label-warning">update</span> the application's system settings menu</td>
        <td>Yes <input type="radio" name="system-settings" value="1"/> No <input type="radio" name="system-settings" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-primary">access</span> the Application documentation</td>
        <td>Yes <input type="radio" name="system-docs" value="1"/> No <input type="radio" name="system-docs" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-primary">access</span> the Application activity log</td>
        <td>Yes <input type="radio" name="system-activity" value="1"/> No <input type="radio" name="system-activity" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-warning">toggle</span> maintenance mode for application</td>
        <td>Yes <input type="radio" name="system-maintenance" value="1"/> No <input type="radio" name="system-maintenance" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-success">create</span> and <span class="label label-warning">edit</span> access roles</td>
        <td>Yes <input type="radio" name="system-roles" value="1"/> No <input type="radio" name="system-roles" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-success">create</span> and <span class="label label-warning">edit</span> wages and taxes</td>
        <td>Yes <input type="radio" name="system-wages" value="1"/> No <input type="radio" name="system-wages" value="0" checked/></td>
    </tr>
    </tbody>
</table>

<h3 class="setting-group">User Actions [UX]</h3>
<table class="table">
    <thead>
    <tr>
        <th width="75%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-success">create</span> user accounts</td>
        <td>Yes <input type="radio" name="user-create" value="1"/> No <input type="radio" name="user-create" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-warning">edit</span> other user accounts and profiles</td>
        <td>Yes <input type="radio" name="user-edit" value="1"/> No <input type="radio" name="user-edit" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-danger">delete</span> user accounts</td>
        <td>Yes <input type="radio" name="user-delete" value="1"/> No <input type="radio" name="user-delete" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-primary">assign</span> users to projects</td>
        <td>Yes <input type="radio" name="user-assign-project" value="1"/> No <input type="radio" name="user-assign-project" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-primary">assign</span> users to groups</td>
        <td>Yes <input type="radio" name="user-assign-group" value="1"/> No <input type="radio" name="user-assign-group" value="0" checked/></td>
    </tr>
    </tbody>
</table>

<h3 class="setting-group">Group Actions [GX]</h3>
<table class="table">
    <thead>
    <tr>
        <th width="75%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-success">create</span> groups</td>
        <td>Yes <input type="radio" name="group-create" value="1"/> No <input type="radio" name="group-create" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-warning">edit</span> groups</td>
        <td>Yes <input type="radio" name="group-edit" value="1"/> No <input type="radio" name="group-edit" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-danger">delete</span> groups</td>
        <td>Yes <input type="radio" name="group-delete" value="1"/> No <input type="radio" name="group-delete" value="0" checked/></td>
    </tr>
    </tbody>
</table>

<h3 class="setting-group">Group Access [GaX]</h3>
<table class="table">
    <thead>
    <tr>
        <th width="75%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-primary">access</span> any created group page</td>
        <td><input type="radio" name="group-access" value="all"/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-primary">access</span> only groups user is assigned to</td>
        <td><input type="radio" name="group-access" value="assigned" checked/></td>
    </tr>
    <tr>
        <td>Cannot <span class="label label-primary">access</span> group pages even if assigned</td>
        <td><input type="radio" name="group-access" value="none"/></td>
    </tr>
    </tbody>
</table>
<p class="alert alert-info">Group owners are responsible for maintaining group page information. If a user is assigned as a group owner, they will have access to the group page and be able to edit it regardless of what their role allows them to do on a group page.</p>
<h3 class="setting-group">Project Actions [PX]</h3>
<table class="table">
    <thead>
    <tr>
        <th width="50%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-success">create</span> a project</td>
        <td>Yes <input type="radio" name="project-create" value="1"/> No <input type="radio" name="project-create" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-warning">edit</span> project details and information to include marking it as complete</td>
        <td>Yes <input type="radio" name="project-edit" value="1"/> No <input type="radio" name="project-edit" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-danger">delete</span> a project</td>
        <td>Yes <input type="radio" name="project-delete" value="1"/> No <input type="radio" name="project-delete" value="0" checked/></td>
    </tr>
    <tr>
        <td>What about project stages</td>
        <td>
            <input type="radio" name="create-project-stages" value="all"/> Can create for all projects<br/>
            <input type="radio" name="create-project-stages" value="assigned"/> Can create if assigned to project<br/>
            <input type="radio" name="create-project-stages" value="none" checked /> Cannot create stages<br/>

        </td>
    </tr>
    <tr>
        <td>Can <span class="label label-danger">delete</span> project stages</td>
        <td>Yes <input type="radio" name="stage-delete" value="1"/> No <input type="radio" name="stage-delete" value="0" checked/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-success">create</span> and <span class="label label-danger">delete</span> project stage briefs</td>
        <td>Yes <input type="radio" name="stage-brief-access" value="1"/> No <input type="radio" name="stage-brief-access" value="0" checked/></td>
    </tr>
    <tr>
        <td>What about project stage tasks</td>
        <td>
            <input type="radio" name="create-project-stage-task" value="all"/> Can create for any stages for any project<br/>
            <input type="radio" name="create-project-stage-task" value="assigned"/> Can create for any stage in a project user is assigned to<br/>
            <input type="radio" name="create-project-stage-task" value="none" checked /> Cannot create stage tasks<br/>

        </td>
    </tr>
    <tr>
        <td>Can <span class="label label-danger">delete</span> project stage tasks</td>
        <td>Yes <input type="radio" name="stage-task-delete" value="1"/> No <input type="radio" name="stage-task-delete" value="0" checked/></td>
    </tr>
    <tr>
        <td>What about project tasks<br/>(Unstaged general tasks for a project)</td>
        <td>
            <input type="radio" name="create-project-task" value="all"/> Can create for any project<br/>
            <input type="radio" name="create-project-task" value="assigned"/> Can create for any project user is assigned to<br/>
            <input type="radio" name="create-project-task" value="none" checked /> Cannot create project tasks<br/>

        </td>
    </tr>
    <tr>
        <td>Can <span class="label label-danger">delete</span> project tasks</td>
        <td>Yes <input type="radio" name="delete-project-task" value="1"/> No <input type="radio" name="delete-project-task" value="0" checked/></td>
    </tr>
    </tbody>
</table>

<h3 class="setting-group">Project Access [PaX]</h3>
<table class="table">
    <thead>
    <tr>
        <th width="75%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-primary">access</span> any created project page</td>
        <td><input type="radio" name="project-access" value="all"/></td>
    </tr>
    <tr>
        <td>Can <span class="label label-primary">access</span> only project user is assigned to</td>
        <td><input type="radio" name="project-access" value="assigned" checked/></td>
    </tr>
    <tr>
        <td>Cannot <span class="label label-primary">access</span> project pages even if assigned</td>
        <td><input type="radio" name="project-access" value="none"/></td>
    </tr>
    </tbody>
</table>
<p class="alert alert-info">Project leads are responsible for maintaining project page information. If a user is assigned as a project lead, they will have access to the project page and be able to edit it regardless of what their role allows them to do on a project's page.</p>

<h3 class="setting-group">Invoicing [InvX]</h3>
<table class="table">
    <thead>
        <tr>
            <th width="50%">Permission</th>
            <th>Setting</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Can <span class="label label-success">create</span> invoices</td>
            <td>Yes <input type="radio" name="inv-create" value="1"/> No <input type="radio" name="inv-create" value="0" checked/></td>
        </tr>
        <tr>
            <td>Can <span class="label label-warning">edit</span> invoices</td>
            <td>Yes <input type="radio" name="inv-edit" value="1"/> No <input type="radio" name="inv-edit" value="0" checked/></td>
        </tr>
        <tr>
            <td>Can <span class="label label-danger">delete</span> invoices</td>
            <td>Yes <input type="radio" name="inv-delete" value="1"/> No <input type="radio" name="inv-delete" value="0" checked/></td>
        </tr>
        <tr>
            <td>Can <span class="label label-primary">receive payment</span> from invoices</td>
            <td>Yes <input type="radio" name="inv-payment" value="1"/> No <input type="radio" name="inv-payment" value="0" checked/></td>
        </tr>
        <tr>
            <td>If this person can create invoices, who can they charge?</td>
            <td>
                <input type="radio" name="inv-chargeto" value="owner"/> Application Owner only<br/>
                <input type="radio" name="inv-chargeto" value="staff"/> Staff only<br/>
                <input type="radio" name="inv-chargeto" value="clients"/> Clients only<br/>
                <input type="radio" name="inv-chargeto" value="all"/> Anyone<br/>
                <input type="radio" name="inv-chargeto" value="none" checked/> No one<br/>
            </td>
        </tr>
    </tbody>
</table>

<h3 class="setting-group">File System Operations and Limitations [FSX]</h3>
<p>Fine tune what users assigned to this role can do with files and limit the space they can use.</p>
<table class="table">
    <thead>
    <tr>
        <th width="50%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Can <span class="label label-primary">upload</span> files</td>
        <td>Yes <input type="radio" name="upload-file" value="1"/> No <input type="radio" name="upload-file" value="0" checked/></td>
    </tr>
    <tr>
        <td>Maximum space allowed</td>
        <td>
            <input style="float:left; width: 50%;" type="text" name="upload-max-space" value="500" />
            <select style="float:left; width: 50%;" name="upload-max-space-unit">
                <option value="kb">KB</option>
                <option value="mb" selected>MB</option>
                <option value="gb">GB</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Maximum space override</td>
        <td>Unlimited space <input type="checkbox" name="upload-max-space-override"/></td>
    </tr>
    <tr>
        <td>
            Administrative File Permission<br/>
            <span class="subdued">Can view all files currently being tracked by the application and can delete any of those files</span>
        </td>
        <td>Yes <input type="radio" name="admin-file" value="1"/> No <input type="radio" name="admin-file" value="0" checked/></td>
    </tr>
    </tbody>
</table>
<p class="alert alert-info">The allowed file type list is hard-coded into the application. Please review the documentation on ways to customize this.</p>

<h3 class="setting-group">Visibility Scope and Communication [VisX]</h3>
<p>Visibility and communication scope is extremely important. Think carefully about which options you give to your users, especially users whose role is that of a third party contractor or sub-contractor.</p>
<table class="table">
    <thead>
    <tr>
        <th width="75%">Permission</th>
        <th>Setting</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2"><strong>Address Book for Instant Messaging</strong></td>
    </tr>
    <tr>
        <td>Full system address book access (All staff, contractors, and clients)</td>
        <td><input type="radio" name="system-addressbook" value="full"/></td>
    </tr>
    <tr>
        <td>Assigned system address book access (Staff, contractors, and clients assigned to the same group or project)</td>
        <td><input type="radio" name="system-addressbook" value="assigned" checked/></td>
    </tr>
    <tr>
        <td>Staff only address book (Only roles considered staff)</td>
        <td><input type="radio" name="system-addressbook" value="staff"/></td>
    </tr>
    <tr>
        <td>No address book access</td>
        <td><input type="radio" name="system-addressbook" value="none"/></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Calendar access</strong></td>
    </tr>
    <tr>
        <td>Can see only those tasks and items that they have access to</td>
        <td><input type="radio" name="cal-view" value="assigned" checked /></td>
    </tr>
    <tr>
        <td>Have admin rights and can see application wide events</td>
        <td><input type="radio" name="cal-view" value="all" /> </td>
    </tr>
    </tbody>
</table>

<div class="text-center push-vertical">
    <button id="access-permissions-submit" type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i> Create Role</button>
</div>

</form>
</div>
</div>
</div>
</div>

<script>
$('#access-permissions').on('submit', function(e) {
    e.preventDefault();
    var submitButton = $('#access-permissions-submit');
    submitButton.html('<i class="fa fa-cog fa-spin"></i> Working...');
    var permissionData = $(this).serialize();
    $.post('./', permissionData, function(data) {
        if (data == 'success') {
            window.location = '<?= BASE_URL.'access/';?>';
        } else {
            entityError('Error Creating Role', data);
        }
        submitButton.html('<i class="fa fa-save"></i> Create Role');
    });

});
</script>