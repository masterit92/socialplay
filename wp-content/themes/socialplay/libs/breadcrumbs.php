<?php if ( ! defined('ABSPATH')) exit('restricted access');

function get_the_breadcrumb()
{
	global $_webnukes, $wp_query;
	$queried_object = get_queried_object();
	//printr($queried_object);exit;
	$breadcrumb = '';

	if ( (! is_home() && !is_front_page() ) || $wp_query->is_posts_page )
	{
		$breadcrumb .= '<li><a href="'.home_url().'">'.__('Home', THEME_NAME).'</a></li>';
		
		/** If category or single post */
		if(is_category())
		{
			$breadcrumb .= '<li><a href="'.get_category_link(get_query_var('cat')).'">'.single_cat_title('', FALSE).'</a></li>';
		}
		elseif(is_tax())
		{
			$breadcrumb .= '<li><a href="'.get_term_link($queried_object).'">'.$queried_object->name.'</a></li>';
		}
		elseif(is_page()) /** If WP pages */
		{
			global $post;
			if($post->post_parent)
			{
                $anc = get_post_ancestors($post->ID);
                foreach($anc as $ancestor)
				{
                    $breadcrumb .= '<li>'.get_the_title($ancestor).'</li>';
                }
				
            }else $breadcrumb .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
		}
		elseif (is_singular())
		{
			if($category = wp_get_object_terms(get_the_ID(), array('category', 'video_category', 'audio_category')))
			{
				if( !is_wp_error($category) )
				{
					$breadcrumb .= '<li><a href="'.get_term_link(kvalue($category, '0')).'">'.kvalue( kvalue($category, '0'), 'name').'</a></li>';
					$breadcrumb .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
				}
			}
		}
		elseif(is_tag()) $breadcrumb .= '<li><a href="'.get_term_link($queried_object).'">'.single_tag_title('', FALSE).'</a></li>'; /**If tag template*/
		elseif(is_day()) $breadcrumb .= '<li><a href="">'.__('Archive for ', THEME_NAME).get_the_time('F jS, Y').'</a></li>'; /** If daily Archives */
		elseif(is_month()) $breadcrumb .= '<li><a href="' .get_month_link(get_the_time('Y'), get_the_time('m')) .'">'.__('Archive for ', THEME_NAME).get_the_time('F, Y').'</a></li>'; /** If montly Archives */
		elseif(is_year()) $breadcrumb .= '<li><a href="'.get_year_link(get_the_time('Y')).'">'.__('Archive for ', THEME_NAME).get_the_time('Y').'</a></li>'; /** If year Archives */
		elseif(is_author()) $breadcrumb .= '<li><a href="'. esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) .'">'.__('Archive for ', THEME_NAME).get_the_author().'</a></li>'; /** If author Archives */
		elseif(is_search()) $breadcrumb .= '<li>'.__('Search Results for ', THEME_NAME).get_search_query().'</li>'; /** if search template */
		elseif( isset($wp_query->is_posts_page)  ) $breadcrumb .= '<li><a href="'.get_permalink(kvalue( $queried_object, 'ID')).'">'.get_the_title(kvalue( $queried_object, 'ID')).'</a></li>'; /** Default value */
		else $breadcrumb .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>'; /** Default value */
	}

	return '<ul class="breadcrumb">'.$breadcrumb.'</ul>';
}