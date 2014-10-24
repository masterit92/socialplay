<?php if( ! defined('ABSPATH')) exit('restricted access');

/**
 * WPnukes Admin Panel
 *
 * An open source wordpress themes options admin panel
 *
 * @package		WPnukes Apanel
 * @author		WPnukes team <info@wpnukes.com>
 * @copyright	Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 * @license		http://wpnukes.com/admin_panel/license.html
 * @website		http://wpnukes.com
 * @since		Version 1.0
 */
 
class fw_wp_html_class extends fw_html_class
{
	function tabs($tabs, $current = '', $icon = 'icon32')
	{
		if(empty($tabs)) return;

		$html = '<div id="icon-themes" class="'.$icon.'"><br></div>';
		$html .= '<h2 class="nav-tab-wrapper">';
		
		foreach($tabs as $tab=>$name)
		{
			$class = ($tab == $current) ? ' nav-tab-active' : '';
			$html .= '<a class="nav-tab'.$class.'" href="?page=theme-settings&tab='.$tab.'">'.$name.'</a>';
		}
		
		$html .= '</h2>';
		
		return $html;
	}
}