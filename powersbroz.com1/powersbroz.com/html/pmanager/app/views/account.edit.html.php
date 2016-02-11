<!-- Page Title -->
<div class="highlight-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Edit My Account</h2>
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
                    <li class="pull-right"><a href="<?= BASE_URL;?>account/" title="My Account" class="btn btn-primary"><i class="fa fa-refresh"></i> My Account</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center">Leaving a field blank will keep your current data upon saving</h3>
                <form id="editAccount" action="./" method="post">
                    <input type="hidden" name="update" value="my-account"/>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="userfname">First Name:</label>
                            <input type="text" name="userfname" id="userfname" placeholder="<?= $current_user->get('fname');?>"/>
                        </div>
                        <div class="col-md-6">
                            <label for="userfname">Last Name:</label>
                            <input type="text" name="userlname" id="userlname" placeholder="<?= $current_user->get('lname');?>"/>
                        </div>
                    </div>
                    <label for="useremail">Your Email Address:</label>
                    <input type="email" id="useremail" name="useremail" placeholder="<?= $current_user->email();?>"/>
                    <div class="text-center push-vertical">
                        <p>Your current system access role is <span class="label label-primary"><?= $current_user->role()->name();?></span></p>
                        <?php if (!$current_user->role()->is_staff()) { ?>
                        <p class="subdued">You will need to contact support to gain more privileges</p>
                        <?php } ?>
                    </div>

                    <div class="well well-lg">
                        <h4 class="text-center"><i class="fa fa-lock"></i> Password Reset</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="userpass">Type your new Password:</label>
                                <input type="password" id="userpass" name="userpass"/>
                            </div>
                            <div class="col-md-6">
                                <label for="userpassrepeat">Confirm your new Password:</label>
                                <input type="password" id="userpassrepeat" name="userpassrepeat"/>
                            </div>
                        </div>
                        <p class="subdued">Your password is hashed and is never stored as plain text for security reasons. Should you forget it, you will have to reset it as we will be unable to send you a plain text password.</p>
                    </div>

                    <label for="userbio">About You:</label>
                    <textarea rows="5" name="userbio" id="userbio" placeholder="<?= $current_user->get('bio');?>"></textarea>

                    <div class="row push-vertical">
                        <div class="col-md-6">
                            <div class="paper paper-curve">
                                <h4>Address Information</h4>
                                <label for="useraddone">Address Line One:</label>
                                <input type="text" name="useraddone" id="useraddone" placeholder="<?= $current_user->get('addressone');?>"/>
                                <label for="useraddtwo">Address Line Two:</label>
                                <input type="text" name="useraddtwo" id="useraddtwo" placeholder="<?= $current_user->get('addresstwo');?>"/>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="usercity">City:</label>
                                        <input type="text" name="usercity" id="usercity" placeholder="<?= $current_user->get('city');?>"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="userstate">State:</label>
                                        <input type="text" name="userstate" id="userstate" placeholder="<?= $current_user->get('state');?>"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="userzip">Zip Code:</label>
                                        <input type="text" name="userzip" id="userzip" placeholder="<?= $current_user->get('zip');?>"/>
                                    </div>
                                </div>
                                <label for="usercountry">Country:</label>
                                <?= get_countries('usercountry', '', $current_user->get('country'));?>
                                <hr/>
                                <h4>Phone and Fax</h4>
                                <label for="userhomephone">Home Phone:</label>
                                <input type="text" name="userhomephone" id="userhomephone" placeholder="<?= $current_user->get('homephone');?>"/>
                                <label for="userworkphone">Work Phone:</label>
                                <input type="text" name="userworkphone" id="userworkphone" placeholder="<?= $current_user->get('workphone');?>"/>
                                <label for="usercellphone">Cell Phone:</label>
                                <input type="text" name="usercellphone" id="usercellphone" placeholder="<?= $current_user->get('cellphone');?>"/>
                                <label for="userfax">Fax:</label>
                                <input type="text" name="userfax" id="userfax" placeholder="<?= $current_user->get('fax');?>"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="paper paper-curve">
                                <h4>Online Contact Information</h4>
                                <label for="userwebsite">Website:</label>
                                <input type="text" name="userwebsite" id="userwebsite" placeholder="<?= $current_user->get('website');?>"/>
                                <label for="userfacebook">Facebook Link:</label>
                                <input type="text" name="userfacebook" id="userfacebook" placeholder="<?= $current_user->get('facebook');?>"/>
                                <label for="usertwitter">Twitter Link:</label>
                                <input type="text" name="usertwitter" id="usertwitter" placeholder="<?= $current_user->get('twitter');?>"/>
                                <label for="usergoogle">Google+ Link:</label>
                                <input type="text" name="usergoogle" id="usergoogle" placeholder="<?= $current_user->get('googleplus');?>"/>
                                <label for="userlinkedin">LinkedIn Link:</label>
                                <input type="text" name="userlinkedin" id="userlinkedin" placeholder="<?= $current_user->get('linkedin');?>"/>
                                <label for="userskype">Skype Username:</label>
                                <input type="text" name="userskype" id="userskype" placeholder="<?= $current_user->get('skype');?>"/>
                                <label for="useryahoo">Yahoo IM Username:</label>
                                <input type="text" name="useryahoo" id="useryahoo" placeholder="<?= $current_user->get('yahoo');?>"/>

                            </div>
                        </div>
                    </div>
                    <div class="well well-lg text-center">
                        <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i> Update your Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#editAccount').on('submit', function(e) {
        e.preventDefault();
        var formData = $('#editAccount').serialize();
        $.post('./', formData, function(data) {
            if (data == 'success') {
                window.location.reload();
            } else {
                entityError('Error Updating Account', data);
            }
        });
    });
</script>