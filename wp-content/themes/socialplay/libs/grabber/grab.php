<?php

class FW_Grab
{
	var $res = '';
	var $source = '';
	
	function __construct($url = '')
	{
		include_once('config/config.php');
		$this->config = $options;
		$this->data = $data;
		if( !$url ) return;
		
		if( $this->type = $this->get_type( $url ) )
		{
			/** Don't add videos in audio section */
			if(isset($_REQUEST['add_audio']) && $this->config[$this->type]['source'] != 'soundcloud') return;
			elseif(isset($_REQUEST['add_video']) && $this->config[$this->type]['source'] == 'soundcloud') return;
			
			$this->source = $this->config[$this->type]['source'];
			$this->res = $this->get_data(rtrim($url, '/'), $this->type);
		}
	}
	
	/**
	 @param		string		$url	Link to grab the videos
	 @param		string		$type	URL type (video, playlist, channel)
	 @param		interger 	$number	Number of videos to grab
	 */
	function result($source = null, $data = null)
	{
		$data = (!$data) ? $this->res : $data;

		$source = (!$source) ? $this->source : $source;
		
		$array = (array)kvalue($this->data, $source);

		if( method_exists($this, $source) )	return $this->$source($data, $array);
		else return false;
	}
	
	function youtube($data, $array)
	{
		$return = array();
		$items = kvalue( kvalue($data, 'data'), 'items');

		if($items)
		{
			foreach( $items as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if($key == 'thumb') $return[$k][$key] = (isset($v->thumbnail)) ? $v->thumbnail->{$val} : '';
					elseif($key == 'source') $return[$k][$key] = 'youtube';
					else $return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
				}
			}
			
		}elseif(is_object( $data ))
		{
			$data = kvalue( $data, 'data');
			
			foreach($array as $key => $val )
			{
				if( $key == 'thumb') $return[$key] = kvalue( kvalue($data, 'thumbnail'), $val, $val );
				else $return[$key] = kvalue( $data, $val, $val );
			}
			
			$return = array($return);
		}

