<?php get_header(); ?>

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
    <div class="contents">
    	<?php $object = get_queried_object(); ?>
    	<h2 class="text-red"><?php echo kvalue($object, 'name'); ?></h2>
        
        <div class="vid-row">
			<?php $key = '_wpnukes_'.get_query_var( 'taxonomy' ).'_'.$wp_query->get_queried_object()->term_id.'_privacy';?>
			<?php if(is_visible_term($key) == true) : ?>
				<?php get_template_part('libs/home_videos'); ?>
			<?php else:?>
	
			<?php _e('This channel is not public.', THEME_NAME);?>
	
			<?php endif;?>
        </div>
        
        <div class="clearfix"></div>
        <?php fw_the_pagination(); ?>
    </div>
</div>

<?php get_footer(); ?>