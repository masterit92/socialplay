<?php if ( ! defined('ABSPATH')) exit('restricted access'); 

// Class FW_Tweets to fetch latest tweets from twitter ID
class Twitter extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'tweets', /* Name */'Twitter', array( 'description' => 'Twitter tweets' ) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		
		extract( $args );
		$title = @apply_filters( 'widget_title', $instance['twitter_title'] );
		echo $before_widget;
		if($title) echo $before_title.$title.$after_title;
		$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');
		if(kvalue($settings, 'twitter_key') != '' && kvalue($settings, 'twitter_secret') != '' ):
			
			include_once('codebird.php');
			
			Codebird::setConsumerKey(kvalue($settings, 'twitter_key'), kvalue($settings, 'twitter_secret'));
			$cb = Codebird::getInstance();
			$cb->setToken(kvalue($settings, 'twitter_token'), kvalue($settings, 'twitter_t_secret'));
			
			$params = array(
			 'screen_name' => kvalue($instance, 'twitter_id'),
			 'count' => kvalue($instance, 'tweets_num'),
			 'exclude_replies'=>0,
			 'include_rts'=>0,
			 'include_entities'=>0,
			 'trim_user'=>false,
			 'contributor_details'=>false
			);
			$reply = $cb->statuses_userTimeline($params);
			if( $reply ):
				unset($reply->httpstatus);
				foreach((array)$reply as $rep):?>
					
                    <div class="tweets">
                        <blockquote>
                          <p><?php echo $rep->text;?></p>
                          <em><?php echo $rep->user->screen_name;?> ( <?php echo  date(get_option('date_format'), strtotime($rep->created_at)); ?> )</em>
                        </blockquote>
                        <div class="follow-tweet">
                          <i class="icon-tweet"></i>
                          <a href="<?php echo 'http://www.twitter.com/'.$rep->user->screen_name; ?>"><?php echo (kvalue($instance, 'follow_label')) ? kvalue( $instance, 'follow_label') : __('Follow us on Twitter', THEME_NAME);?></a>
                        </div>
					</div>
                    
				<?php endforeach?>	
            <?php endif; ?>	
		<?php endif;?>		
		<?php	echo $after_widget;
	}
	
	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['twitter_title'] = strip_tags($new_instance['twitter_title']);
		$instance['twitter_id'] = strip_tags($new_instance['twitter_id']);
		$instance['tweets_num'] = strip_tags($new_instance['tweets_num']);
		$instance['follow_label'] = strip_tags($new_instance['follow_label']);

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$twitter_title = esc_attr( $instance[ 'twitter_title' ] );
			$twitter_id = esc_attr($instance['twitter_id']);
			$tweets_num = esc_attr($instance['tweets_num']);
			$follow_label = esc_attr($instance['follow_label']);

		}
		else
		{
			$twitter_title = _e( 'Twitter', THEME_NAME );
			$twitter_id = 'wordpress';
			$tweets_num = 1;

			$follow_label = '';
		}

	?>    
            <label for="<?php echo $this->get_field_id('twitter_title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_title'); ?>" name="<?php echo $this->get_field_name('twitter_title'); ?>" type="text" value="<?php echo $twitter_title; ?>" />
            <label for="<?php echo $this->get_field_id('twitter_id'); ?>"><?php _e('Twitter ID:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_id'); ?>" name="<?php echo $this->get_field_name('twitter_id'); ?>" type="text" value="<?php echo $twitter_id; ?>" />

            <label for="<?php echo $this->get_field_id('tweets_num'); ?>"><?php _e('Number of Tweets:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('tweets_num'); ?>" name="<?php echo $this->get_field_name('tweets_num'); ?>" type="text" value="<?php echo $tweets_num; ?>" />
            <label for="<?php echo $this->get_field_id('follow_label'); ?>"><?php _e('Follow us Label:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('follow_label'); ?>" name="<?php echo $this->get_field_name('follow_label'); ?>" type="text" value="<?php echo $follow_label; ?>" />
		</p>
	<?php 
	}
}

