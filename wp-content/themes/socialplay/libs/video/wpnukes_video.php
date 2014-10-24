<?php if ( ! defined('ABSPATH')) exit('restricted access');


//session_start();


class Wpnukes_Videos
{

	function __construct()
	{
		
		$this->activation_function();
		/** Video Settings Pages */
		
		add_action( 'init', array($this, 'register_post_type') );
		
		/** Custom field for video categories */
		add_action('video_channel_edit_form_fields', array($this, 'category_edit_form'));
		add_action('video_channel_add_form_fields', array($this, 'category_edit_form'));
		add_action('video_playlist_edit_form_fields', array($this, 'category_edit_form'));
		add_action('video_playlist_add_form_fields', array($this, 'category_edit_form'));
		
		/** Custom field for video categories data saving */
		add_action('created_term', array($this, 'save_term_data'));
        add_action('edit_term', array($this, 'save_term_data'));
		//add_action('publish_wpnukes_videos', array($this, 'save_wpnukes_videos') );
		
		//add_action('wp_ajax_nukes_video_action', array($this, 'ajax_handler'));
		//add_action('wp_ajax_nopriv_nukes_video_action', array($this, 'ajax_handler'));
		
		//add_action( 'admin_print_scripts-edit-tags.php', array($this, 'admin_category_script'), 11 );
		//add_action( 'admin_print_scripts-post.php', array($this, 'admin_script'), 11 );
		//add_action( 'admin_print_scripts-post-new.php', array($this, 'admin_script'), 11 );

		require_once 'video_class.php';
		$this->helper = new WPNukes_Helper;		

		
		/** Video Columns */
		add_filter('manage_edit-wpnukes_videos_columns', array($this, 'head_only_gallery'), 10);  
		add_action('manage_wpnukes_videos_posts_custom_column', array($this, 'content_only_gallery'), 10, 2);
	}
	
	function activation_function()
	{
		add_role('video_contributor', __('Video Contributor', THEME_NAME), array('read' => true, 'edit_posts' => true, 'delete_pots' => false));
	}
	
	function head_only_gallery($default)
	{
		unset($default['date']);
		unset($default['comments']);
		$default['author'] = __('Author', THEME_NAME);
		$default['video_category'] = __('Category', THEME_NAME);
		$default['video_tag'] = __('Tags', THEME_NAME);
		
		$default['comments'] = __('<span><span class="vers"><div class="comment-grey-bubble" title="Comments"></div></span></span>', THEME_NAME);
		$default['date'] = __('Date', THEME_NAME);
		
		return $default;
	}
	
	function content_only_gallery($column_name, $post_ID)
	{
		if($column_name == 'video_category')
		{
			$terms = get_the_term_list( $post_ID , 'video_category' , '' , ',' , '' );
			if(is_string($terms)) echo $terms;
			else echo '&mdash;';
			
		}elseif($column_name == 'video_tag'){
			$terms = get_the_term_list( $post_ID , 'video_tag' , '' , ',' , '' );
			if(is_string($terms)) echo $terms;
			else echo '&mdash;';
		}
	}


	
	function save_wpnukes_videos($id, $data = '')
	{
		global $post;
		$post_id = ($id) ? $id : $post->ID;
		
		if( !$data ) return;
		$prefix = (kvalue($data, 'webnukes_video_post_type') ) ? 'video' : 'audio';
		$default = (array)get_post_meta( $post_id, '_wpnukes_'.$prefix, true);
		if( is_admin() ) return;

		$videos = array();
		foreach($data as $k => $v )
		{

			if( strstr($k, 'webnukes_'.$prefix.'_') && kvalue( $data, $k)) $videos[str_replace('webnukes_'.$prefix.'_', '', $k)] = $v;
		}

		$videos = wp_parse_args( $videos, $default);
		update_post_meta($post_id, '_wpnukes_'.$prefix, $videos);

		$extras = array('_source'=>'', '_duration'=>'', '_safety'=>'off', '_privacy'=>'public');
		foreach( $extras as $k => $v)
		{
			if( $have_value = kvalue( $data, 'webnukes_'.$prefix.$k, $v))
			update_post_meta($post_id, '_wpnukes_'.$prefix.$k, $have_value);
		}
		
	}
	
