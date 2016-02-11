<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Projects</h2>
            </div>
        </div>
    </div>
</div>
<!-- View Controls -->
<div class="toolbar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="toolbar-options">
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Layouts</span>
                        <ul>
                            <li>
                                <?php if ($app_preferences->get_layout('projects') == 'grid') { ?>
                                <span id="projectGridBtn" title="Viewing as grid" class="btn btn-info">
                                <?php } else  { ?>
                                <span id="projectGridBtn" title="View as grid" class="btn btn-primary">
                                <?php } ?>
                                    <i class="fa fa-th"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_layout('projects') == 'list') { ?>
                                <span id="projectListBtn" title="Viewing as list" class="btn btn-info">
                                <?php } else { ?>
                                <span id="projectListBtn" title="View as list" class="btn btn-primary">
                                <?php } ?>
                                    <i class="fa fa-list"></i>
                                </span>
                            </li>
                        </ul>
                    </li>
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Order</span>
                        <ul>
                            <li>
                                <?php if ($app_preferences->get_order('projects') == 'alpha') { ?>
                                <span id="projectAlphaBtn" title="Viewing by project name alphabetically" class="btn btn-info">
                                <?php } else { ?>
                                <span id="projectAlphaBtn" title="View by project name alphabetically" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-sort-by-alphabet"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_order('projects') == 'newest') { ?>
                                <span id="projectNewestBtn" title="Viewing by most recent" class="btn btn-info">
                                <?php } else { ?>
                                <span id="projectNewestBtn" title="View by most recent" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                            </li>
                            <li>
                                <?php if ($app_preferences->get_order('projects') == 'due') { ?>
                                <span id="projectDueBtn" title="Viewing by closest due date" class="btn btn-info">
                                <?php } else { ?>
                                <span id="projectDueBtn" title="View by closest due date" class="btn btn-primary">
                                <?php } ?>
                                    <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                                </span>
                            </li>
                        </ul>
                    </li>
                    <?php if ($current_user->role()->classification() == 'Staff') { ?>

                    <?php if ($current_user->role()->can('project', 'create') || $current_user->role()->can('project', 'assign')) { ?>
                    <li class="toolbar-options-wrap">
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <?php if ($current_user->role()->can('project', 'create')) { ?>
                            <li>
                                <span id="newProjectBtn" title="Create a new project" class="btn btn-success newProjectBtn">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </li>
                            <?php } ?>
                            <?php if ($current_user->role()->can('project', 'assign')) { ?>
                            <li>
                                <span id="assignProjectBtn" title="Assign objects to a project" class="btn btn-success">
                                    <i class="fa fa-link"></i>
                                </span>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } // End action check for projects ?>

                    <?php } // End if user is staff check ?>
                    <li>Filter</li>
                    <li>
                        <select style="display:inline;" id="filterList">
                            <option value="All">All</option>
                            <option value="Overdue">Overdue</option>
                            <option value="Thirty">30 Day Outlook</option>
                            <option value="Sixty">60 Day Outlook</option>
                        </select>
                    </li>
                    <li>
                        <span id="filterBtn" class="btn btn-primary">
                            <i class="fa fa-filter"></i>
                        </span>
                    </li>
                    <li class="pull-right">
                        <a href="<?= BASE_URL;?>" title="Dashboard" class="btn btn-primary">
                            <i class="fa fa-home"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section min-height-300">
    <div class="container">
        <div class="row">

            <?= (DEBUG) ? $app_preferences->debug_line('projects') : null;?>

            <?php if ($projects) { ?>


            <?php if ($app_preferences->get_layout('projects') == 'grid') { ?>

                <?php for ($p = 0;$p < count($projects);$p++) { ?>


                <div id="project-<?= $projects[$p]->id();?>" class="jsProjectContainer col-md-4 push-down <?= $projects[$p]->due_notice_class();?>">

                    <div class="<?= $projects[$p]->box_class();?>">
                        <img class="img img-responsive" width="128" src="<?= $projects[$p]->image();?>" alt="Project Image"/>
                        <h2><?= shorten($projects[$p]->name(), 18);?></h2>
                        <p>
                            <a href="view/<?= $projects[$p]->id();?>" class="btn btn-primary"><i class="fa fa-align-justify"></i> Details</a>
                            <?php if ($current_user->role()->can('project', 'delete')) { ?>
                            <a href="#" class="btn btn-danger deleteProjectBtn" project="<?= $projects[$p]->id();?>"><i class="fa fa-times-circle"></i> Delete</a>
                            <?php } ?>
                        </p>
                    </div>

                </div>


                <?php } // End Project FOR Loop ?>



            <?php } else { ?>


                <table class="table">
                    <thead>
                    <tr>
                        <th colspan="2">Name</th>
                        <th>Starts</th>
                        <th>Ends</th>
                        <th>Created</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for ($p = 0;$p < count($projects);$p++) { ?>
                        <tr class="jsProjectContainer <?= $projects[$p]->list_class();?> <?= $projects[$p]->due_notice_class();?>">
                            <td><img src="<?= $projects[$p]->image();?>" width="64px" alt="Project image for <?= $projects[$p]->name();?>" class="img img-responsive img-thumbnail"/></td>
                            <td><?= $projects[$p]->name();?></td>
                            <td><?= $projects[$p]->get('startdate')->format('F d, Y');?></td>
                            <td><?= $projects[$p]->get('enddate')->format('F d, Y');?></td>
                            <td><?= $projects[$p]->get('created')->format('F d, Y');?></td>
                            <td>
                                <a href="view/<?= $projects[$p]->id();?>" class="btn btn-primary"><i class="fa fa-align-justify"></i> Details</a>
                                <a href="#" class="btn btn-danger deleteProjectBtn" project="<?= $projects[$p]->id();?>"><i class="fa fa-times-circle"></i> Delete</a>
                            </td>
                        </tr>
                    <?php } // End project LIST FOR loop ?>
                    </tbody>
                </table>


            <?php } // End project view preference switch ?>

            <?php } else { ?>

            <?php if ($current_user->role()->can('project', 'create')) { ?>
            <!-- No Project View -->
            <div class="col-md-12">
                <div class="nothing-full">
                    <h2>No Projects have been created yet? Would you like to make one?</h2>
                    <p><a href="#" class="btn btn-success newProjectBtn"><i class="fa fa-plus"></i> Create new project</a></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="col-md-12">
                <div class="nothing-full">
                    <h2>You are currently not assigned to any active projects</h2>
                    <p>Please contact a member of our support staff if you need additional assistance</p>
                </div>
            </div>
            <?php } ?>

            <?php } ?>
        </div>
    </div>
</div>

<?php if ($current_user->role()->classification() == 'Staff') { ?>
<div class="highlight">
    <div class="container">
        <div class="col-md-4">
            <div class="box">
                <h3>Total</h3>
                <p><span class="stat"><?= Project::total();?></span></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <h3>Active</h3>
                <p><span class="stat"><?= Project::total_active();?></span></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <h3>Complete</h3>
                <p><span class="stat"><?= Project::total_complete();?></span></p>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="highlight">
    <div class="container">
        <div class="col-md-4 text-center">
            <img class="img img-thumbnail" src="<?= $current_user->img();?>" alt="My Avatar" width="128"/>
        </div>
        <div class="col-md-8">
            <h3>Welcome <?= $current_user->name();?></h3>
            <p>This page lists all projects which you are currently assigned to or have access to. Please contact one of our staff if you need additional access.</p>
        </div>
    </div>
</div>
<?php } ?>

<script>
    $('#filterBtn').on('click', function() {
        var filter = $('#filterList').val();
        if (filter == 'All') {
            $('.jsProjectContainer').show();
        }
        if (filter == 'Overdue') {
            $('.jsProjectContainer').hide();
            $('.jsProjectContainer.noticeOverdue').show();
        }
        if (filter == 'Thirty') {
            $('.jsProjectContainer').hide();
            $('.jsProjectContainer.noticeThirty').show();
        }
        if (filter == 'Sixty') {
            $('.jsProjectContainer').hide();
            $('.jsProjectContainer.noticeSixty').show();
        }
    });
    $('#assignProjectBtn').click(function() {
        openForm('<?= BASE_URL;?>app/views/forms/assign.form.php', {scope: 'project'});
    });
    $('#viewSettings').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/view.prefs.form.php', {scope: "project"});
    });
    $('.newProjectBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.project.form.php');
    });

    <?php if ($current_user->role()->can('project', 'delete')) { ?>
    $('.deleteProjectBtn').click(function(e) {
        e.preventDefault();
        var userInput = confirm('Are you sure you want to delete this project?');
        if (userInput) {
            var projectID = $(this).attr('project');
            $.post('./', {delete: "project", project: projectID}, function(data) {
                if (data == 'success') {
                    $('#project-' + projectID).attr('class', 'col-md-4 push-down animated zoomOutDown').hide();
                } else {
                    entityError('Unable to Delete Project', data);
                }
            });
        }
    });
    <?php } ?>

    $('#projectGridBtn').click(function() {
        document.cookie="Entity-APL=grid";
        window.location.reload();
    });
    $('#projectListBtn').click(function() {
        document.cookie="Entity-APL=list";
        window.location.reload();
    });
    $('#projectAlphaBtn').click(function() {
        document.cookie="Entity-APO=alpha";
        window.location.reload();
    });
    $('#projectNewestBtn').click(function() {
        document.cookie="Entity-APO=newest";
        window.location.reload();
    });
    $('#projectDueBtn').click(function() {
        document.cookie="Entity-APO=due";
        window.location.reload();
    });
</script>