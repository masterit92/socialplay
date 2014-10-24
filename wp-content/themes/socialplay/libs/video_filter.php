<?php 
global $wpdb;
$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE 1=1 AND $wpdb->posts.post_type IN ('wpnukes_videos', 'wpnukes_audios') AND ($wpdb->posts.post_status = 'publish')" );

$filters = array( 'upload_date' => array('last_hour'=>__('Last Hour', THEME_NAME), 'today'=>__('Today', THEME_NAME),
												'this_week'=>__('This Week', THEME_NAME), 'this_month'=>__('This Month', THEME_NAME),
												'this_year'=>__('This Year', THEME_NAME), 'previous_years'=>__('Previous Years', THEME_NAME),), 
						'result_types' => array('youtube'=>'Youtube', 'vimeo'=>'Vimeo',	'ustream'=>__('uStream', THEME_NAME), 'dailymotion'=>__('DailyMotion', THEME_NAME),
												'blip'=>__('Blip', THEME_NAME), 'metacafe'=>__('Metacafe', THEME_NAME), 'soundcloud'=>__('SoundCloud', THEME_NAME),),
						
						'duration' => array('1_600'=>'0 - 10 Minutes', '600_1800'=>'10 - 30 Minutes', '1800_3600'=>'30 - 60 Minutes', '3600_10800'=>'1 - 3 Hours', '10800_3600000'=>'More than 3 Hours',),
						
						'sort_by' => array('date'=>__('Recent Videos', THEME_NAME), 'random'=>__('Random', THEME_NAME),
												'title'=>__('Title', THEME_NAME), 'type'=>__('Type', THEME_NAME),
												'popular'=>__('Popular', THEME_NAME), 'most_viewed'=>__('Most Viewed', THEME_NAME),),
						);
						
$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_video_settings');
if( kvalue($settings, 'show_filter') != 'on' ) return; // If Filteration is off then don't show filteration. ?>



<div class="filters">
    
    <div class="filter-tp">
        <h2 class="text-red"><?php _e('Now Trending', THEME_NAME); ?></h2>
        <div class="filter-data"> 
        	<span><?php echo number_format($count); ?> <?php _e('Videos and Audios', THEME_NAME); ?></span> 
            <a class="btn-filter" href="javascript:void(0);"><?php _e('Filter Videos', THEME_NAME); ?></a> 
        </div>
    </div>
    
    <div class="filter-content clearfix">
        <div class="row">
        	<?php foreach( $filters as $k => $v ): ?>
            <div class="span3" style="width:22%;">
                <h4 class="text-red"><?php echo ucwords( str_replace('_', ' ', $k ) ); ?></h4>
                <ul class="double">
                	<?php foreach( $v as $key => $val ): ?>
                    <li><a href="<?php echo home_url().'/?social_filter_type='.$k.'&social_filter='.$key;?>"><?php echo $val; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
