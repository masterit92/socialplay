<?php

class Ajax_Handler
{
	protected $fields;
	
	function __construct()
	{
		add_action('wp_ajax_users_ajax_callback', array($this, 'users_callback') );
		add_action('wp_ajax_nopriv_guest_ajax_callback', array($this, 'guest_callback') );
		
		add_action('wp_ajax__ajax_callback', array($this, 'guest_callback') );
		add_action('wp_ajax_nopriv__ajax_callback', array($this, 'guest_callback') );
	}
	
	function users_callback()
	{
		include('video/config/fields.php');
		$this->fields = $options;
		
		switch(kvalue($_REQUEST, 'subaction'))
		{
			case 'rating':
				$this->like_dislike();
			break;
			case 'star_rating':
				$this->star_rating();
			break;
			case 'voting':
				$this->like_dislike();
			break;
			case 'user_profile_form':
				$this->generate_form();
			break;
			case 'fetch_video':
			case 'fetch_audio':
				$this->fetch_video();
			break;
			case 'edit_term':
				$this->generate_term_form();
			break;
			case 'comment_report':
				$this->comment_report();
			break;
			case 'del_term':
				$this->del_term();
			break;
			case 'del_video':
				$this->del_video();
			break;
			case 'upload_video':
				$this->upload_video();
			break;
			
			case 'upload_audio':
				$this->upload_video('audio');
			break;
		}
		exit;
	}
	
	function guest_callback()
	{
		
		include('video/config/fields.php');
		$this->fields = $options;
		
		$t = &$GLOBALS['_webnukes'];
		
		switch($t->kvalue($_REQUEST, 'subaction'))
		{
			case 'star_rating':
				$this->star_rating();
			break;
			case 'fb_login':
				$this->fb_login();
			break;
			case 'home_load_more':
				$this->home_load_more();
			break;
		}
		exit;
	}
	
	function del_term()
	{
		
		if( !is_user_logged_in() ) exit(0);
		
		$id = kvalue($_POST, 'data_id');
		$taxonomies = array('video_channel', 'video_playlist', 'audio_album');
		$tax = kvalue( $_POST, 'type');
		
		if( $id && in_array( $tax, $taxonomies) )
		{
			$del = wp_delete_term( $id, $tax );
			if( $del ) exit('success');
			else exit('failed');
		}else exit(0);
	}
	
	function del_video()
	{
		
		if( !is_user_logged_in() ) exit(0);
		
		$id = kvalue($_POST, 'data_id');
		$post_types = array('wpnukes_videos', 'wpnukes_audios');
		$type = kvalue( $_POST, 'type');
		
		if( $id && in_array( $type, $post_types) )
		{
			$del = wp_delete_post( $id );
			if( $del ) exit('success');
			else exit('failed');
		}else exit(0);
	}
	
	function fb_login()
	{
		if( !class_exists('PHPMailer') ) include(ABSPATH.'wp-includes/class-phpmailer.php');
		
		$existing = get_user_by( 'email', kvalue( $_POST, 'email' ) );

		if( isset($existing->ID))
		{
			wp_set_auth_cookie( $existing->ID );
			exit('success');
		}
		
		if( !isset($existing->ID))
		{
			$username = (!username_exists(kvalue($_POST, 'user_name'))) ? 'username' : 'email';
			$password = wp_generate_password( 12, true );
			
			$options = array('user_login'=>$username, 'user_nicename'=>'name', 'user_url'=>'link', 'user_email'=>'email', 'display_name'=>'name','nickname'=>'name',
						'first_name'=>'first_name', 'last_name'=>'last_name', 'description'=>'', 'role' => 'video_contributor');
			
			$data = array();
			foreach( $options as $k => $v)
			{
				if( kvalue($_POST, $v ) ) $data[$k] = kvalue($_POST, $v);
			}

			$user_id = '';
			if( $data ) 
			{
				$data['user_pass'] = $password;
				$user_id = wp_insert_user($data);
			}
			
			if( !is_wp_error($user_id))
			{
				update_user_meta( $user_id, 'user_gender', kvalue($_POST, 'gender') );
				wp_set_auth_cookie( $user_id );
				$message = sprintf( __("Your new user is created, Details are \nusername is: \t %s \npassword is: \t %s", THEME_NAME), kvalue($_POST, $username), $password );

				wp_new_user_notification( $user_id, $password );

				exit('success');
			}
			exit('failed');
		}
		exit('failed');
		//wp_redirect( home_url() );exit;
	}
	
