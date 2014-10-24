<?php if( ! defined('ABSPATH')) exit('restricted access');

/**
 * WPnukes Admin Panel
 *
 * An open source wordpress themes options admin panel
 *
 * @package		WPnukes Apanel
 * @author		WPnukes team
 * @copyright	Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 * @license		http://wpnukes.com/admin_panel/license.html
 * @website		http://wpnukes.com
 * @since		Version 1.0
 * @filesource
 */
 
/**
 * fw_options_class
 *
 * This class handles the default theme options
 *
 * @package		WPnukes Apanel
 * @subpackage	Models
 * @category	Core
 * @author		WPnukes Development Team
 * @website		http://wpnukes.com
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */
 
class fw_options_class
{
	protected $section, $subsection, $current_section, $_webnukes;
	private $_option_name, $_fields, $_settings, $_shortcodes = array(), $use_as_title = '', $titles = array();
	var $messages, $links = array();
	var $html = '';
	
	/**
	 * Constructor
	 *
	 * Loads the theme options page configuration
	 */
	 
	function __construct()
	{
		$this->_webnukes = &$GLOBALS['_webnukes']; /** Clone the fw_base_class object */
		$this->options = $this->_webnukes->config->get(); /** Load the configurations **/

		/** AJAX function */
		add_action('wp_ajax_webnukes_ajax', array($this, '_ajax_response')); /** Wordpress function for callback with priv */
		add_action('wp_ajax_webnukes_ajax_custom', array($this, '_ajax_response_custom'));
		
		//add_action('wp_ajax_webnukes_ajax_callback', array($this, '_ajax_response_callback')); /** Wordpress function for callback with priv */
		

		if( kvalue($_GET, 'subsection') == 'social_networking' || kvalue($_GET, 'subsection') == 'sidebars')
		{
			$this->get_sections();
			$this->get_fields();
			$this->generate_html();
		}
		elseif($GLOBALS['pagenow'] != 'admin-ajax.php')
		{
			$this->fw_get_settings(); /** Get current settings */		
			$this->generate_html(); /** Generate the HTML */
		}
	}
	
	/**
	 * get_sections
	 *
	 * Get the current section and sub-section information which are based on the settings array.
	 *
	 * @access	public
	 * @example $this->get_sections('section=home_page&subsection=banners'); //Virtually change the section and subsections settings
	 * @param string|array to virtually change the section and subsection info
	 * @return string
	 */
	 
	function get_sections($settings = '')
	{
		/** Virtually change the section and subsection settings */
		if($settings)
		{
			if( ! is_array($settings)) parse_str($settings, $settings);
			$_GET['section'] = $_GET['subsection'] = ''; /** Clear the GET section and subsection values */
			$_GET = array_merge($_GET, $settings); /** Add the settings values into $_GET variable so it can be accessible globally */
		}
		
		$this->section = ( ! empty($_GET['section'])) ? $_GET['section'] : 'general_settings'; /** If there is no section value than load default general_settings section /
		
		/** Trigger error if the section is not declared in config files */
		//if( ! isset($this->options[$this->section])) $this->messages[] = create_message('<h3>'.__( 'The requested section was not found on this server.').'</h3>', 'die');
		
		$this->_option_name = THEME_PREFIX.$this->section; /** section name for Wordpress options table */

		if(isset($_GET['subsection'])) /** Load Subsection values */
		{
			
			$this->subsection = isset($_GET['subsection']) ? $_GET['subsection'] : 'color_and_style';
			/** Trigger error if the sub-section is not declared in config files */
			if( ! isset($this->options[$this->section]['SUB'][$this->subsection])) $this->messages[] = create_message('<h3>'.__( 'The requested section was not found on this server.').'</h3>', 'die');
			$this->_option_name = THEME_PREFIX.'sub_'.$this->subsection; /** Wordpress options table name */
			
		}elseif($this->section == 'general_settings' && isset($this->options[$this->section]['SUB']['color_and_style']))
		{
			$this->subsection = isset($_GET['subsection']) ? $_GET['subsection'] : 'color_and_style';
			/** Trigger error if the sub-section is not declared in config files */
			if( ! isset($this->options[$this->section]['SUB'][$this->subsection])) $this->messages[] = create_message('<h3>'.__( 'The requested section was not found on this server.').'</h3>', 'die');
			$this->_option_name = THEME_PREFIX.'sub_'.$this->subsection; /** Wordpress options table name */
		}
		
		return ($this->subsection) ? $this->subsection : $this->section; /** Return the current section or subsection name */
	}
	
	/**
	 * get_fields
	 *
	 * Get the current sections fields settings
	 *
	 * @access	public
	 * @param bool default is false
	 * @return array return the fields settings if the array is true
	 */
	 
