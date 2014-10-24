<?php if ( ! defined('ABSPATH')) exit('restricted access');


class Wpnukes_Audios
{

	function __construct()
	{
		
		$this->activation_function();
		/** Video Settings Pages */
		add_action('admin_menu', array($this, 'settings_page'));
		
		add_action( 'init', array($this, 'register_post_type') );
		
		/** Custom field for video categories */
		//add_action('video_channel_edit_form_fields', array($this, 'category_edit_form'));
		//add_action('video_channel_add_form_fields', array($this, 'category_edit_form'));
		add_action('audio_album_edit_form_fields', array($this, 'category_edit_form'));
		add_action('audio_album_add_form_fields', array($this, 'category_edit_form'));
		
		/** Custom field for video categories data saving */
		add_action('created_term', array($this, 'save_term_data'));
        add_action('edit_term', array($this, 'save_term_data'));
		add_action('publish_wpnukes_audios', array($this, 'save_wpnukes_audios') );
		
		add_action('wp_ajax_nukes_video_action', array($this, 'ajax_handler'));
		add_action('wp_ajax_nopriv_nukes_video_action', array($this, 'ajax_handler'));
		
		//add_action( 'admin_print_scripts-edit-tags.php', array($this, 'admin_category_script'), 11 );
		add_action( 'admin_print_scripts-post.php', array($this, 'admin_script'), 11 );
		add_action( 'admin_print_scripts-post-new.php', array($this, 'admin_script'), 11 );

		//require_once 'video_class.php';
		//$this->helper = new WPNukes_Helper;		
		
		$this->_settings = get_option('wpnukes_video_settings');//print_r($this->_settings);exit;

		
		/** Video Columns */
		add_filter('manage_edit-wpnukes_audios_columns', array($this, 'head_only_gallery'), 10);  
		add_action('manage_wpnukes_audios_posts_custom_column', array($this, 'content_only_gallery'), 10, 2);
	}
	
	function activation_function()
	{
		global $wpdb;
		add_role('video_contributor', __('Video Contributor', THEME_NAME), array('read' => true, 'edit_posts' => true, 'delete_pots' => false));
		
	}
	
	function head_only_gallery($default)
	{
		unset($default['date']);
		unset($default['comments']);
		$default['author'] = __('Author', THEME_NAME);
		$default['audio_category'] = __('Category', THEME_NAME);
		$default['audio_tag'] = __('Tags', THEME_NAME);
		
		$default['comments'] = __('<span><span class="vers"><div class="comment-grey-bubble" title="Comments"></div></span></span>', THEME_NAME);
		$default['date'] = __('Date', THEME_NAME);
		
		return $default;
	}
	
	function content_only_gallery($column_name, $post_ID)
	{
		if($column_name == 'audio_category')
		{
			$terms = get_the_term_list( $post_ID , 'audio_category' , '' , ',' , '' );
			if(is_string($terms)) echo $terms;
			else echo '&mdash;';
			
		}elseif($column_name == 'audio_tag'){
			$terms = get_the_term_list( $post_ID , 'audio_tag' , '' , ',' , '' );
			if(is_string($terms)) echo $terms;
			else echo '&mdash;';
		}
	}
	
	function get_value($obj, $val, $def = '')
	{
		if(is_array($obj) && isset($obj[$val])) return $obj[$val];
		elseif(is_object($obj) && isset($obj->$val)) return $obj->$val;
		elseif($def) return $def;
		else return false;
	}
	
	function selected($name, $value, $echo = true)
	{
		if( $name == $value ) {
			if($echo) echo ' selected="selected"';
			else return ' selected="selected"';
		}
	}
	
	function settings_page()
	{
		//add_submenu_page('edit.php?post_type=wpnukes_videos', __('Video Settings'), __('Video Settings'), 'edit_posts', basename(__FILE__), array($this, 'video_settings'));
	}
	
	function admin_script()
	{
		global $post_type, $post;
		$custom_thumb = ($this->get_value($this->_settings, 'custom_thumbnail') == 'active') ? 'true' : 'false';
		echo '<script type="text/javascript">var nukes_custom_thumbnail = '.$custom_thumb.'; </script>';
		if($post_type == 'wpnukes_videos')
		{
			wp_enqueue_script('video_custom_script', get_template_directory_uri().'/js/custom.js');
			wp_enqueue_style('video_custom_style', get_template_directory_uri().'/css/video.css');
		}
	}
	
	function save_wpnukes_audios($id)
	{
		global $post;
		$post_id = ($id) ? $id : $post->ID;

		if( is_admin() ) return;

		$videos = array();
		foreach($_POST as $k => $v )
		{
			if( strstr($k, 'webnukes_video_')) $videos[str_replace('webnukes_video_', '', $k)] = $v;
		}

		$safety_mode = kvalue($_POST, 'webnukes_video_safety', 'off');
		$privacy = kvalue($_POST, 'webnukes_video_privacy', 'public');
		update_post_meta($post_id, '_wpnukes_video', $videos);

		update_post_meta($post_id, '_wpnukes_video_safety', $safety_mode);
		update_post_meta($post_id, '_wpnukes_video_privacy', $privacy);
	}
	
