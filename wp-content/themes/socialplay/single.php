<?php get_header(); ?>
<?php $t = $GLOBALS['_wpnukes_videos'];?>

<!-- Page Container -->
<div class="container" id="page-single">

	<!-- BreadCrumbs Bar -->
    <div class="bread-bar clearfix">

		<!-- BreadCrumbs -->
		<?php echo get_the_breadcrumb(); ?> 

		<!-- News Ticker -->
        <?php fw_news_ticker(); ?>
    </div>

	<!-- Page Content -->
    <div class="row">
        <article id="post-<?php the_ID(); ?>" <?php post_class("contents span8"); ?> >
            <?php get_template_part('libs/blog_listing'); ?>
            <?php wp_link_pages(array('before'=>'<div class="pagination blog"><span>Pages:</span> ', 'after'=>'</div>', 'link_before'=>'<span>', 'link_after'=>'</span>')); ?>
            <section class="author">
                <div class="heading">
                    <h1><?php _e('About Author', THEME_NAME);?> - <span class="text-red"><?php the_author(); ?></span></h1>
                </div>
                <?php echo get_avatar(48); ?>
                <div class="description">
                    <p><?php echo get_the_author_meta( 'description' ); ?></p>
                    <ul>
                        <?php if( $facebook = get_user_meta(get_the_author_meta('ID'), 'facebook', true) ): ?>
                        	<li><a href="<?php echo $facebook; ?>" class="tooltip" title="facebook"><i class="icon-facebook"></i><?php _e('Facebook', THEME_NAME); ?></a></li>
                        <?php endif; ?>
                        <?php if( $twitter = get_user_meta(get_the_author_meta('ID'), 'twitter', true) ): ?>
                        	<li><a href="<?php echo $twitter;?>" class="tooltip" title="twitter"><i class="icon-twitter"></i><?php _e('Twitter', THEME_NAME); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </section>
            
            <?php comments_template(); ?>
            <!-- video-comments end --> 
        </article>
        
        <aside class="sidebar span4">
        	<?php $post_settings = get_post_meta( get_the_ID(), 'wpnukes_post_settings', true); 
			if( $post_settings && kvalue( $post_settings, 'sidebar' ) ) dynamic_sidebar( kvalue( $post_settings, 'sidebar') );?>

        </aside>
        <!-- sidebar ends -->
        
    </div>
</div>
<?php get_footer(); ?>