	function get_fields($return = false)
	{
		if( ! $this->section) $this->get_sections(); /** Load the section settings if it's empty */
		$this->_fields = ($this->subsection) ? last_nodes(search_node($this->options[$this->section], $this->subsection), $this->options) : last_nodes($this->section, $this->options); /** Get the last settings node */
		if($return) return $this->_fields;
	}
	
	/**
	 * get_settings
	 *
	 * Get and save the settings information into wp_options table
	 *
	 * @access	public
	 * @return	array
	 */
	function fw_get_settings()
	{
		//TODO: update and reduce the function code
		/** The section fields settings */
		if( ! $this->_fields) $this->get_fields();
		
		/** Load settings from database */
		if( ! $settings = get_option($this->_option_name))
			$_POST = $this->_webnukes->config->read_settings($this->_fields);
		
		if(count($_POST))
		{			
			$this->_webnukes->load('validation_class');

			//TODO: replace the setting variable with sophisticated name
			foreach($this->_fields as $field=>$setting)
			{
				//$setting = array_merge($default, $setting);
				//if($GLOBALS['admin-ajax.php'] && ! isset($_POST[$field])) continue;
				
				if($field == 'DYNAMIC')
				{					
					if( ! isset($_POST['DYNAMIC']) || ! is_array($_POST['DYNAMIC'])) $_POST['DYNAMIC'] = array();
	
					foreach($_POST['DYNAMIC'] as $k=>$v)
					{
						foreach($this->_fields['DYNAMIC'] as $dk=>$dv)
						{
							$this->_webnukes->validation->set_rules('DYNAMIC['.$k.']['.$dk.']', '<strong>'.$dv['label'].'</strong>', $dv['validation']);
						}
					}
				}else
				{
					$option_value = $this->_webnukes->kvalue($settings, 'value', '');
					
					if(is_array($option_value))
					{
						foreach($option_value as $k=>$v)
						{
							if(isset($_POST[$k]))
							{
								$settings[$k] = array_values( (array) $_POST[$k]);
							}
						}
					}
					
					$settings[$field] = $this->_webnukes->kvalue($_POST, $field, '');

					if($setting['type'] == 'multi_input')
					{
						foreach( (array) $setting['value'] as $vk=>$vv)
							$this->_webnukes->validation->set_rules($field.'['.$vk.']', '<strong>'.$setting['label'].' '.$vk.'</strong>', $setting['validation']);
					}else
						$this->_webnukes->validation->set_rules($field, '<strong>'.$setting['label'].'</strong>', $setting['validation']);
				}
			}
			

			if($this->_webnukes->validation->run() !== FALSE && empty($this->_webnukes->validation->_error_array))
			{
				foreach($_POST as $k=>$v)
				{
					$postdata = $this->_webnukes->validation->post($k);
					if($k == 'DYNAMIC') $settings['DYNAMIC'] = $_POST['DYNAMIC'];
					else $settings[$k] = ($postdata !== false) ? $postdata : $_POST[$k];
				}
				
				if( isset( $_FILES ) && ! empty( $_FILES ) )
				{
					$key = current( (array) key($_FILES) );
					$field = str_replace('_file', '', $key);

					$upload = @move_uploaded_file( $_FILES[$key]['tmp_name'], FW_LANG_DIR.'/'.$_FILES[$key]['name']);
					if( !$upload ) $this->_webnukes->msg->create('Failed to Upload the file','error');
				}
				
				update_option($this->_option_name, $settings);
					
				$this->_webnukes->msg->create('Database information updated successfully','success');
			}
		}

		return $this->_settings = $settings;
	}
		
	/**
	 * _dynamic_fields
	 *
	 * A link method for html_settings to read dynamic fields settings.
	 *
	 * @access	private
	 * @param	array of dynamic fields
	 * @param	array of html_settings generated data to attach the dynamic settings
	 * @return	array
	 */
	private function _dynamic_fields($fields, $is_sample = false)
	{
		$data = array();
		
		if( ! $is_sample )
		{
			$this->titles = array();
			$settings = ( ! empty($this->_settings['DYNAMIC'])) ? $this->_settings['DYNAMIC'] : array();
			
		}else $settings = array(0);
		
		$counter = 0;
		foreach($settings as $k=>$v)
		{
			foreach($fields as $fk=>$fv)
			{
				$this->use_as_title = ( ! $this->use_as_title || isset($fv['settings']['use_as_title'])) ? $fk : $this->use_as_title;

				$tab = (!empty($fv['settings']['tab'])) ? $fv['settings']['tab'] : 'default';
				
				$dynamic_key = ($is_sample) ? 'DYNAMIC[${counter}]['.$fk.']' : 'DYNAMIC['.$counter.']['.$fk.']';
				
				$fv['attrs']['class'] = $fv['attrs']['class'].' dynamic-'.$fk;

				$data[$counter][$tab][$fk] = $this->_webnukes->html->generator($dynamic_key, $fv, array($dynamic_key=>$this->_webnukes->kvalue($settings[$k], $fk)));
			}
			
			if( ! $is_sample)
			$this->titles[$counter] = ($title = $this->_webnukes->kvalue($v, $this->use_as_title)) ? $title : '[NO TITLE]';
			
			$counter++;
		}
		
		/*$this->_webnukes->html->inline('<script type="text/javascript">var use_as_title = "'.$this->use_as_title.'", dynamic_fields = '.json_encode($fields).';</script>');*/

		return $data;
	}
	
