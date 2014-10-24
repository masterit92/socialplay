<?php

if( !isset( $_SESSION ) )
session_start();

get_template_part('libs/video/wpnukes_video');
get_template_part('libs/audio/wpnukes_audio');
get_template_part('libs/metaboxes/metaboxes');
get_template_part( 'includes/thirdparty/recaptcha/recaptcha');

add_filter( 'pre_option_link_manager_enabled', '__return_true' );

function fw_social_networks($settings = array())
{
	$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_social_networking');
	
	$html = '';
	if( $settings )
	{
		foreach( $settings as $k => $v )
		{
			if( kvalue( $v, 'status' ) == 'on' ) 
			$html .= '<li><a target="_blank" href="'.kvalue($v, 'url').'" class="icon-'.$k.' tooltip" title="'.$k.'"></a></li>';
		}
	}
	return $html;
}


function fw_get_duration($string, $type = 'vimeo')
{
	if( $type == 'soundcloud' ) $string = $string / 1000;
	$formt = ( (int)$string > 3600 ) ? 'h:i:s' : 'i:s';
	return date($formt, (int)$string);
}


function get_terms_listing( $terms )
{
	$t = $GLOBALS['_wpnukes_videos'];
	if( !is_array( $terms ) ) return;
	foreach( $terms as $chan ): ?>
		<?php $key = '_wpnukes_'.kvalue($chan, 'taxonomy').'_'.kvalue($chan, 'term_id');?>
		<?php $privacy_key = $key.'_privacy'; ?>
		<?php $privacy = get_option($privacy_key); ?>
		<?php if( $privacy == ''  || $privacy == 'public'): ?>
			<div class="chan-box">
				<div class="channel">
				   
					<?php $types = array( 'channel'=>'video_channel', 'playlist'=>'video_playlist', 'album'=>'audio_album'); ?>
					<?php $type = array_search(kvalue( $chan, 'taxonomy'), $types);//( kvalue( $chan, 'taxonomy' ) == 'video_channel' ) ? 'channel' : 'playlist' ; ?>
					<?php if( $image = get_option($key.'_image')): ?>
						<img width="170" height="130" src="<?php echo $image; ?>" alt="thumb">
					<?php else: ?>
						<img width="170" height="130" src="<?php echo get_template_directory_uri().'/images/term-listing.gif'; ?>" alt="thumb">
					<?php endif; ?>
					<span class="title"><a href="<?php echo get_term_link( $chan );?>"><?php echo kvalue($chan, 'name'); ?></a></span>
					<div class="chan-control" data-id="<?php echo kvalue($chan, 'term_id'); ?>">
						<?php if( is_user_logged_in() && is_page_template( 'profile.php') ): ?>
							<a class="del-chan del_term" data-type="add_<?php echo $type; ?>" href="javascript:void(0);"></a>
							<a class="edit-chan edit_term" data-type="add_<?php echo $type; ?>" href="javascript:void(0);"></a>
						<?php endif; ?>
						<span><?php echo kvalue($chan, 'count');?> <?php echo ($type == 'album') ? __('Audios', THEME_NAME) : __('Videos', THEME_NAME); ?></span>
					</div>
				</div><!-- channel ends -->
			</div>
		<?php endif;?>
    <?php endforeach;
}

/**
 * returns the formatted form of the comments
 *
 * @param	array	$args		an array of arguments to be filtered
 * @param	int		$post_id	if form is called within the loop then post_id is optional
 *
 * @return	string	Return the comment form
 */
