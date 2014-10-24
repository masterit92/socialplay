<?php

//TODO:UPDATE THE DYNAMIC FIELDS NAME AND ADD DYNAMIC[COUNTER]
function dynamic_head($data, $counter, $title)
{
	$counter = (is_numeric($counter) && $counter <= 9) ? '0'.$counter : $counter;
	
	return '
		<article>
			<div class="head collapsed" data-toggle="collapse" data-target="#row-one-'.$counter.'">
				<span class="number">'.$counter.'</span>
				<div class="title">'.$title.'</div>
				<div class="row-controls">
					<ul>
						 <li class="first-ctrl"><span><i class="icon-locked"></span></i></li>
						 <li><span><i class="icon-move"></span></i></li>
						 <li><span><i class="icon-create"></span></i></li>
						 <li><span><i class="icon-close"></span></i></li>
					</ul>
				</div>
			</div>
			<div id="row-one-'.$counter.'" class="collapse df-data">
				<div class="content"><div class="locked"></div><div class="msg"></div>'.$data.'<div class="control-group ctrls"></div></div>
			</div>
		</article>';
}


function dynamic_settings($settings, $titles, $is_sample = false)
{
	$data = '';
	foreach($settings as $k=>$v)
	{
		$counter = ($is_sample) ? '${counter}' : $k + 1;
		$data .= dynamic_head(read_settings($v, 'subtab'.$k.'-', $titles), $counter, $titles[$k]);
	}

	return ($is_sample) ? $data : '<div id="dynamic_fields">'.$data.'</div>';
}

function read_settings($settings, $prefix = 'tab-', &$titles)
{
	//$tabs = (count($settings) > 1) ? array_keys($settings) : array(); /** Check if we have tabs information */
	$tabs = array();
	$content_html = '';
	$sample_data = '';
	$active = ' active';
	foreach($settings as $k=>$v) /** Start reading settings */
	{
		//printr($v);
		//$content[$k] = '';
		$content = '';
		
		//TODO:MAKE SLUG
		$tabs[] = '<li class="'.$active.'"><a data-toggle="tab" href="#'.$prefix.$k.'">'.slugtotext($k).'</a></li>';
		if(isset($v['DYNAMIC']))
		{
			//$content[$k] .= dynamic_settings($v['DYNAMIC']);
			$data = '<div class="control-group"><h2 class="section-heading">Manage Dynamic Rows</h2><a href="#" class="btn btn-primary preventDefault" id="add_new_row"><i class="icon-plus icon-white"></i>Add New Row</a><span class="align-right"><a href="#" id="expand-all" class="preventDefault">Expand all</a> | <a href="#" id="collapse-all" class="preventDefault">Collapse all</a></span></div>';
			$v['DYNAMIC'] = $data.dynamic_settings($v['DYNAMIC'], $titles);
		}
		
		if(isset($v['DYNAMIC_SAMPLE_DATA']))
		{
			$sample_data = '<script id="dynamic_data_'.$k.'" type="text/x-jquery-tmpl">'.dynamic_settings($v['DYNAMIC_SAMPLE_DATA'], array(0=>'${title}'), true).'</script>';
			unset($v['DYNAMIC_SAMPLE_DATA']);
		}
		
		$content .= implode("\n", (array) $v);
		
		$content_html .= '<div id="'.$prefix.$k.'" class="tab-pane'.$active.'">'.$content.$sample_data.'</div>';
		$active = '';
	}
	
	$html = (count($tabs) > 1) ? '<ul class="nav nav-tabs">'.implode("\n", $tabs).'</ul><div class="tab-content contents">'.$content_html.'</div>' : $content_html;
	
	return $html;
}