<?php if ( ! defined('ABSPATH')) exit('restricted access');
/**
 * WPnukes Admin Panel
 *
 * An open source wordpress themes options admin panel
 *
 * @package		WPnukes Apanel
 * @author		WPnukes team
 * @copyright	Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 * @license		http://wpnukes.com/admin_panel/license.html
 * @link		http://wpnukes.com
 * @since		Version 1.0
 * @filesource
 */
 
/**
 * fw_html_class
 *
 * This class handles the html of WPnukes Apanel.
 *
 * @package		WPnukes Apanel
 * @subpackage	Core
 * @category	Models
 * @author		WPnukes Development Team
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */
 
class fw_html_class
{
	protected $_webnukes = array();
	private $js = array('_deps'=>array()), $css = array('_deps'=>array()), $html = '';
	var $inline = '';
	
	function __construct()
	{
		$this->_webnukes = $GLOBALS['_webnukes']; /** Clone the fw_base_class object */
		$this->_webnukes->load('validation_class'); /** Load validation class */
		
		add_action( 'admin_print_scripts', array($this, 'admin_script'), 11 );
		add_action( 'admin_print_styles', array($this, 'admin_style'), 11 );
	}
	
	function admin_script()
	{
		foreach($this->js as $k=>$v)
		{
			if($k == '_deps') wp_enqueue_script($v);
			else
			{
				wp_register_script($k, $v, '', '', true);
				wp_enqueue_script($k);
			}
		}
	}
	
	function admin_style()
	{
		foreach($this->css as $k=>$v)
		{
			if($k == '_deps') wp_enqueue_style($v);
			else
			{
				wp_register_style($k, $v);
				wp_enqueue_style($k);
			}
		}
	}
	
	/**
	 * Load javascript
	 *
	 * Load the javascript files
	 *
	 * @access	public
	 * @param string|array of the javascript file(s) names or urls
	 * @param string the url basepath where the file is located
	 * @param bool used for inline javascripts
	 * @return void
	 */
	 
	function js($files = array(), $basepath = '', $deps = array())
	{
		if ( ! is_array($files)) $files = array($files); /** Create the files array */
		
		$this->js['_deps'] = array_merge((array)$this->js['_deps'], $deps);
		
		foreach($files as $file)
		{
			if(preg_match('@http://|/@', $file)) /** Check if the path is already given */
			{
				$hash = md5($file); /** Unique hash to avoide multiple loadings */
				$this->js[$hash] = $file;
				//wp_register_script($hash, $file); /** Built-in wordpress function to load file */
				//wp_enqueue_script($hash);
			}else
			{
				$explod = explode('.', $file );
				$ext = end($explod);
				$filename = ($ext != 'js') ? $file.'.js' : $file;
				$src = ($basepath) ? $basepath.$filename : THEME_URL.'/includes/views/js/'.$filename;
				$hash = $src;
				$this->js[$hash] = $src;
				//wp_register_script($hash, $src);
				//wp_enqueue_script($hash);
				//$this->js[$file] = ($basepath) ? $basepath.$filename : THEME_URL.'/includes/js/'.$filename;
			}
		}
	}
	
	/**
	 * Load stylesheets
	 *
	 * Load stylesheets files
	 *
	 * @access	public
	 * @param string|array of the stylesheet file(s) names or urls
	 * @param string the url basepath where the file is located
	 * @return void
	 */
	 
	function css($files = array(), $basepath = '', $deps = array())
	{
		if ( ! is_array($files)) $files = array($files); /** Create the files array */
		
		$this->css['_deps'] = array_merge((array)$this->css['_deps'], $deps);
		
		foreach($files as $file)
		{
			if(preg_match('@http://|/@', $file)) /** Check if the path is already given */
			{
				$hash = md5($file); /** Unique hash to avoide multiple loadings */
				$this->css[$hash] = $file;
				//wp_register_style($hash, $file); /** Built-in wordpress function to load file */
				//wp_enqueue_style($hash);
			}else
			{
				$explode = explode( '.', $file );
				$ext = end($explode);
				$filename = ($ext != 'css') ? $file.'.css' : $file;
				$src = ($basepath) ? $basepath.$filename : THEME_URL.'/includes/views/css/'.$filename;
				$hash = $src;
				$this->css[$hash] = $src;
				//wp_register_style($hash, $src);
				//wp_enqueue_style($hash);
			}
		}
	}
	
	/**
	 * Load Inline HTML
	 * 
	 * Load Inline Javascript and CSS code
	 * 
	 * @access	private
	 * @param	string code to load
	 * @param	string head or footer (default is head)
	 * @param	int priority
	 * @return	void
	 */
	
