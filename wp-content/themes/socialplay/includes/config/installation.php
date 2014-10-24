<?php if ( ! defined('ABSPATH')) exit('restricted access');

/*//Layout Modules
if( ! get_option(THEME_PREFIX.'layout_modules'))
{
	add_option(THEME_PREFIX.'layout_modules', array('main_slider'=>'Main Slider','advanced_search'=>'Advanced Search',
													'dynamic_tabs'=>'Dynamic Tabs','logo_scroller'=>'Logo Scroller'));
}

//SYMPTOM CHECKER SETTINGS
if( ! get_option(THEME_PREFIX.'symptom_checker'))
{
	require_once('symptom_checker.php');
	$data = array();
	foreach($options as $k=>$v)
	{
		$data[$k] = (isset($v['std'])) ? $v['std'] : '';
	}
	
	update_option(THEME_PREFIX.'symptom_checker', $data, '', 'no');
}
*/

/** THEME LIVE SUPPORT */
add_option(THEME_PREFIX.'theme_live_support', false);


wp_redirect( admin_url('themes.php?page=fw_theme_options') );
exit;