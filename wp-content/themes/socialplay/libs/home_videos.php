<?php 
global $post;
$t = $GLOBALS['_wpnukes_videos'];
$settings = !isset( $settings ) ? $GLOBALS['_webnukes']->fw_get_settings('sub_video_settings') : $settings;

$cols = ( kvalue($settings, 'video_columns') == 'random' ) ? false : kvalue($settings, 'video_columns');
$class = ( $cols == 'two' || $cols == '2' ) ? 'large' : 'small';

if($wp_query ):?>
    
	<?php $count = 0;
	while($wp_query->have_posts()): $wp_query->the_post();?>

        <?php  $format = ($post->post_type == 'wpnukes_videos') ? 'video' : 'audio';
		$vids = (array)kvalue($post, '_wpnukes_'.$format); ?>
        <?php if( !$t->helper->is_visible() ) continue; ?>

			<?php if( !$cols && $count < 1 ) $class = 'large';
			elseif( !$cols && $count > 1 ) $class = 'small'; 
			$source = ( kvalue($vids, 'source') == 'local' ) ? 'local-'.$format : kvalue($vids, 'source');?>

            <div class="vid-box <?php echo $class; ?>">
                <div class="video-thumb">
                    <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'video-large' );
					if( ! $thumb && kvalue( $vids, 'thumb') ) $thumb[0] = kvalue( $vids, 'thumb');
					elseif( !$thumb ) $thumb[0] = get_template_directory_uri().'/images/video-large.gif';

					$thumb = current( (array) $thumb); 
					$img_size = ( $class == 'small' ) ? array('width'=>256, 'height'=>160) : array('width'=>556, 'height'=>315); ?>
                    <img <?php echo http_build_query($img_size, '', ' ');?> class="thumb" src="<?php echo $thumb;?>" alt="<?php the_title(); ?>">
                    <?php echo $t->helper->get_embed($vids); ?>

                    <div class="thumb-info">
                        <div class="info">
                        	<?php if( kvalue( $vids, 'hd' ) ): ?>
                        		<span><i class="icon-hd"></i></span>
                            <?php endif; ?>
                            <span><i class="icon-duration"></i><?php echo fw_get_duration( kvalue($vids, 'duration'), kvalue( $vids, 'source' ) ); ?></span>
                            <span><i class="icon-<?php echo $source; ?>"></i></span> 
						</div>
                        <div class="video-controls" data-id="<?php the_ID(); ?>"> 
                        	<a href="<?php the_permalink();?>"><i class="icon-play"></i></a>
                            <a href="javascript:void(0);" id="video_like">
                            	<i class="icon-like"></i><span><?php echo fw_get_votes(get_the_ID(), 'like');?></span>
                            </a>
                            <a href="javascript:void(0);" id="video_dislike">
                            	<i class="icon-dislike"></i><span><?php echo fw_get_votes(get_the_ID(), 'dislike');?></span>
                            </a>
                        </div>
                        <div class="auth">
                            <ul>
                                <li class="auth-name"><?php the_author(); ?></li>
                                <li class="auth-profession"><?php echo get_the_term_list(get_the_ID(), $format.'_category', '', ', ');?></li>
                            </ul>
                            <?php echo fw_get_avatar(kvalue($post, 'post_author')); ?>
                        </div>
                    </div>
                    <div class="thumb-hover"> 
                    	<span>
                        	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </span>
                    </div>
                </div>
                <!-- video-thumb ends --> 
            </div>
        	<?php $count = ( $count > 4 ) ? 0 : $count + 1;?>
	<?php endwhile; ?>

<?php endif; ?>