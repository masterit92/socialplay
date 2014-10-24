<?php

$api_settings = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');

$options = array();


$options['yt_video'] = array(
							'regex' => '#((?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+)#',
							'link' => 'http://gdata.youtube.com/feeds/api/videos/{id}?v=2&alt=jsonc',
							'type' =>'/(?:youtube(?:-nocookie)?\.com\/watch\?v=[a-zA-Z0-9]+)/',
							'source' => 'youtube',
						);
						
$options['yt_playlist'] = array(
							'regex' => '/([A-Za-z0-9_-]+)$/',
							'link' => 'http://gdata.youtube.com/feeds/api/playlists/{id}?v=2&alt=jsonc&max-results='.kvalue($api_settings, 'youtube_playlist', '10'),
							'type' =>'/(?:youtube(?:-nocookie)?\.com\/playlist\?list=[A-Za-z0-9_-]+)|(?:youtube(?:-nocookie)?\.com\/view_play_list\?p=[A-Za-z0-9_-]+)/',
							'source' => 'youtube',
						);
						
$options['yt_channel'] = array(
							'regex' => '/([A-Za-z0-9_-]+)$/',
							'link' => 'http://gdata.youtube.com/feeds/api/users/{id}/uploads?v=2&alt=jsonc&max-results='.kvalue($api_settings, 'youtube_channel', '10'),
							'type' =>'/(?:youtube(?:-nocookie)?\.com\/channel\/[A-Za-z0-9_-]+)/',
							'source' => 'youtube',
						);
						
$options['yt_embed'] = array(
							'regex' => '/([A-Za-z0-9_-]+)$/',
							'link' => 'http://gdata.youtube.com/feeds/api/videos/{id}?v=2&alt=jsonc',
							'type' =>'/(?:youtube(?:-nocookie)?\.com\/embed\/[A-Za-z0-9_-]+)/',
							'source' => 'youtube',
						);
$options['vim_video'] = array(
							'regex' => '/([0-9]+)/',
							'link' => 'http://vimeo.com/api/v2/video/{id}.json',
							'type' =>'/(?:vimeo(?:-nocookie)?\.com\/[0-9]+)/',
							'source' => 'vimeo',
						);
						
$options['vim_playlist'] = array(
							'regex' => '/([0-9]+)/',
							'link' => 'http://vimeo.com/api/v2/album/{id}/videos.json',
							'type' =>'/(?:vimeo(?:-nocookie)?\.com\/album\/[0-9]+)/',
							'source' => 'vimeo',
						);
						
						
$options['vim_channel'] = array(
							'regex' => '@channels/([a-zA-Z0-9]+)/?@',
							'link' => 'http://vimeo.com/api/v2/channel/{id}/videos.json',
							'type' =>'/(?:vimeo(?:-nocookie)?\.com\/channels\/[a-zA-Z0-9]+)/',
							'source' => 'vimeo',
						);

$options['vim_embed'] = array(
							'regex' => '/([0-9]+)/',
							'link' => 'http://vimeo.com/api/v2/video/{id}.json',
							'type' =>'/(?:player.vimeo(?:-nocookie)?\.com\/video\/[a-zA-Z0-9]+)/',
							'source' => 'vimeo',
						);
						
if( $ustream_api = kvalue( $api_settings, 'ustream_api_key'))
{
	$options['ustr_video'] = array(
								'regex' => '/([0-9]+)/',
								'link' => 'http://api.ustream.tv/json/video/{id}/getInfo?key='.$ustream_api,
								'type' =>'/(?:ustream(?:-nocookie)?\.tv\/recorded\/[0-9]+)/',
								'source' => 'ustream',
							);
	$options['ustr_channel'] = array(
								'regex' => '/([a-zA-Z0-9-]+)$/',
								'link' => 'http://api.ustream.tv/json/channel/{id}/listAllVideos?key='.$ustream_api,
								'type' =>'/(?:ustream(?:-nocookie)?\.tv\/channel\/[a-zA-Z0-9]+)/',
								'source' => 'ustream',
							);
	$options['ustr_embed'] = array(
								'regex' => '/([0-9]+)/',
								'link' => 'http://api.ustream.tv/json/video/{id}/getInfo?key='.$ustream_api,
								'type' =>'/(?:ustream(?:-nocookie)?\.tv\/embed\/[0-9]+)/',
								'source' => 'ustream',
							);
	$options['ustr_playlist'] = array(
								'regex' => '/([a-zA-Z_]+$)/',
								'link' => 'http://api.ustream.tv/json/user/{id}/listAllVideos?key='.$ustream_api,
								'type' =>'/(?:ustream(?:-nocookie)?\.tv\/[a-zA-Z_]+)/',
								'source' => 'ustream',
							);
}


