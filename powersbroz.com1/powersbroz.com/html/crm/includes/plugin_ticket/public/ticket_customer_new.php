<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title><?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 10474 31adef9c9cf17cbd18100c8b1824e959
  * Envato: 893ecafa-6fb9-4299-930f-7526a262c4e8
  * Package Date: 2016-01-13 13:46:18 
  * IP Address: 76.104.145.50
  */ _e('Ticket :');?> <?php echo module_ticket::ticket_number($ticket_id);?></title>

<link rel="stylesheet" href="<?php echo _BASE_HREF;?>css/styles.css?ver=5" type="text/css" />
<link rel="stylesheet" href="<?php echo _BASE_HREF;?>css/desktop.css?ver=5" type="text/css" />
<link type="text/css" href="<?php echo _BASE_HREF;?>css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
<?php module_config::print_css();?>


<script language="javascript" type="text/javascript">
<?php
switch(strtolower(module_config::s('date_format','d/m/Y'))){
    case 'd/m/y':
        $js_cal_format = 'dd/mm/yy';
        break;
    case 'y/m/d':
        $js_cal_format = 'yy/mm/dd';
        break;
    case 'm/d/y':
        $js_cal_format = 'mm/dd/yy';
        break;
    default:
        $js_cal_format = 'yy-mm-dd';
}
?>
var js_cal_format = '<?php echo $js_cal_format;?>';
</script>
<!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/freelancer.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

  
<script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo _BASE_HREF;?>js/timepicker.js"></script>
<script type="text/javascript" src="<?php echo _BASE_HREF;?>js/cookie.js"></script>
<script type="text/javascript" src="<?php echo _BASE_HREF;?>js/javascript.js?ver=2"></script>
<?php module_config::print_js();?>

<script type="text/javascript">
$(function(){
	init_interface();
});
</script>
</head>
<body bgcolor="#FFF">


<div id="holder" style="background: #FFF">
<div id="page_middle_flex">

<div class="content">
<div id="ticket_new_holder_public">

<?php if(isset($errors) && count($errors)){ ?>
    <div class="ui-state-error error_text">
        <ul>
            <?php foreach ($errors as $error) {
                echo '<li>'.$error.'</li>';
            }
        ?>
        </ul>
    </div>
    <?php } ?>