	function inline($code, $section = 'head', $priority = 10)
	{
		$this->inline = ($this->inline) ? $this->inline."\n".$code : $code;
		if($section == 'footer') add_action('admin_footer', array($this, 'admin_footer'), $priority);
		else add_action('admin_head', array($this, 'admin_head'), $priority);
	}
	
	function admin_head()
	{
		echo $this->inline;
		$this->inline = '';
	}
	
	function admin_footer()
	{
		echo $this->inline;
		$this->inline = '';
	}
	
	/**
	 * Load layout
	 *
	 * Load the layout file
	 *
	 * @access	public
	 * @param	string file name to load
	 * @param	array data vairables
	 * @param	bool to return the generated html
	 * @return	string if the return param is true
	 */
	function layout($file = '', $data = array(), $return = false)
	{
		if( ! $file) return;
		@extract($data);
		ob_start();
		
		$file = (strstr($file, '.php')) ? $file : $file.'.php';
		$path = (file_exists($file)) ?  $file : FW_ADMIN_SKIN.$file;
		
		include($path);
		$html = ob_get_contents();
		ob_end_clean();

		if($return) return $html;
		$this->html[$file] = $html;
	}
	
	/**
	 * Build HTML
	 *
	 * Build the final HTML
	 *
	 * @access	public
	 * @param	bool to release the load of memory
	 * @return	string of generated HTML
	 */
	function build($autoclear = true)
	{
		$html = implode('', (array)$this->html);
		if($autoclear) $this->html = '';
		return $html;
	}

