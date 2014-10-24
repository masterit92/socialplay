
<?php while(have_posts()): the_post(); ?>
	<section class="blog-box">
		<div class="title">
			<a href="<?php the_permalink(); ?>">
				<h2 class=""><?php the_title(); ?></h2>
			</a>
		</div>
        
        <?php if( $GLOBALS['_webnukes']->fw_get_settings( 'sub_general_settings', 'tag_status' ) && is_single() ): ?>
        	<div class="tags"><?php the_tags(__('<span>Tags: </span>', THEME_NAME), ''); ?></div>
        <?php endif; ?>
		
		<?php echo blog_post_thumb_format(); ?>
		
        <div class="blog-detail">
			<?php if( is_singular() ) the_content();
			else the_excerpt(); ?>

		</div>
		
		<div class="blog-btm addthis_toolbox addthis_default_style"> 
			<?php echo fw_get_the_categories(); ?>
        	
            <!-- AddThis Button END -->
            <a class="share addthis_button_compact btn btn-share" href="javascript:void(0);">
                <i class="icon-share"></i><?php _e('Share', THEME_NAME); ?>
            </a>
           
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5188e0b41f1dcca6"></script>
            <ul class="blog-infobar">
                <li><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></li>
                <li><a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>"><?php echo get_the_date(); ?></a></li>
                <li><a href="<?php the_permalink();?>#comments"><?php comments_number(); ?></a></li>
            </ul>
		</div>
	</section>
<?php endwhile; ?>
<!-- blog-box ends -->

