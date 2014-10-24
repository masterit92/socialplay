<?php if ( ! defined('ABSPATH')) exit('restricted access'); //stop direct script access

/** Stop execution if we don't have theme settings */
if( ! defined('THEME_NAME')) wp_die( __('Theme settings are missing in functions.php', THEME_NAME) );

/** Define Unique Theme Prefix to avoide conflict between multiple themes settings */
define('THEME_PREFIX', 'fw_'.THEME_NAME.'_');

/** Theme directory path */
define('THEME_PATH', get_template_directory());

/** Theme directory URL */
define('THEME_URL', get_template_directory_uri());

/** Home URL */
define('HOME_URL', home_url());

/** Define Base directory path */
define('BASEPATH', dirname(__FILE__));

/** Define WPnukes Apanel skin directory */
define('FW_ADMIN_SKIN', THEME_PATH.'/includes/views/');

/** Load core classes */
require_once('core/base_class.php');

/** Auto load helper functions */
$_webnukes->functions->load(array('html_forms','helpers'));

/** System Messages */
$_webnukes->load('messages_class', 'msg');

/** Admin functions */
if ( is_admin() )
{
	
	/** Plugin Activation */
	require_once(BASEPATH.DIRECTORY_SEPARATOR.'thirdparty'.DIRECTORY_SEPARATOR.'tgm-plugin-activation'.DIRECTORY_SEPARATOR.'plugins.php');
	
	function fw_theme_activation_hook()
	{
		global $pagenow;
		/** Theme activation configuration */
			//fw_create_default_pages();
			update_option('users_can_register', 1);
			update_option('default_role', 'video_contributor');

			if( $pagenow == 'themes.php' && kvalue($_GET, 'activated') )
			require_once('config/installation.php');
	}
	add_action("after_switch_theme", "fw_theme_activation_hook", 10 ,  2);
	/** Plugin Activation */
	//require_once(BASEPATH.DIRECTORY_SEPARATOR.'thirdparty'.DIRECTORY_SEPARATOR.'tgm-plugin-activation'.DIRECTORY_SEPARATOR.'plugins.php');

	/** We have to include stylesheets and javascript that's why we are not going to use built-in wordpress callback functions */
	if(isset($_GET['page']) && strstr($_GET['page'], 'fw_'))
	{
		
		if(isset($_GET['dummydata_export']))
		{
			$_webnukes->load('backup_class', 'backup');
			$_webnukes->backup->export();

		}
		
		if(isset($_GET['dummydata']))
		{
			$_webnukes->load('backup_class', 'backup');
			$_webnukes->backup->import();

			$_webnukes->msg->create( __('The dummy data is installed successfully', THEME_NAME), 'success' );
			$_SERVER['REQUEST_URI'] = preg_replace('#\&dummydata=1#i', '', $_SERVER['REQUEST_URI']);
		}
		$_webnukes->load('options_class','options');
		
	}
	
	/** Register WPnukes Apanel pages links */
	function admin_menu_links()
	{
		/** Theme Options */
		add_theme_page( __('Theme Options', THEME_NAME), __('Theme Options', THEME_NAME), 'edit_user', 'fw_theme_options', 'fw_settings_page');
		//add_menu_page( __('WP Nukes', THEME_NAME), __('WP Nukes', THEME_NAME), 'manage_options', 'fw_theme_options', 'fw_settings_page');
	}

	add_action('admin_menu', 'admin_menu_links');
	
	/** fw_settings_page WPnukes Apanel pages processing and HTML builder */
	function fw_settings_page()
	{
		echo $GLOBALS['_webnukes']->html->build();
	}
	
	/** Load Plugins */
	
	
	//add_action('admin_init', 'theme_metabox');
	add_action('admin_enqueue_scripts', 'theme_metabox');
	function theme_metabox()
	{
		global $post_type;
		
		if($GLOBALS['pagenow'] == 'post-new.php' || $GLOBALS['pagenow'] == 'post.php')
		{
			if( $post_type == 'page' )
			{
				$GLOBALS['_webnukes']->load('html_class');
				
				if( $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'page_builder' ) == 'on' )
					$GLOBALS['_webnukes']->html->js(array('jquery.jqtransform.js', 'jquery.tmpl.min.js', 'layout.js'), '', array('jquery-ui-dialog'));
					//$GLOBALS['_webnukes']->html->js(array('jquery.tmpl.min.js', 'layout.js'));
				
				$GLOBALS['_webnukes']->html->css(array('responsive.css','page_builder.css'));
			}
		}
		
		if( $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'page_builder' ) == 'on' )
		add_meta_box( 'fw_page_builder', __('Page Builder', THEME_NAME), 'page_builder_settings',
						 'page', 'normal',  'core' );
	}
	
	
	function page_builder_settings()
	{
		$GLOBALS['_webnukes']->load('layout_class');
		$GLOBALS['_webnukes']->layout->meta_box();
		//global $_webnukes;
		//echo $GLOBALS['post_type'];exit;
		//$_webnukes->load('layout_class');
		
	}
	add_action('publish_page', array($GLOBALS['_webnukes']->load('layout_class'), 'publish_page'));
}

// must be called before load_theme_textdomain()
add_filter( 'locale', 'fw_theme_localized' );
function fw_theme_localized($locale) {
	$lang = get_option(THEME_PREFIX.'sub_choose_language');

	$locale = isset($lang['language']) ? $lang['language'] : $locale;
	return $locale;
}

/** END ADMIN OPTIONS **/