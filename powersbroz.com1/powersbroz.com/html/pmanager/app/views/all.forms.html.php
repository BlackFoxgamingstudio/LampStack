<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Application Forms</h2>
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
                        Actions
                        <ul>
                            <li><span class="btn btn-success newFormBtn" title="Create new form"><i class="fa fa-plus"></i></span></li>
                        </ul>
                    </li>
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
                <p>Custom forms allow you to capture the information you need to start a new project. Users from within the application can fill it out and if you allow, so can clients who do not have an account yet.</p>
                <div class="row push-vertical">
                <?php if ($forms) { ?>

                    <?php foreach($forms as $form) { ?>

                        <div class="col-md-4">
                            <div class="normal-box text-center">
                                <h3><?= $form->name();?></h3>
                                <img src="<?= BASE_URL;?>img/ico/smart-file.png" alt="Form Icon"/>
                                <p class="push-vertical">
                                    <a class="btn btn-warning" href="<?= BASE_URL;?>forms/edit/<?= $form->slug();?>" title="Edit the form <?= $form->name();?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </p>
                            </div>
                        </div>

                    <?php } ?>
                </div>
                <?php } else { ?>

                    <div class="nothing-full">
                        <h2>You currently have not created any custom forms</h2>
                        <p><a href="#" class="btn btn-success newFormBtn"><i class="fa fa-plus"></i> Create new form</a></p>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="highlight">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="box text-center">
                    <h3>Total</h3>
                    <p><span class="stat"><?= Form::total();?></span></p>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Upcoming Features</h3>
                <p>I will be actively working on new features for the form creator in upcoming updates. Namely you will be able to deploy forms and other objects to your projects to get valuable feedback form clients and contributors. I expect to have some of these features implemented by 2.0.2.</p>
            </div>
        </div>
    </div>
</div>

<script>
    $('.newFormBtn').click(function() {
        openForm('<?=BASE_URL;?>app/views/forms/new.quote.form.php');
    });
</script>