<?php  /* Template Name: Albums */
get_header(); 
$t  = $GLOBALS['_wpnukes_videos'];
$channel = $t->helper->get_terms_array('audio_album', false, 'object');?>

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
        <article class="contents span8">
            <section class="content-box">
                <div class="title">
                    <h2><?php the_title(); ?></h2>
                </div>
                
                <?php the_content(); ?>
                
                <div class="chan-contain">
                	<?php get_terms_listing( $channel ) ?>
                </div>
                <!-- chan-contain ends -->            
            </section>
        </article>
        <!-- contents end -->
        
        <aside class="sidebar span4">
           <?php _load_dynamic_sidebar('channel', 'default-sidebar'); ?> 
        </aside>
        <!-- sidebar ends --> 
    </div>
</div>

<?php get_footer(); ?>