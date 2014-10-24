<?php get_header();

/** Load settings from post meta */
$settings = get_post_meta(get_the_ID(), 'wpnukes_page_settings', true); ?>

<!-- Page Container -->
<div class="container" id="page-single">

	<!-- BreadCrumbs Bar -->
	<div class="bread-bar clearfix">
		<!--BreadCrumbs -->
		<?php echo get_the_breadcrumb();?>
		
		<!-- News Ticker -->
		<?php fw_news_ticker(); ?>
	</div>
	
	<!-- Page Content -->
	<div class="row">
		<article id="post-<?php the_ID(); ?>" <?php post_class("contents span8"); ?> >
		
			<!-- Blog Listings -->
			<?php get_template_part('libs/blog_listing'); ?>
			
			<!-- WP Link Pages -->
			<?php wp_link_pages(); ?>

			<!-- Author Info -->
			<?php if (kvalue($settings, 'author_info') == 'on'): ?>
				<section class="author">
					<div class="heading">
						<h1><?php _e('About Author', THEME_NAME); ?> - <span class="text-red"><?php the_author(); ?></span></h1>
					</div>
					
					<!-- Avatar -->
					<?php echo get_avatar(48); ?>
					
					<!-- Author Meta -->
					<div class="description">
						<p><?php echo get_the_author_meta('description'); ?></p>
						
						<!-- Social Network -->
						<ul>
							<?php if ($facebook = get_user_meta(get_the_author_meta('ID'), 'facebook', true)): ?>
								<li>
									<a href="<?php echo $facebook; ?>" class="tooltip" title="facebook">
										<i class="icon-facebook"></i><?php _e('Facebook', THEME_NAME); ?>
									</a>
								</li>
							<?php endif;?>
							
							<?php if ($twitter = get_user_meta(get_the_author_meta('ID'), 'twitter', true)): ?>
								<li>
									<a href="<?php echo $twitter; ?>" class="tooltip" title="twitter">
										<i class="icon-twitter"></i><?php _e('Twitter', THEME_NAME); ?>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</section>

			<?php endif; ?>

			<!-- Comments -->
			<?php if (comments_open()) comments_template(); ?>
		</article>
		
		<!-- SideBar -->
		<aside class="sidebar span4">
			<?php if ($settings && kvalue($settings, 'sidebar')) dynamic_sidebar(kvalue($settings, 'sidebar')); ?>
		</aside>

	</div>
</div>

<!-- Footer -->
<?php get_footer();?>