<?php if( ! defined('ABSPATH')) exit('restricted access');

class fw_layout_class
{
	protected $_webnukes;
	protected $_spans;
	
	function __construct()
	{
		$this->_webnukes = $GLOBALS['_webnukes']; /** Clone the fw_base_class object */
		$this->_spans = array('1' => 'span3', '2' => 'span6', '3' => 'span9', '4' => 'span12');
	}
	
	function meta_box()
	{
		require_once(BASEPATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'layout.php');
		
		$sidebars = '';
		foreach($GLOBALS['wp_registered_sidebars'] as $k=>$v)
		{
			$sidebars[$k] = $v['name'];//'<option value="'.$k.'">'.$v['name'].'</option>';
		}
		
		$this->_webnukes->html->layout('layout/index', array('fields'=>$options, 'default_settings'=>$default_settings, 'sidebars'=> $sidebars)); /** Load the side links **/
		echo $GLOBALS['_webnukes']->html->build();
	}
	
	private function html_settings($fields, $settings)
	{
		$data = array();
		foreach($fields as $k=>$v)
		{
			$section = (!empty($v['settings']['section'])) ? $v['settings']['section'] : 'general';
			//$tab = (!empty($v['settings']['tab'])) ? $v['settings']['tab'] : 'default';
			
			if($k == 'DYNAMIC')
			{
				$first = key((array)$v);
				$section = ( ! empty($v[$first]['settings']['section'])) ? $v[$first]['settings']['section'] : 'general';
				//$data[$section][$tab][$k] = $this->_dynamic_fields($v); /** Call another function to bypass 100 times recusive function limitation */
				$data[$section][$k] = $this->_dynamic_fields($v); /** Call another function to bypass 100 times recusive function limitation */

				$data[$section]['DYNAMIC_SAMPLE_DATA'] = $this->_dynamic_fields($v, true);
			}
			else
				//$data[$section][$tab][$k] = $this->_webnukes->html->generator($k, $v, $settings);
				$data[$section][$k] = $this->_webnukes->html->generator($k, $v, $settings);
		}

		return $data;
	}
	
	function publish_page($post_id)
	{
		global $post;
		
		$layout = kvalue($_POST, 'layout');
		if( $layout ) update_post_meta($post_id, 'page_builder_data', $layout);
	}
	
	
	/** Page bulder front end output methods */
	
	function sidebar( $settings, $position = 'left' )
	{
		
		if( ! $settings ) return ;
		
		//$struct = kvalue( $settings, 'structure' );
		$sidebars = kvalue( $settings, 'sidebars');
		
		$sidebar1 = kvalue( $sidebars, 'left');
		$sidebar2 = kvalue( $sidebars, 'right');
		
		$pos = kvalue( $settings, 'structure' );

				
		if( $pos == 'col-full' ) return;
		
		switch( $position )
		{
			case 'left':
				if( $pos == 'col-left' ) $this->aside( $sidebar1);
				elseif( $pos == 'col-both' ) $this->aside( $sidebar1, 'span3' );
				elseif( $pos == 'col-left2' ){
					$this->aside( $sidebar1, 'span3' );
					$this->aside( $sidebar2, 'span3' );
				}
			break;
			
			case 'right':
				if( $pos == 'col-right' ) $this->aside( $sidebar2 );
				elseif( $pos == 'col-both' ) $this->aside( $sidebar2, 'span3' );
				elseif( $pos == 'col-right2' ){
					$this->aside( $sidebar1, 'span3' );
					$this->aside( $sidebar2, 'span3' );
				}
			break;
		}
	}
	
	function aside( $sidebar, $span = 'span4' )
	{?>
		<aside class="sidebar <?php echo $span; ?>">
		   <?php dynamic_sidebar( $sidebar ); ?> 
        </aside>
	<?php
    }
	
	
	function set_cols( $vals )
	{
		$cols = (kvalue($vals, 'cols')) ? kvalue($vals, 'cols') : 1;

		if( isset( $this->_spans[$cols] ) ) return $this->_spans[$cols];
	}
	
	function build_page($settings)
	{
		if( is_array( $settings ) )
		{
			if( isset( $settings['structure'] ) ) unset( $settings['structure'] );
			if( isset( $settings['sidebars'] ) ) unset( $settings['sidebars'] );
			
				
			foreach( $settings as  $array ){
				$name = kvalue( $array, 'id' );

				if( method_exists($this, $name ) ) {?>
					<div class="row-fluid">
						<section class="<?php echo ($name != 'heading') ? $this->set_cols($array) : '';?>">
							<?php $this->$name(kvalue($array, 'data'));?>
						</section>
					</div>
					<?php
				}
			}
				
			
		}
	}
	
