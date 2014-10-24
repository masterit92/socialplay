<?php if ( ! defined('ABSPATH')) exit('restricted access');

class WPNukes_Helper
{
	function __construct()
	{
		add_action( 'wp_enqueue_scripts', array($this, 'print_scripts'), 11 );
		$this->_settings = get_option('wpnukes_video_settings');

		add_action('wpnukes_submit_user_video', array($this, 'update_user_vide'));
		add_action('wpnukes_submit_user_playlist', array($this, 'create_term'));
		add_action('wpnukes_submit_user_profile', array( $this, 'update_user_profile') );
		add_action('wpnukes_submit_new_user_video', array( $this, 'add_videos') );
	}
	
	
	function print_scripts()
	{
		if( !is_admin() )
		{
			//wp_enqueue_script('wpnukes_video_custom', plugin_dir_url( __FILE__ ).'/js/functions.js', '', '', true);
			//wp_enqueue_script('wpnukes_video_upload', plugin_dir_url( __FILE__ ).'/js/video_upload.js', '', '', true);
			//wp_enqueue_script('wpnukes_video_front', plugin_dir_url( __FILE__ ).'/js/front.js', '', '', true);
		}
	}
	
	
	/**
	 * returns the list of playlist created by a specific user.
	 */
	function get_terms_array( $taxonomy, $user = false, $res = 'array', $args = array() )
	{

		$return = array();
		$args = wp_parse_args($args, array('hide_empty'=>false));

		foreach( (array)get_terms($taxonomy, $args) as $term )
		{
			if( $user && !$this->verify_user_term( $term ) ) continue;
			
			$return[kvalue($term, 'term_id')] = ( $res == 'array' ) ? kvalue($term, 'name') : $term;
		}

		return $return;
	}
	
	function verify_user_term($term)
	{
		global $current_user;
		get_currentuserinfo();
		
		if( !is_object($term) ) return false;
		
		$key = '_wpnukes_'.kvalue($term, 'taxonomy').'_'.kvalue($term, 'term_id');
		$author = get_option($key.'_author');
		
		if( $current_user->ID != $author && current_user_role() != 'administrator' ) return false;
		else return true;		
	}
	
	
	function create_term($data)
	{
		$t = &$GLOBALS['_webnukes'];
		$_wpnukes_videos = $GLOBALS['_wpnukes_videos'];
		
		$action = (kvalue($data, 'term_id')) ? 'edit' : 'add';
		
		if( !current_user_can('edit_posts') ) {echo 'Restricted'; return;}
		
		$tax = array('video_playlist' => 'add_playlist', 'video_channel' => 'add_channel', 'audio_album' => 'add_album');

		$find = array_intersect( $tax, array_keys((array)$data));
		
		if( !$find ) { echo 'Taxonomy missing'; return;}
		
		if( !kvalue( $data, 'title') ) { echo 'Title missing'; return;}
		
		if( $action == 'edit' )
		{
			$term = wp_update_term( kvalue($data, 'term_id'), current( (array)array_keys($find) ),
								array( 'name'=>kvalue($data,'title'), 'description'=>kvalue($data, 'description') ) );		
		}
		else
		{
			$term = wp_insert_term(
								kvalue($data, 'title'), current( (array)array_keys($find) ),
								array( 'description'=>kvalue($data, 'description') ));
		}
		if ( is_wp_error($term) && is_array( $term->get_error_messages() ) ) 
		{
			$msg = '';
			foreach($term->get_error_messages() as $message)	$msg .= '<p>'.$message.'</p>';
			$this->push_msg( $msg );
		}
		elseif( !is_wp_error($term) )
		{
			//if( $movefile ) $_POST['term_image'] =  $movefile;
			$_POST['taxonomy'] = current( (array)array_keys($find) );
			$_wpnukes_videos->save_term_data( kvalue($term, 'term_id') );
			$this->push_msg('<p>'.sprintf(__(' %s is Succefully Created', THEME_NAME), kvalue($data, 'title' )).'</p>');
			
		}
		wp_redirect(get_permalink());
	}
	
