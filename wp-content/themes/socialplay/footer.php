<?php $nukes = &$GLOBALS['_webnukes'];
$settings = $nukes->fw_get_settings('sub_footer_settings'); ?>

<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <?php if( is_active_sidebar('footer-sidebar1') ) dynamic_sidebar('footer-sidebar1'); ?>
            </div>
        </div>
    </div>
    <!-- footer ends -->
	
    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="span6"> 
                	<?php if( $logo = kvalue($settings, 'logo')): ?>
                    	<a class="logo" href="<?php echo home_url(); ?>">
                        	<img alt="<?php bloginfo('name'); ?>" src="<?php echo $logo; ?>">
						</a>
                    <?php endif; ?>
                    <?php if( $text = kvalue( $settings, 'copyrights') ) echo $text; ?>
                </div>
                <?php if( fw_social_networks() ): ?>
                    <div class="span6">
                        <div class="social-links">
                            <ul>
                                <li class="follow"><?php _e('Follow Us', THEME_NAME); ?> </li>
                                <?php echo fw_social_networks(); ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- bottom ends --> 

</footer>
<?php wp_footer(); ?>

</body>
</html>