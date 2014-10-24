<?php get_header();?>

	<div id="page-404" class="container">
    	<div class="page-404">
        	<img src="<?php echo get_template_directory_uri();?>/images/404.png" />
            <h2 class="text-red" ><?php echo __('Page not found', THEME_NAME); ?></h2>
            <p><?php echo __('Don&rsquo;t worry... You just need to go back or click at logo to navigation home page', THEME_NAME);?></p>
        	<div class="clearfix"></div>
        </div>
    </div>

<?php get_footer();?>