function fw_comment_form( $args = array(), $post_id = null )
{
	global $user_identity, $id;
	
	if ( null === $post_id )
		$post_id = $id;
	else
		$id = $post_id;

	$commenter = wp_get_current_commenter();

	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	
	$comment_author = (esc_attr( $commenter['comment_author'] )) ? esc_attr( $commenter['comment_author'] ) : '';
	$comment_email = (esc_attr( $commenter['comment_author_email'] )) ? esc_attr( $commenter['comment_author_email'] ) : '';
	$comment_url= (esc_attr( $commenter['comment_author_url'] )) ? esc_attr( $commenter['comment_author_url'] ) : '';
	
	/** Comment form custom fields */
	$author_val = __('Enter Name', THEME_NAME);
	$email_val = __('Enter Email', THEME_NAME);
	$url_val = __('Enter Website', THEME_NAME);
	$comment_val = __('Enter Comment', THEME_NAME);

	$fields =  array(
		'author' => '<li class="row-fluid"><div class="span6"><input placeholder="'.$author_val.'" type="text" name="author" class="input-block-level name" value="'.$comment_author.'"  /></div>',

		'email' => '<div class="span6"><input placeholder="'.$email_val.'" type="text" name="email" class="input-block-level email" value="'.$comment_email.'" /></div></li>',

		'url' => '<li class="row-fluid"><div class="span12"><input placeholder="'.$url_val.'" type="text" name="url" class="input-block-level website" value="'.$comment_url.'" /></div></li>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s', THEME_NAME), '<span class="required">*</span>' );
	$defaults = array(
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field' => '<li><textarea placeholder="'.$comment_val.'" class="input-block-level" id="comment" name="comment" cols="45" rows="5" aria-required="true"></textarea></li>',
		'must_log_in' => '<p>' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.','heavens_corner' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','heavens_corner' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.','heavens_corner' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => '<p>' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s','heavens_corner' ), '<p><code>' . allowed_tags() . '</code></p>' ) . '</p>',
		'id_form' => 'commentform',
		'id_submit' => 'submit',
		'title_reply' => __( 'Leave a Comment', THEME_NAME ),
		'title_reply_to' => __( 'Leave a Reply to %s', THEME_NAME ),
		'cancel_reply_link' => __( 'Cancel reply', THEME_NAME ),
		'label_submit' => __( 'Post', THEME_NAME ),
	);

	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );?>
		<?php if ( comments_open() ) : ?>
			<?php do_action( 'comment_form_before' ); ?>
			<?php if(kvalue( $_SESSION, 'captcha_error') != '') :?>
				<div class="alert alert-error" style="margin-top:20px;">
						<p><?php echo  'Error! Invalid captcha entered, please try again.'; ?></p>
				</div>
			<?php endif;?>
			<div class="replycomment comment-area" id="respond">
               <div id="errormessages"></div>
				<h4><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h4>

				<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
					<?php echo $args['must_log_in']; ?>
					<?php do_action( 'comment_form_must_log_in_after' ); ?>
				<?php else : ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
						<?php do_action( 'comment_form_top' ); ?>
                       
						<ul class="unstyled">
                        
						 <?php if ( is_user_logged_in() ) : ?>
							<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
							<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
						<?php else : ?>
							<?php echo $args['comment_notes_before']; ?>
							<?php do_action( 'comment_form_before_fields' );?>
                            
							<?php foreach ( (array) $args['fields'] as $name => $field ) 
                                echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";?>
                                    
							<?php do_action( 'comment_form_after_fields' );?>
						<?php endif; ?>
						<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
						
						
                        	
                            <li class="row-fluid">
                            	<div class="span12">
                                <input  name="submit" type="submit" value="<?php echo $args['label_submit'];?>" class="btn btn-red" id="<?php echo esc_attr( $args['id_submit'] ); ?>"  />
							    <?php comment_id_fields( $post_id ); ?>
                                </div>
                            </li>
                        </ul>
						
                        <?php echo $args['comment_notes_after']; ?>
						<?php do_action( 'comment_form', $post_id ); ?>
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php do_action( 'comment_form_after' ); ?>
		<?php else : ?>
			<?php do_action( 'comment_form_comments_closed' ); ?>
		<?php endif; ?>
	<?php
	if(kvalue( $_SESSION, 'captcha_error')) unset($_SESSION['captcha_error']);
}

/**
 * A callback function of comments listing
 * 
 * @param	object	$comment	containg a list of comments
 * @param	array	$args		An array of arguments to merge with defaults.
 * @param	int		$depth		Containing the reply depth level.
 */

function fw_list_comments($comment, $args, $depth)
{

	$GLOBALS['comment'] = $comment; 
	//if(get_comment_type() !== 'sscomment'):?>
            <li>
                <div class="comment-box" id="comment-<?php comment_ID();?>"> 
                <?php echo get_avatar( $comment, $size='48'); /** get avatar */?>
                    <div class="comment-tp"> 
                    	<?php /** check if this comment author not have approved comments befor this */
						if($comment->comment_approved == '0' ) : ?>
							<em><?php /** print message below */
							_e( 'Your comment is awaiting moderation.', THEME_NAME ); ?></em>
							<br />
						<?php endif; ?>
                        <strong><?php echo get_comment_author_link(); ?></strong>
                        <span><?php comment_date( get_option( 'date_format' ) ); _e(' at ', THEME_NAME); comment_time( 'g:i a' ); /** print date time */?></span>
                        <ul class="reply">
                            <li><a href="javascript:void(0);" class="video_comment_flag" data-id="<?php comment_ID(); ?>">Flag</a></li>
                        </ul>
                    </div>
                    <?php comment_text(); /** print our comment text */ ?> 
                    <?php /** check if thread comments are enable then print a reply link */
					comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    <?php if ($depth >= 2) { ?><span class="connect-comment"></span><?php } ?>
                </div>
	<?php
	//endif;
}


