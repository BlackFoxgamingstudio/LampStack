<div class="col-md-12">
    <ul class="text-left submenu">
        <li class="active"><a href="?id=contact"><?php _e('Contact Information','themeum'); ?></a></li>
    </ul>
    <div class="dashboard-content">


        <form name="contact-form" id="contact-form" action="<?php echo admin_url("admin-ajax.php"); ?>" method="post" class="dashboard-form">

<?php
    $id             = get_current_user_id();
    $email          = get_user_meta( $id,'startup_email',true );
    $f_name         = get_user_meta( $id,'startup_first_name',true );
    $l_name         = get_user_meta( $id,'startup_last_name',true );
    $address1       = get_user_meta( $id,'startup_address1',true );
    $address2       = get_user_meta( $id,'startup_address2',true );
    $city           = get_user_meta( $id,'startup_city',true );
    $state          = get_user_meta( $id,'startup_state',true );
    $zip            = get_user_meta( $id,'startup_zip',true );
    $country        = get_user_meta( $id,'startup_country',true );
?>
<div class="form-group">
    <input type="hidden" name="action" value="contact_form">
    <label for="first_name"><?php _e('First Name','themeum'); ?></label>
    <input class="form-control" id="first_name" name="first_name" type="text" value="<?php echo $f_name; ?>" disabled />
</div>

<div class="form-group">
    <label for="last_name"><?php _e('Last Name','themeum'); ?>:</label>
    <input class="form-control" id="last_name" name="last_name" type="text" value="<?php echo $l_name; ?>" disabled />
</div>

<div class="form-group">
    <label for="email"><?php _e('Email','themeum'); ?>:</label>
    <input class="form-control" id="email" name="email" type="text" value="<?php echo $email; ?>" disabled />
</div>

<div class="form-group">
    <label for="address1"><?php _e('Address 1','themeum'); ?>:</label>
    <input class="form-control" id="address1" name="address1" type="text" value="<?php echo $address1; ?>" disabled />
</div>

<div class="form-group">
    <label for="address2"><?php _e('Address 2','themeum'); ?>:</label>
    <input class="form-control" id="address2" name="address2" type="text" value="<?php echo $address2; ?>" disabled />
</div>

<div class="form-group">
    <label for="city"><?php _e('City','themeum'); ?>:</label>
    <input class="form-control" id="city" name="city" type="text" value="<?php echo $city; ?>" disabled />
</div>

<div class="form-group">
    <label for="state"><?php _e('State','themeum'); ?>:</label>
    <input class="form-control" id="state" name="state" type="text" value="<?php echo $state; ?>" disabled />
</div>

<div class="form-group">
    <label for="zip"><?php _e('Zip','themeum'); ?>:</label>
    <input class="form-control" id="zip" name="zip" type="text" value="<?php echo $zip; ?>" disabled />
</div>

<div class="form-group">
    <label for="country"><?php _e('Country','themeum'); ?>:</label>
    <input class="form-control" id="country" name="country" type="text" value="<?php echo $country; ?>" disabled />
</div>



            <div class="form-group pull-right">
                <button class="btn btn-default edit-form"><i class="fa fa-pencil-square-o"></i> Edit</button>
                <button type="submit" class="btn btn-default" disabled><i class="fa fa-floppy-o"></i> Save</button>
            </div>
        </form>



    </div>
</div>