<form action="<?php echo module_ticket::link_public_new();?>" method="post" id="ticket_form" enctype="multipart/form-data">
	<input type="hidden" name="_process" value="save_public_ticket" />


    <?php
    /*<input type="hidden" name="tac" value="<?php echo $ticket_account['ticket_account_id'];?>" />*/
    $form_fields = array(
        'fields' => array(
            'name' => _l('Your Name'),
            'email' => _l('Your Email'),
            'type' => _l('Department'),
            'subject' => _l('Subject'),
            'new_ticket_message' => _l('Your Message'),
        ),
        'emails' => array(
            'email' => _l('Your Email'),
        ),
    );
    if(class_exists('module_faq',false) && module_config::c('ticket_submit_product_required',0)){
        $form_fields['fields']['faq_product_id'] = _l('Product');
    }
    module_form::set_required($form_fields);
    ?>

	<table cellpadding="10" class="wpetss" width="100%">
		<tbody>
        <tr>
            <td class="public_header">
               
            <p>Submit your Dreamlancer Application Ticket</p>
            <h2>all infomation will be inputed in to Powersbroz.com bakend CRM. A sales rep will be in contact asap.</h2>
            </td>
        </tr>
        <tr>
            <td class="public_welcome">
                <?php echo module_config::c('ticket_public_welcome','Hello, here you can request a support ticket. <br/> Simply fill in the fields below and press "Submit Ticket".'); ?>
            </td>
        </tr>
			<tr>
				<td valign="top" style="padding:10px;">
					<h3><?php echo _l('Your Details'); ?></h3>
                    <table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form tableclass_full">
						<tbody>
							<tr>
								<th class="width1">
									<?php echo _l('Your Name'); ?>
								</th>
								<td>
									<input type="text" name="name" value="<?php echo isset($_POST['name'])?htmlspecialchars($_POST['name']):'';?>" style="width:90%">
								</td>
							</tr>
							<tr>
								<th>
									<?php echo _l('Your Email'); ?>
								</th>
								<td>
									<input type="text" name="email" value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):'';?>" style="width:90%">
								</td>
							</tr>
						</tbody>
					</table>


                    <?php handle_hook('ticket_create',$ticket_id); ?>


					<h3><?php echo _l('Ticket Details'); ?></h3>

					<table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form tableclass_full">
						<tbody>
                            <?php if(module_config::c('ticket_public_show_type_dropdown',1)){ ?>
							<tr>
								<th>
									<?php _e('Type/Department'); ?>
								</th>
								<td>
									<?php
                                    $types = module_ticket::get_types(true);
                                    echo print_select_box($types,'ticket_type_id',isset($_REQUEST['ticket_type_id'])&&isset($types[$_REQUEST['ticket_type_id']])?$_REQUEST['ticket_type_id']:$ticket_account['default_type'],'',false,'name');
                                    ?>
								</td>
							</tr>
                            <?php } ?>
                            <?php if (module_config::c('ticket_allow_priority_selection',0)){

                                $priorities = module_ticket::get_ticket_priorities();
                                if(isset($priorities[_TICKET_PRIORITY_STATUS_ID])){
                                    unset($priorities[_TICKET_PRIORITY_STATUS_ID]);
                                }
                                ?>

                                <tr>
                                    <th>
                                        <?php echo _l('Priority'); ?>
                                    </th>
                                    <td>
                                        <?php
                                        echo print_select_box($priorities,'priority','','',false);
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th class="width1">
                                    <?php _e('Subject');?>
                                </th>
                                <td>
                                    <input type="text" name="subject" id="subject" value="<?php echo isset($_POST['subject'])?htmlspecialchars($_POST['subject']):'';?>" style="width:90%" />
                                </td>
                            </tr>
                            <tr>
								<th>
									<?php echo _l('Your Message'); ?>
								</th>
								<td>
									<textarea rows="10" cols="20" name="new_ticket_message" style="width:90%"><?php echo isset($_POST['new_ticket_message'])?htmlspecialchars($_POST['new_ticket_message']):'';?></textarea>
                                </td>
                            </tr>
                            <?php if(module_config::c('ticket_allow_extra_data',1)){
                                $extras = module_ticket::get_ticket_extras_keys($ticket_account['ticket_account_id']);
                                if(count($extras)){
                                ?>
                                <tr>
                                    <th> </th>
                                    <td>
                                        <?php _e('Please fill out as many of the below fields as possible:'); ?>
                                    </td>
                                </tr>
                                    <?php
                                    foreach($extras as $extra){
                                    ?>
                                    <tr>
                                        <th nowrap="nowrap">
                                            <?php echo htmlspecialchars($extra['key']);?>
                                        </th>
                                        <td>
                                            <?php module_form::generate_form_element(array(
                                                'type' => $extra['type'],
                                                'name' => 'ticket_extra['.$extra['ticket_data_key_id'].']',
                                                'value' => isset($_POST['ticket_extra']) && isset($_POST['ticket_extra'][$extra['ticket_data_key_id']]) ? $_POST['ticket_extra'][$extra['ticket_data_key_id']] : '',
                                                'options' => isset($extra['options']) && $extra['options'] ? unserialize($extra['options']) : array(),
                                            ));

                                            if(
                                                class_exists('module_encrypt',false) &&
                                                module_config::c('ticket_show_encrypt_fields',1) &&
                                                isset($extra['encrypt_key_id']) && $extra['encrypt_key_id']
                                                &&
                                                ($extra['type'] == 'text' || $extra['type']=='textarea')
                                            ){
                                                // hack to show the encrypt key icon if this is an encryptable key.
                                                echo '<img src="'.full_link('includes/plugin_encrypt/images/lock.png').'" style="vertical-align:top;" border="0">';
                                                if(module_config::c('ticket_show_encrypt_fields_help_popup',1)){
                                                    _h('This field is encrypted in the database using industry standard RSA cryptography.');
                                                }
                                            }

                                            ?>
                                        </td>
                                    </tr>
                                    <?php }
                                }
                            } ?>
                            <?php if(module_config::c('ticket_allow_attachment',1)){ ?>
                            <tr>
                                <th>
                                    <?php _e('Attachment');?>
                                </th>
                                <td>
                                    <div id="file_attachment_holder">
                                        <div class="dynamic_block" style="clear:left;">
                                            <div style="float:left;">
                                                <input type="file" name="attachment[]">
                                            </div>
                                            <div style="float:left; padding: 4px 0 0 10px;">
                                                <a href="#" class="add_addit" onclick="return seladd(this);">+</a>
                                                <a href="#" class="remove_addit" onclick="return selrem(this);">-</a>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        set_add_del('file_attachment_holder');
                                    </script>
                                </td>
                            </tr>
                                <?php } ?>
                            <?php if(module_config::c('ticket_recaptcha',1)){ ?>
                            <tr>
								<th>
									<?php echo _l('Spam Protection'); ?>
								</th>
								<td>
                                        <?php echo module_captcha::display_captcha_form(); ?>
                                </td>
                            </tr>
                            <?php } ?>
						</tbody>
					</table>


                    <?php if(
                                module_config::c('ticket_show_position',1) ||
                                module_config::c('ticket_allow_priority',0) ||
                                module_config::c('ticket_turn_around_rate_show',1) ||
                                module_config::c('ticket_turn_around_days_show',1)
                            ){ ?>

                    <h3><?php echo _l('Ticket Information'); ?></h3>

                    <table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form tableclass_full">
                        <tbody>
                        <?php if(module_config::c('ticket_show_position',1)){ ?>
                        <tr>
                            <th class="width1">
                                <?php echo _l('Position'); ?>
                            </th>
                            <td id="ticket_position_field">
                                <?php echo _l('%s out of %s other support tickets',ordinal($ticket['position']),$ticket['total_pending']); ?>
                                <?php //echo ordinal($ticket['position']); ?>
                            </td>
                        </tr>
                            <?php } ?>
                        <?php if(module_config::c('ticket_allow_priority',0)){ ?>
                        <tr>
                            <th class="width1">
                                <?php echo _l('Priority Support'); ?>
                            </th>
                            <td>
                                <?php module_template::init_template('ticket_priority_support','<em>New!</em> Need a <strong>fast</strong> reply? Priority support will place your ticket at the front of the support queue. <br/>Priority Support Cost: {COST}<br/>{CHECKBOX} <label for="do_priority">Yes, upgrade to <strong>priority support</strong> and move my ticket to position: {TICKET_POSITION}</label>','Displayed at the bottom of ticket support signup','code',array(
                                    'cost' => 'Cost as defined by ticket_priority_code advanced setting',
                                    'TICKET_POSITION' => 'displays the string (example) "1st out of 44"',
                                ));
                                $template = module_template::get_template_by_key('ticket_priority_support');

                                $template->assign_values(array(
                                    'cost'=>dollar(module_config::c('ticket_priority_cost',10),true,module_config::c('ticket_priority_currency',1)),
                                    'TICKET_POSITION'=>_l('%s out of %s',ordinal(module_ticket::ticket_count('priority')+1),$ticket['total_pending']),
                                    'CHECKBOX'=>'<input type="checkbox" name="do_priority" id="do_priority" value="1">',
                                ));
                                echo $template->replace_content();
                                ?>

                            </td>
                        </tr>
                            <?php } ?>
                        <?php if(module_config::c('ticket_turn_around_days_show',1)){ ?>
                        <tr>
                            <th class="width1">
                                <?php echo _l('Reply'); ?>
                            </th>
                            <td>
                                <?php echo _l('We will reply between %s and %s %s',module_config::c('ticket_turn_around_days_min',2),module_config::c('ticket_turn_around_days',5), module_config::c('ticket_turn_around_period','days')); ?>
                            </td>
                        </tr>
                            <?php } ?>
                        <?php if(module_config::c('ticket_turn_around_rate_show',1)){ ?>
                        <tr>
                            <th class="width1">
                                <?php echo _l('Rate'); ?>
                            </th>
                            <td>
                                <?php
                                $rate = module_ticket::get_reply_rate();
                                echo _l('We are currently processing about %s tickets every 24 hours',$rate['daily']); ?>
                            </td>
                        </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <?php } ?>


                    <p align="center">
                        <em><small><?php echo _l('* required fields');?> </small></em> <br/>
                    <input type="submit" name="butt_save" id="butt_save" value="<?php echo _l('Submit Ticket or contenue to fill out your idea below'); ?>" class="submit_button save_button" />
                    </p>
				</td>
			</tr>
		</tbody>
	</table>


</form>
<!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Submit Your Idea</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="sentMessage" id="contactForm" novalidate>
                    
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What is the Idea?</label>
                                <textarea rows="5" class="form-control" placeholder="Start By Telling us your Idea.." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What are my Goals?</label>
                                <textarea rows="5" class="form-control" placeholder="Next tell us your goals for your idea..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What are the parts of the practice?</label>
                                <textarea rows="5" class="form-control" placeholder="now brake down your big idea into small parts (reffrence textbook ToC)..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Based off my research/ I like the textbook/pdf...because it's a template that is easy to understand...(Framework)
</label>
                                <textarea rows="5" class="form-control" placeholder="Next Research online Each Keyword to find Examples and templetes..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What are the practical applications?</label>
                                <textarea rows="5" class="form-control" placeholder="Examples and templetes are great in theroy, how do you plan to use each practicaly...." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What are the relevent keywords? (Database Keywords)

</label>
                                <textarea rows="5" class="form-control" placeholder="Index ALL Web Searchable Keywords that are relevent to your idea..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What are situations i’ve encountered in Regards to Questions i have asked? (test Questions/ Case Studies)
</label>
                                <textarea rows="5" class="form-control" placeholder="Index All Tough Test Questions in the field of study behind your idea (find Practice Test Online)" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Are there any mathematical formulas to assist in the practice?...
</label>
                                <textarea rows="5" class="form-control" placeholder="use your Keywords and examples to Identify any Functions or algerathems that are closely related to your Idea..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What are ways I can get income from the practice of…? (Business Model)</label>
                                <textarea rows="5" class="form-control" placeholder="What kind of job and what job tittles will be created by your idea or service..(think about the skills need) " id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What resources can i use? (Repository)

</label>
                                <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>How do i store these resources? (Data Modeling)
</label>
                                <textarea rows="5" class="form-control" placeholder="How will you organize all your research, education and business documentation..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>How many resources do i have that address each part of the practice? (Data Sets, Views, Pivet tables)

</label>
                                <textarea rows="5" class="form-control" placeholder=" Index every video, pdf, image, audio andwebsite by Each part of the practice of your business" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>How do i organize my list of resources?
</label>
                                <textarea rows="5" class="form-control" placeholder="in what ways will you share infomation (resource) about your idea and practice..." id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>What is the most effective outline of a book on the practice?

</label>
                                <textarea rows="5" class="form-control" placeholder="What is the most effective outline of a book on the practice?" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>is my outline efficient? (test outline)
</label>
                                <textarea rows="5" class="form-control" placeholder="is my outline efficient? (test outline)" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Based on my Test i have decided an effective and efficient guide for the practice is…(Solution Manafest)

</label>
                                <textarea rows="5" class="form-control" placeholder="Based on my Test i have decided an effective and efficient guide for the practice is…(Solution Manafest)" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Using my guide i have design my own template for practicing…(Study Guide)

</label>
                                <textarea rows="5" class="form-control" placeholder="Using my guide i have design my own template for practicing…(Study Guide)
" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>The final Solution is a Practical Utility Kit


</label>
                                <textarea rows="5" class="form-control" placeholder="What is your final practical Solution" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <p align="center">
                        <em><small><?php echo _l('* required fields');?> </small></em> <br/>
                    <input type="submit" class="btn btn-success btn-lg" name="butt_save" id="butt_save" value="<?php echo _l('Submit Idea & Ticket'); ?>" class="submit_button save_button" />
                   
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>