function fw_video_row( $query, $prefix = 'video' )
{
	global $post;
	if( !isset($query->posts)) return;
	
	$array = array();
	
	while( $query->have_posts()): $query->the_post();
		
		$video = kvalue( $post, '_wpnukes_'.$prefix );
		
		
		$user_thumb = '';
		if( $avatar = get_user_meta( get_the_author_meta('ID'), 'avatar', true))
        $user_thumb = '<img src="'.$avatar.'" alt="profile" />';

		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'video-large' ); 
		if( ! $thumb && kvalue( $video, 'thumb') ) $thumb[0] = kvalue( $video, 'thumb');
		elseif( !$thumb ) $thumb[0] = get_template_directory_uri().'/images/video-large.gif';

		$source = ( kvalue($video, 'source') == 'local' ) ? 'local-'.$prefix : kvalue($video, 'source');
		
		$pending = ( $post->post_status == 'pending' ) ? '<span><i>'.__('Pending', THEME_NAME).'</i></span>' : '';
		
		$content = '<div class="vid-box large" style="visibility:visible;">
						<div class="video-thumb video-thumb-gray">
							<div class="vid-top"> <img width="335" height="190" class="thumb" src="'.kvalue($thumb, '0').'" alt="'.get_the_title().'" />
								<div class="thumb-info">
									<div class="info"> 
										<span><i class="icon-duration"></i>'.fw_get_duration( kvalue($video, 'duration'), kvalue( $video, 'source' )).'</span> 
										<span><i class="icon-'.$source.'"></i></span> 
										'.$pending.'
									</div>
									<div class="video-controls" data-id="'.get_the_ID().'"> 
										<a href="'.get_permalink().'"><i class="icon-play"></i></a> 
										<a href="javascript:void(0);" id="video_like">
											<i class="icon-like"></i><span>'.(int)get_post_meta(get_the_ID(), '_fw_video_like',true).'</span>
										</a> 
										<a href="javascript:void(0);" id="video_dislike">
											<i class="icon-dislike"></i><span>'.(int)get_post_meta(get_the_ID(), '_fw_video_dislike',true).'</span>
										</a> 
									</div>
									<div class="auth">
										<ul>
											<li class="auth-name">'.get_the_author().'</li>
											<li class="auth-profession">'.get_the_term_list(get_the_ID(), $prefix.'_category').'</li>
										</ul>
										
										'.$user_thumb.' 
									</div>
								</div>
								<div class="thumb-hover"> <span><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().' </a></span> </div>
							</div>
							<div class="vid-bot" data-id="'.get_the_ID().'"> 
								<a href="javascript:void(0);" class="edit_'.$prefix.'">'.__('Edit ', THEME_NAME).ucwords($prefix).'</a> 
								<a href="javascript:void(0);" class="del_'.$prefix.'">'.__('Delete ', THEME_NAME).ucwords($prefix).'</a> 
							</div>
						</div>
					</div>';
		$album = ($prefix == 'audio') ? 'audio_album' : 'video_playlist';
		$terms = wp_get_post_terms( get_the_ID(), $album);
		if( $terms )
		{
			$name = kvalue( current((array) $terms), 'name' );
			$counts = kvalue( current((array) $terms), 'count' );
		}
		$term_key = ( $terms ) ? $name : __('Uncategorized', THEME_NAME);
		$array[$term_key][get_the_ID()] = $content;
	endwhile;
	wp_reset_query();
	return $array;
}

