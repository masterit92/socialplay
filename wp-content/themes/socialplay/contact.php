<?php /* Template Name: Contact Page */

if( kvalue( $_POST, 'contact_form') ) $messages = fw_contact_form_submit();

get_header(); ?>

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
            <section class="contacts">
                <div class="heading">
                    <h1 class="text-red"><?php the_title(); ?></h1>
                </div>
                <?php the_content(); ?>
                <!-- heading ends -->
                <?php $settings = $GLOBALS['_webnukes']->fw_get_settings('sub_contact_page_settings');?>
                
                    <!-- google-maps ends -->
                    <?php if(isset($messages )) echo $messages; ?>
                    <?php get_template_part('libs/contactform'); ?>
                    <?php fw_contact_form( $settings ); ?> 
            </section>
            <!-- contacts end --> 
        </article>
        <!-- contents end -->
        
        <aside class="sidebar span4">
           <?php _load_dynamic_sidebar('contact', 'contact-sidebar'); ?> 
        </aside>
        <!-- sidebar ends --> 
        
    </div>
</div>
<?php get_footer(); ?>