	function comment_report()
	{
		$type = kvalue($_POST, 'type');
		$dataid = kvalue( $_POST, 'data_id');
		
		if( !$dataid ) return;
		
		$meta = (int)get_comment_meta($dataid, 'fw_flag', true);//echo $meta;exit;
		$count = ( $meta ) ? ( $meta + 1 ) : 1;
		
		update_comment_meta($dataid, 'fw_flag', $count, true);echo $count;
	}
	
	function like_dislike()
	{
		$t = &$GLOBALS['_webnukes'];
		$key = $t->kvalue($_POST, 'type');
		$id = $t->kvalue($_POST, 'postid');
		
		$user_data = ( function_exists('wp_get_current_user') ) ? wp_get_current_user() : '';//printr($user_data);
		if( !isset($user_data->ID)) return;
		
		if( !$id ) return;
		
		$meta = (int)get_post_meta( $id, '_fw_'.$key, true );
		
		$rating = ( $meta ) ? ( $meta + 1 ) : 1;
		
		//array_push($users, $user_data->ID);
		
		//update_post_meta( $id, '_video_rating_users', $users );
		update_post_meta( $id, '_fw_'.$key, $rating );
		echo number_format( (int)$rating );
	}
	
	function generate_form()
	{
		$type = kvalue($_REQUEST, 'type');
		
		$type = ($type == 'upload_audio') ? 'upload_video' : $type;
		
		$fields = kvalue($this->fields, $type);
		$settings = ($type == 'user_profile') ? $this->get_user_array() : array();
		$data = array();
		if( kvalue( $_POST, 'type' ) == 'upload_video' || kvalue( $_POST, 'type' ) == 'upload_audio')
		{
			$data = array('heading'=> __('File Uploader', THEME_NAME));
			echo '<div id="progress-data" style="display:none;"><div class="progress"><div class="bar"></div ><div class="percent">0%</div ></div><div id="status"></div></div>';
		}

		echo $this->generate_html($fields, $settings, '', $data);exit;
	}
	
	function generate_term_form()
	{
		$id = kvalue($_POST, 'data_id');
		$type = kvalue($_POST, 'type');
		$fields = kvalue($this->fields, $type);
		$types = array( 'add_channel'=>'video_channel', 'add_playlist'=>'video_playlist', 'add_album'=>'audio_album'); 
		$tax = array_search($type, array_flip($types));
		
		if( !$fields ) return;
		
		$term = get_term( (int)$id, $tax );
		
		if( !$term ) return;
		
		$settings = array();
		if( $term )
		{
			$key = '_wpnukes_'.kvalue($term, 'taxonomy').'_'.kvalue($term, 'term_id');
			$settings['term_id'] = kvalue( $term, 'term_id');
			$settings['title'] = kvalue($term, 'name');
			$settings['description'] = kvalue($term, 'description');
			$settings['image'] = get_option($key.'_image');
			$settings['privacy'] = get_option($key.'_privacy');
			$fields['term_id'] = array( 'label'=>'', 'type' => 'hidden' );
		}

		echo $this->generate_html($fields, $settings);exit;
	}
	
	function get_user_array()
	{
		global $current_user;
		get_currentuserinfo();

		$fields = kvalue($this->fields, kvalue($_POST, 'type'));
		if( !$fields ) return;
		if( !$current_user ) return;
		$meta = array('first_name', 'last_name', 'nickname', 'description', 'aim', 'yim', 'jabber', 'avatar', 'facebook', 'twitter');
		//$keys = array( 'ID' => 'user_id', 'user_login' => 'user_login', 'user_nicename'=>'nickname', 'user_email'=>'email', 'user_url'=>'url', 'display_name'=>'display_name');
		$keys = array_keys($fields);
		$user_data = array();
		foreach($current_user->data as $k => $v )
		{
			if( in_array($k, $keys) ) $user_data[$k] = $current_user->data->$k;
		}
		
		foreach( $meta as $m )
		{
			if( $val = get_user_meta($current_user->ID, $m, true ) ) $user_data[$m] = $val;
		}

		return $user_data;
	}
	
