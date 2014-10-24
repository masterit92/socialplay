
<?php if(is_user_logged_in() ): ?>
	<?php global $current_user;
    $user_thumb = '';
    if( $avatar = get_user_meta( kvalue($current_user, 'ID'), 'avatar', true)){
     $user_thumb = '<img src="'.$avatar.'" alt="profile" />';
    }?>
 
    <ul class="user-bar">
        <li>
            <?php echo $user_thumb; ?>
            <span><?php _e('Welcome', THEME_NAME); ?>, <strong><?php echo kvalue( kvalue($current_user, 'data'), 'display_name'); ?></strong></span>
        </li>
        <?php if( $profile_obj = fw_page_template('profile.php') ): ?>
            <li><a href="<?php echo get_permalink( kvalue( $profile_obj, 'ID') ); ?>" title="<?php echo kvalue( $profile_obj, 'post_title'); ?>"><?php _e('Dashboard', THEME_NAME); ?></a></li>
        <?php endif; ?>
        <li><a href="<?php echo wp_logout_url( home_url() ); ?>" title="<?php _e('Logout', THEME_NAME); ?>"><?php _e('Logout', THEME_NAME); ?></a></li>
    </ul>

<?php else: ?>

    <ul class="pull-left">
        <?php if( $login_obj = fw_page_template('login.php') ): ?>
            <li><a class="btn btn-cyan" href="<?php echo get_permalink( kvalue( $login_obj, 'ID') ); ?>" title="<?php echo kvalue( $login_obj, 'post_title'); ?>"><?php _e('Login', THEME_NAME); ?></a></li>
        <?php endif; ?>
        <?php if( $GLOBALS['_webnukes']->fw_get_settings('sub_APIs', 'facebook_api_key') ):?>
        	<li><?php get_template_part('libs/fb'); ?></li>
            <li class="sp"></li>
        <?php endif; ?>
        
        <?php $signup_obj = fw_page_template('signup.php');
		if( $signup_obj && get_option('users_can_register') == 1 ): ?>
            <li><a class="btn btn-red" href="<?php echo get_permalink( kvalue( $signup_obj, 'ID') ); ?>" title="<?php echo kvalue( $signup_obj, 'post_title'); ?>"><?php _e('Signup', THEME_NAME); ?></a></li>
        <?php endif; ?>
    </ul>

<?php endif; ?>