function fw_homepage_msg()
{
	$nukes = $GLOBALS['_webnukes'];
	$settings = $nukes->fw_get_settings('sub_message_box');?>
   <?php if( kvalue($settings, 'msg_status') == 'on'): ?>
    <div class="alert">
        <a class="close" href="#"></a>
        <h4 class="text-red"><?php echo kvalue($settings, 'msg_heading'); ?></h4>
        <p><?php echo kvalue($settings, 'msg_text'); ?></p>
        <?php if( kvalue($settings, 'msg_button') == 'on' && !is_user_logged_in() ): ?>
        	<?php $signup_obj = fw_page_template('signup.php'); ?>
            <?php if($signup_obj ): ?>
        		<a class="btn btn-red" href="<?php echo get_permalink( kvalue( $signup_obj, 'ID') ); ?>"><?php _e('SignUp', THEME_NAME); ?></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php endif; 
}


function fw_news_ticker()
{
	$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_header_settings');?>
    
	<?php if( kvalue( $settings, 'ticker_status') == 'on'): ?>
    	<?php $query = query_posts(array('showposts'=>kvalue($settings, 'ticker_number'), 'category'=>kvalue($settings, 'ticker_category') )); ?>
		<div class="news-strip"> <strong><?php _e('Latest News :', THEME_NAME); ?></strong>
            <?php if( $query ): ?>
            <ul id="ticker">
            	<?php foreach( $query as $q ): ?>
                	<li><a href="<?php echo get_permalink(kvalue($q, 'ID')); ?>"><?php echo kvalue($q, 'post_title'); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <a class="up" href="javascript:void(0);"></a> <a class="down" href="javascript:void(0);"></a> 
            <?php endif; ?>
		</div>
   
   <?php wp_reset_query();
   endif;
   
}


function fw_user_posts_count($author, $post_type = 'post')
{
	global $wpdb;
	$post_count = $wpdb->get_var("SELECT COUNT(ID) FROM ".$wpdb->prefix."posts WHERE post_author = '" . $author . "' AND post_type = '".$post_type."' AND post_status = 'publish'");
	if( $post_count ) return $post_count;
	else return __('Not Found', THEME_NAME);
}

function fw_get_avatar($userid)
{

	$user_thumb = '';
	if( $avatar = get_user_meta( $userid, 'avatar', true)){
	 	$user_thumb = '<img src="'.$avatar.'" alt="profile" />';
	}
	return $user_thumb;
}

function get_video_meta($id, $post_type = 'wpnukes_videos')
{
	global $post;
	$id = !is_int( $id ) ? kvalue($post, 'ID') : $id;
	
	$type = get_video_meta_type( $post_type );
	return get_post_meta( $id, '_wpnukes_'.$type, true);
}

function get_video_meta_type($post_type = 'wpnukes_videos')
{
	$type = ( $post_type == 'wpnukes_videos' ) ? 'video' : 'audio' ;
	return $type;
}