	function generate_html($options, $settings, $prefix = '', $data = array())
	{
		$_webnukes = $GLOBALS['_webnukes'];
		
		$_webnukes->load('html_class', '', true); /** Load WPnukes HTML class */
		$_webnukes->load('wp_html_class','html'); /** Load wordpress HTML class */
		

		//$settings = get_post_meta($post->ID, 'fw_slider_settings', true);
		
		foreach( (array)$options as $k=>$v )
		{
			$value = (kvalue($settings, $k)) ? $settings : array( $k => kvalue($v, 'std') );

			$key = ( $prefix ) ? $prefix.$k : $k;
			if( $prefix ) $value = array( $key => kvalue($value, $k) );
			if( kvalue($v, 'icon') ) $data['icon'] = '<i class="icon-'.kvalue($settings, 'source').'"></i>';

			$data['fields'][$k] = $_webnukes->html->generator($key, $v, $value, 'array');
			
			if(kvalue($v, 'separator')) $data['fields'][$k]['separator'] = true;
		}
		
		$custom = (array)kvalue( current( (array)$options ), 'settings');
		foreach($custom as $k => $v) $data[$k] = $v;
		$data['key'] = kvalue($_REQUEST, 'type');
		
		return $_webnukes->html->layout(get_template_directory().'/libs/video/views/form.php', $data, true);
	}
	
