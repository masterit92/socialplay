<?php 

class FW_Metaboxes
{
	var $_config;
	
	function __construct()
	{
		global $pagenow;
		add_action('admin_init', array($this, 'fw_post_meta_box'));
		
		add_action('save_post', array( $this, 'save_post'));
		if($pagenow == 'post.php' || $pagenow == 'post-new.php')
		{
			include('config/posts.php');
			$this->_config = $options;
		}
	}

	function fw_post_meta_box()
	{
		add_action('admin_print_scripts', array( $this, 'scripts_styles'));
		
		add_meta_box( 'video_settings', __('Video Settings', THEME_NAME), array($this, 'post_settings'),
						 'wpnukes_videos', 'normal',  'core' );
		add_meta_box( 'audio_settings', __('Audio Settings', THEME_NAME), array($this, 'post_settings'),
						 'wpnukes_audios', 'normal',  'core' );
		add_meta_box( 'post_settings', __('Post Settings', THEME_NAME), array($this, 'post_settings'),
						 'post', 'normal',  'core' );
		add_meta_box( 'page_settings', __('Page Settings', THEME_NAME), array($this, 'post_settings'),
						 'page', 'normal',  'core' );
						 
		
	}
	
	function save_post($post_id)
	{
		global $post;

		$types = array('post', 'page', 'fw_slider', 'wpnukes_videos', 'wpnukes_audios');
		$post_type = kvalue($_POST, 'post_type');
	
		if( !in_array( $post_type, $types ) ) return;
		
		$config = kvalue($this->_config, $post_type);
		if( !$config ) return;
		
		$data = array();
		foreach( (array)$config as $k => $v)
		{
			if( kvalue($_POST, $k )) $data[str_replace('webnukes_', '', $k)] = kvalue($_POST, $k );
		}
		
		$format = '';
		if($post_type == 'wpnukes_videos') $format = 'video';
		elseif( $post_type == 'wpnukes_audios') $format = 'audio';
		
		$key = ( $format ) ? '_wpnukes_'.$format : 'wpnukes_'.$post_type.'_settings';

		if( $data ) update_post_meta( $post_id, $key, $data);
		

		if( $post_type == 'wpnukes_videos' || $post_type == 'wpnukes_audios' )
		{
			$extras = array('_source'=>'', '_duration'=>'', '_safety'=>'off', '_privacy'=>'public');
			foreach( $extras as $k => $v)
			{
				if( $have_value = kvalue( $_POST, 'webnukes'.$k, $v))
				update_post_meta($post_id, '_wpnukes_'.$format.$k, $have_value);
			}
		}
	}
	
	function scripts_styles()
	{
		global $pagenow, $post_type; //printr($post_type);
		
		wp_enqueue_style( 'metabox-jqtransform-css', get_template_directory_uri().'/includes/views/css/jqtransform.css' );
		$styles = array('custom_style'=> 'views/css/style.css');
		foreach($styles as $k => $v ) wp_register_style( $k, get_template_directory_uri().'/libs/metaboxes/'.$v);
		
		if( $post_type != 'page'){
			if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) wp_enqueue_style('custom_style');
		}
		
		wp_enqueue_script( 'metabox-jqtransform_script', get_template_directory_uri().'/includes/views/js/jquery.jqtransform.js' );
		//wp_enqueue_script( 'metabox-custom_script', get_template_directory_uri().'/libs/metaboxes/views/js/scripts.js' );
	}

	
	function post_settings( $post )
	{	
		global $post_type;
		
		$config = kvalue( $this->_config, $post_type );
		if( !$config ) return;
		
		$format = '';
		if($post_type == 'wpnukes_videos') $format = 'video';
		elseif( $post_type == 'wpnukes_audios') $format = 'audio';
		
		$key = ( $format ) ? '_wpnukes_'.$format : 'wpnukes_'.$post_type.'_settings';
		
		$settings = (array)get_post_meta($post->ID, $key, true);
		
		$extras = array('safety'=>'off', 'privacy'=>'public');
		foreach( $extras as $k => $v)
		{
			if( $have_value = get_post_meta($post->ID, '_wpnukes_'.$format.'_'.$k, true) ) $settings[$k] = $have_value;
		}
		
		//printr($settings);
		echo $this->generate( $config, $settings, get_template_directory().'/libs/metaboxes/views/posts.php');
	}
	
	function generate( $options, $settings, $tpl)
	{
		
		$_webnukes = $GLOBALS['_webnukes'];
		$_webnukes->load('html_class', '', true); /** Load WPnukes HTML class */
		$_webnukes->load('wp_html_class','html'); /** Load wordpress HTML class */
		
		$data = array();
		//$settings = get_option('wpnukes_video_settings');
		
		foreach( (array)$options as $k=>$v )
		{
			$value = (isset($settings[str_replace('webnukes_', '', $k)])) ? array($k=>$settings[str_replace('webnukes_', '', $k)]) : array($k=>kvalue($v, 'std'));
			$data['fields'][$k] = $_webnukes->html->generator($k, $v, $value, 'array');
		}
		return $_webnukes->html->layout($tpl, $data, true);
	}
	
}

new FW_Metaboxes;