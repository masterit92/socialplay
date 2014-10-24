<?php get_header(); ?>
<?php $t = $GLOBALS['_webnukes'];
$t->load('layout_class');
$settings = get_post_meta(get_the_ID(), 'page_builder_data', true);
$meta = get_post_meta(get_the_ID(), 'wpnukes_page_settings', true);?>

<div class="container" id="page-single">
    
    <div class="bread-bar clearfix"> 
		<?php echo get_the_breadcrumb(); ?> 
        <!-- breadcrumb ends -->
        <?php fw_news_ticker(); ?>
    </div>
    <!-- bread-bar ends -->
    
    <div class="row">
    	<?php $sidebar = ''; $count = '';
		$span = ( $count == 2 ) ? 'span2' : 'span4'; ?>
        <?php if( $sidebar == 'left' ): ?>

            <?php foreach( range(1, $count) as $c): ?>
                <aside class="sidebar <?php echo $span; ?>">
                   <?php dynamic_sidebar( kvalue($meta, 'sidebar'.$c)); ?> 
                </aside>
            <?php endforeach; ?>
            
        <?php endif ; ?>
        
        <?php $content_span = ( !$count ) ? 'span12' : 'span8'; ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class("contents ".$content_span); ?> >
            <?php $t->layout->build_page($settings); ?>
            <?php if( kvalue( $meta, 'author_info') == 'on'): ?>
                <section class="author">
                    <div class="heading">
                        <h1><?php _e('About Author', THEME_NAME);?> - <span class="text-red"><?php the_author(); ?></span></h1>
                    </div>
                    <?php echo get_avatar(48); ?>
                    <div class="description">
                        <p><?php echo get_the_author_meta( 'description' ); ?></p>
                        <ul>
                            <?php if( $facebook = get_user_meta(get_the_author_meta('ID'), 'facebook', true) ): ?>
                                <li><a href="<?php echo $facebook; ?>" class="tooltip" title="facebook"><i class="icon-facebook"></i><?php _e('Facebook', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                            <?php if( $twitter = get_user_meta(get_the_author_meta('ID'), 'twitter', true) ): ?>
                                <li><a href="<?php echo $twitter;?>" class="tooltip" title="twitter"><i class="icon-tweeter"></i><?php _e('Twitter', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </section>
            <?php endif; ?>
            
            <?php if( kvalue( $meta, 'enable_comments') == 'on' ) comments_template(); ?>
            <!-- video-comments end --> 
        </article>
        
        <?php if( $sidebar == 'right' ): ?>
            <?php foreach( range(1, $count) as $c): ?>
                <aside class="sidebar <?php echo $span; ?>">
                   <?php dynamic_sidebar( kvalue($meta, 'sidebar'.$c)); ?>
                </aside>
            <?php endforeach; ?>
        <?php endif ; ?>
        <!-- sidebar ends -->
        
    </div>
</div>
<?php get_footer(); ?>