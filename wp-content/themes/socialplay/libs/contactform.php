<?php
function fw_contact_form( $settings )
{ 
	$profile_obj = fw_page_template('contact.php');
	$recaptcha_opt = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');
	$permalink = ($profile_obj) ? get_permalink(kvalue($profile_obj, 'ID')) : ''; ?>

    <div class="contact-form">
		<?php if( kvalue( $settings, 'google_map_status') == 'on'):?>
            <div class="google-maps"><?php echo kvalue( $settings, 'google_map'); ?></div>
        <?php endif; ?>
        <form action="<?php echo $permalink; ?>" method="post">
            <ul>
                <li class="row-fluid">
                    <div class="span6">
                        <label><?php _e('Name', THEME_NAME); ?> <small><?php _e('(Required)', THEME_NAME); ?></small></label>
                        <input type="text" name="contact_name" value="<?php echo kvalue($_POST, 'contact_name');?>" class="input-block-level">
                    </div>
                    <div class="span6">
                        <label><?php _e('Email', THEME_NAME); ?> <small><?php _e('(Required)', THEME_NAME); ?></small></label>
                        <input type="text" name="contact_email" value="<?php echo kvalue($_POST, 'contact_email');?>" class="input-block-level">
                    </div>
                </li>
                <li class="row-fluid">
                    <div class="span12">
                        <label><?php _e('Subject', THEME_NAME); ?> <small><?php _e('(Required)', THEME_NAME); ?></small></label>
                        <input name="contact_subject" type="text" value="<?php echo kvalue($_POST, 'contact_subject');?>" class="input-block-level">
                    </div>
                </li>
                <li class="row-fluid">
                    <div class="span12">
                        <label><?php _e('Message', THEME_NAME); ?> <small><?php _e('(Required)', THEME_NAME); ?></small></label>
                        <textarea name="contact_message" class="input-block-level"><?php echo kvalue($_POST, 'contact_message');?></textarea>
                    </div>
                </li>
                <?php if( kvalue( $settings, 'captcha_status') == 'on'):?>
                    <li class="row-fluid">
                    	<div class="span12">
                          <label><?php _e('Antispam', THEME_NAME); ?> <small><?php _e('(Required)', THEME_NAME); ?></small></label>
                          <?php echo recaptcha_get_html($recaptcha_opt['recaptcha_key'], __('Invalid Captcha', THEME_NAME)); ?>
                      </div>
                    </li>
                <?php endif; ?>
                
                <li class="row-fluid">
                    <div class="span12">
                        <input type="submit" name="contact_form" value="<?php _e('Send Message', THEME_NAME); ?>" class="btn btn-red">
                    </div>
                </li>
            </ul>
        </form>
    </div>              
<?php 
}