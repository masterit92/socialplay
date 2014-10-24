<?php if( ! defined('ABSPATH')) exit('restricted access'); /** Do not delete these lines */?>

<?php
/** if post is password protected then password is required */
if ( post_password_required() ):?>
	<p class="alert"><?php _e( 'This post is password protected. Enter the password to view comments.', THEME_NAME ); ?></p>
	<?php return;
endif;?>

<section class="video-comments">
    <div class="heading">
        <h1><?php comments_number(); ?></h1>
    </div>
    <!-- heading ends -->
    
    
	<?php
	/** Let's Seperate the comments from Trackbacks */
	if(have_comments()):?>
		<div class="user-comments">
			<ul>
				<?php wp_list_comments( 'callback=fw_list_comments' ); /** Callback to Comments */ ?>  
			</ul>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<div class="pagination"><?php paginate_comments_links(); ?></div>
			<?php endif; ?>
		</div>
	<?php else: 
		/** If comments are open, but there are no comments. */
		if( ! comments_open()) : ?>
			<h3><?php _e( 'Sorry comments are closed for this Post.', THEME_NAME ); ?></h3>
		<?php endif;
	endif; ?>
   <!-- <div class="comment-area">-->
    	<?php fw_comment_form(); ?>
    <!--</div>-->
    <!-- comment-area ends -->
</section>