	/**
	 * html_settings
	 *
	 * A method to read the fields settings and generate the html structure.
	 *
	 * @access	private
	 * @param	array of fields
	 * @param 	array default settings or values
	 * @return	array of settings
	 */
	private function html_settings($fields, $settings)
	{
		$data = array();
		foreach($fields as $k=>$v)
		{
			$section = (!empty($v['settings']['section'])) ? $v['settings']['section'] : 'general';
			//$tab = (!empty($v['settings']['tab'])) ? $v['settings']['tab'] : 'default';
			
			if($k == 'DYNAMIC')
			{
				$first = key((array)$v);
				$section = ( ! empty($v[$first]['settings']['section'])) ? $v[$first]['settings']['section'] : 'general';
				//$data[$section][$tab][$k] = $this->_dynamic_fields($v); /** Call another function to bypass 100 times recusive function limitation */
				$data[$section][$k] = $this->_dynamic_fields($v); /** Call another function to bypass 100 times recusive function limitation */

				$data[$section]['DYNAMIC_SAMPLE_DATA'] = $this->_dynamic_fields($v, true);
			}
			else
				//$data[$section][$tab][$k] = $this->_webnukes->html->generator($k, $v, $settings);
				$data[$section][$k] = $this->_webnukes->html->generator($k, $v, $settings);
		}

		return $data;
	}
	
	function social_network()
	{
		
		$social_icon = array('facebook' => 'Facebook', 'twitter' => 'Twitter', 'linkedin' => 'Linkedin', 'myspace' => 'Myspace',
					'vimeo' => 'Vimeo', 'digg' => 'Digg', 'lastfm' => 'Lastfm', 'rss-feeds' => 'RSS Feeds', 
					'stumble-upon' => 'Stumbleupon', 'dribble' => 'Dribble', 'reddit' => 'Reddit', 'flickr' => 'Flickr',
					'skype' => 'Skype', 'youtube' => 'Youtube', 'pinterest' => 'Pinterest', 'foursquare' => 'FourSquare',
					'tumbler' => 'Tumbler', 'gplus' => 'Google +', 'instagram' => 'Instagram', 'blogger' => 'Blogger', 
					'soundcloud' => 'SoundCloud', 'rdio' => 'Rdio', 'groovshark' => 'GroovShark', 'path' => 'Amazon', 
					'itunes' => 'iTunes', 'spotify' => 'Spotify');
		
		if( isset($_POST) && count($_POST) )
		{
			foreach( $social_icon as $k => $v)
			{
				$field = array('url'=>'', 'status'=>'off');
				$_POST[$k] = isset($_POST[$k]) ? array_merge($field, $_POST[$k]) : array($k=>$field);
			}
		}
		//print_r($_POST);
		//$_POST = array('facebook');

		$this->fw_get_settings();
		//print_r($this->_settings);
		foreach( $social_icon as $k => $v )
		{
			$value = isset($this->_settings[$k]) ? $this->_settings[$k] : array();
			$data[$k] = $this->_webnukes->html->generator($k.'[url]', array('type'=>'input', 'label' => $v), array($k.'[url]' =>kvalue($value, 'url')), 'array');
			$data[$k]['status'] = $this->_webnukes->html->generator($k.'[status]', array('type'=>'switch'), array($k.'[status]' =>kvalue($value, 'status') ), 'array');
		}
		return $data;
	}
	
	function sidebars_creator()
	{
		$this->fw_get_settings();
		return $this->_settings;
	}
	