	/**
	 * HTML generator
	 *
	 * Generate the option HTML
	 *
	 * @access	public
	 * @param	string|int key
	 * @param	string value
	 * @param	array of settings
	 * @param	string (html|element|array)
	 * @return	string
	 */
	function generator($field, $settings, $user_settings, $return = 'html')
	{		
		$default_value = (isset($_POST[$field])) ? $_POST[$field] : $this->_webnukes->kvalue($user_settings, $field);
		
		$default_value = html_entity_decode($default_value);


		$default = array('type'=>'input','settings'=>'','attrs'=>array(),'label'=>'','std'=>'','info'=>'','value'=>'');		
		
		$settings = array_merge($default, $settings);
		
		$html = array();		
		$html['label'] = '<label for="'.$field.'" class="control-label">'.$settings['label'].'</label>';
		$html['info'] = (!empty($settings['info'])) ? '<span class="help-block">'.$settings['info'].'</span>' : '';

		switch($settings['type'])
		{
			case "input":
				$html['element'] = form_input(array_merge(array('name'=>$field,'value'=>$default_value,'id'=>$field), (array) $settings['attrs']));
			break;
			
			case "password":
				$html['element'] = '<input type="password" id="'.$field.'" name="'.$field.'" '._parse_form_attributes('', $settings['attrs']).' />';
			break;
			
			case "dropdown":
				$settings['attrs'] = array_to_string(array_merge((array) $settings['attrs'], array('id'=>$field)));
				$html['element'] = '<span class="form_style">'.form_dropdown($field, $settings['value'], $this->_webnukes->validation->set_value($field, $default_value), $settings['attrs']).'</span>';
			break;
			
			case "multiselect":
				$size = (count($settings['value']) < 10) ? count($settings['value']) * 20 : 220;
				$settings['attrs'] = array_to_string(array_merge((array) $settings['attrs'], array('id'=>$field, 'style'=>"height:".$size."px;")));
				$html['element'] = form_multiselect($field.'[]', $settings['value'], $this->_webnukes->validation->set_value($field, $default_value), $settings['attrs'] );
			break;
			
			case "textarea":
				$settingsvalue = empty($user_settings[$field]) ? $settings['value'] : $user_settings[$field];
				$html['element'] = form_textarea(array_merge(array('name'=>$field,'value'=>$this->_webnukes->validation->set_value($field, html_entity_decode($settingsvalue)),'id'=>$field), (array) $settings['attrs']));
			break;
			
			case "image":
				$counter = (is_numeric($field)) ? $field : 1;
				$html['element'] = '<span class="file_upload" id="imageupload">'.form_input(array_merge(array('name'=>$field,'value'=>$this->_webnukes->validation->set_value($field, $default_value),'id'=>$field), (array) $settings['attrs']));
				$html['element'] .= '<em id="image_button'.$counter.'" class="upload_image" type="submit">Upload</em></span>';
				
				/** Sample Code **/
				if(isset($settings['settings']['preview']))
				{
					$image = $this->_webnukes->validation->set_value($field, $default_value);
					$preview = '<div class="upload-preview pattern-none">
								  <a class="close close-preview" href="#"></a>
								  <div class="preview-hold">
									<img alt="" src="'.$this->_webnukes->validation->set_value($field, $default_value).'">
								  </div>
								</div>';


					if($return == 'html') $html['info'] = $html['info'].$preview;
					else $html['element'] .= $preview;
				}
			break;
			
			case "wp_gallery" : 
				$html['element'] = form_input(array_merge(array('name'=>$field,'value'=>$default_value,'id'=>$field), (array) $settings['attrs']));
				
				$html['element'] .= '<div class="wp-layout-gallery wp-media-buttons">
				<a href="#" class="button insert-media add_media" data-editor="content" title="Add Media">
				<span class="wp-media-buttons-icon"></span> Select Media</a>
				</div>';
			break;
			case "switch" : 
				$html['element'] = '';
				$checked = ($this->_webnukes->kvalue($user_settings, $field) == 'on') ? 'checked="checked"' : '';
				$html['element'] = '<span class="form_style switch"><input type="checkbox" name="'.$field.'" '.$checked.'></span>';
			break;
			case 'file':
				$html['element'] = '<span class="file_upload">';
									
				$html['element'] .= form_input(array_merge(array('name'=>$field,'value'=>$default_value), (array) $settings['attrs'])).
									'<input type="file" onchange="this.form.'.$field.'.value = this.value" class="fileUpload" name="'.$field.'_file" id="fileUpload">
									<em>'.__('UPLOAD', THEME_NAME).'</em>';
				$html['element'] .= '</span>';
				$html['preview'] = '';
				if(kvalue($user_settings, $field)) $html['preview'] = kvalue($user_settings, $field);
			break;
			
			case "multi_input":
				$html['element'] = '';
				foreach($settings['value'] as $key=>$val)
					$html['element'] .= '<span class="help-inline">'.slugtotext($key).': </span>'.form_input(array_merge(array('name'=>$field.'['.$key.']','value'=>$default_value[$key],'id'=>$key), (array) $settings['attrs']));
			break;
			case "checkbox":
			case "radio":
				$html['element'] = '<div class="clearfix">';
				foreach($settings['value'] as $key=>$val):
					$html['element'] .= '<span class="form_style form_style_radio">'.form_radio($field, $key, ($default_value == $key) ? true : '',$settings['attrs']).'<label class="'.$settings['type'].' cont-lable" for="'.$field.'"> '.$val.'</label></span>'.
									'';
				endforeach;
			$html['element'] .= '</div>';
			break;
			
			case "colorbox": /**Code by coder 3.2.0 */
				$html['element'] = form_input(array_merge(array('name'=>$field,'value'=>$default_value,'id'=>$field, 'class'=>'nuke-color-field'), (array) $settings['attrs']));
			break;
			
			case "timepicker": /**Code by coder 3.2.0 */
				$html['element'] = form_input(array_merge(array('name'=>$field,'value'=>$default_value,'id'=>$field), (array) $settings['attrs']));
			break;
			
			case "hidden":
				$html['label'] = '';
				$html['element'] = form_input(array_merge(array('type'=>'hidden','name'=>$field,'value'=>$default_value,'id'=>$field), kvalue($settings, 'attrs')));
			break;	
			
			case "patterns":
				$settings['attrs']['style'] = 'display:none;';
				$html['element'] = '<ul class="patterns">';
				foreach((array)kvalue($settings, 'value') as $val):
					$chosen = ( $val == $default_value ) ? ' chosen' : '';
					$html['element'] .= '<li class="'.$val.' '.$chosen.'"><i class="icon-chosen"></i>'.form_radio($field, $val, ($default_value == $val) ? true : '',$settings['attrs']).'</li>';
				endforeach;
				$html['element'] .= '</ul>';
			break;		
		}
		
		if( kvalue( kvalue( $settings, 'settings'),  'html' ) ) $html['html'] = kvalue( kvalue( $settings, 'settings'),  'html' );
				
		if($return == 'element') return kvalue($html, 'element');
		elseif($return == 'array') return $html;
		else
		{
			$return =
			'<div class="control-group">
				'.kvalue($html, 'label').'
				<div class="controls">
					'.kvalue($html, 'element').'
					'.kvalue($html, 'info').'
				</div>
			</div>
			<hr class="sp">';

			if(isset($settings['settings']['section_heading'])) $return = '<h2 class="section-heading">'.$settings['settings']['section_heading'].'</h2>'.$return;

			return $return;
		}
	}
}