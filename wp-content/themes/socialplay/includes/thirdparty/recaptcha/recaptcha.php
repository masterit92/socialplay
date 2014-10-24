<?php


require_once (dirname(__FILE__) . '/recaptchalib.php');

$recaptcha_opt = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');

#doesn't need to be secret, just shouldn't be used by any other code.
define ("RECAPTCHA_WP_HASH_COMMENT", "b7e0638d85f5d7f3694f68e944136d62");

function recaptcha_wp_hash_comment ($id)
{
	global $recaptcha_opt;
	$recaptcha_opt = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');
	if (function_exists('wp_hash') ) {
		return wp_hash (RECAPTCHA_WP_HASH_COMMENT . $id);
	} else {
		return md5 (RECAPTCHA_WP_HASH_COMMENT . $recaptcha_opt['recaptcha_p_key'] . $id);
	}
}

function recaptcha_wp_get_html ($recaptcha_error) 
{
	global $recaptcha_opt;
	$recaptcha_opt = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');

	return recaptcha_get_html($recaptcha_opt['recaptcha_key'], $recaptcha_error);
}

/**
 *  Embeds the reCAPTCHA widget into the comment form.
 * 
 */	
function recaptcha_comment_form() {
	//modify the comment form for the reCAPTCHA widget 
	
	$recaptcha_js_opts = "
		<script type='text/javascript'>
			var RecaptchaOptions = { theme : 'red', tabindex : 5 };
		</script>";

	$comment_string = '
		<div id="recaptcha-submit-btn-area"></div> 
		<script type="text/javascript">
			var sub = document.getElementById("submit");
			sub.parentNode.removeChild(sub);
			document.getElementById("recaptcha-submit-btn-area").appendChild (sub);
			document.getElementById("submit").tabIndex = 6;
			if ( typeof _recaptcha_wordpress_savedcomment != "undefined") {
				document.getElementById("comment").value = _recaptcha_wordpress_savedcomment;
			}
		</script>
		<noscript>
		 <style type="text/css">#submit {display:none;}</style>
		 <input name="submit" type="submit" id="submit-alt" tabindex="6" value="Submit Comment"/> 
		</noscript>';
		
	echo $recaptcha_js_opts .  recaptcha_wp_get_html(kvalue($_GET, 'rerror')) . $comment_string;
}

if( $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings', 'comment_status') == 'on' )
add_action( 'comment_form', 'recaptcha_comment_form' );


function recaptcha_wp_show_captcha_for_comment () {
        global $user_ID;
        return true;
}


$recaptcha_saved_error = '';

/**
 * Checks if the reCAPTCHA guess was correct and sets an error session variable if not
 * @param array $comment_data
 * @return array $comment_data
 */
function recaptcha_wp_check_comment($comment_data) {

	global $user_ID, $recaptcha_opt;
	global $recaptcha_saved_error;

	$status = $GLOBALS['_webnukes']->fw_get_settings('sub_general_settings');
	
	if( kvalue( $status, 'comment_status' ) != 'on' ) return $comment_data;
	
	$recaptcha_opt = $GLOBALS['_webnukes']->fw_get_settings('sub_APIs');
	if (recaptcha_wp_show_captcha_for_comment ()) {
		if ( $comment_data['comment_type'] == '' ) { // Do not check trackbacks/pingbacks
			
			$challenge = kvalue( $_POST, 'recaptcha_challenge_field');
			$response = kvalue( $_POST, 'recaptcha_response_field');
		
			$recaptcha_response = recaptcha_check_answer ($recaptcha_opt ['recaptcha_p_key'], $_SERVER['REMOTE_ADDR'], $challenge, $response);
			//printr($recaptcha_response);
			if ($recaptcha_response->is_valid) {
				return $comment_data;
			}
			else {
				$recaptcha_saved_error = $recaptcha_response->error;
				$_SESSION['captcha_error'] = $recaptcha_saved_error;
				add_filter('pre_comment_approved', create_function('$a', 'return \'spam\';'));
				return $comment_data;
			}
		}
	}
	return $comment_data;
}


/*
 * If the reCAPTCHA guess was incorrect from recaptcha_wp_check_comment, then redirect back to the comment form 
 * @param string $location
 * @param OBJECT $comment
 * @return string $location
 */
function recaptcha_wp_relative_redirect($location, $comment) {
	global $recaptcha_saved_error;
	if($recaptcha_saved_error != '') { 
		//replace the '#comment-' chars on the end of $location with '#commentform'.

		$location = substr($location, 0,strrpos($location, '#')) .
			((strrpos($location, "?") === false) ? "?" : "&") .
			'rcommentid=' . $comment->comment_ID . 
			'&rerror=' . $recaptcha_saved_error .
			'&rchash=' . recaptcha_wp_hash_comment ($comment->comment_ID) . 
			'#commentform';

	}
	return $location;
}


/*
 * If the reCAPTCHA guess was incorrect from recaptcha_wp_check_comment, then insert their saved comment text
 * back in the comment form. 
 * @param boolean $approved
 * @return boolean $approved
 */
function recaptcha_wp_saved_comment() {
	if ( !is_single() && !is_page() )
		return;

	if ( kvalue($_GET, 'rcommentid') && kvalue($_GET, 'rchash') == recaptcha_wp_hash_comment ( kvalue($_GET, 'rcommentid'))) {
		$comment = get_comment($_GET['rcommentid']);
		echo "<script type='text/javascript'>
			var _recaptcha_wordpress_savedcomment =  '" . rawurlencode(utf8_decode($comment->comment_content)) ."';
			_recaptcha_wordpress_savedcomment = unescape(_recaptcha_wordpress_savedcomment);
		      </script>";
		wp_delete_comment($comment->comment_ID);	
	}

}



function recaptcha_wp_blog_domain ()
{
	$uri = parse_url(get_option('siteurl'));
	return $uri['host'];
}

add_filter('wp_head', 'recaptcha_wp_saved_comment',0);
add_filter('preprocess_comment', 'recaptcha_wp_check_comment',0);
add_filter('comment_post_redirect', 'recaptcha_wp_relative_redirect',0,2);



?>
