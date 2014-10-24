<?php get_header(); ?>
<?php $webnukes = $GLOBALS['_wpnukes_videos'];?>
<?php $settings = $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings');?>
<div class="container" id="page-single">
    
    <div class="bread-bar clearfix">
        <?php echo get_the_breadcrumb(); ?>
        <!-- breadcrumb ends -->
        <?php fw_news_ticker(); ?>
    </div>
    <!-- bread-bar ends -->
    
    <div class="row">
        <article class="contents span8">
        	<?php while( have_posts() ): the_post(); ?>
            
            <?php $type = get_video_meta_type( $post->post_type );
			$vids = get_video_meta(get_the_ID(), kvalue($post, 'post_type'));?>
            
				<section class="video-thumb">
					<?php  echo $webnukes->helper->get_embed($vids); ?>
				</section>
			
            <!-- video-thumb ends -->
            <section class="video-details">
                <div class="heading"> 
                	<a class="text-black" href="<?php the_permalink(); ?>"><h1><?php the_title(); ?></h1></a>
                </div>
                <!-- heading ends -->
                <div class="socialize" data-id="<?php the_ID(); ?>"> 
                	<span class="pulished"><?php _e('Published Date:', THEME_NAME); ?> <?php echo get_the_date(); ?></span> 
                    <a class="btn btn-green-grad" id="video_like" href="javascript:void(0);">
                    	<i class="icon-like2"></i><?php _e('Like', THEME_NAME); ?> ( <span><?php echo fw_get_votes(get_the_ID(),'like');?></span> )
                    </a> 
                    <a class="btn btn-red-grad" id="video_dislike" href="javascript:void(0);">
                    	<i class="icon-dislike2"></i><?php _e('Dislike', THEME_NAME); ?> ( <span><?php echo fw_get_votes(get_the_ID(),'dislike');?></span> )
                    </a>
                    
                    <?php if( $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'post_views') == 'on' ):?>
                    	<span class="pulished"><?php _e('Views:', THEME_NAME); ?> <?php echo fw_get_post_views( get_the_ID() ); ?></span>
                    <?php endif; ?>
                    
                    <ul class="social-icons pull-right">
                        <?php echo fw_social_networks(); ?>
                    </ul>
                </div>
                <div class="details">
                    <div class="user"> <?php echo fw_get_avatar( kvalue($post, 'post_author')); ?>
                        <div class="user-data">
                            <h4><?php the_author(); ?></h4>
                            
                            <p>
                            	<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                                    <i class="icon-video"></i>
                                    <?php echo fw_user_posts_count(kvalue($post, 'post_author'), kvalue($post, 'post_type')); ?> 
                                    <?php echo ( $type == 'video' ) ? __('Videos', THEME_NAME) : __('Audios', THEME_NAME); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="description">
                        <h6><?php _e('Description:', THEME_NAME); ?></h6>
                        <?php echo html_entity_decode(get_the_content());?>
                    </div>
                </div>
            </section>
            <?php endwhile; ?>
            <!-- video-details end -->
            <?php comments_template(); ?>
            <!-- video-comments end --> 
        </article>
        <!-- contents end -->
        
        <aside class="sidebar span4">
           <?php _load_dynamic_sidebar($type.'_single', 'default-sidebar'); ?> 
        </aside>
        <!-- sidebar ends --> 
    </div>
</div>
<?php get_footer(); ?>