	function register_post_type()
	{
		
		register_post_type( 'wpnukes_audios',
			array(
				'labels' => array(
					'name' => _x('Audios', 'WPnukes Audios', THEME_NAME),
					'singular_name' =>  _x('Audio', 'WPnukes Audio', THEME_NAME),
					'add_new' =>  _x('Add New', THEME_NAME),
					'add_new_item' =>  __('Add New Audio', THEME_NAME),
					'edit' =>  __('Edit', THEME_NAME),
					'edit_item' =>  __('Edit Audio', THEME_NAME),
					'new_item' =>  __('New Audio', THEME_NAME),
					'view' =>  __('View', THEME_NAME),
					'view_item' =>  __('View Audio', THEME_NAME),
					'search_items' =>  __('Search Audios', THEME_NAME),
					'not_found' =>  __('No Audio found', THEME_NAME),
					'not_found_in_trash' =>  __('No Audio found in Trash', THEME_NAME),
					'parent' =>  __('Parent Audio', THEME_NAME)
				),
				'description' => __('Audio post type to create a Audio collection', THEME_NAME),
				'public' => true,
				'show_in_admin_bar' => false,
				'show_ui' => true,
				'menu_position' => 15,
				'supports' => array( 'title', 'thumbnail', 'editor', 'comments', 'author' ),
				'taxonomies' => array( 'audio_category', 'audio_tag', 'audio_album'),
				'menu_icon' => get_template_directory_uri(). '/images/audio.png',
				'rewrite' => array( 'slug' => 'audio' ),
				'has_archive' => true,
				//'register_meta_box_cb' => array($this, 'register_meta_box_cb'),
			)
	    );
		
		register_taxonomy('audio_category', 'wpnukes_audios', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Audio Categories', 'WPnukes Audio Categories', THEME_NAME ),
				'singular_name' => _x( 'Audio Category', 'WPnukes Audio Category', THEME_NAME ),
				'search_items' =>  __( 'Search Audio Categories', THEME_NAME ),
				'all_items' => __( 'All Audio Categories', THEME_NAME ),
				'parent_item' => __( 'Parent Category', THEME_NAME ),
				'parent_item_colon' => __( 'Parent Category:', THEME_NAME ),
				'edit_item' => __( 'Edit Category' , THEME_NAME),
				'update_item' => __( 'Update Category', THEME_NAME ),
				'add_new_item' => __( 'Add New Category', THEME_NAME ),
				'new_item_name' => __( 'New Audio Category Name', THEME_NAME ),
				'menu_name' => __( 'Audio Categories', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'audio_category', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube/" */
			),
		));
		
		register_taxonomy('audio_tag', 'wpnukes_audios', array(
			'hierarchical' => false,
			'labels' => array(
				'name' => _x( 'Audio Tags', 'WPnukes Audio Tags', THEME_NAME ),
				'singular_name' => _x( 'Audio Tag', 'WPnukes Audio Tag', THEME_NAME ),
				'search_items' =>  __( 'Search Audio Tags', THEME_NAME ),
				'all_items' => __( 'All Audio Tags', THEME_NAME ),
				'parent_item' => __( 'Parent Tag', THEME_NAME ),
				'parent_item_colon' => __( 'Parent Tag:', THEME_NAME ),
				'edit_item' => __( 'Edit Tag', THEME_NAME ),
				'update_item' => __( 'Update Tag', THEME_NAME ),
				'add_new_item' => __( 'Add New Tag', THEME_NAME ),
				'new_item_name' => __( 'New Audio Tag Name', THEME_NAME ),
				'menu_name' => __( 'Audio Tags', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'audio_tag', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube/" */
			),
		));
		
		
		register_taxonomy('audio_album', 'wpnukes_audios', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Audio Album', 'WPnukes Audio Albums', THEME_NAME ),
				'singular_name' => _x( 'Audio Album', 'WPnukes Audio Album', THEME_NAME ),
				'search_items' =>  __( 'Search Audio Albums', THEME_NAME ),
				'all_items' => __( 'All Audio Albums', THEME_NAME ),
				'parent_item' => __( 'Parent Album' , THEME_NAME),
				'parent_item_colon' => __( 'Parent Album:', THEME_NAME ),
				'edit_item' => __( 'Edit Album', THEME_NAME ),
				'update_item' => __( 'Update Album', THEME_NAME ),
				'add_new_item' => __( 'Add New Album', THEME_NAME ),
				'new_item_name' => __( 'New Audio Album Name', THEME_NAME ),
				'menu_name' => __( 'Audio Albums', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'audio_album', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube/" */
			),
		));

	}
	
	function category_edit_form($term)
	{
		$term = (object) $term;
		$term->term_id = isset($term->term_id) ? $term->term_id : '';
		include('taxonomy_meta.php');
	}
	
	function save_term_data($term_id)
	{
		$t = $GLOBALS['_webnukes'];
		$current = wp_get_current_user();
		$taxonomies = array('video_channel', 'video_playlist');
		$key = '_wpnukes_'.kvalue($_POST, 'taxonomy').'_'.$term_id;
		
		if( !in_array(kvalue($_POST, 'taxonomy'), $taxonomies) ) return;

		$movefile = array();
		if( $_FILES && kvalue( kvalue($_FILES, 'image_file'), 'name') ) 
		{
			$movefile = $this->helper->upload_file( kvalue($_FILES, 'image_file'), true, array(170, 125) );
			if( !$movefile ) $movefile = get_option($key.'_image');
		}else $movefile = get_option($key.'_image');

		if( is_admin() && kvalue($_POST, 'author') ) $c_user = kvalue($_POST, 'author');
		else $c_user = in_array('video_contributor', $current->roles) ? $current->ID : 1;
		
		
		update_option($key.'_author', $c_user);
		update_option($key.'_privacy', kvalue($_POST, 'privacy', 'public'));
		update_option($key.'_image', $movefile);
		
	}

}

$_wpnukes_audios = new Wpnukes_Audios;
$GLOBALS['_wpnukes_audios'] = $_wpnukes_audios;