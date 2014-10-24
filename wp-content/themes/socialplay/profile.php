<?php
/* Template Name: Profile */
global $current_user;
get_currentuserinfo();

$t  = $GLOBALS['_wpnukes_videos'];
$profile_settings = $GLOBALS['_webnukes']->fw_get_settings( 'sub_profile_settings');

$posts_per_page = get_option('posts_per_page');
$playlist = $t->helper->get_terms_array('video_playlist', true, 'object');
$channel = $t->helper->get_terms_array('video_channel', true, 'object');
$album = $t->helper->get_terms_array('audio_album', true, 'object');
$user_video = $t->helper->get_user_videos($current_user->ID, array('post_status' => array('publish', 'pending'), 'showposts'=>-1));
$user_audio = $t->helper->get_user_videos($current_user->ID, array('post_type'=> 'wpnukes_audios', 'post_status' => array('publish', 'pending'), 'showposts'=>-1));

if( !is_user_logged_in() ) 
{
	if( $login = fw_page_template('login.php') )
	{
		wp_redirect( get_permalink( kvalue( $login, 'ID') ) );
		exit;
	}
	else
	{
		wp_redirect( home_url() );
		exit;
	}
}

if( kvalue($_POST, 'add_video') || kvalue($_POST, 'add_audio') ){ do_action('wpnukes_submit_new_user_video', $_POST);}
if( kvalue($_POST, 'edit_video') || kvalue($_POST, 'edit_audio') ) do_action('wpnukes_submit_user_video', $_POST);
if( kvalue($_POST, 'add_playlist') || kvalue($_POST, 'add_channel')  || kvalue($_POST, 'add_album')) do_action('wpnukes_submit_user_playlist', $_POST);
if( kvalue($_POST, 'user_profile') ) do_action('wpnukes_submit_user_profile', $_POST);


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
            <div class="single-contents">
                
				<?php $user_data = ( function_exists('wp_get_current_user') ) ? wp_get_current_user() : '';?>
                
				<?php if( !is_user_logged_in() || !$user_data->ID ) die( __('You are not authorized to access this area', THEME_NAME) ); ?>
                <?php echo $t->helper->show_msg(); ?>
                
                <div class="heading user-heading">
                    <?php if( $avatar = get_user_meta( $user_data->ID, 'avatar', true)): ?>
                    	<img src="<?php echo $avatar; ?>" alt="profile" class="img-bordr" />
                    <?php endif; ?>
                    <a href="<?php echo get_author_posts_url( kvalue( $user_data, 'ID')); ?>" class="text-red">
                    	<h1><?php echo kvalue( kvalue( $user_data, 'data'), 'display_name' ); ?></h1>
                    </a>
                    <ul class="social-icons">
                    	<?php if( $fb = get_user_meta(kvalue($current_user,'ID'), 'facebook', true) ):?>
                        	<li><a href="<?php echo $fb; ?>" class="icon-facebook tooltip" title="Facebook"></a></li>
                        <?php endif; ?>
                        <?php if( $tw = get_user_meta(kvalue($current_user,'ID'), 'twitter', true) ):?>
                        	<li><a href="<?php echo $tw; ?>" class="icon-twitter tooltip" title="Twitter"></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- heading ends -->
                
                <div class="tabbable">
                    <div class="tab-bar clearfix">
                        
                        <ul class="nav nav-tabs">
                        	
                            <?php if( kvalue( $profile_settings, 'channel' ) == 'on' || is_super_admin() ): ?>
                            	<li><a href="#tab1"><?php _e('Channels', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if( kvalue( $profile_settings, 'playlist' ) == 'on' || is_super_admin() ): ?>
                            	<li><a href="#tab2"><?php _e('Playlists', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if( kvalue( $profile_settings, 'video' ) == 'on' || is_super_admin() ): ?>
                            	<li><a href="#tab3"><?php _e('Videos', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if( kvalue( $profile_settings, 'album' ) == 'on' || is_super_admin() ): ?>
                            	<li><a href="#tab4"><?php _e('Albums', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                            
                            <?php if( kvalue( $profile_settings, 'audio' ) == 'on' || is_super_admin() ): ?>
                            	<li><a href="#tab5"><?php _e('Audios', THEME_NAME); ?></a></li>
                            <?php endif; ?>
                            
                        </ul>
                        
                        <a href="#" id="user_profile" class="edit-profile">
                        	<?php _e('Edit Profile', THEME_NAME); ?>
                        	<i class="icon-edit"></i>
                        </a> 
                    
                    </div>
                    
                    <div class="tab-content vid-condense">
                        
                        <?php if( kvalue( $profile_settings, 'channel' ) == 'on' || is_super_admin() ): ?>
                            <div class="tab-pane active" id="tab1">
                                <div class="heading">
                                    <h2><?php _e('Channels', THEME_NAME); ?></h2>
                                    
                                    <a href="#" id="add_channel" class="btn btn-red pull-right">
                                        <?php _e('Add New Channel', THEME_NAME); ?>
                                    </a> 
                                </div>
                                
                                <section class="content">
                                    
                                    
                                    <div class="chan-contain">
                                        <?php get_terms_listing( $channel ) ?>
                                    </div>
                                    <!-- chan-contain ends --> 
                                </section>
                                
                            </div>
                        <!-- tab-pane ends -->
                        <?php endif; ?>
                        
                        <?php if( kvalue( $profile_settings, 'playlist' ) == 'on' || is_super_admin() ): ?>
                            <div class="tab-pane active" id="tab2">
                                <div class="heading">
                                    <h2><?php _e('Playlists', THEME_NAME); ?></h2>
                                    
                                    <a href="#" id="add_playlist" class="btn btn-red pull-right">
                                        <?php _e('Add New Playlist', THEME_NAME); ?>
                                    </a> 
                                </div>
                                
                                <section class="content">
                                    
                                    <div class="chan-contain">
                                        <?php get_terms_listing( $playlist ); ?>
                                    </div>
                                    <!-- chan-contain ends --> 
                                    
                                </section>
                            </div>
                        <!-- tab-pane ends -->
                        <?php endif; ?>
                        
                        <?php if( kvalue( $profile_settings, 'video' ) == 'on' || is_super_admin() ): ?>
                            <div class="tab-pane" id="tab3">
                                
                                <div class="heading">
                                    <h2>&nbsp;<?php //_e('Videos Collection', THEME_NAME); ?></h2>
                                        
                                    <div class="pull-right">
                                    <a href="javascript:void(0);" id="add_video" class="btn btn-red" style="position:relative;">
                                        <?php _e('Add New Video', THEME_NAME); ?>
                                    </a>
                                    <?php if( kvalue( $profile_settings, 'video_upload' ) == 'on' || is_super_admin() ): ?>
                                        <a href="javascript:void(0);" id="upload_video" class="btn btn-red" style="position:relative; margin-left: 10px;">
                                            <?php _e('Upload New Video', THEME_NAME); ?>
                                        </a>
                                    <?php endif;?>
                                    </div>
    
                                </div>
                                <?php $vid_count = 0;
                                if( $vids = fw_video_row( $user_video, 'video' )): ?>
                                    <section class="content">
                                        
                                        <div class="vid-row" id="itemContainer">
                                            <?php foreach( $vids as $k => $v ): ?>
                                                
                                               <h2><?php echo $k; ?></h2>
                                                <?php if( is_array( $v ) ){?>
                                                    
                                                    <?php foreach( $v as $chunk )	
                                                    {
                                                        echo $chunk; 
                                                        $vid_count++;
                                                    }
                                                } ?>
                                                
                                            <?php endforeach; ?>
                                        </div>
                                        <?php 
                                         if( $vid_count > $posts_per_page ): ?>
                                            <!-- Future navigation panel -->
                                            <div class="holder pagination"></div>
                                        <?php endif; ?>
                                    </section>
                                <?php endif; ?>
                                
                            </div>
                        <!-- tab-pane ends -->
                        <?php endif; ?>
                        
                        <?php if( kvalue( $profile_settings, 'album' ) == 'on' || is_super_admin() ): ?>
                            <div class="tab-pane active" id="tab4">
                                <div class="heading">
                                    <h2><?php _e('Albums', THEME_NAME); ?></h2>
                                    
                                    <a href="#" id="add_album" class="btn btn-red pull-right">
                                        <?php _e('Add New Album', THEME_NAME); ?>
                                    </a> 
                                </div>
                                
                                <section class="content">
                                    
                                    <div class="chan-contain">
                                        <?php get_terms_listing( $album ); ?>
                                    </div>
                                    <!-- chan-contain ends --> 
                                </section>
                            </div>
                        <!-- tab-pane ends -->
                        <?php endif; ?>
                        
                        <?php if( kvalue( $profile_settings, 'audio' ) == 'on' || is_super_admin() ): ?>
                            <div class="tab-pane" id="tab5"> 
                                
                                <div class="heading">
                                    <h2>&nbsp;<?php //_e('Audio Collection', THEME_NAME); ?></h2>
                                    
                                    <div class="pull-right">
                                        <a href="javascript:void(0);" id="add_audio" class="btn btn-red" style="position:relative;">
                                            <?php _e('Add New Audio', THEME_NAME); ?>
                                        </a>
                                        <?php if( kvalue( $profile_settings, 'audio_upload' ) == 'on' || is_super_admin() ):?>
                                            <a href="javascript:void(0);" id="upload_audio" class="btn btn-red" style="position:relative; margin-left: 10px;">
                                                <?php _e('Upload New Audio', THEME_NAME); ?>
                                            </a>
                                        <?php endif;?>
                                    </div>
                                    
                                </div>
                                
                                <?php $vid_count = 0;
                                if( $vids = fw_video_row( $user_audio, 'audio' )): ?>
                                    
                                    <section class="content">
                                        
                                        <div class="vid-row" id="itemContainer1">
                                            <?php foreach( $vids as $k => $v ): ?>
                                                
                                                <h2><?php echo $k; ?> ( <?php echo count($v); ?> )</h2>
            
                                                <?php if( is_array( $v ) ){?>
                                                    
                                                    <?php foreach( $v as $chunk )	
                                                    {
                                                        echo $chunk; 
                                                        $vid_count++;
                                                    }
                                                }?>
                                                
                                            <?php endforeach; ?>
                                        </div>
                                        
                                       <?php if( $vid_count > $posts_per_page ): ?>
                                            <!-- Future navigation panel -->
                                            <div class="holder1 pagination"></div>
                                        <?php endif; ?>
                                    </section>
                                    
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    <!-- tab-content ends --> 
                </div>
                <!-- tabbable ends --> 
            </div>
            
            <!-- pagination ends --> 
        </article>

        <aside class="sidebar span4">
            <?php _load_dynamic_sidebar('profile', 'default-sidebar'); ?>
        </aside>
        <!-- sidebar ends --> <!-- sidebar ends --> 
    </div>
</div>
<!-- bread-bar ends -->

<div id="dialog-load">
    <div class="load-vid"> <i class="icon-download-arrow"></i>
        <div class="progress">
            
        </div>
        <h3>
            <?php _e('Getting Contents ', THEME_NAME); ?>
        </h3>
    </div>
</div>

<div id="dialog-editProfile"> </div>
<div id="dialog-addPlaylist"> </div>
<div id="dialog-uploadVideo"> </div>
<div class="dialog-overlay"></div>

<?php get_footer(); ?>