	/**
	 * generate_html
	 *
	 * Generate the html of fields
	 *
	 * @access	public
	 * @return string of generated HTML
	 */
	function generate_html()
	{
		global $_dynamics, $_dynamic_headings;

		$this->_webnukes->load('html_class'); /** Load HTML Class **/
		
		if(!isset($_GET['ajax']) && $_GET['page'] == 'fw_theme_options') 
		{
			/** Load stylesheets files for theme options */
			$this->_webnukes->html->css(array(/*'http://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800',*/
										'style.css','responsive.css','jqtransform.css', 'jquery.ui.datepicker.css', 'colorpicker.css'),'', array('wp-color-picker','thickbox'));
			
			/** Load javascript files for theme options */		
			
			$this->_webnukes->html->js(array('script.js','jquery.jqtransform.js','colorpicker.js', 'timepicker.js', 'functions.js'), '', array('jquery-ui-sortable','media-upload','thickbox','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-tabs', 'jquery-ui-dialog', 'jquery-ui-datepicker', 'wp-color-picker')); /** Load javascript files for theme options */
			$this->_webnukes->html->inline('<script type="text/javascript">jQuery(function($){$(".form_style").jqTransform({imgPath:"'.THEME_URL.'/includes/views/js/img/"});});</script>');
		}
		
		
		//TODO: code to check if the shortcodes are defined
		$data = array('tabs'=>'');
		$data['name'] = ($this->subsection) ? $this->subsection : $this->section;
		$data['side_links'] = $this->_webnukes->html->layout('theme_options/left', array('options'=>$this->options, 'section', $this->section), true); /** Load the side links **/
		$data['titles'] =& $this->titles;
		
		
		if(isset($this->_settings['DYNAMIC']))
		$this->_webnukes->html->inline('<script type="text/javascript">var use_as_title = "'.$this->use_as_title.'";</script>');
		
		if($this->subsection == 'social_networking') 
		{
			$data['html'] = $this->social_network();
			$this->_webnukes->html->layout('theme_options/table', $data); /** Load the layout */
		}
		elseif( kvalue($_GET, 'subsection') == 'sidebars' )
		{
			$data['html'] = $this->sidebars_creator();
			$this->_webnukes->html->layout('theme_options/sidebars', $data); /** Load the layout */
		}
		else 
		{
			/** Generate the form on PHP side */
			$data['html'] = $this->html_settings($this->_fields, $this->_settings);
			$this->_webnukes->html->layout('theme_options/main', $data); /** Load the layout */
		}
		
		if(isset($_GET['ajax']) && $_GET['page'] == 'fw_theme_options') 
		{
			/*echo '<script type="text/javascript">jQuery(function($){$(".form_style").jqTransform({imgPath:"'.THEME_URL.'/includes/views/js/img/"});});</script>';*/
			echo $this->_webnukes->html->build(); /** Load the layout */
			exit;
		}
	}
	
	/**
	 * Ajax Response
	 *
	 * A method to return the ajax response, the method name must contain _ajax in start to prevent hackers access
	 *
	 * @access	public
	 * @return string
	 */
	 
	function _ajax_response()
	{
		$this->get_fields();

		//TODO: ADD THIS MESSAGE INTO GLOBAL MESSAGE CLASS
		if( ! $this->_fields || empty($_POST['data'])) $this->_webnukes->msg->display( __('Invalid Request', THEME_NAME), 'die' );
		
		parse_str($_POST['data'], $data);
		unset($_POST);
		//unset($_POST['action']);
		
		$_POST = $data;

		if( ! $settings = get_option($this->_option_name)) exit('The settings not found');
		else
		{
			$this->_webnukes->load('validation_class');
			
			foreach($_POST['DYNAMIC'] as $k=>$v)
			{
				foreach($this->_fields['DYNAMIC'] as $dk=>$dv)
				{
					$this->_webnukes->validation->set_rules('DYNAMIC['.$k.']['.$dk.']', '<strong>'.$dv['label'].'</strong>', $dv['validation']);
				}
			}
			
			//$db_id = (int) (is_numeric($_POST['db_id'])) ? (int) $_POST['db_id'] : 1;
			
			if($this->_webnukes->validation->run() !== false)
			{
				$settings['DYNAMIC'] = $_POST['DYNAMIC'];
				update_option($this->_option_name, $settings);
				$this->_webnukes->msg->create('The database is updated successfully','success');
			}
		}
		
		$status = ($this->_webnukes->validation->_error_array) ? 0 : 1;
		die(json_encode(array('status'=>$status,'msgs'=>$this->_webnukes->msg->display(array(), 'error', true, true))));
	}
	
	function _ajax_response_custom()
	{
		if( kvalue( $_POST, 'subaction') == 'google_fonts' )
		{
			if( $key = kvalue( $_POST, 'apikey' ) )
			{
				$fonts = @file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key='.$key);
				if( $fonts ) {
					update_option( 'google_web_fonts', $fonts );
					die(json_encode(array('status'=>'success','msgs'=>$this->_webnukes->msg->display(array('Fonts updated successfull, Please refresh the page to view new fonts'), 'success', true, true))));
				}
			}
		}
		die(json_encode(array('status'=>'error','msgs'=>$this->_webnukes->msg->display(array('Failed to get fonts'), 'error', true, true))));
	}
	
}