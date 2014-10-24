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
        
        <div class="vid-row">
        	<div class="details">
            	<h2 class="text-red"><?php echo kvalue($object->data, 'display_name'); ?></h2>
                <?php echo apply_filters('the_content', kvalue($object, 'description') ); ?>
			</div>
		</div>
        
        <div class="vid-row">
			<?php get_template_part('libs/home_videos'); ?>
        </div>
        
        <?php fw_the_pagination(); ?>
    </div>
    
</div>

<?php get_footer(); ?>