	function upload_file($obj, $resize = false, $size = array(80, 80))
	{
		if( ! is_array( $obj ) ) return false;
		
		$obj['extension'] = pathinfo(kvalue( $obj, 'name' ), PATHINFO_EXTENSION);
		
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		$movefile = wp_handle_upload( $obj, array( 'test_form' => false ) );
		
		if( ! $resize )
		{
			if( $movefile ) return $movefile;
			else return false;
		}
		
		
		$upload_dir = wp_upload_dir();
		$image = wp_get_image_editor( kvalue($movefile, 'file'), $obj );
		
		//$ext = kvalue ( pathinfo(kvalue($obj, 'name')), 'extension');

		if ( ! is_wp_error( $image ) ) {
			$image->resize( $size[0], $size[1], true );
			$filepath = $image->generate_filename($image->get_suffix(), kvalue($upload_dir, 'path'), kvalue( $obj, 'extension'));
			$saved = $image->save( $filepath );
			if( !is_wp_error ($saved) ) return kvalue($upload_dir, 'url').'/'.kvalue($saved, 'file');
			else return false;
		}else return false;
	}
	
	function push_msg( $msg, $type = 'success' )
	{
		$_SESSION['wpnukes_video_messages'][] = array('msg'=>$msg, 'type'=>$type);
	}
	
	function show_msg()
	{	
		if( $msg = kvalue( $_SESSION, 'wpnukes_video_messages' ) ) 
		{
			if( !$_POST && is_array( $msg ) )
			{
				foreach( $msg as $m ){
					echo '<div class="alert alert-'.kvalue( $m, 'type' ).'">'.kvalue( $m, 'msg' ).'</div>';
				}
				unset($_SESSION['wpnukes_video_messages']);
			}
		}
	}
	
	/**
	 * returns all the videos
	 */
	function get_videos($args = '')
	{
		$default = array('post_type' => 'wpnukes_videos', 'post_status' => 'publish');
		$args = wp_parse_args($args, $default);

		$query = new WP_Query($args);

		$meta = array('', '_safety', '_privacy', '_source', '_duration');
		foreach($query->posts as $vid)
		{
			$prefix = ( kvalue($vid, 'post_type') == 'wpnukes_videos') ? '_wpnukes_video' : '_wpnukes_audio';
			foreach($meta as $m)
			{
				$key = $prefix.$m;
				$vid->$key = get_post_meta($vid->ID, $key, true);
			}
		}
		
		return $query;
	}
	