//echo date('h', strtotime('-1 hours'));exit;
function fw_prep_video_query()
{
	global $post_type;
	
	$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_video_settings');
	$showposts = ( kvalue($settings, 'number') ) ? kvalue($settings, 'number') : '';
	if( kvalue( $_GET, 'social_filter_type') && kvalue($_GET, 'social_filter'))
	{
		$_SESSION['social_filter_type'] = kvalue( $_GET, 'social_filter_type');
		$_SESSION['social_filter'] = kvalue( $_GET, 'social_filter');
	}
	
	$key = kvalue($_SESSION, 'social_filter_type');
	$val = kvalue($_SESSION, 'social_filter');
	
	if(kvalue($settings, 'show') == 'both'){
		$query = array( 'post_type' => array('wpnukes_videos', 'wpnukes_audios'));
	}elseif(kvalue($settings, 'show') == 'audios'){
		$query = array( 'post_type' => array('wpnukes_audios'));
	}elseif(kvalue($settings, 'show') == 'videos'){
		$query = array( 'post_type' => array('wpnukes_videos'));
	}
	
	if($showposts ) $query['showposts'] = $showposts;
	
	if( $key && $val )
	{
		switch( $key )
		{
			case 'upload_date':
				$query = array_merge( fw_date_filter_where($val), $query);
			break;
			case 'result_types':
				$type = !in_array( $val, array('soundcloud', 'stereomood')) ? '_wpnukes_video_source' : '_wpnukes_audio_source';
				$query = array_merge(array( 'meta_key' => $type, 'meta_value' => $val ), $query);
			break;
			case 'duration':
				$aud_duration = array('1_600'=>array(1*1000, 600*1000), '600_1800'=>array(600*1000, 1800*1000), '1800_3600'=>array(1800*1000, 3600*1000), '3600_10800'=>array(3600*1000, 10800*1000), '10800_3600000'=>array(10800*1000, 3600000*1000));
				$vid_duration = array('1_600'=>array(1, 600), '600_1800'=>array(600, 1800), '1800_3600'=>array(1800, 3600), '3600_10800'=>array(3600, 10800), '10800_3600000'=>array(10800, 3600000));
				$meta_query = array('meta_query' => array(
								'relation' => 'OR',
								array(
									'key' => '_wpnukes_video_duration',
									'value' => kvalue($vid_duration, $val),
									'type' => 'numeric',
									'compare' => 'BETWEEN'
								),
								array(
									'key' => '_wpnukes_audio_duration',
									'value' => kvalue($aud_duration, $val),
									'type' => 'numeric',
									'compare' => 'BETWEEN'
								)
							));
				$query = array_merge($meta_query, $query);
			break;
			case 'sort_by':
				if( $val == 'type') $query = array_merge(array ( 'meta_key'=>'_wpnukes_video_source', 'orderby' => 'meta_value', 'order' => 'DESC' ), $query);
				if( $val == 'popular') $query = array_merge(array ( 'meta_key'=>'_fw_video_like', 'orderby' => 'meta_value', 'order' => 'DESC' ), $query);
				else {
					if( $val == 'title' ) $query = array_merge(array ( 'orderby' => $val, 'order' => 'ASC' ), $query);
					else $query = array_merge(array ( 'orderby' => $val, 'order' => 'DESC' ), $query);
				}
			break;
			
		}
	}
	elseif(kvalue($settings, 'video_sortby') == 'title')
	{
		$query = array_merge(array ( 'orderby' => 'title', 'order' => kvalue($settings, 'video_order' )), $query);
	}
	elseif(kvalue($settings, 'video_sortby') == 'duration'){
		
		$query=  array_merge(array(
									'meta_key' => '_wpnukes_video_duration',
									'orderby'=>'meta_value_num',
									'order' => kvalue($settings, 'video_order' )
								), $query
		);
				
	}elseif(kvalue($settings, 'video_sortby') == 'most_views'){
		
		$query = array_merge(array(
									'meta_key' => '_webnukes_post_views',
									'orderby' => 'meta_value_num',
									'order'=>'DESC'
								), $query
							);
	}elseif(kvalue($settings, 'video_sortby') == 'most_rated'){
		
		$query = array_merge(array(
									'meta_key' => '_fw_video_like',
									'orderby' => 'meta_value_num',
									'order'=>kvalue($settings, 'video_order' ),
														
								), $query
							);
	}elseif(kvalue($settings, 'video_sortby') == 'source'){
		
		$query=  array_merge(array(
									'meta_key' => '_wpnukes_video_source',
									'orderby'=>'meta_value',
									'order' => kvalue($settings, 'video_order' )
								), $query
							);
	}
	else $query = array_merge(array( 'orderby' => 'ID', 'order' => 'DESC' ), $query);
	
	return $query;
}

function fw_date_filter_where( $val = '' ) {
	
	$today = getdate();
	$query = array();
	switch( $val )
	{
		case 'last_hour':
			$query = array('hour' => date('h', strtotime('-1 hours')));
		break;
		case 'today':
			$query = array('day' => $today['mday']);
		break;
		case 'this_week':
			$query = array('w' => date('W'));
		break;
		case 'this_month':
			$query = array('monthnum' => $today['mon']);
		break;
		case 'this_year':
			$query = array('year' => $today['year']);
		break;
		case 'previous_year':
			$query = array('year' => date('Y', strtotime('-1 years')));
		break;
	}
	return $query;
}


function fw_get_votes($postid, $type = 'like')
{
	return number_format( (int)get_post_meta($postid, '_fw_video_'.$type,true) );
}


/**
 * this function either prints or return the pagination of any archive/listing page.
 *
 * @param	array	$args	Array of arguments
 * @param	bolean	$echo	whether print or return the output.
 *
 * @return	string	Prints or return the pagination output.
 */
