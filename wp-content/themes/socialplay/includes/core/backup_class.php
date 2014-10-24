<?php if( ! defined('ABSPATH')) exit('restricted access');

class fw_backup_class
{
	var $refresh = false;
	private $_webnukes = array(), $path = '';
	
	function __construct()
	{
		$this->_webnukes = &$GLOBALS['_webnukes'];

		$this->path = BASEPATH.DIRECTORY_SEPARATOR.'backup';
		//$this->export();
	}
	
	function export()
	{
		//$this->menu_import();
		//$this->user_import();
		//$this->wp_option_import();
		$this->sidebar_export();
		$this->theme_options_export();
		$this->revslider_export();
	}
	
	function import()
	{
		//$this->menu_import();
		//$this->user_import();
		//$this->wp_option_import();
		$this->sidebar_import();
		$this->theme_options_import();
		$this->revslider_import();
	}
	
	function menu_import($file = '')
	{
		global $wpdb;
		$file = ($file) ? $file : 'menu_settings';
		
		$content = $this->read_file($this->newdir($this->path.DIRECTORY_SEPARATOR.'menu_settings').$file);

		if(!$content) return;
		
		$array = array('_menu_item_status', '_menu_item_sidebar', '_nuke_menu_columns', '_nuke_menu_devide', '_nuke_menu_align', '_megamenu_auto_adjust');
		
		$IDs = $wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'nav_menu_item' AND post_status = 'publish' order by menu_order");

		if($IDs && is_array($IDs))
		{
			foreach((array)$content as $k => $v)
			{
				if(is_array($v))
				{
					foreach($v as $key => $val)
					{
						if(in_array($key, $array))
						{
							if(isset($IDs[$k])) update_post_meta($IDs[$k]->ID, $key, $val[0]);
						}
					}
				}
			}
		}
	}
	
