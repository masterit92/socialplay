<?php
/* Template Name: Forgot Password */

if( is_user_logged_in() ) 
{
	wp_redirect( home_url() );
	exit;
}

get_header(); ?>

<!-- Page Container -->

<div class="container" id="page-single"> 
	
	<!-- BreadCrumbs Bar -->
	<div class="bread-bar clearfix"> 
		
		<!-- BreadCrumbs --> 
		<?php echo get_the_breadcrumb(); ?> 
		
		<!-- News Ticker -->
		<?php fw_news_ticker(); ?>
	</div>
	<!-- bread-bar ends --> 
	
	<!-- Page Content -->
	<div class="row">
		<article class="contents span12">
			<div style="background-image:url(<?php echo get_template_directory_uri();?>/images/source/login-background.jpg);" class="signing-block">
				<section class="login-box login-box-mar">
					<?php do_action('lost_password'); ?>
					<div class="heading">
						<h1 class="text-red">
							<?php _e('Lost Password', THEME_NAME);?>
						</h1>
					</div>
					<p>
						<?php _e('Please enter your username or email address. You will receive a link to create a new password via email.', THEME_NAME);?>
					</p>
					<form action="<?php echo wp_lostpassword_url( esc_url(get_permalink()) ); ?>" method="post" id="socialplay_forgot">
						<input type="text" name="user_login" id="user_login" class="input-block-level" placeholder="<?php _e('Username or E-mail', THEME_NAME);?>" value="<?php echo esc_attr(kvalue($_POST, 'user_login')); ?>" size="20" />
						</label>

						<input type="hidden" name="security" value="<?php echo wp_create_nonce("socialplay-forgot-password");?>" />
						<input type="hidden" name="action" value="forgot_password" />
						<input type="hidden" name="redirect_to" value="<?php echo esc_url( get_permalink() ); ?>" />
						<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-cyan" value="<?php _e('Get New Password', THEME_NAME); ?>" />
						<?php if( $signup_obj = fw_page_template('signup.php') && get_option('users_can_register') == 1 ):?>
						<?php $signup_obj = fw_page_template('signup.php');?>
						<a href="<?php echo get_permalink( kvalue( $signup_obj, 'ID') ); ?>" class="btn btn-red" title="<?php echo kvalue( $signup_obj, 'post_title'); ?>">
						<?php _e('Signup', THEME_NAME); ?>
						</a>
						<?php endif; ?>
						<?php if( $GLOBALS['_webnukes']->fw_get_settings('sub_APIs', 'facebook_api_key') ): ?>
						<?php get_template_part('libs/fb'); ?>
						<?php endif; ?>
					</form>
				</section>
				<!-- contacts end --> 
			</div>
			<!-- signing-block ends --> 
		</article>
		<!-- contents end --> 
	</div>
</div>
<?php get_footer(); ?>
