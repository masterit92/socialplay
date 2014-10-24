<?php 
$t = $GLOBALS['_wpnukes_videos'];

$options = array();

$options['post']['sidebar']			= array(
											'label' =>__('Post Sidebar', THEME_NAME),
											'type' =>'dropdown',
											'info' => __( 'select the sidebar for the current post' , THEME_NAME),
											'validation'=>'',
											'value' => fw_sidebars_array(),
											'attrs'=>array('class' => ''),
										);
$options['post']['webnukes_format'] = array(
											'label' =>__('Post Format', THEME_NAME),
											'type' =>'dropdown',
											'info' => __( 'select post format' , THEME_NAME),
											'validation'=>'',
											'value' => array('image'=>__('Image', THEME_NAME),'slider'=>__('Slider', THEME_NAME), 'audio'=>__('Audio', THEME_NAME), 'video'=>__('Video', THEME_NAME),),
											'attrs'=>array('class' => ''),
										);
$options['post']['webnukes_audio_embed'] = array(
											'label' =>__('Audio Embed', THEME_NAME),
											'type' =>'textarea',
											'info' => __( 'Enter embed code for audio' , THEME_NAME),
											'validation'=>'',
											'attrs'=>array('class' => 'input-block-level', 'style' => 'width:100%; min-height:110px;'),
										);
$options['post']['webnukes_video_embed'] = array(
											'label' =>__('Video Embed Code', THEME_NAME),
											'type' =>'textarea',
											'info' => __( 'Enter embed code for video' , THEME_NAME),
											'validation'=>'',
											'attrs'=>array('class' => 'input-block-level', 'style' => 'width:100%; min-height:110px;'),
										);

$options['wpnukes_videos']['webnukes_source'] = array(
									'label'=>__('Source', THEME_NAME),
									'type'=>'hidden',
									'icon' => true,
									'settings' => array('heading' => __('Upload Video Detail', THEME_NAME), 'button_text' => __('Submit Video', THEME_NAME)),
								);
$options['wpnukes_videos']['webnukes_id'] = array(
									'label'=>__('ID', THEME_NAME),
									'type'=>'hidden',
								);

$options['wpnukes_videos']['webnukes_safety'] = array(
									'label'=>__('Safety', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('on' => __('On', THEME_NAME), 'off' => __('Off', THEME_NAME)),
								);
$options['wpnukes_videos']['webnukes_privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);
$options['wpnukes_videos']['webnukes_views'] = array(
									'label'=>__('Views', THEME_NAME),
									'type'=>'hidden',
								);
$options['wpnukes_videos']['webnukes_thumb'] = array(
									'label'=>__('Thumb', THEME_NAME),
									'type'=>'hidden',
								);
$options['wpnukes_videos']['webnukes_rating'] = array(
									'label'=>__('Rating', THEME_NAME),
									'type'=>'hidden',
								);
$options['wpnukes_videos']['webnukes_duration'] = array(
									'label'=>__('Duration', THEME_NAME),
									'type'=>'hidden',
								);
$options['wpnukes_videos']['webnukes_hd'] = array(
									'label'=>__('High Definition', THEME_NAME),
									'type'=>'hidden',
								);

$options['wpnukes_audios']['webnukes_source'] = array(
									'label'=>__('Source', THEME_NAME),
									'type'=>'hidden',
									'icon' => true,
									'settings' => array('heading' => __('Upload Video Detail', THEME_NAME), 'button_text' => __('Submit Video', THEME_NAME)),
								);
$options['wpnukes_audios']['webnukes_id'] = array(
									'label'=>__('ID', THEME_NAME),
									'type'=>'hidden',
								);

$options['wpnukes_audios']['webnukes_safety'] = array(
									'label'=>__('Safety', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>''),
									'value' => array('on' => __('On', THEME_NAME), 'off' => __('Off', THEME_NAME)),
								);
$options['wpnukes_audios']['webnukes_privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>''),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);
$options['wpnukes_audios']['webnukes_views'] = array(
									'label'=>__('Views', THEME_NAME),
									'type'=>'hidden',
								);

$options['wpnukes_audios']['webnukes_rating'] = array(
									'label'=>__('Rating', THEME_NAME),
									'type'=>'hidden',
								);
$options['wpnukes_audios']['webnukes_duration'] = array(
									'label'=>__('Duration', THEME_NAME),
									'type'=>'hidden',
								);
$options['wpnukes_audios']['webnukes_hd'] = array(
									'label'=>__('High Definition', THEME_NAME),
									'type'=>'hidden',
								);

$options['page']['sidebar']			= array(
											'label' =>__('Page Sidebar 1', THEME_NAME),
											'type' =>'dropdown',
											'info' => __( 'select the sidebar for the current page' , THEME_NAME),
											'validation'=>'',
											'value' => fw_sidebars_array(),
											'attrs'=>array('class' => ''),
										);

$options['page']['author_info']			= array(
											'label' =>__('Show Author Information', THEME_NAME),
											'type' =>'switch',
											//'info' => __( 'select the sidebar for the current page' , THEME_NAME),
											'validation'=>'',
											'attrs'=>array('class' => 'input-block-level'),
										);





