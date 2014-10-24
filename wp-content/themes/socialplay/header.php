<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<title>
<?php ( is_home() || is_front_page() ) ? bloginfo('name') : wp_title(); ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php if( $favicon = $GLOBALS['_webnukes']->fw_get_settings('sub_header_settings', 'favicon' ) ): ?>
	<link rel="icon" type="image/png" href="<?php echo $favicon; ?>" />
<?php endif; ?>

<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<?php wp_head(); ?>
</head>

<?php $styles = $GLOBALS['_webnukes']->fw_get_settings('sub_color_and_style', 'color_scheme', 'light'); 
$_COOKIE['social_color_scheme'] = kvalue( $_COOKIE, 'social_color_scheme', $styles );?>
<body <?php body_class( $styles ); ?>>

<div class="tp-bar">
    <div class="container">
        <?php get_template_part('libs/userbar'); ?>
        <?php wp_nav_menu(array('theme_location' => 'top_menu', 'container'=>null, 'menu_class' => 'tp-links', 'fallback_cb' => false)); ?>
    </div>
</div>
<!-- tp-links end -->
<div class="logo-bar">
    <div class="container">
        
        <div class="logo">
            <?php $logo = $GLOBALS['_webnukes']->fw_get_settings('sub_logo'); ?>
            <a href="<?php echo home_url(); ?>">
            	<img src="<?php echo kvalue($logo, 'logo', get_template_directory_uri().'/images/logo.png'); ?>" alt="<?php bloginfo('name');?>"> 
			</a>
		</div>
        <!-- logo ends -->
        <?php $main_menu_toggle = $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'menu_status');

		$main_menu = wp_nav_menu(array('theme_location' => 'main_menu', 'container'=>null, 'menu_class' => 'menu', 'echo' => false,'fallback_cb' => false)); ?>
        
        <div class="menu-btn" <?php if( $main_menu && $main_menu_toggle == 'on' ): ?>style="display:none;"<?php endif; ?>>
        	<a href="javascript:void(0);" class="open-menu">
			<?php _e('Menu', THEME_NAME);?>
            </a>
        </div>
        
        <div class="socialize">
            <ul>
                <li>
                    <div class="main-search">
                        <form action="" method="get" onsubmit="return validate();">
                            <input type="text" name="s" id="search" placeholder="<?php _e('Search Videos', THEME_NAME);?>" value="<?php echo kvalue($_GET, 's');?>" />
                            <input type="submit" value="" />
                        </form>
                    </div>
                </li>
                <li class="sp"></li>
                <?php echo fw_social_networks();?>
            </ul>
        </div>
    </div>
</div>
<!-- logo-bar ends -->
<div class="menu-bar" style=" <?php echo ($main_menu_toggle == 'on' ) ? 'display:block;' : ''; ?>">
    <div class="container">
        <?php echo $main_menu; ?>
    </div>
</div>
<!-- menu-bar ends --> 