function fw_the_pagination($args = array(), $echo = 1)
{
	
	global $wp_query;
	
	$default =  array('base' => str_replace( 99999, '%#%', esc_url( get_pagenum_link( 99999 ) ) ), 'format' => '?paged=%#%', 'current' => max( 1, get_query_var('paged') ),
						'total' => $wp_query->max_num_pages, 'next_text' => __('Next', THEME_NAME), 'prev_text' => __('Previous', THEME_NAME));
						
	$args = wp_parse_args($args, $default);			
	
	$pagination = '<div class="pagination">'.paginate_links($args).'</div>';
	
	if(paginate_links(array_merge(array('type'=>'array'),$args)))
	{
		if($echo) echo $pagination;
		return $pagination;
	}
}


function fw_search_filter($query) {
	$post_type = kvalue($_GET, 'type');
	if (!$post_type) {
		$post_type = 'wpnukes_videos';
	}
    if ($query->is_search) {
        $query->set('post_type', $post_type);
    };
    return $query;
};

add_filter('pre_get_posts','fw_search_filter');



function blog_post_thumb_format()
{
	global $post;
	
	$settings = get_post_meta(kvalue($post, 'ID'), 'wpnukes_post_settings', true);
	$attachments = get_posts(array('post_type'=>'attachment', 'post_parent' => kvalue($post, 'ID')));
	$class = array('class' => 'thumb');
	$size = 'blog-single';

	switch(kvalue($settings, 'format'))
	{
		
		
		case 'image':
			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(kvalue($post, 'ID')), 'large');
			$hover = '<div class="thumb-hover"> <a class="blog-zoom" href="'.$large_image_url[0].'" title="'.get_the_title(kvalue($post, 'ID')).'" data-rel="prettyPhoto[gallery1]"><i class="icon-zoom"></i></a> <a class="blog-link" href="'.get_post_permalink(kvalue($post, 'ID')).'"><i class="icon-link"></i></a></div>';
			if( has_post_thumbnail() ) return '<div class="block-pic">'.get_the_post_thumbnail(kvalue($post, 'ID'), $size, $class).$hover.'</div>';
			elseif( $attachments ) return '<div class="block-pic">'.wp_get_attachment_image( kvalue(current((array)$attachments), 'ID'), $size, $class).$hover.'</div>';
		break;
		case 'slider':
			if( $attachments ) 
			{
				$content = '<div class="block-picSlide">
								<ul class="picSlide">';
				foreach( $attachments as $a )
					$content .= '<li>'.wp_get_attachment_image( kvalue($a, 'ID'), $size, $class).'</li>';
				$content .= '</ul></div>';
				return $content;
			}
		break;
		case 'audio':
			return '<div class="block-audio">'.kvalue($settings, 'audio_embed').'</div>';
		break;
		case 'video':
			$content = '';
			if( has_post_thumbnail() ) $content = get_the_post_thumbnail(kvalue($post, 'ID'), $size, $class);
			elseif( $attachments ) $content = wp_get_attachment_image( kvalue(current((array)$attachments), 'ID'), $size, $class);
			elseif(kvalue($settings, 'video_embed') != '') return '<div class="block-vid">'.$content.kvalue($settings, 'video_embed').'</div>';
		break;
		default:
			if( has_post_thumbnail() ) return '<div class="block-pic">'.get_the_post_thumbnail(kvalue($post, 'ID'), $size, $class).'</div>';
			elseif( $attachments ) return '<div class="block-pic">'.wp_get_attachment_image( kvalue(current((array)$attachments), 'ID'), $size, $class).'</div>';
	}
}


