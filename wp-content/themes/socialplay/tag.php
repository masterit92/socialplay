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
    <div id="page" class="container">
    	<div class="row">
			
			<article class="contents span8">
				<?php $object = get_queried_object(); ?>
                <!--<h2 class="text-red"><?php echo kvalue($object, 'name'); ?></h2>-->
    
                <?php get_template_part('libs/blog_listing'); ?>
                
                <?php fw_the_pagination(); ?>
				<!-- pagination ends --> 
			</article>
            	
            <aside class="sidebar span4">
               <?php _load_dynamic_sidebar('post_tag', 'default-sidebar'); ?> 
            </aside>
            <!-- sidebar ends -->

        </div>
    </div>
    
</div>

<?php get_footer(); ?>