<?php
$t = $GLOBALS['_wpnukes_videos'];

$options['add_video']['link'] = array(
									'label'=>__('Link', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Enter URL of Video/Channel/Playlist', THEME_NAME)),
									'validation'=>'required',
									'separator' => true,
									'settings' => array('heading' => __('Upload Video', THEME_NAME), 'button_text' => __('Add Video', THEME_NAME)),
								);
$options['add_video']['embed_code'] = array(
									'label'=>__('Embed Code', THEME_NAME),
									'type'=>'textarea',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Or Enter Embed Code of the Video', THEME_NAME)),
									'validation'=>'required',
								);
$options['upload_video']['file_upload'] = array(
									'label'=>__('Upload', THEME_NAME),
									'type'=>'file',
									'info'=>__('Upload the file', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level'),
									'validation'=>'required',
								);


$options['fetch_video']['source'] = array(
									'label'=>__('Source', THEME_NAME),
									'type'=>'hidden',
									'icon' => true,
									'settings' => array('heading' => __('Upload Video Detail', THEME_NAME), 'button_text' => __('Submit Video', THEME_NAME)),
								);
$options['fetch_video']['id'] = array(
									'label'=>__('ID', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_video']['title'] = array(
									'label'=>__('Title', THEME_NAME),
									'type'=>'input',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Give the title to video', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_video']['desc'] = array(
									'label'=>__('Description', THEME_NAME),
									'type'=>'textarea',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Provide detail description about the video', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_video']['tags'] = array(
									'label'=>__('Tags', THEME_NAME),
									'type'=>'input',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Give tags to video', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_video']['image'] = array(
									'label'=>__('Custom Image', THEME_NAME),
									'type'=>'file',
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Enter Custom Image', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_video']['category'] = array(
									'label'=>__('Category', THEME_NAME),
									'type'=>'dropdown',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level'),
									'value' => $t->helper->get_terms_array('video_category'),
								);
$options['fetch_video']['channel'] = array(
									'label'=>__('Channel', THEME_NAME),
									'type'=>'dropdown',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level'),
									'value' => $t->helper->get_terms_array('video_channel', true),
								);
$options['fetch_video']['playlist'] = array(
									'label'=>__('Playlist', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => $t->helper->get_terms_array('video_playlist', true),
								);
$options['fetch_video']['safety'] = array(
									'label'=>__('Safety', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('on' => __('On', THEME_NAME), 'off' => __('Off', THEME_NAME)),
								);
$options['fetch_video']['privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);
$options['fetch_video']['views'] = array(
									'label'=>__('Views', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_video']['author'] = array(
									'label'=>__('Author', THEME_NAME),
									'type'=>'hidden',
									'std'=>get_current_user_id(),
								);
$options['fetch_video']['rating'] = array(
									'label'=>__('Rating', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_video']['duration'] = array(
									'label'=>__('Duration', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_video']['hd'] = array(
									'label'=>__('High Definition', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_video']['post_type'] = array(
									'label'=>__('POST Type', THEME_NAME),
									'type'=>'hidden',
								);


$options['add_playlist']['title'] = array(
									'label'=>__('Title', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
									'settings' => array('heading' => __('Add / Edit Playlist', THEME_NAME), 'button_text' => __('Submit Playlist', THEME_NAME)),
								);
$options['add_playlist']['description'] = array(
									'label'=>__('Description', THEME_NAME),
									'type'=>'textarea',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['add_playlist']['image'] = array(
									'label'=>__('Playlist Image', THEME_NAME),
									'type'=>'file',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['add_playlist']['privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);
$options['add_channel']['title'] = array(
									'label'=>__('Title', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
									'settings' => array('heading' => __('Add / Edit Channel', THEME_NAME), 'button_text' => __('Submit Channel', THEME_NAME)),
								);
$options['add_channel']['description'] = array(
									'label'=>__('Description', THEME_NAME),
									'type'=>'textarea',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['add_channel']['image'] = array(
									'label'=>__('Playlist Image', THEME_NAME),
									'type'=>'file',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['add_channel']['privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);

$options['add_album']['title'] = array(
									'label'=>__('Title', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
									'settings' => array('heading' => __('Add / Edit Album', THEME_NAME), 'button_text' => __('Submit Album', THEME_NAME)),
								);
$options['add_album']['description'] = array(
									'label'=>__('Description', THEME_NAME),
									'type'=>'textarea',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['add_album']['image'] = array(
									'label'=>__('Album Image', THEME_NAME),
									'type'=>'file',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['add_album']['privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);

$options['user_profile']['ID'] = array(
									'label'=>__('User ID', THEME_NAME),
									'type'=>'hidden',
								);
$options['user_profile']['user_login'] = array(
									'label'=>__('Username', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level', 'disabled' => 'disabled'),

								);
$options['user_profile']['first_name'] = array(
									'label'=>__('First Name', THEME_NAME),
									'type'=>'input',
									'validation' => 'alpha_space',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['last_name'] = array(
									'label'=>__('Last Name', THEME_NAME),
									'type'=>'input',
									'validation' => 'alpha_space',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['nickname'] = array(
									'label'=>__('Nickname', THEME_NAME),
									'type'=>'input',
									'validation' => 'alpha_space',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['display_name'] = array(
								'label'=>__('Display Name', THEME_NAME),
								'type'=>'input',
								'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['user_email'] = array(
									'label'=>__('Email', THEME_NAME),
									'type'=>'input',
									'validation' => 'valid_email',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['avatar'] = array(
									'label'=>__('Avatar', THEME_NAME),
									'type'=>'file',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['user_url'] = array(
									'label'=>__('Website', THEME_NAME),
									'type'=>'input',
									'validation' => 'valid_url',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['aim'] = array(
									'label'=>__('AIM', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['yim'] = array(
									'label'=>__('Yahoo IM', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['jabber'] = array(
									'label'=>__('Jabber / Google Talk', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['facebook'] = array(
									'label'=>__('Facebook Link', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
								);
$options['user_profile']['twitter'] = array(
									'label'=>__('Twitter Link', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level'),
								);
								
$options['user_profile']['description'] = array(
									'label'=>__('Biographical Info', THEME_NAME),
									'type'=>'textarea',
									'validation' =>'strip_tags',
									'attrs'=>array('class'=>'input-block-level'),

								);
$options['user_profile']['pass1'] = array(
									'label'=>__('New Password', THEME_NAME),
									'type'=>'password',
									'attrs'=>array('class'=>'input-block-level'),

								);
$options['user_profile']['pass2'] = array(
									'label'=>__('Repeat Password', THEME_NAME),
									'type'=>'password',
									'attrs'=>array('class'=>'input-block-level'),

								);

$options['add_audio']['link'] = array(
									'label'=>__('Link', THEME_NAME),
									'type'=>'input',
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Enter URL of Audio', THEME_NAME)),
									'validation'=>'required',
									'separator' => true,
									'settings' => array('heading' => __('Upload Audio', THEME_NAME), 'button_text' => __('Add Audio', THEME_NAME)),
								);
$options['add_audio']['embed_code'] = array(
									'label'=>__('Embed Code', THEME_NAME),
									'type'=>'textarea',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Or Enter Embed Code of the Audio', THEME_NAME)),
									'validation'=>'required',
								);

$options['fetch_audio']['source'] = array(
									'label'=>__('Source', THEME_NAME),
									'type'=>'hidden',
									'icon' => true,
									'settings' => array('heading' => __('Upload Audio Detail', THEME_NAME), 'button_text' => __('Submit Audio', THEME_NAME)),
								);
$options['fetch_audio']['id'] = array(
									'label'=>__('ID', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_audio']['title'] = array(
										'label'=>__('Title', THEME_NAME),
										'type'=>'input',
										'info'=>__('Give the title to audio', THEME_NAME),
										'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Or Enter Embed Code of the Audio', THEME_NAME)),
										'validation'=>'required',
								);
$options['fetch_audio']['desc'] = array(
									'label'=>__('Description', THEME_NAME),
									'type'=>'textarea',
									'info'=>__('Add description to audio', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Or Enter Embed Code of the Audio', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_audio']['tags'] = array(
									'label'=>__('Tags', THEME_NAME),
									'type'=>'input',
									'info'=>__('Insert comma separated tags', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Inser comma separated tags', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_audio']['image'] = array(
									'label'=>__('Custom Image', THEME_NAME),
									'type'=>'file',
									'attrs'=>array('class'=>'input-block-level', 'placeholder'=>__('Enter Custom Image', THEME_NAME)),
									'validation'=>'required',
								);
$options['fetch_audio']['category'] = array(
									'label'=>__('Category', THEME_NAME),
									'type'=>'dropdown',
									'info'=>__('Or Insert the embed code', THEME_NAME),
									'attrs'=>array('class'=>'input-block-level'),
									'value' => $t->helper->get_terms_array('audio_category'),
								);
$options['fetch_audio']['album'] = array(
									'label'=>__('Album', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => $t->helper->get_terms_array('audio_album', true),
								);
$options['fetch_audio']['safety'] = array(
									'label'=>__('Safety', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('on' => __('On', THEME_NAME), 'off' => __('Off', THEME_NAME)),
								);
$options['fetch_audio']['privacy'] = array(
									'label'=>__('Privacy', THEME_NAME),
									'type'=>'dropdown',
									'attrs'=>array('class'=>'input-block-level'),
									'value' => array('public' => __('Public', THEME_NAME), 'private' => __('Private', THEME_NAME), 'unlisted' => __('Unlisted', THEME_NAME)),
								);
$options['fetch_audio']['views'] = array(
									'label'=>__('Views', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_audio']['author'] = array(
									'label'=>__('Author', THEME_NAME),
									'type'=>'hidden',
									'std'=>get_current_user_id(),
								);
$options['fetch_audio']['rating'] = array(
									'label'=>__('Rating', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_audio']['duration'] = array(
									'label'=>__('Duration', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_audio']['hd'] = array(
									'label'=>__('High Definition', THEME_NAME),
									'type'=>'hidden',
								);
$options['fetch_audio']['post_type'] = array(
									'label'=>__('POST Type', THEME_NAME),
									'type'=>'hidden',
								);


