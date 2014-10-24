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

/**
 * fw_base_class
 *
 * This class provides the core functionality of WPnukes admin panel
 * It has functionality to load and intiate any PHP file and we can access it with parent object
 *
 * @package		WPnukes Apanel
 * @subpackage	Core
 * @category	Core
 * @author		WPnukes Development Team
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */
 
class fw_base_class
{	
	/** Check the Wpnukes admin panel reserver object names */
	private $reserves = array('config');
	/** Auto load the core classes */
	private $_autoload = array('config', 'functions', 'ajax_class');
	/** The default section */
	private $_section = array();
	/** Database Options */
	private $dboptions = array();
	
	/**
	 * Constructor
	 *
	 * Auto Load the core classess
	 *
	 */

	function __construct()
	{
		$classes = (object) array();
		
		foreach($this->_autoload as $class)
			$this->load($class);
	}
	
	/**
	 * Load PHP classes
	 *
	 * This method includes and auto initiate the constant of PHP classes from includes folder.
	 * These classes are accessible locally through $this
	 *
	 * @access	public
	 * @example	fw_base_class::load(options_class, options); it will be accessible through fw_base_class::options
	 * @param	string the class and file name without fw_ prefix
	 * @param	string the class constant name default is the same as class name
	 * @param	boolen load or create the object default is false
	 * @return	object
	 */
	 
	function load($class = '', $objectName = '', $loadonly = false)
	{
		//TODO: remove the limitation so we can load any php file
		$className = 'fw_'.$class;
		$objectName = ($objectName) ? $objectName : str_ireplace('_class', '', $class);

		if(isset($this->$objectName)) return; //return if the class is already initiated

		$filepath = BASEPATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$class.'.php';

		//TODO: Fix the bug of name
		if( ! class_exists($className) && file_exists($filepath)) require_once($filepath);
		elseif( ! is_a($this, $className) && ! class_exists($className)) return false;
		elseif( ! class_exists($className)) return false;
		
		/** Don't create object */
		if($loadonly) return;
		
		/** Chain methods support */
		return $this->$objectName = new $className;
	}

	/**
	 * Get the current section info
	 *
	 * @access	public
	 * @param	bool true to return array
	 * @return	array
	 */
	 
	function get_sections($return = false)
	{
		if($this->_sections_list) return $this->_sections_list;
		elseif( ! $this->optionName) return array();
		
		$sections = get_option(THEME_PREFIX.$this->optionName);

		foreach((array)$sections['DYNAMIC'] as $section)
		{
			$this->_sections_list[$section['slug']] = $section;
		}
		
		if($return) return (array) $this->_sections_list;
	}
	
	/**
	 * Section settings
	 *
	 * A method to read the settings
	 * if the section list is empty it will call the get_sections.
	 *
	 * @access	public
	 * @param string section name (default is general settings)
	 * @param string array key
	 * @return array
	 */
	 
	function fw_get_settings($section = 'general_settings', $key = '', $std = '')
	{
		$options = wp_cache_get( 'alloptions', 'options');
		if( $value = kvalue( $options, $section ) ) 
			$settings = maybe_unserialize( $value );
		else
			$settings = get_option(THEME_PREFIX.$section);
			
		if($key) return (isset($settings[$key])) ? $settings[$key] : $std;
		else return $settings;
	}
	
	/**
	 * Set value
	 *
	 * A method to check the variable value, if the value is not exists it will return the second variable default value
	 *
	 * @access	public
	 * @param	string defined value or variable
	 * @param	string default value
	 * @param	boolean true if the string is defined
	 * @return string
	 */
	 
	function set_value($constant, $value = '', $is_defined = false)
	{
		if($is_defined)
		{
			$constant_value = constant($constant);
			if ( defined($constant) && ! empty($constant_value)) return $constant_value;
			else return $value;
		}else
		{
			if(empty($constant)) return $value;
			else return $constant;
		}
	}
	