		return $return;
	}
	
	function vimeo($data, $array)
	{
		$return = array();
		foreach( $data as $k => $v )
		{
			foreach($array as $key => $val )
			{
				if($key == 'source') $return[$k][$key] = 'vimeo';
				else $return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
			}
		}
		
		return $return;
	}
	
	function ustream($data, $array)
	{
		$return = array();
		$results = kvalue($data, 'results');
		if( is_array( $results ) )
		{
			foreach( (array)kvalue($data, 'results') as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if( $key == 'thumb' ) $return[$k][$key] = kvalue( kvalue( $v, $val), 'medium');
					else $return[$k][$key] = kvalue( $v, $val, $val );
				}
			}
		}
		else
		{
			foreach($array as $key => $val )
			{
				if( $key == 'thumb' ) $return[0][$key] = kvalue( kvalue( $results, $val), 'medium');
				else $return[0][$key] = kvalue( $results, $val, $val );
			}
		}

		return $return;
	}
	
	function soundcloud($data, $array)
	{
		$return = array();
		if(is_array( $data ) )
		{
			foreach( $data as $k => $v )
			{
				foreach($array as $key => $val )
				{
					$return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
					
					if($key == 'thumb')
					{
						$return[$k][$key] = ($return[$k]['thumb']) ? $return[$k]['thumb'] : $v->{$val}->user->avatar_url;
						$return[$k][$key] = str_replace('-large.', '-t500x500.', $return[$k][$key]);
					}elseif($key == 'source') $return[$k][$key] = 'soundcloud';
				}
			}
			
		}else
		{
			foreach($array as $key => $val)
			{
				$return[0][$key] = (isset($data->{$val})) ? $data->{$val} : '';
				
				if($key == 'thumb')
				{
					$return[0][$key] = ($return[0]['thumb']) ? $return[0]['thumb'] : $data->user->avatar_url;
					$return[0][$key] = str_replace('-large.', '-t500x500.', $return[0][$key]);
				}
				elseif($key == 'source') $return[0][$key] = 'soundcloud';
			}
		}

		return $return;
	}
	
	function dailymotion($data, $array)
	{
		$return = array();

		if( is_array( kvalue( $data, 'list') ) )
		{
			foreach( kvalue( $data, 'list') as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if( $key == 'tags' ) $return[$k][$key] = (is_array( kvalue( $v, $val))) ? implode(', ', kvalue($v, $val)) : kvalue($v, $val);
					elseif($key == 'source') $return[$k][$key] = 'dailymotion';
					else $return[$k][$key] = (isset($v->{$val})) ? $v->{$val} : '';
				}
			}
		}
		else
		{
			foreach($array as $key => $val )
			{
				if( $key == 'tags' ) $return[0][$key] = (is_array( kvalue( $data, $val))) ? implode(', ', kvalue($data, $val)) : kvalue($data, $val);
				elseif($key == 'source') $return[0][$key] = 'dailymotion';
				else $return[0][$key] = (isset($data->{$val})) ? $data->{$val} : '';
			}
		}
		
		return $return;
	}
	
	function blip($data, $array)
	{
		$return = array();
		
		$post = kvalue( kvalue( $data, '0'), 'Post');

		if( ! $post )
		{
			foreach( $data as $k => $v )
			{
				foreach($array as $key => $val )
				{
					if( $key == 'tags' && is_array ( kvalue($v, $val) ) ) {
						$tags = '';
						foreach( (array)kvalue($v, $val) as $tag)
							$tags .= kvalue( $tag, 'name').', ';
						
						$return[$k][$key] = $tags;
					}
					elseif( $key == 'duration' ) $return[$k][$key] = kvalue( kvalue($v, 'media'), $val);
					else $return[$k][$key] = kvalue( $v, $val, $val );
				}
			}
		}
		else
		{
			foreach($array as $key => $val )
			{
				if( $key == 'tags' && is_array ( kvalue($post, $val) ) ) {
					$tags = '';
					foreach( (array)kvalue($post, $val) as $tag)
						$tags .= kvalue( $tag, 'name').', ';
					
					$return[0][$key] = $tags;
				}
				elseif( $key == 'duration' ) $return[0][$key] = kvalue( kvalue($post, 'media'), $val);
				else $return[0][$key] = kvalue( $post, $val, $val );
			}
		}

		return $return;
	}
	
	function metacafe($data, $array)
	{
		$return = array();
		
		$post = kvalue( kvalue( $data, '0'), 'Post');
		$item = kvalue( kvalue($data, 'channel'), 'item');

		if( $item )
		{
			$item = is_array( $item ) ? $item : array($item);
			foreach( $item as $k => $v )
			{
				$v = (array)$v;
				foreach($array as $key => $val )
				{
					$return[$k][$key] = kvalue( $v, $val, $val );
				}
			}
		}
		else return false;

		return $return;
	}
	
	function get_id($url, $type)
	{
		if( isset($this->config[$type]) )
		{
			preg_match($this->config[$type]['regex'], $url, $matches);

			if( isset($matches[1]) ) return $matches[1];
			else return false;
		}
	}
	
	function fetch_links($id, $type)
	{
		if( isset($this->config[$type]) ) return str_replace('{id}', $id, $this->config[$type]['link']);
		else return false;		
	}
	
	function get_data($url, $type)
	{	
		$id = $this->get_id($url, $type);
		$link = '';

		if($id) $link = $this->fetch_links($id, $type);
		else return false;

		if( ! function_exists('curl_init') )
		{
			$data = @file_get_contents($link);
		}else{
			$ch = curl_init($link);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686; rv:20.0) Gecko/20121230 Firefox/20.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$data = curl_exec($ch);
			curl_close($ch);
		}

		return $this->json_decodes($data);
	}
	
	function get_type($url)
	{
		foreach($this->config as $k => $v )
		{
			preg_match($v['type'], $url, $matches);
			if( $matches ) return $k;
		}
		
		return false;
	}
	
	function json_decodes($str)
	{
		$data = json_decode( (string)$str);
		if( !$data )
		{
			$replace = str_replace( "blip_ws_results([[{", "[{", $str, $replaced_count );
            if($replaced_count > 0) {
                $replace = str_replace( "]);", "", $replace );
            }
            else
            {
                $replace = str_replace( "blip_ws_results([{", "[{", $replace, $replaced_count );
                $replace = str_replace( "]);", "]", $replace );
            }

			$data = json_decode( (string)$replace, true);
			if( !$data ) $data = simplexml_load_string($str);
		}
		
		return $data;
	}
}