	protected function slider($vals)
	{

		$args = array();
		if( $ids = kvalue( $vals, 'ids') ) $args['post__in'] = explode( ',', $ids );
		if( $show = kvalue( $vals, 'number') ) $args['showposts'] = (int)$show;
		fw_slider($args);
	}
	
	protected function gallery($vals)
	{
		$ids = kvalue( $vals, 'ids');
		$shortcode = '';
		if( $ids ) {
			$shortcode = '[gallery ids="'.$ids.'" columns="'.kvalue($vals, 'columns', 3).'"]';
		}
		echo '<div class="blog-box">'.apply_filters( 'the_content', $shortcode ).'</div>';

	}
	
	protected function content($vals)
	{
		if( $content = kvalue( $vals, 'content') )
		echo '<div class="blog-box">'.apply_filters( 'the_content', $content ).'</div>';
	}
	
	protected function videos($vals)
	{
		$t = &$GLOBALS['_wpnukes_videos'];
		$query = array('post_type' => 'wpnukes_videos');
		
		if( $number = kvalue( $vals, 'number' )) $query['showposts'] = $number;
		if( $cat = kvalue( $vals, 'category' ) ) $query['tax_query'] = array( array( 'taxonomy' => 'video_category', 'field' => 'id', 'terms' => $cat )	);
		
		$wp_query = $t->helper->get_videos($query);?>
		<div class="blog-box clearfix  no-padding">
        	<?php $settings['video_columns'] = kvalue( $vals, 'columns' ); ?>
			<?php include(get_template_directory().'/libs/home_videos.php');?>
		</div>
		<?php wp_reset_query();
	}
	
	protected function albums($vals)
	{
		$t  = $GLOBALS['_wpnukes_videos'];
		$args = array();
		if( $number = kvalue( $vals, 'number') ) $args['number'] = $number;
		
		$channel = $t->helper->get_terms_array('audio_album', false, 'object', $args);?>
        
        <div class="blog-box">
            <div class="chan-contain">
                <?php get_terms_listing( $channel ); ?>
            </div>
        </div>
        
		<?php
    }
	
	protected function playlists($vals)
	{
		$t  = $GLOBALS['_wpnukes_videos'];
		
		$args = array();
		if( $number = kvalue( $vals, 'number') ) $args['number'] = $number;
		
		$channel = $t->helper->get_terms_array('video_playlist', false, 'object', $args);?>
        
        <div class="blog-box">      
            <div class="chan-contain">
                <?php get_terms_listing( $channel ) ?>
            </div>
        </div>

		<?php
	}
	
	protected function channels($vals)
	{
		$t  = $GLOBALS['_wpnukes_videos'];
		
		$args = array();
		if( $number = kvalue( $vals, 'number') ) $args['number'] = $number;
		
		$channel = $t->helper->get_terms_array('video_channel', false, 'object', $args);?>
        
        <div class="blog-box">
            <div class="chan-contain">
                <?php get_terms_listing( $channel ) ?>
            </div>
        </div>
		<?php
	}
	
	protected function audios($vals)
	{
		global $wp_query;
		$t = &$GLOBALS['_wpnukes_videos'];
		$query = array('post_type'=>'wpnukes_audios');
		
		if( $number = kvalue( $vals, 'number' )) $query['showposts'] = $number;
		if( $cat = kvalue( $vals, 'category' ) ) $query['tax_query'] = array( array( 'taxonomy' => 'audio_category', 'field' => 'id', 'terms' => $cat )	);
		
		$wp_query = $t->helper->get_videos($query);?>
        <div class="blog-box"><?php get_template_part('libs/home_videos');?></div>
		<?php wp_reset_query();
	}
	
	protected function contactus($vals)
	{
		$settings = $GLOBALS['_webnukes']->fw_get_settings('sub_contact_page_settings');
		get_template_part('libs/contactform');
        fw_contact_form($settings);

	}
	
	protected function blog($vals)
	{
		global $wp_query;
		$args = array('post_type'=>'post');
		if( $cat = kvalue($vals, 'category')) $args['category__in'] = $cat;
		if( $num = kvalue($vals, 'number')) $args['showposts'] = $num;
		$wp_query = new WP_Query($args);
		get_template_part('libs/blog_listing');
		
	}
	
	protected function heading($vals)
	{
		$heading = kvalue( $vals, 'heading');
		$tag = kvalue($vals, 'tag', '2');
		
		if( $heading ) echo '<h'.$tag.' class="'.$this->set_cols($vals).'">'.$heading.'</h'.$tag.'>';
	}
}