$options['dmotion_video'] = array(
							'regex' => '/([a-zA-Z0-9-_.#]+)$/',
							'link' => 'https://api.dailymotion.com/video/{id}?fields=title,thumbnail_url,owner%2Cdescription%2Cduration%2Cembed_html%2Cembed_url%2Cid%2Crating%2Ctags%2Cviews_total',
							'type' =>'/(?:dailymotion(?:-nocookie)?\.com\/video\/[a-zA-Z0-9-_]+)/',
							'source' => 'dailymotion',
						);

$options['dmotion_playlist'] = array(
							'regex' => '/([a-zA-Z0-9-_]+)$/',
							'link' => 'https://api.dailymotion.com/playlist/{id}/videos?fields=title,thumbnail_url,owner%2Cdescription%2Cduration%2Cembed_html%2Cembed_url%2Cid%2Crating%2Ctags%2Cviews_total',
							'type' =>'/(?:dailymotion(?:-nocookie)?\.com\/playlist\/[a-zA-Z0-9-_]+)/',
							'source' => 'dailymotion',
						);
/*
$options['dmotion_channel'] = array(
							'regex' => '/([a-zA-Z0-9-_]+)$/',
							'link' => 'https://api.dailymotion.com/channel/{id}/videos?fields=title,thumbnail_url,owner%2Cdescription%2Cduration%2Cembed_html%2Cembed_url%2Cid%2Crating%2Ctags%2Cviews_total',
							'type' =>'/(?:dailymotion(?:-nocookie)?\.com\/(.*)\/channel\/[a-zA-Z0-9-_]+)/',
							'source' => 'dailymotion',
						);
*/
$options['dmotion_embed'] = array(
							'regex' => '/([a-zA-Z0-9-_.#]+)$/',
							'link' => 'https://api.dailymotion.com/video/{id}?fields=title,thumbnail_url,owner%2Cdescription%2Cduration%2Cembed_html%2Cembed_url%2Cid%2Crating%2Ctags%2Cviews_total',
							'type' =>'/(?:dailymotion(?:-nocookie)?\.com\/embed\/video\/[a-zA-Z0-9-_]+)/',
							'source' => 'dailymotion',
						);
						
$options['blip_embed'] = array(
							'regex' => '/([a-zA-Z0-9-\.]+)$/',
							'link' => 'http://blip.tv/post/{id}?skin=json',
							'type' =>'/(?:blip(?:-nocookie)?\.tv\/play\/[a-zA-Z0-9]+)/',
							'source' => 'blip',
						);
$options['blip_video'] = array(
							'regex' => '/[a-zA-Z0-9-]+\/[a-zA-Z0-9-]+$/',
							'link' => 'http://blip.tv/{id}?skin=json',
							'type' =>'/(?:blip(?:-nocookie)?\.tv\/[a-zA-Z0-9-]+\/[a-zA-Z0-9]+)/',
							'source' => 'blip',
						);
$options['blip_channel'] = array(
							'regex' => '/([a-zA-Z0-9-]+)$/',
							'link' => 'http://blip.tv/{id}/posts?skin=json',
							'type' =>'/(?:blip(?:-nocookie)?\.tv\/[a-zA-Z0-9]+)/',
							'source' => 'blip',
						);
$options['meta_embed'] = array(
							'regex' => '/([0-9]+)/',
							'link' => 'http://metacafe.com/api/item/{id}/',
							'type' =>'/(?:metacafe(?:-nocookie)?\.com\/embed\/[0-9])/',
							'source' => 'metacafe',
						);
