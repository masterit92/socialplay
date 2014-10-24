<div class="wrap">
	<?php $this->_webnukes->msg->adminMessage();?>
	<form method="post" action="<?php echo admin_url($action); ?>">
		<?php wp_nonce_field($nonce_action, $nonce_name);?>
		<?php echo $content;?>
		
		<p class="submit">
			<input type="submit" value="<?php _e('Save Changes', THEME_NAME);?>" class="button button-primary" id="submit">
		</p>
	</form>
</div>