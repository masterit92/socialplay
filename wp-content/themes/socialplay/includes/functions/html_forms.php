<?php if ( ! defined('ABSPATH')) exit('restricted access');

function form_input($data = '', $value = '', $extra = '')
{
	$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
	return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}

function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
{
	if ( ! strpos($extra, 'multiple'))
	{
		$extra .= ' multiple="multiple"';
	}

	return form_dropdown($name, $options, $selected, $extra);
}

function form_textarea($data = '', $value = '', $extra = '')
{
	$defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '90', 'rows' => '12');

	if ( ! is_array($data) OR ! isset($data['value']))
	{
		$val = $value;
	}
	else
	{
		$val = $data['value']; 
		unset($data['value']); // textareas don't use the value attribute
	}
	
	$name = (is_array($data)) ? $data['name'] : $data;
	
	if(is_array($extra))
	{
		$newextra = $extra;
		foreach($newextra as $k=>$v)
		{
			$extra = ' '.$k.'="'.$v.'"';
		}
	}

	return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";
}

function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
{
		
	
	if ( ! is_array($selected))
	{
		$selected = array($selected);
	}
	
	// If no selected state was submitted we will attempt to set it automatically
	if (count($selected) === 0)
	{
		// If the form name appears in the $_POST array we have a winner!
		if (@isset($_POST[$name]))
		{
			$selected = array($_POST[$name]);
		}
	}
	
	if(is_array($extra))
	{
		$newextra = $extra;
		foreach($newextra as $k=>$v)
		{
			$extra = ' '.$k.'="'.$v.'"';
		}
	}

	if ($extra != '') $extra = ' '.$extra;

	$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';
	$rest = str_replace('\n','',$selected[0]);
	$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
	foreach ((array)$options as $key => $val)
	{
		$key = (string) $key;
		
		
		if (is_array($val))
		{
			$form .= '<optgroup label="'.$key.'">'."\n";

			foreach ($val as $optgroup_key => $optgroup_val)
			{
				
				$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

				$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
			}

			$form .= '</optgroup>'."\n";
		}
		else
		{
			$sel = (in_array($key , $selected)) ? ' selected="selected"' : '';
	
			$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
		}
	}

	$form .= '</select>';

	return $form;
}

function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '')
{
	$defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

	if (is_array($data) AND array_key_exists('checked', $data))
	{
		$checked = $data['checked'];

		if ($checked == FALSE)
		{
			unset($data['checked']);
		}
		else
		{
			$data['checked'] = 'checked';
		}
	}

	if ($checked == TRUE)
	{
		$defaults['checked'] = 'checked';
	}
	else
	{
		unset($defaults['checked']);
	}
	
	if(is_array($extra))
	{
		$newextra = $extra;
		foreach($newextra as $k=>$v)
		{
			$extra = ' '.$k.'="'.$v.'"';
		}
	}


	return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}


function form_radio($data = '', $value = '', $checked = FALSE, $extra = '')
{
	if ( ! is_array($data))
	{
		$data = array('name' => $data);
	}

	$data['type'] = 'radio';
	return form_checkbox($data, $value, $checked, $extra);
}



function _parse_form_attributes($attributes, $default)
{
	if (is_array($attributes))
	{
		foreach ($default as $key => $val)
		{
			if (isset($attributes[$key]))
			{
				$default[$key] = $attributes[$key];
				unset($attributes[$key]);
			}
		}

		if (count($attributes) > 0)
		{
			$default = array_merge($default, $attributes);
		}
	}

	$att = '';

	foreach ($default as $key => $val)
	{
		if ($key == 'value')
		{
			$val = form_prep($val, $default['name']);
		}

		$att .= $key . '="' . $val . '" ';
	}

	return $att;
}



function form_prep($str = '', $field_name = '')
{
	static $prepped_fields = array();

	// if the field name is an array we do this recursively
	if (is_array($str))
	{
		foreach ($str as $key => $val)
		{
			$str[$key] = form_prep($val);
		}

		return $str;
	}

	if ($str === '')
	{
		return '';
	}

	// we've already prepped a field with this name
	// @todo need to figure out a way to namespace this so
	// that we know the *exact* field and not just one with
	// the same name
	if (isset($prepped_fields[$field_name]))
	{
		return $str;
	}

	$str = htmlspecialchars($str);

	// In case htmlspecialchars misses these.
	$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

	if ($field_name != '')
	{
		$prepped_fields[$field_name] = $field_name;
	}

	return $str;
}