	function fetch_video()
	{
		
		$type = kvalue( $_POST, 'type');
		$fields = kvalue($this->fields, kvalue($_POST, 'subaction'));//printr($type);
		//printr($fields);
		if( !$fields ) return;
		$result = array();
		
		$prefix = ( $type == 'edit_audio' || $type == 'fetch_audio' ) ? 'audio' : 'video';
		if( $type == 'fetch_video' || $type == 'fetch_audio' )
		{
			$link = (kvalue($_POST, 'link')) ? kvalue($_POST, 'link') : kvalue($_POST, 'embed_code');
			get_template_part('grabber/grab');
			$res = new FW_Grab($link);
			$result = current((array)$res->result());
		}
		elseif( $type == 'edit_video' || $type == 'edit_audio' )
		{
			$data_id = kvalue( $_POST, 'data_id');
			
			
			$result = get_post_meta($data_id, '_wpnukes_'.$prefix, true);
			if( kvalue( $result, 'source' ) == 'local' ) $fields['attachment_id'] = array('type'=>'hidden');
			$get_post = get_post( $data_id );
			$result['title'] = kvalue( $get_post, 'post_title' ) ? kvalue( $get_post, 'post_title') : kvalue( $result, 'title');
			$result['desc'] = kvalue( $get_post, 'post_content') ? kvalue( $get_post, 'post_content') : kvalue( $result, 'desc');
			
			if($result) $fields['ID'] = array('label'=>'ID', 'type'=>'hidden', 'std'=>$data_id);
			
			/** Check if post type is audio or video and get terms accordingly */
			if( $type == 'edit_audio' ) $terms = wp_get_post_terms($data_id, array('audio_category', 'audio_tag', 'audio_album'));
			else $terms = wp_get_post_terms($data_id, array('video_category', 'video_tag', 'video_channel', 'video_playlist'));
			
			$tags = '';
			foreach( (array)$terms as $term )
			{
				$tax = kvalue($term, 'taxonomy');
				
				$key = ($tax == $prefix.'_tag') ? 'tags' : str_replace($prefix.'_', '', $tax);
				
				if( $tax == $prefix.'_tag') $result[$key] = $tags .= kvalue($term, 'name').',';
				else $result[$key] = kvalue($term, 'term_id');
			}
			
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($data_id), 'video-large' );
			if( $thumb ) $result['image'] = current( (array) $thumb);
			
			$result['post_type'] = ( $prefix == 'audio' ) ? 'wpnukes_audios' : 'wpnukes_videos';
		}
		
		
		if( $result ) echo $this->generate_html($fields, $result, 'webnukes_'.$prefix.'_');exit;
	}
	
	function star_rating()
	{
		$t = &$GLOBALS['_webnukes'];
		
		$value = (int)$t->kvalue($_POST, 'value');
		$id = $t->kvalue($_POST, 'post_id');
		
		if( !$id ) return;
		
		$meta = (int)get_post_meta( $id, '_video_star_rating', true );
		$number = (int)get_post_meta( $id, '_video_star_rating_number', true );
		
		$rating = ( $meta ) ? ( $meta * $number ) : 1;
		$num = ( $number ) ? ( $number + 1 ) : 1;
		
		$average = ( (int)$rating + $value ) / $num ;
		
		update_post_meta( $id, '_video_star_rating', $average );
		update_post_meta( $id, '_video_star_rating_number', $num );

		echo round( (int)$average, 2 );
	}
	
	function home_load_more()
	{
		global $wp_query;
		$t = $GLOBALS['_wpnukes_videos'];
		$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_video_settings');
		if( $paged = kvalue( $_POST, 'paged' ) )
		{
			$query = fw_prep_video_query();
			$wp_query = $t->helper->get_videos(array_merge(array('paged'=>$paged), $query));
	
			include(get_template_directory().'/libs/home_videos.php');
			wp_reset_query();
		}
	}
	
	function upload_video($uploading = 'video')
	{		
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		$uploadedfile = kvalue($_FILES, 'file_upload_file');
		$file_type = kvalue( $uploadedfile, 'type' );
		
		$post_type = '';
		
		if( strstr( $file_type, 'video' ) ) $post_type = 'wpnukes_videos';
		elseif( strstr( $file_type, 'audio' ) ) $post_type = 'wpnukes_audios';

		if($uploading == 'audio' && $post_type != 'wpnukes_audios') exit( __('Please upload a valid audio file.', THEME_NAME));
		elseif($uploading == 'video' && $post_type == 'wpnukes_audios') exit( __('Please upload a valid video file.', THEME_NAME));
		
		$_REQUEST['type'] = ( $post_type == 'wpnukes_videos' ) ? 'edit_video' : 'edit_audio';

		if( !$post_type ) exit( __('Invalid file format', THEME_NAME));

		$upload_overrides = array( 'test_form' => false );
		$movefile = media_handle_upload( 'file_upload_file', 1 );
		
		if ( $movefile )
		{
			$meta_data = get_post_meta( $movefile, '_wp_attachment_metadata', true );
			$data = array('source'=>'local', 'duration'=>kvalue($meta_data, 'length'), 'attachment_id'=>$movefile, 'title'=>get_the_title($movefile) );
			$fields = kvalue($this->fields, ( $post_type == 'wpnukes_videos' ) ? 'fetch_video' : 'fetch_audio');//printr($fields);
			$att_url = wp_get_attachment_url( $movefile );

			if( !$fields ) return;
			
			$file_format = strstr(kvalue( $meta_data, 'mime_type'), 'video' ) ? 'video/' : 'audio/';
			$fields['attachment_id'] = array('type'=>'hidden');
			
			if( strstr( $file_type, 'video' ) ) $data['post_type'] = 'wpnukes_videos';
			elseif( strstr( $file_type, 'audio' ) ) $data['post_type'] = 'wpnukes_audios';

			if( $post_type == 'wpnukes_videos' )
			{
				echo '<div class="video-capture-container">
						<video id="video" width="100%" height="100%" autoplay controls>
							  <source src="'.$att_url.'" type="'.$file_format.kvalue($meta_data, 'fileformat').'">
							  Your browser does not support the video tag.
						</video>
						<button class="btn-red btn capture-video">'.__('Capture', THEME_NAME).'</button>
						<span class="images">'.__('<p>Click the button "Capture" to capture the image of the video</p>', THEME_NAME).'</span>
					</div>';
			}
			echo $this->generate_html($fields, $data, ($post_type == 'wpnukes_videos' ) ? 'webnukes_video_' : 'webnukes_audio_');exit;
		} else {
			_e( "Possible file upload attack!\n", THEME_NAME );
		}
	}
	
	
}


new Ajax_Handler;