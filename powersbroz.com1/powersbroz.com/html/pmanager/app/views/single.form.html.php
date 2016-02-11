<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?= $form->name();?></h2>
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
                        <span class="subdued-caps">Actions</span>
                        <ul>
                            <li><span class="btn btn-danger deleteFrmBtn"><i class="fa fa-times"></i> Delete Form</span></li>
                            <li><a href="<?= BASE_URL;?>forms/submissions/<?= $form->slug();?>" class="btn btn-primary"><i class="fa fa-eye"></i> Submissions <sup><?= $form->total_submissions();?></sup></a></li>
                        </ul>
                    </li>

                    <li class="pull-right"><a href="<?= BASE_URL;?>forms/" title="All forms" class="btn btn-primary"><i class="fa fa-refresh"></i> All Forms</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="highlight-dark-recessed">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <blockquote>
                    <p><?= $form->desc();?></p>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Add Field Controls -->
                <p>Add fields to <?= $form->name();?></p>
                <ul class="form-field-types">
                    <li class="form-field-types-group">
                        <img src="<?= IMAGES;?>ico/ui-input-text.png" alt="Text fields" />
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="input-text"><i class="fa fa-plus"></i> Text</span>
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="input-email"><i class="fa fa-plus"></i> Email</span>
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="input-date"><i class="fa fa-plus"></i> Date</span>
                    </li>
                    <li class="form-field-types-group">
                        <img src="<?= IMAGES;?>ico/ui-input-textarea.png" alt="Text area" />
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="text-area"><i class="fa fa-plus"></i> Text Area</span>
                    </li>
                    <li class="form-field-types-group">
                        <img src="<?= IMAGES;?>ico/ui-input-checkbox.png" alt="Checkbox group" />
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="input-checkbox"><i class="fa fa-plus"></i> Checkbox group</span>
                    </li>
                    <li class="form-field-types-group">
                        <img src="<?= IMAGES;?>ico/ui-input-radio.png" alt="Radio button group" />
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="input-radio"><i class="fa fa-plus"></i> Radio group</span>
                    </li>
                    <li class="form-field-types-group">
                        <img src="<?= IMAGES;?>ico/ui-input-dropdown.png" alt="Select box" />
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="select-box"><i class="fa fa-plus"></i> Dropdown</span>
                    </li>
                    <li class="form-field-types-group">
                        <img src="<?= IMAGES;?>ico/file-search.png" alt="File field" />
                        <span class="btn btn-success btn-sm jsAddFieldBtn" data-type="input-file"><i class="fa fa-plus"></i> File Upload</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="form-field-creation">
            <div class="half-box">
                <form id="createFieldFrm" action="" method="post">
                    <input type="hidden" name="create" value="form-field"/>
                    <input type="hidden" name="parent" value="<?= $form->id();?>"/>
                    <input id="inputFieldType" type="hidden" name="field-type" value=""/>
                    <p class="bump-down">Field Label:</p>
                    <input type="text" name="field-label" placeholder="Label text for field"/>
                    <p class="bump-vertical">HTML Name: (Use only letters and dashes)</p>
                    <input type="text" name="field-name" placeholder="Label text for field"/>
                    <p class="bump-vertical">Placeholder Text: (Applies only to Text, Email, and Text Areas)</p>
                    <input type="text" name="field-placeholder" placeholder="Placeholder"/>
                    <p class="bump-vertical">
                        Required:
                        <input type="radio" name="field-required" value="1"/> Yes <input type="radio" name="field-required" value="0" checked /> No
                    </p>
                    <button class="btn btn-success"><i class="fa fa-plus"></i> Add Field</button>
                    <span class="form-field-creation-close"><i class="fa fa-times"></i></span>
                </form>
            </div>
        </div>

        <div class="row push-vertical">
            <div class="col-md-12">
                <!-- Form Fields -->
                <?php if ($form->has_fields()) { ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="sortable-positions">
                            <?php foreach ($form->get_fields() as $field) { ?>

                                <div class="row bump-vertical jsFormField" data-field="<?= $field->id();?>">
                                    <div class="col-md-12">
                                        <div class="field-display-container">
                                            <table class="form-field-table">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <img class="img" src="<?= IMAGES .'ico/'.FormField::system_types()[$field->type()]["icon"];?>" alt="Field type icon" />
                                                            <?= $field->label();?>
                                                            <?php if ($field->required()) { ?>
                                                            <i class="glyphicon glyphicon-fire fire"></i>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <span class="label label-primary">
                                                                <?= FormField::system_types()[$field->type()]["readable"];?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <hr/>
                                            <p>
                                                <span class="btn btn-danger btn-sm deleteFieldBtn" data-field="<?= $field->id();?>">
                                                    <i class="fa fa-times"></i> Delete Field
                                                </span>
                                                <span class="btn btn-primary btn-sm optionFieldBtn" data-field="<?= $field->id();?>">
                                                    <i class="fa fa-cogs"></i> Options
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            <?php } // End foreach field loop ?>
                            </div>
                        </div>
                        <div id="option-window" class="col-md-4">
                            <h3>Options</h3>
                            <hr/>
                            <p class="small">Options are allowed on elements which can have more than one value, such as dropdown boxes and radio groups. You can set as many options as you like. You cannot add options to fields such as textareas and input fields. To learn more, read the documentation.</p>
                            <div id="field-options">
                                <p class="alert alert-info">No field selected</p>
                            </div>
                        </div>
                    </div>

                <?php } else { ?>
                <div class="nothing-full">
                    <h3>This form has no fields</h3>
                    <p>Why don't you add a new one!</p>
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
                <div class="box">
                    <h3>Field Count</h3>
                    <p><span class="stat"><?= $form->total_fields();?></span></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3>Submissions</h3>
                    <p><span class="stat"><?= $form->total_submissions();?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Form Information</h2>
                <p>This form was created on <span class="label label-primary"><?= $form->created()->format('F d, Y');?> <?= readable_time($form->created());?></span> and last updated on <span class="label label-primary"><?= $form->updated()->format('F d, Y');?> <?= readable_time($form->updated());?></span></p>
                <p>
                    Form Published:
                    <input type="radio" name="formPublished" value="1" <?= ($form->is_published()) ? 'checked' : '';?> /> Yes
                    <input type="radio" name="formPublished" value="0" <?= (!$form->is_published()) ? 'checked' : '';?> /> No
                </p>
                <hr/>
                <p>
                    <span class="btn btn-success">
                        <i class="fa fa-save"></i> Commit Changes
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>


<script>
    // Delete the form
    $('.deleteFrmBtn').click(function() {
        var confirmDeletion = confirm('Are you sure you want to delete this form? You will lose all submitted data.');
        if (confirmDeletion) {
            $.post('./', {delete: "form", form: "<?= $form->id();?>"}, function(data) {
                if (data == 'success') {
                    window.location = '<?= BASE_URL;?>forms/';
                } else {
                    entityError('Unable to Delete Form', data);
                }
            });
        }
    });
    // Delete a form field
    $('.deleteFieldBtn').click(function() {
        var confirmDeletion = confirm('Are you sure you want to remove this field? Old submissions will still have this field captured, but new ones will not.');
        var fieldID = $(this).attr('data-field');
        if (confirmDeletion) {
            $.post('./', {delete: 'form-field', field: fieldID}, function(data) {
                if (data == 'success') {
                    window.location.reload();
                } else {
                    entityError('Unable to Delete Field', data);
                }
            });
        }
    });
    // Add Field
    $('.jsAddFieldBtn').click(function() {
        var fieldType = $(this).attr('data-type');
        $('#inputFieldType').val(fieldType);
        $('.form-field-creation').slideDown('fast');
    });
    $('.form-field-creation-close').click(function () {
        $('.form-field-creation').slideUp('fast');
    });
    $('#createFieldFrm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.post('./', formData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Unable to Create Field', data);
            }
        });
    });
    $('.optionFieldBtn').click(function() {
        var fieldID = $(this).attr('data-field');
        $.get('<?= VIEW_COLLECTIONS;?>form.field.options.html.php', {field: fieldID}, function(data) {
            $('#field-options').html(data);
        });
    });

    // Handle option window when on a long list of fields
    var optionWindowPosition = $('#option-window').offset();

    $(window).scroll(function(){
        if($(window).scrollTop() > optionWindowPosition.top){
            $('#option-window').css('position','fixed').css('right','0').css('top', '0');
        } else {
            $('#option-window').css('position','static');
        }
    });
</script>