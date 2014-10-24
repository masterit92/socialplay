<?php if ( ! defined('ABSPATH')) exit('restricted access');
$vid = &$GLOBALS['_wpnukes_videos'];

$default_settings = array(
							//'slider' => array('title'=>'Slider','min-col'=>1,'max-col'=>3,'delete'=>false),
							'blog' => array('title'=>'Blog','min-col'=>1,'max-col'=>4),
							'playlists' => array('title'=>'Playlists','min-col'=>1,'max-col'=>4),
							'content' => array('title'=>'Contents','min-col'=>1,'max-col'=>4, 'content' => ''),
							'gallery' => array('title'=>'Gallery','min-col'=>1,'max-col'=>4),
							'albums' => array('title'=>'Albums','min-col'=>1,'max-col'=>4),
							'contactus' => array('title'=>'Contact Us','min-col'=>1,'max-col'=>4),
							'audios' => array('title'=>'Audios','min-col'=>1,'max-col'=>4),
							'videos' => array('title'=>'Videos','min-col'=>1,'max-col'=>4, 'delete'=>false),
							'channels' => array('title'=>'Channels','min-col'=>1,'max-col'=>4, 'delete'=>false),
							'heading' => array('title'=>'Heading','min-col'=>1,'max-col'=>4, 'delete'=>false),
							
						);
$options = array();


$options['videos']['category'] = array(
										'label' => __('Category', THEME_NAME),
										'type' =>'dropdown',
										'info' => 'Select the category for videos',
										'validation'=>'',
										'std' => '8',
										'value' => $vid->helper->get_terms_array('video_category', false, 'array'),
										'attrs'=>array('class' => ''),
									);
$options['videos']['columns'] = array(
										'label' => __('Columns', THEME_NAME),
										'type' =>'dropdown',
										'info' => 'Select the Columns',
										'validation'=>'',
										'std' => '2',
										'value' => array('2'=>'2 Cols', '4'=>'4 Cols', 'rand' => 'Random'),
										'attrs'=>array('class' => ''),
									);
$options['videos']['number'] = array(
										'label' => __('Number of Videos', THEME_NAME),
										'type' =>'input',
										'info' => 'Enter number of videos to show',
										'validation'=>'',
										'std' => '5',
										'attrs'=>array('class' => 'input-block-level'),
									);
$options['videos']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);

$options['audios']['category'] = array(
										'label' => __('Category', THEME_NAME),
										'type' =>'dropdown',
										'info' => 'Select the category for audios',
										'validation'=>'',
										'std' => '8',
										'value' => $vid->helper->get_terms_array('audio_category', false, 'array'),
										'attrs'=>array('class' => ''),
									);
$options['audios']['columns'] = array(
										'label' => __('Columns', THEME_NAME),
										'type' =>'dropdown',
										'info' => 'Select the Columns',
										'validation'=>'',
										'std' => '2',
										'value' => array('2'=>'2 Cols', '4'=>'4 Cols', 'rand' => 'Random'),
										'attrs'=>array('class' => ''),
									);
$options['audios']['number'] = array(
										'label' => __('Number of Audios', THEME_NAME),
										'type' =>'input',
										'info' => 'Enter number of audios to show',
										'validation'=>'',
										'std' => '5',
										'attrs'=>array('class' => 'input-block-level'),
									);
$options['audios']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);
$options['gallery']['ids'] = array(
										'label' => __('Image', THEME_NAME),
										'type' =>'wp_gallery',
										'info' => 'Enter the comma seprated images ids',
										'validation'=>'',
										'std' => '8',
										'attrs'=>array('class' => 'input-block-level'),
									);
$options['gallery']['columnsssss'] = array(
										'label' => __('Columns', THEME_NAME),
										'type' =>'input',
										'info' => 'Enter the number of columns for gallery',
										'validation'=>'',
										'std' => '3',
										'attrs'=>array('class' => 'input-small'),
									);
$options['gallery']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);
$options['content']['content'] = array(
										'label' => __('Content', THEME_NAME),
										'type' =>'textarea',
										'info' => 'You can add content and html tags',
										'validation'=>'',
										'std' => '8',
										'attrs'=>array('class' => 'input-block-level'),
									);
$options['content']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);
$options['channels']['number'] = array(
										'label' => __('Number of Channels', THEME_NAME),
										'type' =>'input',
										'validation'=>'',
										'std' => '8',
										'attrs'=>array('class' => 'input-small'),
									);
$options['channels']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);
									
$options['playlists']['number'] = array(
										'label' => __('Number of Playlists', THEME_NAME),
										'type' =>'input',
										'validation'=>'',
										'std' => '8',
										'attrs'=>array('class' => 'input-small'),
									);
$options['playlists']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);


$options['albums']['number'] = array(
										'label' => __('Number of Albums', THEME_NAME),
										'type' =>'input',
										'validation'=>'',
										'std' => '8',
										'attrs'=>array('class' => 'input-small'),
									);
$options['albums']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);


$options['heading']['tag'] = array(
										'label' => __('Heading Tag', THEME_NAME),
										'type' =>'dropdown',
										'validation'=>'',
										'std' => '2',
										'value' => array('1' => 'H1', '2' => 'H2', '3' => 'H3', '4' => 'H4', '5' => 'H5', '6' => 'H6'),
										'attrs'=>array('class' => 'input-small'),
									);
$options['heading']['heading'] = array(
										'label' => __('Heading', THEME_NAME),
										'type' =>'input',
										'validation'=>'',
										'std' => '',
										'attrs'=>array('class' => 'input-small'),
									);
$options['heading']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);


$options['contactus']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);
$options['blog']['category'] = array(
										'label' => __('Category', THEME_NAME),
										'type' =>'dropdown',
										'info' => 'Select the category for videos',
										'validation'=>'',
										'std' => '8',
										'value' => $vid->helper->get_terms_array('category', false, 'array'),
										'attrs'=>array('class' => ''),
									);
$options['blog']['number'] = array(
										'label' => __('Number of Posts', THEME_NAME),
										'type' =>'input',
										'info' => 'Enter number of videos to show',
										'validation'=>'',
										'std' => '5',
										'attrs'=>array('class' => 'input-block-level'),
									);
$options['blog']['cols'] = array(
										'type' =>'hidden',
										'std' =>'1',
										'attrs'=>array('class' => 'widget_cols'),
									);