	/**
	 * returns videos created by a specific user
	 */
	function get_user_videos($user_id, $args = array())
	{
		$default = array();
		if( current_user_role() != 'administrator') $default = array('author'=>$user_id);
		$args = wp_parse_args( $args, $default);
		$videos = $this->get_videos( $args );
		return $videos;
	}
	
		
	function get_embed($array = array(), $w = '100%', $h = '100%')
	{
		global $post;
		$t = &$GLOBALS['_webnukes'];
		$array = (!$array) ? $post->_wpnukes_video : $array;

		$type = $t->kvalue($array, 'source');
		$id = $t->kvalue($array, 'id');

		switch($type)
		{
			case "vimeo" :
				return '<iframe src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=f7d64f" width="'.$w.'" height="'.$h.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen>Loading..</iframe>';
			break;

			case "youtube" :
				return '<iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
			break;
			case 'ustream':
				return '<iframe width="'.$w.'" height="'.$h.'" src="http://www.ustream.tv/embed/recorded/'.$id.'?v=3&amp;wmode=direct" scrolling="no" frameborder="0" style="border: 0px none transparent;"></iframe>';
			break;
			case 'dailymotion':
				return '<iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="http://www.dailymotion.com/embed/video/'.$id.'"></iframe>';
			break;
			case 'blip':
				return '<iframe src="http://blip.tv/play/'.$id.'.x?p=1" width="'.$w.'" height="'.$h.'" frameborder="0" allowfullscreen></iframe><embed type="application/x-shockwave-flash" src="http://a.blip.tv/api.swf#'.$id.'" style="display:none"></embed>';
			break;
			case 'metacafe':
				return '<iframe src="http://www.metacafe.com/embed/'.$id.'/" width="'.$w.'" height="'.$h.'" allowFullScreen frameborder=0></iframe>';
			break;
			case 'soundcloud':
				return '<iframe width="'.$w.'" height="'.$h.'" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$id.'"></iframe>';
			break;
			case 'local':
			
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'video-single' );
				$att = get_post( kvalue( $array, 'attachment_id' ), true );
				$meta = wp_get_attachment_metadata( kvalue( $array, 'attachment_id' ), true );
				if( strstr( kvalue( $meta, 'mime_type'), 'audio/' ) ){
					return do_shortcode('[audio '.kvalue( $meta, 'fileformat').'="'.wp_get_attachment_url(kvalue( $array, 'attachment_id' )).'" width="750"][/audio]');
				}else if( strstr( kvalue( $meta, 'mime_type'), 'video/' ) ){
					return '<video class="wp-video-shortcode" width="100%" height="330" poster="'.kvalue($thumb, 0).'" preload="metadata" src="'.wp_get_attachment_url(kvalue( $array, 'attachment_id' )).'">
								<source type="video/'.kvalue( $meta, 'fileformat').'" src="'.wp_get_attachment_url(kvalue( $array, 'attachment_id' )).'">
								<a href="'.wp_get_attachment_url(kvalue( $array, 'attachment_id' )).'">'.wp_get_attachment_url(kvalue( $array, 'attachment_id' )).'</a>
							</video>';
							
					//return do_shortcode('[video '.kvalue( $meta, 'fileformat').'="'.wp_get_attachment_url(kvalue( $array, 'attachment_id' )).'" width="750" height="330" poster="'.kvalue($thumb, 0).'"][/video]');
				}
				return '';
			break;
		}
	}
	
	
	function update_user_vide($data, $uploading = false)
	{
		$t = &$GLOBALS['_webnukes'];
		$_wpnukes_videos = $GLOBALS['_wpnukes_videos'];
		
		/** Remove tags and replace special entities*/		
		foreach($data as $k=>$v)
		{
			$data[$k] = strip_tags($v, '<br><strong>');
			
			if(preg_match('@&([a-z0-9]{3,15}+);@i', $data[$k]))
			{
				$data[$k] = htmlentities($data[$k]);
			}
		}

		$def_status = $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'default_status', 'pending');
		
		$post_type = ( isset($data['webnukes_audio_post_type'] ) ) ? 'wpnukes_audios' : 'wpnukes_videos';

		$prefix = ($post_type == 'wpnukes_videos' ) ? 'webnukes_video_' : 'webnukes_audio_';

		if( !current_user_can('edit_posts') )
		{
			$this->push_msg( '<p>'.__('You are not authorized to Manage videos/audios', THEME_NAME).'</p>', 'success');
			wp_redirect(get_permalink());
			exit;
		}

		if( $_FILES && kvalue(kvalue($_FILES, $prefix.'image_file'), 'name') ) 
		{	
			$movefile = $this->upload_file( kvalue($_FILES, $prefix.'image_file') );
		}
		else
		{
			$attach_id = kvalue( $_POST, $prefix.'attachment_id');
			if( $attach_id && kvalue( $_POST, 'video_canvas_image') )
			{
				$movefile = $this->_save_html5_thumb( kvalue( $_POST, 'video_canvas_image'), kvalue( $data, $prefix.'title') );
			}
		}
		
		$user_data = ( function_exists('wp_get_current_user') ) ? wp_get_current_user() : '';

		if( $post_type == 'wpnukes_videos' )
		{
			$tax_input = array( 'video_tag' => explode( ',', kvalue($data, 'webnukes_video_tags' ) ),
										'video_channel' => kvalue($data, 'webnukes_video_channel'),
										'video_playlist' => kvalue($data, 'webnukes_video_playlist'),
										'video_category' => kvalue($data, 'webnukes_video_category', 1) );
		}
		else
		{
			$tax_input = array( 'audio_tag' => explode( ',', kvalue($data, 'webnukes_audio_tags' ) ),
										'audio_album' => kvalue($data, 'webnukes_audio_album'),
										'audio_category' => kvalue($data, 'webnukes_audio_category', 1) );
		}
		
		$post_status = in_array( 'administrator', kvalue( $user_data, 'roles' ) ) ? 'publish' : $def_status ;

		$post = array(
		  'ID'             => $t->kvalue($data, $prefix.'ID'), //Are you updating an existing post?
		  'comment_status' => 'open', // 'closed' means no comments.
		  'ping_status'    => 'closed', // 'closed' means pingbacks or trackbacks turned off
		  'pinged'         => false,
		  'post_author'    => $t->kvalue((array)$user_data, 'ID' , 1), //The user ID number of the author.

		  'post_content'   => $t->kvalue($data, $prefix.'desc'), //The full text of the post.
		  'post_excerpt'   => character_limiter(strip_tags($t->kvalue($data, $prefix.'desc') ), 100), //For all your post excerpt needs.
		  'post_name'      => str_replace( ' ', '_', strtolower( $t->kvalue($data, $prefix.'title') ) ), // The name (slug) for your post

		  'post_status'    => $post_status, //Set the status of the new post.
		  'post_title'     => $t->kvalue($data, $prefix.'title'), //The title of your post.
		  'post_type'      => $post_type, //You may want to insert a regular post, page, link, a menu item or some custom post type

		  'tax_input'      => $tax_input  // support for custom taxonomies. 
		);  

		$res = wp_insert_post( $post );
		if( !is_wp_error($res) ) 
		{
			if(isset( $movefile ) && $movefile)
			{
				$filename = kvalue($movefile, 'file');
				$url = kvalue($movefile, 'url');
				$wp_filetype = wp_check_filetype(basename($filename), null );
				$opt = array('post_title' => kvalue($data, 'webnukes_video_title'), 'guid' =>$url, 'post_content'=>'', 'post_status'=>'inherit', 'post_mime_type' => kvalue($movefile, 'type'));
				
				$attachment = wp_insert_attachment( $opt, $filename, $res );
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attachment, $filename );
				wp_update_attachment_metadata( $attachment, $attach_data );
				if($attachment) set_post_thumbnail( $res, $attachment );
			}
			
			if( $attach_id ){
				$video_attachment = (array)get_post( $attach_id );
				$video_attachment['post_parent'] = $attach_id;
				wp_update_post( $video_attachment );
			}
			

			$_wpnukes_videos->save_wpnukes_videos($res, $data);
			
			$type = ($post_type == 'wpnukes_audios') ? __('Audio', THEME_NAME) : __('Video', THEME_NAME);
			
			if(!$uploading)
			{
				$this->push_msg( '<p>'.__('The file has been saved successfully.', THEME_NAME).'</p>', 'success');
				wp_redirect(get_permalink());
			}
			
			return true;
		}else if ( is_wp_error($res) && is_array( $res->get_error_messages() ) ) 
		{
			$msg = '';
			foreach($res->get_error_messages() as $message)	$this->push_msg($message, 'error');
			
			return false;
		}
		
		//wp_redirect(get_permalink());
	}
	
	function add_videos($data)
	{
		$type = $post_type = '';
		if( kvalue( $data, 'add_audio' ))
		{
			$type = __('Audio file', THEME_NAME);
			$post_type = 'wpnukes_audios';
		}
		elseif( kvalue( $data, 'add_video'))
		{
			$type = __('Video file', THEME_NAME);
			$post_type = 'wpnukes_videos';
		}
		
		$prefix = ($post_type == 'wpnukes_videos' ) ? 'webnukes_video_' : 'webnukes_audio_';
		$link = ( kvalue( $data, 'link' ) ) ? kvalue( $data, 'link' ) : kvalue( $data, 'embed_code' );
		
		if($embed = kvalue( $data, 'embed_code'))
		{
			preg_match( '#<iframe\s*[^>]*src="([^"]+)"#i', stripslashes($embed), $matches );

			if(isset($matches[1]))
			{
				if( strstr($matches[1], 'soundcloud.com') ) $link = $matches[1];
				else $link = current( (array)explode('?', $matches[1]));
			}
			else $link = '';
		}

		if(strstr( $link, 'soundcloud.com') )
		{
			$post_type = 'wpnukes_audios';
			$prefix = 'webnukes_audio_';
		}
		
		
		if( $link )
		{
			get_template_part('libs/grabber/grab');
			$grab = new FW_Grab( $link );
			$result = $grab->result();

			if( $result && is_array( $result ) )
			{
				$success = false;
				foreach($result as $res)
				{
					if($res['id'] == 'id' || $res['id'] == '') break;
					
					$combined = array();
					foreach( $res as $k => $v )
					{
						$combined[$prefix.$k] = $v;
					}

					$combined[$prefix.'post_type'] = $post_type;

					if($this->update_user_vide($combined, true)) $success = true;
				}

				if($success) $this->push_msg(sprintf( __('Your %s has been submitted successfully and pending for review', THEME_NAME), $type), 'success');
				else $this->push_msg( __('We are unable to process your request, please try again or check the URL you entered.', THEME_NAME), 'error');
			}else
			{
				$this->push_msg( __('The URL you entered is not supported.', THEME_NAME), 'error');
			}

			wp_redirect( get_permalink() );
		}
	}
	
	
	function form_live_search()
	{
		$t = $GLOBALS['_webnukes'];
		$query = $t->kvalue($_POST, 'query');
		$wpquery = $this->get_videos('showposts=10&meta_key=_wpnukes_video_privacy&meta_value=public&s='.$query);
		exit(json_encode($wpquery));
	}
	
	function is_visible($obj = '')
	{
		global $post, $current_user;
		get_currentuserinfo();

		$format = ($post->post_type == 'wpnukes_videos') ? 'video' : 'audio';
		$safety_key = '_wpnukes_'.$format.'_safety';
		$privacy_key = '_wpnukes_'.$format.'_privacy';
		
		
		
		$obj = ( !$obj ) ? $post : $obj;
		
		$safety = $post->$safety_key;
		$privacy = $post->$privacy_key;
		//$array = (!$array) ? $post->_wpnukes_video : $array;

		if( $privacy == 'private' || $privacy == 'unlisted')
		{
			if( !is_user_logged_in() && current_user_role() != 'administrator' ) return false;
			elseif( (is_user_logged_in() && $current_user->ID == $post->post_author) || current_user_role() == 'administrator' ) return true;
			else return false;
		}
		elseif( $privacy == 'public')	return true;
		else return true;
		
	}
	
	function update_user_profile($data)
	{
		global $current_user, $_webnukes;
		get_currentuserinfo();
		
		$_webnukes->load('validation_class');
		
		if( !isset($current_user->ID) ) return;
		$msg = '';
		if( $pass = kvalue($data, 'pass1'))
		{
			if( $pass == kvalue($data, 'pass2')) $data['user_pass'] = $pass;
			else $this->push_msg(__('Both passwords do not match, Retry again', THEME_NAME), 'error');
		}
		
		if( $file = kvalue( $_FILES, 'avatar_file') ) 
		{
			if( kvalue( $file, 'name') )
			{
				$avatar = $this->upload_file($file);
				if( $avatar ) update_user_meta( kvalue($data, 'ID'), 'avatar', kvalue($avatar, 'url'));
				else $this->push_msg( __('Failed to updload profile image', THEME_NAME), 'error' );
			}
		}
		
		include('config/fields.php');
		$fields = $options['user_profile'];
		foreach($fields as $k=>$v)
		{
			if( kvalue($v, 'validation')) $_webnukes->validation->set_rules($k, kvalue($v, 'label'), kvalue($v, 'validation'));
		}

		$_webnukes->validation->run();
		if($_webnukes->validation->run() !== false)
		{
			$update = wp_update_user( $data );
			if( !is_wp_error( $update ) ) $this->push_msg(__('Profile is updated successfully', THEME_NAME));
			else $this->push_msg(__('Failed to update profile', THEME_NAME), 'error' );
		}
		else
		{
			$errors = (!is_array($_webnukes->validation->_error_array)) ? array($_webnukes->validation->_error_array) : $_webnukes->validation->_error_array ;
			/** Prepare errors HTML to show on frontend */
			foreach($errors as $k=>$message) $this->push_msg($message, 'error');
			
		}
		
		wp_redirect( get_permalink() );
	}
	
	function _save_html5_thumb($src, $name) {

		if ( true ) {

			$uploads = wp_upload_dir();

			$raw_png = $src;

			$movieoffset = 0;

			$posterfile = sanitize_file_name($name).'_thumb'.$movieoffset;
			if (!file_exists($uploads['path'].'/thumb_tmp')) { mkdir($uploads['path'].'/thumb_tmp'); }
			$tmp_posterpath = $uploads['path'].'/thumb_tmp/'.$posterfile.'.png';
			$thumb_url = $uploads['url'].'/'.$posterfile.'.jpg';

			$raw_png = str_replace('data:image/png;base64,', '', $raw_png);
			$raw_png = str_replace(' ', '+', $raw_png);
			$decoded_png = base64_decode($raw_png);

			$success = file_put_contents($tmp_posterpath, $decoded_png);

			$editor = wp_get_image_editor( $tmp_posterpath );
			$new_image_info = $editor->save( $uploads['path'].'/thumb_tmp/'.$posterfile.'.jpg', 'image/jpeg' );//pirntr($new_image_info);
			$new_image_info['url'] = $uploads['url'].'/thumb_tmp/'.$posterfile.'.jpg';
			$new_image_info['type'] = kvalue( $new_image_info, 'mime-type' );
			$new_image_info['file'] = kvalue( $new_image_info, 'path' );
			
			return $new_image_info;
		}
	}
	
}


add_filter('user_contactmethods', 'newuserfilter');

function newuserfilter($test)
{
	$array =array('facebook' => 'Facebook', 'twitter'=> 'Twitter');
	$test = array_merge($array, $test);
	return $test;
}

_wp_get_user_contactmethods( );