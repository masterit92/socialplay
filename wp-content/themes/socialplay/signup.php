<?php
/* Template Name: Signup */

if( is_user_logged_in() || get_option('users_can_register') != 1 ) 
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

	<!-- Page Content -->
  <div class="row">
    <article class="contents span12">
			<div style="background-image:url(<?php echo $GLOBALS['_webnukes']->fw_get_settings('sub_background', 'signup_bg_image'); ?>);" class="signing-block">
        <section class="login-box">
          <div class="heading">
            <h1 class="text-red"><?php _e('Register Yourself', THEME_NAME); ?></h1>
          </div>
          <?php if(isset($_POST) && $_POST) fw_create_user($_POST); ?>
          <form action="<?php echo get_permalink();?>" method="post">

            <input type="text" class="input-block-level" placeholder="Enter Username" name="user_login" value="<?php echo kvalue($_POST, 'user_login');?>">
            <input type="text" class="input-block-level" placeholder="Enter Email" name="user_email" value="<?php echo kvalue($_POST, 'user_email');?>">
            
            <p id="reg_passmail"><?php _e('A password will be e-mailed to you.', THEME_NAME); ?></p>
			
            <input type="submit" value="<?php _e('Sign Up', THEME_NAME); ?>" class="btn btn-red">
          </form>
        </section><!-- contacts end -->
      </div><!-- signing-block ends -->
    </article><!-- contents end -->
  </div>

  
</div>
<?php get_footer(); ?>