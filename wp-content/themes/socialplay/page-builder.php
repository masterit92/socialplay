<?php /* Template Name: Page Builder */
get_header(); 
$webnukes = $GLOBALS['_webnukes'];
$webnukes->load('layout_class');
$settings = get_post_meta(get_the_ID(), 'page_builder_data', true);
$lay = kvalue( $settings, 'structure' );?>


<?php $setting = get_post_meta(get_the_ID(), 'wpnukes_page_settings', true);?>

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
        
		<?php $webnukes->layout->sidebar( $settings, 'left' ); ?>
        
        <?php if( $lay == 'col-full' || !$lay ) $content_span = 'span12';
		elseif( $lay == 'col-left2' || $lay == 'col-right2' || $lay == 'col-both' ) $content_span = 'span6';
		else $content_span = 'span8'; ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class("contents ".$content_span); ?> style="margin-bottom:20px;" >
            
            <?php $webnukes->layout->build_page($settings); ?>
        </article>
        
        <?php $webnukes->layout->sidebar( $settings, 'right' ); ?>
	</div>
    
</div>

<?php get_footer(); ?>