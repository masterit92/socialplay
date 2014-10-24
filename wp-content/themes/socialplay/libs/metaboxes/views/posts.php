
<script type="text/javascript">
	jQuery(function(){
		jQuery('.form_style').jqTransform({imgPath:'<?php echo get_template_directory_uri(); ?>/includes/views/js/img/'});
	});
</script>
<div class="form-horizontal dialog-container">
    <?php foreach( $fields as $k => $v ): ?>
    <?php if( kvalue( $v, 'label')): ?>
        <div class="control-group">
            <?php echo kvalue($v, 'label'); ?>
            <div class="controls">
                <?php echo kvalue($v, 'element'); ?>
                <?php if(kvalue( $v, 'info' ) ): ?>
                    <p><?php echo kvalue($v, 'info'); ?></p>
                <?php endif; ?>
                <?php if( kvalue( $v, 'html' ) ) echo kvalue( $v, 'html' ); ?>
            </div>
            
        </div>
    <?php else: echo kvalue($v, 'element'); 
    
	endif; ?>
    <?php endforeach; ?>
</div>