<?php if( ! defined('ABSPATH')) exit('restricted access');

/**
 * WPnukes Admin Panel
 *
 * An open source wordpress themes options admin panel
 *
 * @package		WPnukes Apanel
 * @author		WPnukes team
 * @copyright	Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 * @license		http://wpnukes.com/admin_panel/license.html
 * @website		http://wpnukes.com
 * @since		Version 1.0
 * @filesource
 */
 
/**
 * fw_options_class
 *
 * This class handles the admin panel messages
 *
 * @package		WPnukes Apanel
 * @subpackage	Models
 * @category	Core
 * @author		WPnukes Development Team
 * @website		http://wpnukes.com
 * @path includes/core/base_class.php
 * @license Copyright 2012 © Fourthwave technologies, (PVT) Limited. - All rights reserved
 */

//@TODO: SESSION message support
class fw_messages_class
{
	protected $_webnukes = array();
	var $msgs = array();
	
	/**
	 * Constructor
	 *
	 * The constructor of message class
	 */
	 
	function __construct()
	{
		$this->_webnukes = &$GLOBALS['_webnukes']; /** Clone the fw_base_class object */
	}
	
	/**
	 * Create
	 *
	 * Create a new message on fly
	 *
	 * @access	public
	 * @param	string contain message
	 * @param	string attention|success|error|information
	 * @return	array
	 */
	 
	function create($msgs = array(), $type = 'error', $return = false)
	{
		if( ! is_array($msgs))
			$msgs = array($msgs);
		
		foreach($msgs as $msg)
			$this->msgs[] = array('msg'=>$msg, 'type'=>$type);
		
		if($return) return $this->msgs;
	}
	
	//die, attention, success, error, information
	//message = array(messages = array(message, type, return)
	/**
	 * Display Messages
	 *
	 * This method display or create the messages on fly
	 *
	 * @access	public
	 * @example	fw_messages_class::display((array) $messages)
	 * @param	string|array to create or add new messages
	 * @param	string type of message die|error|attention|success|information default is error
	 * @param	boolean include form validation errors or not
	 * @param	boolean|string return the HTML
	 * @return	void|string
	 */
	function display($msgs = array(), $type = 'error', $validation = true, $return = false)
	{
		if( ! empty($msgs)) $this->create($msgs, $type);
		
		/** Show validation messages */
		if($validation && ! empty($this->_webnukes->validation->_error_array))
		{
			$this->create($this->_webnukes->validation->_error_array);
		}
		
		//TODO: return all messages without skin
		
		/** Auto flush messages */
		if( ! $msgs = $this->flush(true)) return;
		
		/** Skin Messages */
		$html = NULL;
		foreach($msgs as $msg)
		{
			$html .= '<!-- '.slugtotext($msg['type']).' -->';
			
			$html .= '<div class="alert alert-'.$msg['type'].'">
						<button type="button" class="close" data-dismiss="alert" id="close_message"></button>
						'.$msg['msg'].'
					  </div>';
		}

		/** Message Behaviours */
		if($type == 'die') wp_die($html);		
		elseif($return) return $html;
		else echo $html;
	}
	
	function adminMessage($msgs = array(), $type = 'error', $validation = true, $autoflush = false)
	{
		if( ! empty($msgs)) $this->create($msgs, $type);
		
		/** Show validation messages */
		if($validation && ! empty($this->_webnukes->validation->_error_array))
		{
			$this->create($this->_webnukes->validation->_error_array);
		}
		
		/** Auto flush messages */
		if( ! $msgs = $this->flush(true)) return;
		
		/** Skin Messages */
		if( current_user_can('read') )
		{
			foreach($msgs as $msg)
			{
				$type = ($msg['type'] != 'error') ? 'updated' : 'error';
				echo '<div class="'.$type.'"><p>'.$msg['msg'].'</p></div>';
			}
    	}
	}
		
	function flush($return = false)
	{
		$msgs = $this->msgs;
		$this->msgs = '';
		if($return) return $msgs;
	}
}