//Class Recent_posts with images
class Recent_Posts extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'tbrecent_posts', /* Name */'Social Play Recent Posts', array( 'description' => 'Recent posts with images' ) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
?>
		<div class="posts-list">
          	<ul>
                	<?php
					query_posts('posts_per_page='.$instance['post_limit']);
					while(have_posts()): the_post();?>
                    	<li>
              				<a href="<?php the_permalink(); ?>">
									<?php if(has_post_thumbnail()):	the_post_thumbnail('widget-post');
									else:?><img width="55" height="55" src="<?php echo get_template_directory_uri();?>/images/widget-post.gif" align="noimage" title="noimage" />
                                    <?php endif; ?>
                            </a>
                			<div class="post-text">
                                <h5> <a href="<?php the_permalink(); ?>"><?php echo substr(get_the_title(), 0, 25) . '...'; //the_title(); ?></a></h5>
                                <p><?php $content = substr(strip_tags(apply_filters('the_content', get_the_content())), 0, 60); 
										echo $content.'...';?></p>
                			</div>
              			</li>
                    <?php endwhile; ?>
                </ul>
          </div>
		<?php wp_reset_query();
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['post_limit'] = strip_tags($new_instance['post_limit']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$post_title = esc_attr($instance['title']);
			$limit =  esc_attr($instance['post_limit']);
		}
		else
		{
			$post_title = __( 'Recent Posts', THEME_NAME );
			$limit = 5;
		}
	?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $post_title; ?>" />
            
			<label for="widget-recent-posts-3-number"><?php _e('Number of posts to show:', THEME_NAME);?></label>
			<input type="text" size="3" value="<?php echo $limit; ?>" name="<?php echo $this->get_field_name('post_limit'); ?>" id="<?php echo $this->get_field_id('post_limit'); ?>">
		</p>
	<?php 
	}
}

//Class Recent_posts with images
class Top_videos extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'widget-top-vids', /* Name */'Social Play Top Videos', array( 'description' => 'Socail play top videos with most number of likes' ) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		global $post;
		$t  = $GLOBALS['_wpnukes_videos'];
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
		$videos = $t->helper->get_videos(array('post_status' => 'publish', 'showposts'=>kvalue($instance, 'video_limit'),'meta_key'=>'_fw_video_like','compare'=>'meta_value','order'=>'desc','orderby'=>'meta_value'));
			
?>
					<div class="widget-top-vids">
                     <?php $i =1;?>
                	<?php while($videos->have_posts()): $videos->the_post(); ?>
                    	<?php //printr($post);?>
                    	<?php if(!$t->helper->is_visible()) continue;?>
                    	<div class="vid-box">
                          <div class="video-thumb">
                          	<?php $img_size = ( $i == 1 ) ? array('width'=>369, 'height'=>212) : array('width'=>175, 'height'=>99);
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'video-large' );
							if( ! $thumb && kvalue( $post->_wpnukes_video, 'thumb') ) $thumb[0] = kvalue( $post->_wpnukes_video, 'thumb');
							elseif( !$thumb ) $thumb[0] = get_template_directory_uri().'/images/video-large.gif';?>
							<img <?php echo http_build_query($img_size, '', ' ');?> class="thumb" src="<?php echo current( (array) $thumb);?>" alt="<?php the_title(); ?>">                          
                            <div class="thumb-info">
                              <div class="info">
                                <span><i class="icon-hd"></i></span>
                                <span><i class="icon-duration"></i><?php echo fw_get_duration(kvalue($post->_wpnukes_video, 'duration')); ?></span>
                                <span><i class="icon-<?php echo kvalue($post->_wpnukes_video, 'source'); ?>"></i></span>
                              </div>
                              <div class="video-controls" data-id="<?php the_ID();?>">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><i class="icon-play"></i></a>
                                <a href="javascript:void(0);" id="video_like"><i class="icon-like"></i><span><?php echo (int)get_post_meta($post->ID, '_fw_video_like', true);?></span></a>
                                <a href="javascript:void(0);" id="video_dislike"><i class="icon-dislike"></i><span><?php echo (int)get_post_meta($post->ID, '_fw_video_dislike', true);?></span></a>
                              </div>
                              <span class="vid-title">
							  	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php echo substr(get_the_title(), 0, 30);?>..
                                </a>
                              </span>
                            </div>
                          </div><!-- video-thumb ends -->
        				</div>
                       	<?php $i++;?>
                    <?php endwhile; ?>
                    <?php wp_reset_query(); ?>
                    </div>
              
	<?php	echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['video_limit'] = strip_tags($new_instance['video_limit']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$post_title = esc_attr($instance['title']);
			$limit =  esc_attr($instance['video_limit']);
		}
		else
		{
			$post_title = __( 'Top Videos', THEME_NAME );
			$limit = 5;
		}
	?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $post_title; ?>" />
            
			<label for="widget-recent-posts-3-number"><?php _e('Number of videos to show:', THEME_NAME);?></label>
			<input type="text" size="3" value="<?php echo $limit; ?>" name="<?php echo $this->get_field_name('video_limit'); ?>" id="<?php echo $this->get_field_id('video_limit'); ?>">
		</p>
	<?php 
	}
}


