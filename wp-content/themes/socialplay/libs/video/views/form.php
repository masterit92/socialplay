<div class="heading">
	<?php if(isset($heading)): ?>
    	<h2 class="text-red"><?php echo $heading; ?></h2>
    <?php endif; ?>
    
    <?php if( isset($icon)  ) echo $icon; ?>

</div>
<form action="" id="fws_<?php echo $key; ?>" method="post" enctype="multipart/form-data">
	<?php
    foreach( $fields as $field) 
    {
        echo $field['label'];
		echo $field['element'];
        if(kvalue($field, 'separator')) echo '<span class="or">or</span>';
		if($preview = kvalue($field, 'preview')) echo '<div class="clearfix">
													  <div class="snap">
														<a class="close" href="#"></a>
														<img alt="snap-3" src="'.$preview.'">
													  </div>
												</div>';
    }?>
    
    <input type="submit" name="<?php echo $key;?>" id="fw_<?php echo $key; ?>" value="<?php echo isset($button_text) ? $button_text : __('Submit', THEME_NAME); ?>" class="btn btn-red" />
</form>