function fw_get_the_categories()
{
	$categories = get_the_category();
	$separator = ' ';
	$output = '';
	if($categories){
		foreach($categories as $category) {
			$output .= '<a class="btn btn-cyan" href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", THEME_NAME ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
		return trim($output, $separator);
	}
}

function fw_author_role_template( $templates='' )
{
	$author = get_queried_object();
	$role=$author->roles[0];
	
	if(!is_array($templates) && !empty($templates)) {
		$templates=locate_template(array("author-$role.php",$templates),false);
	} 
	elseif(empty($templates)) {
		$templates=locate_template("author-$role.php",false);
	}
	else {
		$new_template=locate_template(array("author-$role.php"));
		if(!empty($new_template)) array_unshift($templates,$new_template);
	}
	
	//return $templates;
	return locate_template("author-video_contributor.php",false);
}
add_filter( 'author_template', 'fw_author_role_template' );

function fw_contact_form_submit()
{
	$t = $GLOBALS['_webnukes'];
	$t->load('validation_class');
	$settings = $t->fw_get_settings('sub_contact_page_settings');
	$recaptcha_opt = $t->fw_get_settings('sub_APIs');
	
	/** set validation rules for contact form */
	$t->validation->set_rules('contact_name','<strong>'.__('Name', THEME_NAME).'</strong>', 'required|min_length[4]|max_lenth[30]');
	$t->validation->set_rules('contact_email','<strong>'.__('Email', THEME_NAME).'</strong>', 'required|valid_email');
	$t->validation->set_rules('contact_subject','<strong>'.__('Subject', THEME_NAME).'</strong>', 'required|min_length[5]');
	$t->validation->set_rules('contact_message','<strong>'.__('Message', THEME_NAME).'</strong>', 'required|min_length[5]');
	
	if( kvalue($settings, 'captcha_status') == 'on')
	{
		$challenge = $_POST['recaptcha_challenge_field'];
		$response = $_POST['recaptcha_response_field'];
		$recaptcha_response = recaptcha_check_answer ($recaptcha_opt['recaptcha_p_key'], $_SERVER['REMOTE_ADDR'], $challenge, $response);
		if( !$recaptcha_response->is_valid )
		{
			$t->validation->_error_array['captcha'] = __('Invalid captcha entered, please try again.', THEME_NAME);
		}
	}
	
	$messages = '';
	
	if($t->validation->run() !== FALSE && empty($t->validation->_error_array))
	{
		$name = $t->validation->post('contact_name');
		$email = $t->validation->post('contact_email');
		$subject = $t->validation->post('contact_subject');
		$message = $t->validation->post('contact_message');
		$contact_to = ( kvalue($settings, 'contact_email') ) ? kvalue($settings, 'contact_email') : get_option('admin_email');
		
		$headers = 'From: '.$name.' <'.$email.'>' . "\r\n";
		wp_mail($contact_to, $subject, $message, $headers);
		
		$message = kvalue($settings, 'success_msg') ? $settings['success_msg'] : sprintf( __('Thank you <strong>%s</strong> for using our contact form! Your email was successfully sent and we will be in touch with you soon.',THEME_NAME), $name);

		$messages = '<div class="alert alert-success">
						<p class="title">'.__('SUCCESS! ', THEME_NAME).$message.'</p>
					</div>';
							
		if( kvalue($settings, 'redirect_url')){
			wp_redirect(kvalue($settings, 'redirect_url'));exit;
		}
	}else
	{
		 if( is_array( $t->validation->_error_array ) )
		 {
			 foreach( $t->validation->_error_array as $msg )
			 {
				 $messages .= '<div class="alert alert-error">
									<p>'.__('Error! ', THEME_NAME).$msg.'</p>
								</div>';
			 }
		 }
	}
	
	return $messages;
}


function fw_slider($args = array())
{
	$default = array('post_type'=>'fw_slider', 'showposts'=> 5 );
	$args = wp_parse_args($args, $default);?>
    <div class="main-slider">
		<?php include('libs/slider/slider.php');?>
    </div>
    <?php 
}



function fw_create_default_pages()
{
	$array = array( 'tpl-channels.php' => __('Channels', THEME_NAME), 'contact.php' => __('Contact Us', THEME_NAME), 'forgot.php' => __('Forgot Password', THEME_NAME), 
			'front-page-tpl.php' => __('Homepage', THEME_NAME), 'login.php' => __('Login', THEME_NAME), 'profile.php' => __('Profile', THEME_NAME), 'signup.php' => __('Signup', THEME_NAME),
			'tpl-albums.php' => __('Albums', THEME_NAME), 'tpl-playlists.php' => __('Playlists', THEME_NAME) );

	foreach( $array as $k => $v )
	{
		if( ! fw_page_template( $k ) )
		{
			$id = wp_insert_post( array('post_title' => $v, 'post_type' => 'page', 'post_status' => 'publish', 'ping_status'=>'closed', 'comment_status' => 'closed' ) );
	
			if( !is_wp_error($id) )	update_post_meta( $id, '_wp_page_template', $k );

		}

	}
	
}
function fw_custom_style()
{
	global $_webnukes;
	$genral_setting_background =  $GLOBALS['_webnukes']->fw_get_settings('sub_background');

	$general_setttings =  $GLOBALS['_webnukes']->fw_get_settings('sub_color_and_style');
	$custom_style = '';
	if($_webnukes->kvalue($general_setttings, 'heading_color') != ''){
		$custom_style .= 'h1,h2,h3,h4,h5,h6{
			color:'.$_webnukes->kvalue($general_setttings, 'heading_color').' !important;
		}';
	}
	if($_webnukes->kvalue($general_setttings, 'text_color') != ''){
		$custom_style .= 'p{color:'.$_webnukes->kvalue($general_setttings, 'text_color').' !important;
		}';
	}
	if($_webnukes->kvalue($genral_setting_background, 'type') == 'image'){
		$custom_style .='body{';
		$custom_style .= 'background-image:url('.$_webnukes->kvalue($genral_setting_background, 'bg_image').') !important;';
		$custom_style .= 'background-repeat:'.$_webnukes->kvalue($genral_setting_background, 'repeat').' !important;';
		$custom_style .= 'background-position:'.$_webnukes->kvalue($genral_setting_background, 'position').' !important;';
		$custom_style .= 'background-attachment:'.$_webnukes->kvalue($genral_setting_background, 'attachment').'  !important;';
		$custom_style .= 'background-color:'.$_webnukes->kvalue($genral_setting_background, 'color').' !important;';
		$custom_style .= '}';
	}
	if($_webnukes->kvalue($genral_setting_background, 'type') == 'pattern'){
		$custom_style .='body{';
		$custom_style .= 'background-image:url('.get_template_directory_uri().'/images/'.$_webnukes->kvalue($genral_setting_background, 'patterns').'.png);';
		$custom_style .= 'background-color:'.$_webnukes->kvalue($genral_setting_background, 'color').';';
		$custom_style .= '}';
	}
	return $custom_style;
}