//Class Recent_Videos with images
class Recent_videos extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'widget-recent-vids', /* Name */'Recent Videos', array( 'description' => 'Socail play recent videos' ) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		global $post;
		$t  = $GLOBALS['_wpnukes_videos'];
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
		$videos = $t->helper->get_videos(array('post_status' => 'publish', 'showposts'=>kvalue($instance, 'video_limit'),'order'=>'desc','orderby'=>'date'));
			
?>
					 <div class="vid-row">
					
                	<?php while($videos->have_posts()): $videos->the_post(); ?>
                    	<?php //printr($post);?>
                    	<?php if(!$t->helper->is_visible()) continue;?>
                        <?php 
							//$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'video-large' );
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'video-large' );
							if( ! $thumb && kvalue( $post->_wpnukes_video, 'thumb') ) $thumb[0] = kvalue( $post->_wpnukes_video, 'thumb');
							elseif( !$thumb ) $thumb[0] = get_template_directory_uri().'/images/video-large.gif'
						?>
                        <div class="vid-box">
                            <a href="<?php the_permalink(); ?>">
                              <div class="video-thumb">
                              	<?php  if($thumb): ?>
                                	<img class="thumb" src="<?php echo kvalue($thumb, '0');?>" />
                                <?php endif;?>
                                <div class="thumb-info">
                                  <div class="info">
                                    <span><i class="icon-duration"></i><?php echo fw_get_duration(kvalue($post->_wpnukes_video, 'duration')); ?></span>
                                  </div>
                                </div>
                              </div><!-- video-thumb ends -->
                            </a>
                            <div class="vid-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo substr(get_the_title(), 0, 30);?>..</div>
         				</div>
 
                    <?php endwhile; ?>
                    <?php wp_reset_query(); ?>
                    
              </div>
	<?php	echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['video_limit'] = strip_tags($new_instance['video_limit']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$post_title = esc_attr($instance['title']);
			$limit =  esc_attr($instance['video_limit']);
		}
		else
		{
			$post_title = __( 'Recent Videos', THEME_NAME );
			$limit = 5;
		}
	?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $post_title; ?>" />
            
			<label for="widget-recent-posts-3-number"><?php _e('Number of videos to show:', THEME_NAME);?></label>
			<input type="text" size="3" value="<?php echo $limit; ?>" name="<?php echo $this->get_field_name('video_limit'); ?>" id="<?php echo $this->get_field_id('video_limit'); ?>">
		</p>
	<?php 
	}
}

//Class Recent_Videos with images
class Contact_us extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'widget-contact', /* Name */'Contact Us', array( 'description' => 'Display contact detail' ) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;?>
        
         <ul class="contact-matter">
            <li class="addrs"><?php echo kvalue($instance, 'address');?></li>
            <li class="contact"><?php echo kvalue($instance, 'phone');?>  /  <?php echo kvalue($instance, 'fax');?></li>
            <li class="cell"><?php echo kvalue($instance, 'mobile');?></li>
            <li class="email"><a href="mailto:<?php echo kvalue($instance, 'email');?>"><?php echo kvalue($instance, 'email');?></a></li>
        </ul>
		<?php	echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['phone'] = strip_tags($new_instance['phone']);
		$instance['fax'] = strip_tags($new_instance['fax']);
		$instance['mobile'] = strip_tags($new_instance['mobile']);
		$instance['email'] = strip_tags($new_instance['email']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$post_title = esc_attr($instance['title']);
			$address =  esc_attr($instance['address']);
			$phone = esc_attr($instance['phone']);
			$fax =  esc_attr($instance['fax']);
			$mobile = esc_attr($instance['mobile']);
			$email =  esc_attr($instance['email']);
		}
		else
		{
			$post_title = __( 'Contact Us', THEME_NAME );
			$address = '';
			$phone = '';
			$fax = '';
			$mobile = '';
			$email = '';
			
		}
	?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $post_title; ?>" />
        </p>
        <p>    
			<label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address:', THEME_NAME);?></label><br />
			<textarea rows="3" cols="34" name="<?php echo $this->get_field_name('address'); ?>" id="<?php echo $this->get_field_id('address'); ?>"><?php echo $address; ?></textarea>
        </p>
        <p>    
            <label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone Number:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo $phone; ?>" />
        </p>
        <p>    
            <label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo $fax; ?>" />
        </p>
        <p>    
            <label for="<?php echo $this->get_field_id('mobile'); ?>"><?php _e('Mobile:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('Mobile'); ?>" name="<?php echo $this->get_field_name('mobile'); ?>" type="text" value="<?php echo $mobile; ?>" />
        </p>
        <p>    
            <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" />
		</p>
	<?php 
	}
}

