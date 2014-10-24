<?php if( ! defined('ABSPATH')) exit('restricted access');

//printr($html);

?>
<?php include('tabs.php');?>

<?php if(!isset($_GET['ajax']) && kvalue($_GET, 'page') == 'fw_theme_options'): ?>

	<?php include(FW_ADMIN_SKIN.'shared/header.php');?>
	
	<div class="tb-contents-section">
		<article class="tb-builder-content">
 <?php endif; ?>
			<section class="tb-block" id="infoBox">
					<div class="tb-heading">
                      <a class="close close-block" href="#"></a>
                      <h3><?php echo slugtotext($name);?></h3>
                    </div>
					<div class="tb-detail">
                        <form method="post" id="fw_form" action="<?php echo $_SERVER['REQUEST_URI'];?>" class="form-horizontal">
                            <div class="system_msg"><?php $this->_webnukes->msg->display();?></div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"><?php _e('Create Sidebar', THEME_NAME); ?></label>
                                <div class="controls" id="sidebar_field">
                                    <input type="text" name="DYNAMIC[create_sidebar][]" placeholder="<?php _e('Enter Sidebar Name', THEME_NAME); ?>" class="input-small">
                                    <a class="btn btn-red FW_create_sidebar" href="javascript:void(0);" style="margin-bottom:10px;">
										<?php _e('Create Sidebar', THEME_NAME); ?>
                                    </a>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?php _e('Name', THEME_NAME); ?></th>
                                        <th><?php _e('Action', THEME_NAME); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php $sidebars = array_filter((array)kvalue( kvalue($html, 'DYNAMIC'), 'create_sidebar'));
									foreach( $sidebars as $index => $sidebar): ?>
                                    <tr>
                                        <td class="value"><?php echo $sidebar; ?></td>
                                        <td class="action-icon"><a class="icon-config FW_edit_sidebar" href="#"></a><a class="icon-trash FW_del_sdiebar" href="#"></a></td>
                                        <input type="hidden" name="DYNAMIC[create_sidebar][]" value="<?php echo $sidebar; ?>" >
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <hr class="sp">
                            <div class="control-group">
                                <div class="controls align-right">
                                    <a href="#" class="btn btn-green fw_submit preventDefault"><?php _e('Save', THEME_NAME); ?></a>
                                    <a id="fw_cancel" href="<?php echo $_SERVER['REQUEST_URI'];?>" class="btn btn-green"><?php _e('Cancel', THEME_NAME); ?></a>
                                <?php /*
                                    echo form_input(array('type'=>'submit','value'=>'save','class'=>'btn','id'=>'fw_submit'));
                                    echo form_input(array('type'=>'reset','value'=>'cancel','class'=>'btn','id'=>'fw_submit')); */?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- tb-detail ends --> 
                </section>
<?php if(!isset($_GET['ajax']) && $_GET['page'] == 'fw_theme_options'): ?>
		</article>
		<?php echo $side_links;?>
	</div>
</div>
<div style="display: none;" class="overlay-wrap"></div>
<div class="loading-icon"></div>
<?php endif; ?>

