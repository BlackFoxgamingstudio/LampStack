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
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="list-unstyled list-inline">
                    <li>Filter</li>
                    <li><span id="resetFilters" class="btn btn-primary"><i class="fa fa-refresh"></i> All</span></li>
                    <li><span class="btn btn-primary toggleRole" data="Staff"><i class="fa fa-user"></i> Staff</span></li>
                    <li><span class="btn btn-danger toggleRole" data="Contractor"><i class="fa fa-briefcase"></i> Contractors</span></li>
                    <li><span class="btn btn-success toggleRole" data="Client"><i class="fa fa-money"></i> Clients</span></li>
                    <li>Actions</li>
                    <li><a href="<?= BASE_URL;?>access/create/" title="Create a new role" class="btn btn-success"><i class="fa fa-plus"></i> New</a></li>
                    <li class="pull-right"><a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary"><i class="fa fa-home"></i> Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="15%">Name</th>
                            <th width="10%">Association</th>
                            <th>Description</th>
                            <th>View Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($r = 0; $r < count($roles);$r++) { ?>
                        <tr class="roleRecord role<?= $roles[$r]->classification();?>">
                            <td><?= $roles[$r]->name();?></td>
                            <td><?= $roles[$r]->classification('format');?></td>
                            <td><?= $roles[$r]->description();?></td>
                            <td>
                                <a class="btn btn-primary"
                                   href="<?= BASE_URL;?>access/view/<?= $roles[$r]->id();?>"
                                   title="View permissions for <?= $roles[$r]->name();?>">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr/>


                <h2>Mass Actions</h2>
                <div class="well well-lg">
                    <form id="massReassignRole" name="massReassignRole" action="" method="post">
                    <input type="hidden" name="mass" value="reassign-user-role"/>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>I want to reassign all users who are currently assigned as:</td>
                                <td>
                                    <?= Role::html_select('initial-role');?>
                                </td>
                            </tr>
                            <tr>
                                <td>To now have the following role:</td>
                                <td>
                                    <?= Role::html_select('new-role');?>
                                </td>
                            </tr>
                            <tr>
                                <td>Delete old role? (Entity will only delete custom roles)</td>
                                <td>
                                    Yes <input type="radio" name="delete-old" value="1"/> No <input type="radio" name="delete-old" value="0" checked/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button id="massReassignRoleBtn" class="btn btn-success" type="submit"><i class="fa fa-save"></i> Do it</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h3>Total</h3>
                    <p><span class="stat"><?= Role::total();?></span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3>Custom</h3>
                    <p><span class="stat"><?= Role::total_custom();?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.toggleRole').click(function() {
        var classifaction = $(this).attr('data');
        $('.roleRecord').hide();
        $('.role' + classifaction).show();
    });
    $('#resetFilters').click(function() {
        $('.roleRecord').show();
    });
    $('#massReassignRole').on('submit', function(e) {
        e.preventDefault();
        $('#massReassignRoleBtn').html('<i class="fa fa-cog fa-spin"></i> Working...');
        var massReassignRoleData = $('#massReassignRole').serialize();
        $.post('./', massReassignRoleData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Complete Request', data);
                $('#massReassignRoleBtn').html('<i class="fa fa-save"></i> Do it');
            }
        })
    });
</script>