class Top_playlist extends WP_Widget{
	
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'top-videos', /* Name */'Top Playlists', array( 'description' => 'Socail play top play lists' ) );
	}
	
	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		global $post;
	
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
		//$videos = $t->helper->get_videos(array('post_status' => 'publish', 'showposts'=>kvalue($instance, 'video_limit'),'taxonomy'=>'video_playlist','compare'=>'meta_value','order'=>'desc','orderby'=>'meta_value'));
		$terms = get_terms(array('video_playlist'), array('orderby' => 'count', 'order'=>'desc','number'=>kvalue($instance, 'video_limit')));?>					<?php foreach($terms as $term): ?>
					<?php $thumb = '_wpnukes_video_playlist_'.$term->term_id.'_image';?>
                        <div class="tp-video">
                            <a href="<?php echo get_term_link( $term ); ?>" title="<?php echo kvalue( $term, 'name'); ?>">
                            <?php $thumb = get_option($thumb);?>
                            <?php if($thumb):?>
                            	<img src="<?php echo $thumb; ?>" />
                            <?php else:?>
                            	<img src="<?php echo get_template_directory_uri(); ?>/images/source/empty-thumb.jpg" alt="Empty">
                            <?php endif;?>
                                        <span class="vid-title"><?php echo substr($term->name, 0, 30);?></span>
                            <span class="vid-count"><?php echo $term->count;?></span>
                          </a>
                        </div><!-- tp-video ends -->
                    <?php endforeach;?>
                    
              
	<?php	echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['video_limit'] = strip_tags($new_instance['video_limit']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$post_title = esc_attr($instance['title']);
			$limit =  esc_attr($instance['video_limit']);
		}
		else
		{
			$post_title = __( 'Top Playlists', THEME_NAME );
			$limit = 5;
		}
	?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $post_title; ?>" />
            
			<label for="widget-recent-posts-3-number"><?php _e('Number of lists to show:', THEME_NAME);?></label>
			<input type="text" size="3" value="<?php echo $limit; ?>" name="<?php echo $this->get_field_name('video_limit'); ?>" id="<?php echo $this->get_field_id('video_limit'); ?>">
		</p>
	<?php 
	}
}

class Social_Networks extends WP_Widget{
	
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'widget-social', /* Name */'Social Networks', array( 'description' => 'Socail Network Widget' ) );
	}
	
	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		global $post;
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;

		$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');
		include_once('codebird.php');
		
		Codebird::setConsumerKey(kvalue($settings, 'twitter_key'), kvalue($settings, 'twitter_secret'));
		$cb = Codebird::getInstance();
		$cb->setToken(kvalue($settings, 'twitter_token'), kvalue($settings, 'twitter_t_secret'));
		
		$params = array(
		 'screen_name' =>  kvalue($instance, 'twitter_url')
		);
		$reply = $cb->users_show($params);?>
            <div class="widget-social">
                <ul>
                    <?php if( $facebook = kvalue($instance, 'facebook_url')):?>
                        <li class="facebook">
                        <div>
                            <i class="icon-facebook"></i>
                          <a href="http://www.facebook.com/<?php echo $facebook; ?>"><strong><?php echo $this->get_likes($facebook);?></strong></a>
                          <span><?php _e('Facebok Followers', THEME_NAME); ?></span>
                        </div>
                        </li>
                    <?php endif;?>
                    <?php if( $twitter = kvalue($instance, 'twitter_url')):?>
                        <li class="twitter">
                        <div>
                            <i class="icon-tweeter"></i>
                          <a href="http://www.twitter.com/<?php echo $twitter; ?>"><strong><?php echo number_format((int)$reply->followers_count);?></strong></a>
                          <span><?php _e('Twitter Followers', THEME_NAME); ?></span>
                        </div>
                        </li>
                    <?php endif;?>
                    <?php if( $google_plus = kvalue($instance, 'gplus_url')):?>
                        <li class="gplus">
                        <div>
                            <i class="icon-gplus"></i>
                            <?php
                            if( !strstr( $google_plus, 'http://' ) && !strstr( $google_plus, 'https://' )){
                                $google_plus = 'https://plus.google.com/'.$google_plus;
                            }?>
                          <a href="<?php echo $google_plus; ?>"><strong><?php echo $this->get_plusones($google_plus);?></strong></a>
                          <span><?php _e('Google+ Followers', THEME_NAME); ?></span>
                        </div>
                        </li>
                    <?php endif;?>
            </ul>
        </div>
		