	function register_post_type()
	{
		
		register_post_type( 'wpnukes_videos',
			array(
				'labels' => array(
					'name' => _x('Videos', 'WPnukes Videos', THEME_NAME),
					'singular_name' =>  _x('Video', 'WPnukes Video', THEME_NAME),
					'add_new' =>  _x('Add New', THEME_NAME),
					'add_new_item' =>  __('Add New Video', THEME_NAME),
					'edit' =>  __('Edit', THEME_NAME),
					'edit_item' =>  __('Edit Video', THEME_NAME),
					'new_item' =>  __('New Video', THEME_NAME),
					'view' =>  __('View', THEME_NAME),
					'view_item' =>  __('View Video', THEME_NAME),
					'search_items' =>  __('Search Videos', THEME_NAME),
					'not_found' =>  __('No Video found', THEME_NAME),
					'not_found_in_trash' =>  __('No Video found in Trash', THEME_NAME),
					'parent' =>  __('Parent Video', THEME_NAME)
				),
				'description' => __('Custom post type for video support', THEME_NAME),
				'public' => true,
				'show_in_admin_bar' => false,
				'show_ui' => true,
				'menu_position' => 15,
				'supports' => array( 'title', 'thumbnail', 'editor', 'comments', 'author' ),
				'taxonomies' => array( 'video_category', 'video_tag'),
				'menu_icon' => get_template_directory_uri(). '/images/video.png',
				'rewrite' => array( 'slug' => 'video' ),
				'has_archive' => true,
				//'register_meta_box_cb' => array($this, 'register_meta_box_cb'),
			)
	    );
		
		register_taxonomy('video_category', 'wpnukes_videos', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Video Categories', 'WPnukes Video Categories', THEME_NAME ),
				'singular_name' => _x( 'Video Category', 'WPnukes Video Category', THEME_NAME ),
				'search_items' =>  __( 'Search Video Categories', THEME_NAME ),
				'all_items' => __( 'All Video Categories', THEME_NAME ),
				'parent_item' => __( 'Parent Category', THEME_NAME ),
				'parent_item_colon' => __( 'Parent Category:', THEME_NAME ),
				'edit_item' => __( 'Edit Category' , THEME_NAME),
				'update_item' => __( 'Update Category', THEME_NAME ),
				'add_new_item' => __( 'Add New Category', THEME_NAME ),
				'new_item_name' => __( 'New Video Category Name', THEME_NAME ),
				'menu_name' => __( 'Video Categories', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'video_category', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube/" */
			),
		));
		
		register_taxonomy('video_tag', 'wpnukes_videos', array(
			'hierarchical' => false,
			'labels' => array(
				'name' => _x( 'Video Tags', 'WPnukes Video Tags', THEME_NAME ),
				'singular_name' => _x( 'Video Tag', 'WPnukes Video Tag', THEME_NAME ),
				'search_items' =>  __( 'Search Video Tags', THEME_NAME ),
				'all_items' => __( 'All Video Tags', THEME_NAME ),
				'parent_item' => __( 'Parent Tag', THEME_NAME ),
				'parent_item_colon' => __( 'Parent Tag:', THEME_NAME ),
				'edit_item' => __( 'Edit Tag', THEME_NAME ),
				'update_item' => __( 'Update Tag', THEME_NAME ),
				'add_new_item' => __( 'Add New Tag', THEME_NAME ),
				'new_item_name' => __( 'New Video Tag Name', THEME_NAME ),
				'menu_name' => __( 'Video Tags', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'video_tag', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube/" */
			),
		));
		
		
		register_taxonomy('video_playlist', 'wpnukes_videos', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Video Playlist', 'WPnukes Video Playlists', THEME_NAME ),
				'singular_name' => _x( 'Video Playlist', 'WPnukes Video Playlist', THEME_NAME ),
				'search_items' =>  __( 'Search Video Playlists', THEME_NAME ),
				'all_items' => __( 'All Video Playlists', THEME_NAME ),
				'parent_item' => __( 'Parent Playlist' , THEME_NAME),
				'parent_item_colon' => __( 'Parent Playlist:', THEME_NAME ),
				'edit_item' => __( 'Edit Playlist', THEME_NAME ),
				'update_item' => __( 'Update Playlist', THEME_NAME ),
				'add_new_item' => __( 'Add New Playlist', THEME_NAME ),
				'new_item_name' => __( 'New Video Playlist Name', THEME_NAME ),
				'menu_name' => __( 'Video Playlists', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'video_playlist', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube/" */
			),
		));
		register_taxonomy('video_channel', 'wpnukes_videos', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Video Channel', 'WPnukes Video Channels', THEME_NAME ),
				'singular_name' => _x( 'Video Channel', 'WPnukes Video Channel', THEME_NAME ),
				'search_items' =>  __( 'Search Video Channels', THEME_NAME ),
				'all_items' => __( 'All Video Channels', THEME_NAME ),
				'parent_item' => __( 'Parent Channel', THEME_NAME ),
				'parent_item_colon' => __( 'Parent Channel:', THEME_NAME ),
				'edit_item' => __( 'Edit Channel', THEME_NAME ),
				'update_item' => __( 'Update Channel', THEME_NAME ),
				'add_new_item' => __( 'Add New Channel', THEME_NAME ),
				'new_item_name' => __( 'New Video Channel Name', THEME_NAME ),
				'menu_name' => __( 'Video Channels', THEME_NAME ),
			),
			'rewrite' => array(
				'slug' => 'video_channel', /** This controls the base slug that will display before each term */
				'with_front' => false, /** Don't display the category base before "/galleries/" */
				'hierarchical' => true /** This will allow URL's like "/galleries/videos/youtube*/ 
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
		$taxonomies = array('video_channel', 'video_playlist', 'audio_album');
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

$_wpnukes_videos = new Wpnukes_Videos;
$GLOBALS['_wpnukes_videos'] = $_wpnukes_videos;