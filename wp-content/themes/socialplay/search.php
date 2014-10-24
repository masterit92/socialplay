<?php get_header();?>

<!-- Page Container -->
<div class="container" id="page-single">
    
	<!-- BreadCrumbs Bar -->
    <div class="bread-bar clearfix">
		
		<!-- BreadCrumbs -->
        <?php echo get_the_breadcrumb();?>
        
		<!-- News Ticker -->
        <?php fw_news_ticker();?>
    </div>

	<!-- Page Content -->
    <div id="page" class="contents clearfix">
    	<?php $object = get_queried_object();?>
		
    	<h2 class="text-red"><?php echo kvalue($object, 'name');?></h2>
		
		<div class="vid-row">
			<?php get_template_part('libs/home_videos');?>
        </div>
		
		<div class="clearfix"></div>
        <?php fw_the_pagination();?>
    </div>
</div>

<?php get_footer();?>