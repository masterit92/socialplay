<?php if ( ! defined('ABSPATH')) exit('restricted access');

//SUB, DYNAMIC
$options = array();

//settings
//string section, preview boleen, 
//section_heading = HEADING OF THE SECTION
/**
	DYNAMIC SECTION
	Use the section only in very first element
	Use tab to create the sub tabs
*/

//GENERAL SETTINGS

$options['general_settings']['SUB']['color_and_style']['style'] = array(
												'label' =>__('Style', THEME_NAME),
												'type' =>'colorbox',
												'info' => __( 'Select the default color scheme' , THEME_NAME),
												'validation'=>'',
												'std' => '#c50b0b',
												'attrs'=>array('class' => 'nuke-color-field'),
											);
$options['general_settings']['SUB']['color_and_style']['color_scheme'] = array(
												'label' =>__('Color Scheme', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Select the default color scheme' , THEME_NAME),
												'validation'=>'',
												'std' => 'light',
												'value'=>array('light'=>__('Light', THEME_NAME), 'dark'=>__('Dark', THEME_NAME)),
												'attrs'=>array('class' => 'input-block-level'),
											);											
$options['general_settings']['SUB']['color_and_style']['heading_color'] = array(
												'label' =>__('Heading Color', THEME_NAME),
												'type' =>'colorbox',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'nuke-color-field'),
											);
$options['general_settings']['SUB']['color_and_style']['text_color'] = array(
												'label' =>__('Content Text Color', THEME_NAME),
												'type' =>'colorbox',
												'info' => __( 'Choose the color for normal text' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'nuke-color-field'),
											);

