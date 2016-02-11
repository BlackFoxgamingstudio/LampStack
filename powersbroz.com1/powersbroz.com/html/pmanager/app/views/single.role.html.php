<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?= $role->name();?></h2>
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
                    <li class="pull-right"><a href="<?= BASE_URL;?>access/" title="All roles" class="btn btn-primary"><i class="fa fa-refresh"></i> All Roles</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Permissions:</h1>
                <p class="alert alert-info">
                    The ability to edit an access role's permissions will be released in version 2.0.2
                </p>
                <p><pre><?= var_dump($role->show_permissions(false));?></pre></p>
            </div>
        </div>
    </div>
</div>