$options['meta_video'] = array(
							'regex' => '/([0-9]+)/',
							'link' => 'http://metacafe.com/api/item/{id}/',
							'type' =>'/(?:metacafe(?:-nocookie)?\.com\/watch\/[0-9])/',
							'source' => 'metacafe',
						);
$options['meta_channel'] = array(
							'regex' => '/[a-zA-Z0-9_]+$/',
							'link' => 'http://metacafe.com/{id}/rss.xml',
							'type' =>'/(?:metacafe(?:-nocookie)?\.com\/[a-zA-Z0-9_-]+)/',
							'source' => 'metacafe',
						);

if( $sound_cloud_key = kvalue( $api_settings, 'soundcloud_api_key') )
{
	$options['sc_embed'] = array(
								'regex' => '@/([0-9]+)@',
								'link' => 'https://api.soundcloud.com/tracks/{id}.json?consumer_key='.$sound_cloud_key,
								'type' =>'/(?:soundcloud(?:-nocookie)?\.com\/play(.*)[a-zA-Z0-9_-]+)/',
								'source' => 'soundcloud',
							);
	$options['sc_track'] = array(
								'regex' => '/([a-zA-Z0-9_]+)$/',
								'link' => 'https://api.soundcloud.com/tracks/{id}.json?consumer_key='.$sound_cloud_key,
								'type' =>'/(?:soundcloud(?:-nocookie)?\.com\/[a-zA-Z0-9_-]\/[a-zA-Z0-9_-]+)/',
								'source' => 'soundcloud',
							);
	$options['sc_tracks'] = array(
								'regex' => '/([a-zA-Z0-9_-]+)$/',
								'link' => 'https://api.soundcloud.com/tracks/{id}.json?consumer_key='.$sound_cloud_key,
								'type' =>'/(?:soundcloud(?:-nocookie)?\.com\/[a-zA-Z0-9_-]+)/',
								'source' => 'soundcloud',
							);
}


$data['youtube'] =  array('source'=>'youtube','id'=>'id','thumb'=>'hqDefault','title'=>'title','desc'=>'description','duration'=>'duration','views'=>'viewCount','author'=>'uploader','rating'=>'ratingCount','tags'=>'', 'hd' => true);
$data['vimeo'] =  array('source'=>'vimeo','id'=>'id','thumb'=>'thumbnail_large','title'=>'title','desc'=>'description','duration'=>'duration','views'=>'stats_number_of_plays','author'=>'user_name','rating'=>'stats_number_of_likes','tags'=>'tags', 'hd' => false);
$data['ustream'] =  array('source'=>'ustream','id'=>'id','thumb'=>'imageUrl','title'=>'title','desc'=>'description','duration'=>'lengthInSecond','views'=>'totalViews','author'=>'userName','rating'=>'rating','tags'=>'title', 'hd' => false);
$data['dailymotion'] =  array('source'=>'dailymotion','id'=>'id','thumb'=>'thumbnail_url','title'=>'title','desc'=>'description','duration'=>'duration','views'=>'views_total','author'=>'owner','rating'=>'rating','tags'=>'tags', 'hd' => false);
$data['blip'] =  array('source'=>'blip','id'=>'item_id','thumb'=>'thumbnailUrl','title'=>'title','desc'=>'description','duration'=>'duration','views'=>'','author'=>'userId','rating'=>'contentRating','tags'=>'tags', 'hd' => false);
$data['metacafe'] =  array('source'=>'metacafe','id'=>'id','thumb'=>'','title'=>'title','desc'=>'','duration'=>'','views'=>'','author'=>'author','rating'=>'','tags'=>'', 'hd' => false);

$data['soundcloud'] =  array('source'=>'soundcloud','id'=>'id','thumb'=>'artwork_url','title'=>'title','desc'=>'description','duration'=>'duration','views'=>'playback_count','author'=>'user_id','rating'=>'favoritings_count','tags'=>'tag_list', 'hd' => false);