$options['general_settings']['SUB']['general_settings']['admin_access'] = array(
												'label' =>__('Restrict wp-admin Access', THEME_NAME),
												'type' =>'switch',
												'info' => __('Whether user can access wp-admin area or not?', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['menu_status'] = array(
												'label' =>__('Menu Toggle', THEME_NAME),
												'type' =>'switch',
												'info' => __('Whether the menu has toggle funtionality or not', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['post_views'] = array(
												'label' =>__('Video / Audio Views', THEME_NAME),
												'type' =>'switch',
												'info' => __('Show or hide views of videos / audios on detail page', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['play_video'] = array(
												'label' =>__('Play Audio / Video', THEME_NAME),
												'type' =>'switch',
												'info' => __('Play audios / videos on the same page or false to play on the detail page', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['featured_image'] = array(
												'label' =>__('Featured Image', THEME_NAME),
												'type' =>'switch',
												'info' => __('Show featured image on video detail page', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['default_status'] = array(
												'label' =>__('Default Audio / Video Status', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('What should be the default audios / videos status uploaded by users', THEME_NAME),
												'validation'=>'',
												'std' => 'pending',
												'value'=> array('pending'=>__('Pending for Review', THEME_NAME), 'publish'=>__('Published', THEME_NAME), 'draft'=>__('Draft', THEME_NAME), ),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['page_builder'] = array(
												'label' =>__('Page Builder Status', THEME_NAME),
												'type' =>'switch',
												'info' => __('Enable / Disable page builder', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['comment_status'] = array(
												'label' =>__('Comment Captcha', THEME_NAME),
												'type' =>'switch',
												'info' => __('Show / Hide captcha on Comment Form', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['tag_status'] = array(
												'label' =>__('Tag Status', THEME_NAME),
												'type' =>'switch',
												'info' => __('Show / Hide tags on blog detail page', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['smtp_status'] = array(
												'label' =>__('SMTP Status', THEME_NAME),
												'type' =>'switch',
												'info' => __('Enable whether mail should be shouted thruogh smtp', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('Mail Settings', THEME_NAME))
											);
$options['general_settings']['SUB']['general_settings']['smtp_host'] = array(
												'label' =>__('SMTP Host', THEME_NAME),
												'type' =>'input',
												'info' => __('Enter smtp host', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level', 'placeholder'=>'smtp.google.com'),
											);
$options['general_settings']['SUB']['general_settings']['smtp_port'] = array(
												'label' =>__('SMTP Port', THEME_NAME),
												'type' =>'input',
												'info' => __('Enter smtp port', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-small', 'placeholder'=>'587'),
											);
$options['general_settings']['SUB']['general_settings']['smtp_auth'] = array(
												'label' =>__('Authentication', THEME_NAME),
												'type' =>'switch',
												'info' => __('Whether smtp required authentication', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['smtp_ssl'] = array(
												'label' =>__('SMTP SSL', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Whether smtp required authentication', THEME_NAME),
												'validation'=>'',
												'value'=>array(''=>'None', 'ssl'=>'SSL', 'tls'=> 'TLS', 'starttls'=>'StartTLS'),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['smtp_username'] = array(
												'label' =>__('SMTP Username', THEME_NAME),
												'type' =>'input',
												'info' => __('Enter smtp username', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['general_settings']['smtp_password'] = array(
												'label' =>__('SMTP Password', THEME_NAME),
												'type' =>'input',
												'info' => __('Enter smtp password', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['background']['type'] = array(
												'label' =>__('Background Type', THEME_NAME),
												'type' =>'radio',
												'info' => '',
												'validation'=>'',
												'value' => array('image'=>'Image', 'pattern' => 'Pattern'),
												'attrs'=>array('class' => ''),
											);
$options['general_settings']['SUB']['background']['bg_image'] = array(
												'label' =>__('Background Image', THEME_NAME),
												'type' =>'image',
												'info' => '',
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'Upload Background Image', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);
$options['general_settings']['SUB']['background']['login_bg_image'] = array(
												'label' =>__('Login Page Background Image', THEME_NAME),
												'type' =>'image',
												'info' => '',
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'Upload Login page Background Image', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);
$options['general_settings']['SUB']['background']['signup_bg_image'] = array(
												'label' =>__('Singup Page Background Image', THEME_NAME),
												'type' =>'image',
												'info' => '',
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'Upload Signup Page Background Image', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);
$options['general_settings']['SUB']['background']['patterns'] = array(
												'label' =>__('Patterns', THEME_NAME),
												'type' =>'patterns',
												'info' => '',
												'validation'=>'',
												'value' => array('', 'pattern-2', 'pattern-3', 'pattern-4', 'pattern-5', 'pattern-6', 'pattern-7', 'pattern-8', 'pattern-9', 'pattern-10', 'pattern-11'),
												'attrs'=>array('class' => ''),
											);
$options['general_settings']['SUB']['background']['position'] = array(
												'label' =>__('Background Position', THEME_NAME),
												'type' =>'radio',
												'info' => '',
												'validation'=>'',
												'value' => array('left'=>'Left', 'right' => 'Right', 'center' => 'Center'),
												'attrs'=>array('class' => ''),
											);
$options['general_settings']['SUB']['background']['repeat'] = array(
												'label' =>__('Background Repeat', THEME_NAME),
												'type' =>'radio',
												'info' => '',
												'validation'=>'',
												'value' => array('no-repeat'=>'No Repeat', 'repeat' => 'Tile', 'repeat-x' => 'Tile Horizontally', 'repeat-y' => 'Tile Vertically'),
												'attrs'=>array('class' => ''),
											);
$options['general_settings']['SUB']['background']['attachment'] = array(
												'label' =>__('Background Attachment', THEME_NAME),
												'type' =>'radio',
												'info' => '',
												'validation'=>'',
												'value' => array('scroll'=>'Scroll', 'fixed' => 'Fixed'),
												'attrs'=>array('class' => ''),
											);
$options['general_settings']['SUB']['background']['color'] = array(
												'label' =>__('Background Color', THEME_NAME),
												'type' =>'colorbox',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'nuke-color-field'),
											);
$options['general_settings']['SUB']['logo']['logo'] = array(
												'label' =>__('Logo', THEME_NAME),
												'type' =>'image',
												'info' => '',
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'Upload logo', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);
/*$options['general_settings']['SUB']['logo']['slogan'] = array(
												'label' =>__('Slogan', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'required',
												'attrs'=>array('placeholder'=>'Enter slogan', 'class' => 'input-block-level'),
											);*/

											
$options['general_settings']['SUB']['header_settings']['favicon'] = array(
												'label' =>__('Favicon', THEME_NAME),
												'type' =>'image',
												'info' => __( 'Enter the .ico file URL or use upload option, <a href="http://en.wikipedia.org/wiki/Favicon" target="_blank"><strong>what is favicon?</strong></a>.' , THEME_NAME),
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'Upload Favicon', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);
$options['general_settings']['SUB']['header_settings']['ticker_status'] = array(
												'label' =>__('Status', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Enable / Disbale to hide and show news ticker' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings' => array('section_heading'=> __('News Ticker', THEME_NAME)),
											);
$options['general_settings']['SUB']['header_settings']['ticker_number'] = array(
												'label' =>__('Number', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Enter the number of news to show' , THEME_NAME),
												'validation'=>'required',
												'std' => 5,
												'attrs'=>array('class' => 'input-small'),
											);
$options['general_settings']['SUB']['header_settings']['ticker_category'] = array(
												'label' =>__('Category', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Choose a category to show news from' , THEME_NAME),
												'validation'=>'',
												'value' => fw_get_categories(array('hide_empty'=>false)),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['header_settings']['css_js'] = array(
												'label' =>__('Header CSS/JS', THEME_NAME),
												'type' =>'textarea',
												'info' => '',
												'validation'=>'stripslashes',
												'attrs'=>array('placeholder'=>'Header CSS/JS', 'class' => 'input-block-level'),
												'settings' => array('section_heading' => __('Header Script / Styles', THEME_NAME)),
											);
$options['general_settings']['SUB']['footer_settings']['logo'] = array(
												'label' =>__('Footer Logo', THEME_NAME),
												'type' =>'image',
												'info' => '',
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'upload Footer logo', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);
$options['general_settings']['SUB']['footer_settings']['copyrights'] = array(
												'label' =>__('Copyrights', THEME_NAME),
												'type' =>'textarea',
												'info' => '',
												'validation'=>'stripslashes',
												'attrs'=>array('placeholder'=>'Enter copyright text', 'class' => 'input-block-level'),
											);
/*$options['general_settings']['SUB']['footer_settings']['powered'] = array(
												'label' =>__('Powered By', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('placeholder'=>'Enter powered by text', 'class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['footer_settings']['icon'] = array(
												'label' =>__('Powered By Icon', THEME_NAME),
												'type' =>'image',
												'info' => '',
												'validation'=>'valid_url',
												'attrs'=>array('placeholder'=>'Upload powered by icon', 'class' => 'input-block-level'),
												'settings'=>array('preview'=>true),
											);*/

$options['general_settings']['SUB']['footer_settings']['analytics'] = array(
												'label' =>__('Analytics Code', THEME_NAME),
												'type' =>'textarea',
												'info' => __( 'Insert the analytics code or custom scripts to include in footer', THEME_NAME ),
												'validation'=>'stripslashes',
												'attrs'=>array('placeholder'=>'Enter tracking code here', 'class' => 'input-block-level'),
											);
/*$options['general_settings']['SUB']['captcha_settings']['status'] = array(
												'label' =>__('Captcha', THEME_NAME),
												'type' =>'switch',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);*/
$options['general_settings']['SUB']['APIs']['twitter_key'] = array(
												'label' =>__('Consumer Key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the twitter consumer key to fetch data' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('Twitter API Information')),
											);
$options['general_settings']['SUB']['APIs']['twitter_secret'] = array(
												'label' =>__('Consumer Secret', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the twitter consumer secret' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['APIs']['twitter_token'] = array(
												'label' =>__('Token', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the twitter token' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['APIs']['twitter_t_secret'] = array(
												'label' =>__('Twitter Token Secret', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the twitter token secret' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['APIs']['recaptcha_key'] = array(
												'label' =>__('Recaptcha Public Key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the recaptcha Public key' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('Recapctha Settings')),
											);
$options['general_settings']['SUB']['APIs']['recaptcha_p_key'] = array(
												'label' =>__('Recaptcha Private Key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the recaptcha Private key' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['APIs']['facebook_api_key'] = array(
												'label' =>__('Facebook Apps API key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'To Allow user Facebook login Create new App at <a href="http://developers.facebook.com/apps/">Facebook Apps</a>, Insert API key of that app here.' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('Facebook API key')),
											);
$options['general_settings']['SUB']['APIs']['facebook_channel_url'] = array(
												'label' =>__('Channel URL', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert channel URL you have given in facebook app' , THEME_NAME),
												'validation'=>'valid_url',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['general_settings']['SUB']['APIs']['ustream_api_key'] = array(
												'label' =>__('uStream API key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the uStream API key to fetch data' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('uStream API key')),
											);
$options['general_settings']['SUB']['APIs']['soundcloud_api_key'] = array(
												'label' =>__('SoundCloud API key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the SoundCloud API key to fetch data' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('SoundCloud API key')),
											);
											
$options['general_settings']['SUB']['APIs']['youtube_playlist'] = array(
												'label' =>__('Youtube Playlist limit', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Change the number to fetch number of playlist videos from youtube.' , THEME_NAME),
												'validation'=>'required',
												'std' => 10,
												'attrs'=>array('class' => 'input-small'),
												'settings'=>array('section_heading'=>__('Youtube Api Settings')),
											);

$options['general_settings']['SUB']['APIs']['youtube_channel'] = array(
												'label' =>__('Youtube Channel limit', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Change the number to fetch number of channel videos from youtube.' , THEME_NAME),
												'validation'=>'required',
												'std' => 10,
												'attrs'=>array('class' => 'input-small'),
											);
									
$options['home_page_settings']['SUB']['home_page_slider']['status'] = array(
												'label' =>__('Show Slider', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Switch it on if you want to show slider at home page. If you switch it off it will not show slider at home page.' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['home_page_slider']['revslider'] = array(
												'label' =>__('Choose Slider', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Select the slider to show at homepage' , THEME_NAME),
												'validation'=>'',
												'std' => 5,
												'value' => fw_get_rev_sliders(),
												'attrs'=>array('class' => 'input-small'),
											);
/*$options['home_page_settings']['SUB']['home_page_slider']['shortcode'] = array(
												'label' =>__('Slider Shortcode', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Please enter the Slider Short Code like "customslides", you can use this shortcode in the editor' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);*/
$options['home_page_settings']['SUB']['message_box']['msg_status'] = array(
												'label' =>__('Status', THEME_NAME),
												'type' =>'switch',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
												'settings'=>array('section_heading'=>__('Homepage Message Settings', THEME_NAME)),
											);
$options['home_page_settings']['SUB']['message_box']['msg_button'] = array(
												'label' =>__('Signup Button', THEME_NAME),
												'type' =>'switch',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['message_box']['msg_heading'] = array(
												'label' =>__('Heading', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['message_box']['msg_text'] = array(
												'label' =>__('Message Text', THEME_NAME),
												'type' =>'textarea',
												'info' => '',
												'validation'=>'stripslashes',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['video_settings']['show_filter'] = array(
												'label' =>__('Show Filters', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show or hide the video filters on homepage.' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['video_settings']['video_columns'] = array(
												'label' =>__('No. of Columns', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Select columns view for homepage videos/audios.' , THEME_NAME),
												'validation'=>'',
												'value' => array('two'=>__('Two Columns', THEME_NAME), 'four'=>__('Four Columns', THEME_NAME), 'random'=>__('Random', THEME_NAME), ),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['video_settings']['video_sortby'] = array(
												'label' =>__('Video/Audio Sortby', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Select sortby of videos/audios at home page.' , THEME_NAME),
												'validation'=>'',
												'value' => array('ID'=>'ID', 'title'=>__('Title', THEME_NAME),'duration'=>__('Duration', THEME_NAME), 'most_views'=>__('Most Views', THEME_NAME), 'source'=>__('Source', THEME_NAME), 'most_rated'=>__('Most Rated', THEME_NAME) ),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['home_page_settings']['SUB']['video_settings']['video_order'] = array(
												'label' =>__('Video/Audio Order', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Select order of videos/audios at home page.' , THEME_NAME),
												'validation'=>'',
												'value' => array('asc'=>__('Ascending', THEME_NAME),'desc'=>__('Descending', THEME_NAME)),
												'attrs'=>array('class' => 'input-block-level'),
											);
											
$options['home_page_settings']['SUB']['video_settings']['show'] = array(
												'label' =>__('Show Audios/Videos', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Select Audios/Videos to show at home page.' , THEME_NAME),
												'validation'=>'',
												'value' => array('videos'=>__('Videos', THEME_NAME),'audios'=>__('Audios', THEME_NAME), 'both'=>__('Both', THEME_NAME)),
												'attrs'=>array('class' => 'input-block-level'),
											);

$options['home_page_settings']['SUB']['video_settings']['number'] = array(
												'label' =>__('No. of Videos/Audios', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'select number of videos/audios to show on homepage' , THEME_NAME),
												'validation'=>'',
												'value' => array_combine( range(10, 100, 5), range(10, 100, 5)), 
												'attrs'=>array('class' => 'input-block-level'),
											);
		
											
$options['profile_settings']['SUB']['profile_settings']['def_val'] = array(
												'label' =>'',
												'type' =>'hidden',
												'info' => '',
												'validation'=>'',
												'std' => 'true',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['channel'] = array(
												'label' =>__('Channel Tab Visibility', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show / Hide channel tab on user profile page' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['playlist'] = array(
												'label' =>__('Playlist Tab Visibility', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show / Hide playlist tab on user profile page' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['video'] = array(
												'label' =>__('Video Tab Visibility', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show / Hide playlist tab on user profile page' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['album'] = array(
												'label' =>__('Album Tab Visibility', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show / Hide album tab on user profile page' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['video_upload'] = array(
												'label' =>__('Video Upload', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Allow user to upload video' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['audio_upload'] = array(
												'label' =>__('Audio Upload', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Allow user to upload audio' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['profile_settings']['SUB']['profile_settings']['audio'] = array(
												'label' =>__('Audio Tab Visibility', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show / Hide audio tab on user profile page' , THEME_NAME),
												'validation'=>'',
												'std' => 'on',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['contact_page_settings']['SUB']['contact_page_settings']['google_map_status'] = array(
												'label' =>__('Google Map Status', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Show / Hide Google map on contact page' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['contact_page_settings']['SUB']['contact_page_settings']['google_map'] = array(
												'label' =>__('Google Map Code', THEME_NAME),
												'type' =>'textarea',
												'info' => __( 'Insert google map code with &lt;ifram&gt; tag' , THEME_NAME),
												'validation'=>'stripslashes',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['contact_page_settings']['SUB']['contact_page_settings']['captcha_status'] = array(
												'label' =>__('Anti Spam', THEME_NAME),
												'type' =>'switch',
												'info' => __( 'Enable / Disable antispam captcha' , THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['contact_page_settings']['SUB']['contact_page_settings']['contact_email'] = array(
												'label' =>__('Contact Email', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert email ID where contact email should be shouted' , THEME_NAME),
												'validation'=>'valid_email',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['contact_page_settings']['SUB']['contact_page_settings']['redirect_url'] = array(
												'label' =>__('Redirect URL', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Insert the link where user should be redirected after contact form submission' , THEME_NAME),
												'validation'=>'valid_url',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['contact_page_settings']['SUB']['contact_page_settings']['success_msg'] = array(
												'label' =>__('Success Message', THEME_NAME),
												'type' =>'textarea',
												'info' => __( 'Insert custom success message for contact form submission' , THEME_NAME),
												'validation'=>'stripslashes',
												'attrs'=>array('class' => 'input-block-level'),
											);

$options['fonts']['SUB']['fonts_settings']['font_api_key'] = array(
												'label' =>__('Google Font API Key', THEME_NAME),
												'type' =>'input',
												'info' => __( 'Over 651+ Google fonts included. Please <a href="javascript:void(0);" id="google_font_update">Click Here</a> to update latest fonts from google' , THEME_NAME),
												'validation'=>'',
												'value' => array('option1'=>'option 1','option2'=>'option 2','option3'=>'option 3',),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['fonts']['SUB']['fonts_settings']['font_family'] = array(
												'label' =>__('Font Familly', THEME_NAME),
												'type' =>'dropdown',
												'info' => __( 'Over 651+ Google fonts included' , THEME_NAME),
												'validation'=>'',
												'value' => google_fonts_array(),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['fonts']['SUB']['fonts_settings']['content_family'] = array(
												'label' =>__('Content Font Familly', THEME_NAME),
												'type' =>'dropdown',
												'info' => '',
												'validation'=>'',
												'value' => google_fonts_array(),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['fonts']['SUB']['fonts_settings']['h1'] = array(
												'label' =>__('H1 Font Size', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['fonts']['SUB']['fonts_settings']['h2'] = array(
												'label' =>__('H2 Font Size', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['fonts']['SUB']['fonts_settings']['h3'] = array(
												'label' =>__('H3 Font Size', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['fonts']['SUB']['fonts_settings']['h4'] = array(
												'label' =>__('H4 Font Size', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['fonts']['SUB']['fonts_settings']['h5'] = array(
												'label' =>__('H5 Font Size', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['fonts']['SUB']['fonts_settings']['body'] = array(
												'label' =>__('Body Content Font Size', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['sidebars']['SUB']['sidebars']['sidebar'] = array(
												'label' =>__('Create Sidebar', THEME_NAME),
												'type' =>'input',
												'info' => '',
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);

$options['sidebars']['SUB']['sidebars_settings']['post_category'] = array(
												'label' =>__('Post Category', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose sidebar for Post Category page', THEME_NAME),
												'validation'=>'',
												'value' => fw_sidebars_array(),
												'attrs'=>array('class' => 'input-small'),
											);
$options['sidebars']['SUB']['sidebars_settings']['post_tag'] = array(
												'label' =>__('Post Tag', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose sidebar for Post Tag page', THEME_NAME),
												'validation'=>'',
												'value' => fw_sidebars_array(),
												'attrs'=>array('class' => 'input-small'),
											);
$options['sidebars']['SUB']['sidebars_settings']['post_author'] = array(
												'label' =>__('Post Author', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose sidebar for Post Author page', THEME_NAME),
												'validation'=>'',
												'value' => fw_sidebars_array(),
												'attrs'=>array('class' => 'input-small'),
											);
$options['sidebars']['SUB']['sidebars_settings']['post_archive'] = array(
												'label' =>__('Post Archive', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose sidebar for Post Archive page', THEME_NAME),
												'validation'=>'',
												'value' => fw_sidebars_array(),
												'attrs'=>array('class' => 'input-small'),
											);

$options['sidebars']['SUB']['sidebars_settings']['video_single'] = array(
												'label' =>__('Video Detail', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose sidebar for video detail page', THEME_NAME),
												'validation'=>'',
												'value' => fw_sidebars_array(),
												'attrs'=>array('class' => 'input-small'),
											);
$options['sidebars']['SUB']['sidebars_settings']['audio_single'] = array(
												'label' =>__('Audio Detail', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose sidebar for audio detail page', THEME_NAME),
												'validation'=>'',
												'value' => fw_sidebars_array(),
												'attrs'=>array('class' => 'input-small'),
											);

$options['slider_settings']['SUB']['superslides']['pagination'] = array(
												'label' =>__('Slider Pagination', THEME_NAME),
												'type' =>'switch',
												'info' => __('If true, slider nav buttons will be included', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['slider_settings']['SUB']['superslides']['speed'] = array(
												'label' =>__('Speed', THEME_NAME),
												'type' =>'input',
												'info' => __('Enter the slider slides speed in miliseconds or enter "normal"', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);
$options['slider_settings']['SUB']['superslides']['effect'] = array(
												'label' =>__('Animation Effects', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Choose the animation effect', THEME_NAME),
												'validation'=>'',
												'value' => array('linear'=>'Linear', 'easeInOutCubic' => 'EaseInOutCubic' ),
												'attrs'=>array('class' => 'input-small'),
											);
$options['slider_settings']['SUB']['superslides']['scrollable'] = array(
												'label' =>__('Scrollable', THEME_NAME),
												'type' =>'switch',
												'info' => __('Whether slider is scrollable or not', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-small'),
											);

$options['language']['SUB']['choose_language']['language'] = array(
												'label' =>__('Select Language', THEME_NAME),
												'type' =>'dropdown',
												'info' => __('Select the langauge which are uploaded in language directory', THEME_NAME),
												'validation'=>'',
												'value' => fw_get_languages(FW_LANG_DIR),
												'attrs'=>array('class' => 'input-block-level'),
											);
$options['language']['SUB']['upload_new_language']['language'] = array(
												'label' =>__('Upload Language', THEME_NAME),
												'type' =>'file',
												'info' => __('Upload .mo language file', THEME_NAME),
												'validation'=>'callback_ext[mo]',
												'attrs'=>array('class' => 'input-block-level', 'id'=>'file_upload'),
												'settings' => array('directory'=>get_template_directory().'/languages'),
											);

$options['social_networking']['SUB']['social_networking']['SOCIAL']['social'] = array(
												'label' =>__('Upload Language', THEME_NAME),
												'type' =>'social',
												'info' => __('Upload .mo language file', THEME_NAME),
												'validation'=>'',
												'attrs'=>array('class' => 'input-block-level', 'id'=>'file_upload'),
												'settings' => array(),
											);







