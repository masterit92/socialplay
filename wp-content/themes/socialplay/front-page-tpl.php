<?php 
/* Template Name: Frontpage */

get_header(); 
$t = $GLOBALS['_wpnukes_videos'];
$nukes = $GLOBALS['_webnukes'];?>


<?php $slider_settings = $nukes->fw_get_settings('sub_home_page_slider');
if( kvalue( $slider_settings, 'status') == 'on'):?>
	<?php if( function_exists( 'putRevSlider')):?>
        <?php putRevSlider(kvalue( $slider_settings, 'revslider'),"homepage"); ?> 
    <?php endif;?>
<?php endif;?>


<!-- Page Container -->
<div id="page" class="container">
	<?php fw_homepage_msg(); ?>
    
	<?php /** Show homepage video */
	$query = fw_prep_video_query();
	get_template_part('libs/video_filter'); 
	$wp_query = $t->helper->get_videos($query);?>
	<div class="vid-row">
    	<?php $settings = $GLOBALS['_webnukes']->fw_get_settings('sub_video_settings'); ?>
		<?php include('libs/home_videos.php'); ?>
    </div>
	<?php wp_reset_query(); ?>
    <div class="box">
    	<a class="loadmore" data-page="2" id="home_load_more" href="javascript:void(0);"><i class="icon-refresh"></i><?php _e('Load More Vidoes', THEME_NAME); ?></a>
    </div>
</div>

<?php if( is_active_sidebar('homepage-sidebar1')) dynamic_sidebar('homepage-sidebar1'); ?>

<?php get_footer(); ?>