	/**
	 * Function to check whether a key is exists in array, Otherwise return default value
	 * @param arr  array an array from which a key need to be checked
	 * @param key  string A string need to be checked either exists in an array or not
	 * @param default string/array If the key is not exists in given array then the default value will be returned
	 * 
	 * @return string/array Either array or string will be returned.
	 */
	 
	 //@TODO: we have to add the support of xpath
	function kvalue($arr = array(), $key, $default = false)
	{	
		if(isset($arr[$key])) return $arr[$key];
		else return $default;
	}
}

$_webnukes = new fw_base_class;

/**
 * Load the configuration files
 *
 * This class loads the configuration files from config folder.
 *
 * @package		WPnukes Apanel
 * @subpackage	Core
 * @category	Core
 * @author		WPnukes Development Team
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */
class fw_config
{
	private $_config = array();
	private $loaded = '';
	
	/**
		load method loads the files from config directory
	**/
	function load($files = '', $objectName = '')
	{
		if( ! is_array($files)) $files = array($files);
		
		foreach($files as $file)
		{
			$filepath = BASEPATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.$file.'.php';
			require_once($filepath);
			$this->_config = array_merge($options, $this->_config);
		}

		return $this->_config;
	}
	
	function add($key, $value)
	{
		if( ! is_array($key))
		{
			$key = array($key=>$value);
		}
		
		foreach($key as $k=>$v)
		{
			if( ! isset($this->_config[$k])) $this->_config[$k] = $v;
		}
	}
	
	function get($key = '', $default = '')
	{
		if(empty($this->_config)) $this->load('theme_options'); //Load theme options configurations
		if( ! $key) return $this->_config;
		elseif($key == 'array') return $this->_config;
		return ($this->_config[$key]) ? $this->_config[$key] : $default;
	}
	
	function _default_settings($key = 'std', $data = false)
	{
		$data = ($data === false) ? $this->_config[$this->loaded] : $data;

		$settings = array();
		foreach($data as $k=>$v)
		{
			if( ! isset($v[$key]) && is_array($v))
			{
				$settings = $this->_default_settings($key, $v);
			}else
			{
				$settings[$k] = $v[$key];
			}
		}
		
		return $settings;
	}
	
	/** Read the default settings */
	function read_settings($settings)
	{
		$data = array();
		
		foreach($settings as $k=>$v)
		{
			$std = (isset($v['std'])) ? $v['std'] : '';
			
			$data[$k] = $GLOBALS['_webnukes']->kvalue($_POST, $k, $std);
			
			if($k == 'DYNAMIC') continue;
			elseif($v['type'] == 'multi_input')
			{
				foreach($v['value'] as $vk=>$vv)
					$data[$k][$vk] = $vv;
			}
		}
		
		return $data;
	}
}

/**
 * Load functions
 *
 * This class loads the helper functions from includes/functions folder
 *
 * @package		WPnukes Apanel
 * @subpackage	Core
 * @category	Core
 * @author		WPnukes Development Team
 * @website		http://www.wpnukes.com
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */
 
class fw_functions
{
	/**
	 * Load
	 *
	 * Load the functions files
	 *
	 * @access	public
	 * @param	array|string the file(s) names
	 * @return	void
	 */
	function load($files = array())
	{
		if( ! is_array($files)) $files = array($files);
		
		foreach($files as $file)
		{
			$filepath = BASEPATH.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.$file.'.php';		
			require_once($filepath);
		}
	}
}

/**
 * Print array in readable format
 *
 * A print_r similar function to print the array in readable format with the help of HTML &lt;pre&gt; tag
 *
 * @access	public
 * @param	array to print
 * @param	boolean default is true to auto exit the php script execution
 * @return	void
 */
 
function printr($var, $exit = true)
{
	echo '<pre>';
	print_r($var);
	if($exit) exit;
}

//TODO: This is todo marker