<?php	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['facebook_url'] = strip_tags($new_instance['facebook_url']);
		$instance['twitter_url'] = strip_tags($new_instance['twitter_url']);
		$instance['gplus_url'] = strip_tags($new_instance['gplus_url']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance )
	{
		if ( $instance )
		{
			$post_title = esc_attr($instance['title']);
			$facebook_url =  esc_attr($instance['facebook_url']);
			$twitter_url =  esc_attr($instance['twitter_url']);
			$gplus_url =  esc_attr($instance['gplus_url']);
		}
		else
		{
			$post_title = __( 'Social Networks', THEME_NAME );
			$facebook_url = '';
			$twitter_url = '';
			$gplus_url = '';
		}
	?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEME_NAME); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $post_title; ?>" />
        </p>
        <p>
        	<label for="facebook"><?php _e('Facebook ID:', THEME_NAME);?></label><br />
			<input class="widefat" type="text" value="<?php echo $facebook_url; ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" id="<?php echo $this->get_field_id('facebook_url'); ?>">
        </p>
        <p>
        	<label for="twitter"><?php _e('Twitter ID:', THEME_NAME);?></label><br />
			<input class="widefat" type="text" value="<?php echo $twitter_url; ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" id="<?php echo $this->get_field_id('twitter_url'); ?>">
        </p>
        <p>
        	<label for="twitter"><?php _e('Gplus URL:', THEME_NAME);?></label><br />
			<input class="widefat" type="text" value="<?php echo $gplus_url; ?>" name="<?php echo $this->get_field_name('gplus_url'); ?>" id="<?php echo $this->get_field_id('gplus_url'); ?>">
        </p>
	<?php 
	}
	
	function get_likes($url) {
	
		$page_id = $url;
		
		if( strstr( $url, 'http://' )){
			$parse = parse_url($url);
			$url = str_replace('/', '', kvalue($parse, 'path'));
		}
		$content = @file_get_contents('http://graph.facebook.com/wordpress');
		if( $content ) return number_format((int)kvalue( json_decode($content), 'likes', 0));	 
	}
	 
	function get_plusones($url) {
		
		
		//$doc = new DOMDocument();
		//@$doc->loadHTMLFile('https://apis.google.com/_/+1/fastbutton?url='.urlencode($url)); 

		if( strstr( $url, 'http://' ) || strstr( $url, 'https://' )){
			preg_match('/[0-9]+/', $url, $match);
			if( kvalue( $match, 0 ) ) $url = $match[0];
		}
		
		if( $url  )
		{
			if( !function_exists('curl_init') ){
				$content = @file_get_contents('https://www.googleapis.com/plus/v1/people/'.$url.'?key=AIzaSyAtF_e9L7pavcb_dAwu_2Q7SmW0owktCv8');//echo $data;exit;
			}else{
				$ch = curl_init('https://www.googleapis.com/plus/v1/people/'.$url.'?key=AIzaSyAtF_e9L7pavcb_dAwu_2Q7SmW0owktCv8');
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686; rv:20.0) Gecko/20121230 Firefox/20.0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				$content = curl_exec($ch);
				curl_close($ch);
			}

			$likes = 0;
			if( $content )
			{
				$likes = kvalue( json_decode( $content) , 'plusOneCount' );
			}
			return number_format((int)$likes);
		}
	}
}

//register Recent_Posts class
add_action( 'widgets_init', create_function( '', 'register_widget("Recent_Posts");' ) );

//Register Blog + Twitter tab widget
add_action('widgets_init', create_function('', 'register_widget("Twitter");' ) );

//Register Top Videos widget
add_action('widgets_init', create_function('', 'register_widget("Top_videos");' ) );

//register Recent_Videos class
add_action( 'widgets_init', create_function( '', 'register_widget("Recent_videos");' ) );

//register Contact_us class
add_action( 'widgets_init', create_function( '', 'register_widget("Contact_us");' ) );

//register Top_playlist class
add_action('widgets_init', create_function('', 'register_widget("Top_playlist");') );

//register Top_playlist class
add_action('widgets_init', create_function('', 'register_widget("Social_Networks");') );