function fw_apply_color_scheme( $cookie = false )
{
	$styles = $GLOBALS['_webnukes']->fw_get_settings('sub_color_and_style', 'style', '#c50b0b');
	$_COOKIE['fw_color_scheme'] = isset( $_COOKIE['fw_color_scheme'] ) ? $_COOKIE['fw_color_scheme'] : $styles;

	$custom_style = ( $cookie && isset( $_COOKIE['fw_color_scheme'] ) ) ? $_COOKIE['fw_color_scheme'] : $styles;
	
	$content = @file_get_contents(get_template_directory_uri().'/css/color.css');
	
	if( $custom_style ){
		
		
		$replace = str_replace('#c50b0b', $custom_style, $content );
		
		$replace = ( $custom_style ) ? $replace : $content ;
	}else $replace = $content;
	
	echo "\n".'<style title="fw_color_scheme">'.$replace.'</style>';
}

add_filter('pre_get_posts', 'fw_cached_results');
function fw_cached_results($query)
{
	return $query->set('cache_results', true);
}

// Replaces the excerpt "more" text by a link
function new_excerpt_more($more) {
       global $post;
	return '<a class="moretag" href="'. get_permalink($post->ID) . '"> '.__('Read more..', THEME_NAME).'</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');


function fw_admin_access()
{
	$admin_access = $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'admin_access');
	$current_user = wp_get_current_user();

	if( $admin_access == 'on' && is_admin() && kvalue( $_REQUEST, 'action') != 'users_ajax_callback' && kvalue( $_REQUEST, 'action' ) != '_ajax_callback' )
	{
		if( !in_array( 'administrator', kvalue( $current_user, 'roles' ) ) ){
			$page = fw_page_template( 'login.php' );
			if( $page ) wp_redirect( get_permalink( kvalue( $page, 'ID' ) ) );
			else wp_redirect( home_url() );
			exit;
		}
	}
}

function fw_get_post_views( $post_id = null )
{
	global $post;
	
	$post_id = is_int( $post_id ) ? $post_id : kvalue( $post, 'ID' );
	
	$meta = get_post_meta( $post_id, '_webnukes_post_views', true);
	$views = ( $meta ) ? $meta + 1 : 1;
	
	update_post_meta( $post_id, '_webnukes_post_views', $views );
	
	return number_format($views);
}

function is_visible_term($key)
{
	global $post, $current_user;
	get_currentuserinfo();

	$privacy = get_option($key); 
	
	if( $privacy == 'private' || $privacy == 'unlisted')
	{
		if( !is_user_logged_in() && current_user_role() != 'administrator' ) return false;
		elseif( (is_user_logged_in() && $current_user->ID == $post->post_author) || current_user_role() == 'administrator' ) return true;
		else return false;
	}
	elseif( $privacy == 'public')	return true;
	else return true;
}