	function menu_export($file = '')
	{
		global $wpdb;
		$file = ($file) ? $file : 'menu_settings';
		
		$IDs = $wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'nav_menu_item' AND post_status = 'publish'");
		$menus_array = array();
		foreach($IDs as $ID)
		{
			$menus_array[] = get_post_meta($ID->ID);
		}
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'menu_settings');
		$fp = $this->file_open($dir.$file);
		$this->write_file($fp, $this->encrypt($menus_array));
		$this->file_close($fp);
	}
	
	
	function revslider_export( $file = '' )
	{
		global $wpdb;
		$file = ($file) ? $file : 'default_settings';
		
		$data = array();
		
		$sliders = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."revslider_sliders", ARRAY_A);
		$slides = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."revslider_slides", ARRAY_A);
		foreach( $sliders as $k=>$s )
		{
			$slider_id = kvalue( $s, 'id' );
			if( isset( $s['id'] ) ) unset( $s['id']);
			$data['slider'][$k] = $s;
			foreach( $slides as $ss )
			{
				if( isset( $ss['id'] ) ) unset( $ss['id']);
				
				if( $slider_id == kvalue( $ss, 'slider_id' ) )
				$data['slider'][$k]['slides'][] = $ss ;
			}
		}
		
		//return $data;
		
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'revslider_options');
		$fp = $this->file_open($dir.$file);
		$this->write_file($fp, $this->encrypt($data));
		$this->file_close($fp);
	}
	
	function revslider_import( $file = '' )
	{
		global $wpdb;
		
		$file = ($file) ? $file : 'default_settings';
		
		$settings = $this->read_file($this->newdir($this->path.DIRECTORY_SEPARATOR.'revslider_options').$file);
		
		foreach( (array)$settings['slider'] as $v )
		{
			$slider_id = '';
			
			$res = $wpdb->get_results( "SELECT * FROM `".$wpdb->prefix."revslider_sliders` WHERE `title` LIKE '%".$v['title']."%'");
			
			if( $res ) continue;
			
			$slides = kvalue( $v, 'slides' );
			if( $slides ) unset( $v['slides'] );
			
			$wpdb->insert( $wpdb->prefix."revslider_sliders", $v );
			$slider_id = $wpdb->insert_id;
			
			if( $slider_id ) {
				foreach( $slides as $key => $val )
				{
					if( $val ){
						$val['slider_id'] = $slider_id;
						$wpdb->insert( $wpdb->prefix."revslider_slides", $val );
					}
				}
			}
			
		}
	}
	
	
	function user_export($file = '')
	{
		$file = ($file) ? $file : 'user_settings';
		$newusers = array();
		$users = new WP_User_Query(array('number'=>10, 'role'=>'doctors'));
		foreach($users->results as $user)
		{
			$meta = array();
			foreach(get_user_meta($user->ID) as $k=>$v)
						$meta[$k] = $this->pseudo($v[0]);
			$newusers[] = array('data'=>(array)$user->data, 'meta'=>$meta);
		}
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'user_settings');
		$fp = $this->file_open($dir.$file);
		$this->write_file($fp, $this->encrypt($newusers));
		$this->file_close($fp);
	}
	
	
	function user_import($file = '')
	{
		global $wpdb;
		$file = ($file) ? $file : 'user_settings';
		
		$content = $this->read_file($this->newdir($this->path.DIRECTORY_SEPARATOR.'user_settings').$file);

		if(!$content) return;
		
		foreach((array)$content as $user)
		{
			if(username_exists($user['data']['user_login'])) continue;
			
			$user['data']['user_pass'] = wp_generate_password();
			//$user['data']['user_status'] = 1;
			unset($user['data']['ID']);
			$user_id = wp_insert_user($user['data']);
			$wpdb->update($wpdb->prefix.'users', array('user_status' => 1), array('ID' => $user_id));
			
			foreach($user['meta'] as $k=>$v) 
			{
				update_user_meta($user_id, $k, $this->replace_pseudo($v));
			}
		}
		return;
	}

	
	function db_export($file = '')
	{
		global $wpdb;
		
		$file = ($file) ? $file : 'db.sql';
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'db');
		
		$fp = $this->file_open($dir.$file);
		
		$tables = $wpdb->tables;
		
		foreach($tables as $table)
		{
			$query = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$table, ARRAY_A);
			$create_table = $wpdb->get_results("SHOW CREATE TABLE ".$wpdb->prefix.$table, ARRAY_A);
			
			$output = ' DROP TABLE IF EXISTS `'.$wpdb->prefix.$table.'`'.";\n";
			$output .= ' '.$create_table[0]['Create Table'].";\n";
			
			if($create_table && isset($create_table[0]['Create Table']) && $query)
			{
				$output .= 'INSERT INTO '.$wpdb->prefix.$table.' VALUES ';
				
				$insert = '';
				$c = 0;
				foreach($query as $q)
				{
					$values = '';
					foreach($q as $val)
					{
						$values[] = (!$val) ? "''" : "'".$wpdb->escape($val)."'";
					}
					
					$insert[] = '('.implode(',', $values).')';
					
					if($c >= 30)
					{
						$this->write_file($fp, $output.implode(',', (array)$insert)."\n");
						$output = '';
						$insert = array();
					}
					
					$c++;
				}
				
				$this->write_file($fp, $output.implode(',', (array)$insert).";\n");
			}
			
			$this->write_file($fp, $output);
		}
		
		$this->file_close($fp);
	}
	
	function wp_option_import($file = '')
	{
		$file = ($file) ? $file : 'option_settings';
		
		$data = $this->read_file($this->newdir($this->path.DIRECTORY_SEPARATOR.'option_settings').$file);
		
		if(!$data) return;
		
		foreach((array)$data as $k=>$v)
		{
			$v = $this->replace_pseudo($v);
			update_option($k, $v);
		}
	}
	
	function wp_option_export($keys = array(), $file = '')
	{
		$file = ($file) ? $file : 'option_settings';
		
		$data = array();
		foreach($keys as $k)
		{
			if($option = get_option($k))
			{
				$data[$k] = $this->pseudo($option);
			}
		}
		
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'option_settings');
		$fp = $this->file_open($dir.$file);
		$this->write_file($fp, $this->encrypt($data));
		$this->file_close($fp);
	}
	
	function theme_options_import($file = '')
	{
		global $wpdb;
		$file = ($file) ? $file : 'default_settings';
		
		$data = $this->read_file($this->newdir($this->path.DIRECTORY_SEPARATOR.'theme_options').$file);

		foreach($data as $k=>$v)
		{
			$v = $this->replace_pseudo($v);
			$prefix = THEME_PREFIX.$k;
			
			update_option($prefix, $v);
		}
		
		/** Update the front page */
		$front_page = get_page_by_title('Home');
		$blog_page = get_page_by_title('Blog');
		if($front_page){
			if(get_option('show_on_front') != 'page' && !get_option('page_on_front')) 
			{
				update_option('show_on_front', 'page');
				update_option('page_on_front', $front_page->ID);
				update_option('page_for_posts', $blog_page->ID);
			}
		}
		update_option('posts_per_page', 6);
		
		$res = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."terms WHERE ".$wpdb->prefix."terms.slug = 'main-menu' OR ".$wpdb->prefix."terms.slug = 'top-menu'");

		if($res){
			$nav_menu = array('');

			$nav_menu['nav_menu_locations']['main_menu'] = $res[0]->term_id;
			$nav_menu['nav_menu_locations']['top_menu'] = $res[1]->term_id;

			update_option('theme_mods_'.basename(get_template_directory()), $nav_menu);

		}
		
		$users = $wpdb->get_results("SELECT ID, user_login FROM ".$wpdb->prefix."users");
		$user_meta = array('facebook'=>'http://www.facebook.com/{uid}', 'twitter'=>'http://twitter.com/{uid}', 'avatar' => get_template_directory_uri().'/images/author-{id}.jpg');
		
		foreach( $users as $k => $v)
		{
			foreach( $user_meta as $key => $val ) update_user_meta(kvalue($v, 'ID'), $key, str_replace(array('{id}', '{uid}'), array($k, 'wpnukes'), $val));
			if( $k == 2 ) break;
		}
		
	}
	
	function theme_options_export($file = '')
	{
		$file = ($file) ? $file : 'default_settings';
		
		$data = array();
		
		$options = $this->_webnukes->config->get();
		foreach($options as $k=>$v)
		{
			if(key($v) == 'SUB')
			{
				foreach($v['SUB'] as $vk=>$vv)
				{
					$option_name = 'sub_'.$vk;
					if($settings = $this->_webnukes->fw_get_settings($option_name))
					{
						$data[$option_name] = $this->pseudo($settings);
					}
				}
			}else
			{
				$option_name = $k;
				if($settings = $this->_webnukes->fw_get_settings($option_name))
				{
					$data[$option_name] = $this->pseudo($settings);
				}
			}
		}
		
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'theme_options');
		$fp = $this->file_open($dir.$file);
		$this->write_file($fp, $this->encrypt($data));
		$this->file_close($fp);
	}
	
	function sidebar_import($file = '')
	{
		$file = ($file) ? $file : 'default_settings';
		
		$data = $this->read_file($this->newdir($this->path.DIRECTORY_SEPARATOR.'widgets').$file);

		if( ! isset($data['settings']) || ! isset($data['sidebars'])) return;
		
		foreach($data['settings'] as $k=>$v)
		{
			update_option('widget_'.$k, $this->replace_pseudo($v));
		}
		
		/** Now update sidebars settings */
		update_option('sidebars_widgets', $data['sidebars']);
	}
	
	function sidebar_export($file = '')
	{
		$file = ($file) ? $file : 'default_settings';
		
		$settings = array();
		$sidebars = wp_get_sidebars_widgets();
		
		if(isset($sidebars['wp_inactive_widgets'])) unset($sidebars['wp_inactive_widgets']);
		
		foreach($sidebars as $name=>$widgets)
		{
			if( ! count($widgets) || $name == 'orphaned_widgets') continue;
			
			foreach($widgets as $widget)
			{
				if(preg_match('#(.*?)-(\d+)$#', $widget, $matches))
				{
					$type = $matches[1];
					$id = $matches[2];
					
					if($widget_settings = get_option('widget_'.$type))
					{
						$settings[$type][$id] = $this->pseudo($widget_settings[$id]);
					}
				}
			}
		}
		
		$dir = $this->newdir($this->path.DIRECTORY_SEPARATOR.'widgets');
		$fp = $this->file_open($dir.$file);
		$this->write_file($fp, $this->encrypt(array('settings'=>$settings, 'sidebars'=>$sidebars)));
		$this->file_close($fp);
	}
	
	function encrypt($data)
	{
		if(is_array($data)) return base64_encode(serialize($data));
		else return $data;
	}
	
	function decrypt($data)
	{
		$data = base64_decode($data);
		
		if(is_serialized($data)) return unserialize($data);
		else return $data;
	}
	
	function newdir($path, $mode = '0777', $recursive = false)
	{
		if(is_dir($path)) return $path.DIRECTORY_SEPARATOR;
		elseif( ! mkdir($path, $mode, $recursive)) wp_die( sprintf( __('System is not able to create backup directory, please create a directory named "backup" in "%s" manually and give it 0777 write permission.', THEME_NAME), $path));
		
		return $path.DIRECTORY_SEPARATOR;
	}
	
	function filename($prefix = '', $suffix = '')
	{
		return $prefix.md5(time().get_option('admin_email')).$suffix;
	}
	
	function read_file($file)
	{
		if ( ! file_exists($file)) return FALSE;

		if(function_exists('file_get_contents')) return $this->decrypt(file_get_contents($file));

		if ( ! $fp = @fopen($file, 'rb')) return FALSE;

		flock($fp, LOCK_SH);

		$data = '';
		if(filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}
		
		flock($fp, LOCK_UN);
		fclose($fp);

		return $this->decrypt($data);
	}
	
	function file_open($path, $mode = 'wb+')
	{
		if ( ! $fp = @fopen($path, $mode)) return FALSE;
		flock($fp, LOCK_EX);
		
		return $fp;
	}
	
	function write_file($fp, $data)
	{
		fwrite($fp, $this->encrypt($data));
	}
	
	function file_close($fp)
	{
		flock($fp, LOCK_UN);
		fclose($fp);
	}
		
	function pseudo($options = array())
	{
		foreach($options as $k=>$v)
		{
			if(is_array($v)) $options[$k] = $this->pseudo($v);
			elseif(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $v))
			{
				$options[$k] = '{ADMIN_EMAIL}';
			}
			else
			{
				$options[$k] = str_replace(array(THEME_URL, HOME_URL, get_option('admin_email')),array('{THEME_URL}', '{HOME_URL}', '{ADMIN_EMAIL}'),$v);
			}
		}
	
		return $options;
	}
	
	
	function replace_pseudo($options = array())
	{
		foreach((array)$options as $k=>$v)
		{
			if(is_array($v)) $options[$k] = $this->replace_pseudo($v);
			else
			{
				$options[$k] = str_replace(array('{THEME_URL}', '{HOME_URL}', '{ADMIN_EMAIL}'), array(THEME_URL, HOME_URL, get_option('admin_email')), $v);
			}
		}
	
		return $options;
	}
}