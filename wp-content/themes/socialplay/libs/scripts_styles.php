<?php if ( ! defined('ABSPATH')) exit('restricted access');

if( ! is_admin())
{
	/** Include styles and scripts */
	add_action('wp_enqueue_scripts', 'fw_enqueue_scripts');
	
	/** add action to wp_print_styles for print our styles */
	add_action('wp_head', 'fw_theme_head', 30);
	
	/** add action wp_footer */
	add_action('wp_footer', 'fw_theme_footer', 30);
}

function fw_enqueue_scripts()
{
	global $wp_styles, $wp_query;
	$css_dir = THEME_URL . 'css/';
	$js_dir = THEME_URL . '/js/';

	$scripts = array('socialplay-login'=>'login.js', 'jquery-ui-tooltip'=>'jquery-ui-tooltip.js', 'jquery-superslides'=>'jquery.superslides.js', 'jquery-jcarousel-min'=>'jquery.jcarousel.min.js',
				'jquery-totemticker'=>'jquery.totemticker.js', 'jquery-cookies'=>'jquery.cookie.js', 'jPages-min'=>'jPages.js', 'custom-scripts'=>'script.js', 
				'jquery-form-upload'=>'jquery.form.js', 'custom-js'=>'custom.js', 'profile_script' => 'profile_script.js','jquery-prettyPhoto'=>'jquery.prettyPhoto.js');
	
	fw_demo_color_picker();
	
	/** register and enqueue scripts */
	foreach($scripts as $js => $file)
	{
		wp_register_script($js, $js_dir.$file, array(), '', true);
	}
	
	wp_enqueue_script(array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-ui-tooltip', 'jquery-cookies', 'jquery-effects-drop'));
	
	if( (is_home() || is_front_page()) && !$wp_query->is_posts_page ) wp_enqueue_script( array('jquery-superslides') );
	else wp_enqueue_script( array( 'jquery-totemticker' ) );

	if( is_archive() || $wp_query->is_posts_page ) wp_enqueue_script( array('jquery-jcarousel-min') );
	if( is_page() && is_page_template('profile.php') )
	{
		wp_enqueue_script( array('jquery-ui-tabs', 'jPages-min', 'jquery-ui-dialog', 'profile_script', 'jquery-form-upload') );
		
	}elseif( is_single() || is_page() || $wp_query->is_posts_page )
	{
		wp_enqueue_script( array('socialplay-login', 'comment-reply', 'jquery-jcarousel-min','jquery-prettyPhoto' ) );
	}
	
	//wp_enqueue_media();
	wp_enqueue_script( array( 'custom-scripts', 'custom-js' ) );
	
	$cache = wp_cache_get( 'alloptions', 'options');
	if( kvalue( $cache, 'google_web_fonts' ) ) $fonts = kvalue( $cache, 'google_web_fonts' );
	else $fonts = @file_get_contents(get_template_directory().'/libs/default_fonts');
	$fonts = @json_decode($fonts);
	
	/** Applying google fonts for headings and content */
	$font_family = $GLOBALS['_webnukes']->fw_get_settings('sub_fonts_settings', 'font_family');
	$content_family = $GLOBALS['_webnukes']->fw_get_settings('sub_fonts_settings', 'content_family');
	$google_font = '';
	$google_content_font = '';
	
	if( $fonts )
	{
		foreach( (array)kvalue( $fonts, 'items' ) as $f )
		{
			$family = kvalue( $f, 'family');
			if( $family == $font_family ){
				$google_font = str_replace(' ', '+', $family) ;
				if( $varians = kvalue( $f, 'variants') ) $google_font .= ':'.implode(',',$varians);
				if( $subset = kvalue( $f, 'subsets') ) $google_font .= '&subset='.implode(',',$subset);
			}
			if( kvalue( $f, 'family') == $content_family ) {
				$google_content_font = str_replace(' ', '+', $family) ;
				if( $varians = kvalue( $f, 'variants') ) $google_content_font .= ':'.implode(',',$varians);
				if( $subset = kvalue( $f, 'subsets') ) $google_content_font .= '&subset='.implode(',',$subset);
			}
			
			if( $google_content_font && $google_font ) break;
		}
	}
	
	if( ! $google_font ) $google_font = 'Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700';
	if( ! $google_content_font ) $google_content_font = 'Oswald:400,700,300';
	
	/** Include style files */
	$styles = array (
		'main-styles' => 'style.css',
		'common-styles' => 'css/common.css',
		'dark-styles' => 'css/dark.css',
		//'color-styles' => 'css/color.css',
		'responsive-styles' => 'css/responsive.css',
		'superslides' => 'css/superslides.css',
		'ie10lt' => 'css/ie-lt10.css',
		'jpages-animate' => 'css/animate.css',
		'prettyPhoto' => 'css/prettyPhoto.css',
	);
	wp_enqueue_style( 'google_font_family', 'http://fonts.googleapis.com/css?family='.$google_font);
	wp_enqueue_style( 'goog_font_content_family', 'http://fonts.googleapis.com/css?family='.$google_content_font);
	
	foreach($styles as $css=>$file)
	{
		/** register our stylesheets from array */
		wp_register_style($css, THEME_URL.'/'.$file, false, '1.0', 'screen' );
		wp_enqueue_style( $css );
	}
	
	$wp_styles->add_data( 'ie10lt', 'conditional', 'lt IE 10' );
	
}

function fw_theme_head()
{
	global $_webnukes;
	echo '<script type="text/javascript">var ajaxurl="'.admin_url('admin-ajax.php').'";</script>'."\n";
	$font_settings = $GLOBALS['_webnukes']->fw_get_settings('sub_fonts_settings');
	$bg_settings = $GLOBALS['_webnukes']->fw_get_settings('sub_background');//printr($font_settings);
	
	$style = '<style>';
	if( $font_family = kvalue( $font_settings, 'font_family' ) ) $style .= 'h1, h2, h3, h4, h5, h6{ font-family: '.$font_family.' !important;}';
	if( $content_family = kvalue( $font_settings, 'content_family' ) ) $style .= 'body{ font-family: '.$content_family.' !important;}';
	
	foreach( range(1, 6) as $h )
	{
		if( kvalue( $font_settings, 'h'.$h ) ) $style .= 'h'.$h.'{ font-size: '.kvalue( $font_settings, 'h'.$h ).'px !important;}';
	}
	$style .= fw_custom_style();
	if( $body_size = kvalue( $font_settings, 'body' ) ) $style .= 'body{ font-size: '.$body_size.' !important;}';
	$style .= '</style>';
	
	echo $style;
	fw_apply_color_scheme();
	echo $_webnukes->fw_get_settings('sub_header_settings', 'css_js')."\n";
}

function fw_theme_footer()
{
	global $_webnukes, $wp_query;
	$slider_settings = $_webnukes->fw_get_settings('sub_superslides'); ?>
    
    <script type="text/javascript">
		jQuery(document).ready(function($) {
	
			<?php if( is_archive() || is_singular('post') || $wp_query->is_posts_page || is_page_template('page-builder.php')): ?>
        
				jQuery('.picSlide').jcarousel({
						scroll: 1,
						auto: 2,
						visible: 1
				});
			
			<?php endif;

			if( is_page_template('front-page-tpl.php') ): ?>
			
				$('#slides').superslides('update');
				
				$('#slides').superslides({
					slide_easing: '<?php echo kvalue($slider_settings, 'effect', 'easeInOutCubic'); ?>',
					slide_speed: '<?php echo kvalue($slider_settings, 'speed', 'normal'); ?>',
					pagination:  <?php echo (kvalue($slider_settings, 'pagination')) ? 'true' : 'false'; ?>,
					hashchange: true,
					scrollable: <?php echo (kvalue($slider_settings, 'scrollable')) ? 'true' : 'false'; ?>
				});
			
	  
			<?php endif;?>
			
    		<?php if( is_page() && is_page_template('profile.php')) : ?>

				jQuery(function($){
		
				  $("div.holder").jPages({
					containerID : "itemContainer",
					perPage : <?php echo (int)get_option('posts_per_page');?>,
					startPage    : 1,
					startRange   : 1,
					midRange     : 5,
					endRange     : 1
					
				  });
				  
				  $("div.holder1").jPages({
					containerID : "itemContainer1",
					perPage : <?php echo (int)get_option('posts_per_page');?>,
					startPage    : 1,
					startRange   : 1,
					midRange     : 5,
					endRange     : 1
				  });
				
				});

    		<?php endif; ?>

            <?php 
			if( $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'play_video') == 'on' ):?>
				
				/*** Open video on clicking video thumb ***/
				$('.icon-play').live('click', function(e){
					var iframe = $(this).parents('.video-thumb').children('iframe');
					if(iframe.length)
					{
						$(iframe).css('display','block');
						$('.mejs-container', $(this).parents('.video-thumb')).css('display','block');
						e.preventDefault();
					}
				});
				
			<?php endif; ?>

		});
	</script>
	<?php echo "\n".$_webnukes->fw_get_settings('sub_footer_settings', 'analytics')."\n";
}

function fw_demo_color_picker()
{
	wp_enqueue_style( 'wp-color-picker' );
	
	wp_enqueue_script(
        'iris',
        admin_url( 'js/iris.min.js' ),
        array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
        false,
        1
    );
    wp_enqueue_script(
        'wp-color-picker',
        admin_url( 'js/color-picker.min.js' ),
        array( 'iris' ),
        false,
        1
    );
    $colorpicker_l10n = array(
        'clear' => __( 'Clear' ),
        'defaultString' => __( 'Default' ),
        'pick' => __( 'Select Color' )
    );
    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
}

function fw_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'fw_login_logo_url' );

function fw_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter( 'login_headertitle', 'fw_login_logo_url_title' );

function fw_login_stylesheet() { 
	global $_webnukes;
	$genral_settings = $_webnukes->fw_get_settings('sub_logo');?>
	
    <style>
		.login h1 a{background-image:url(<?php echo kvalue($genral_settings, 'logo', get_template_directory_uri().'/images/logo.png');?>);background-size:274px 63px;width:326px;height:67px;}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'fw_login_stylesheet' );