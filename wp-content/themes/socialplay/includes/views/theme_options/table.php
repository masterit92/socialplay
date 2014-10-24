<?php if( ! defined('ABSPATH')) exit('restricted access');

//printr($html);

?>
<?php include('tabs.php');?>

<?php if(!isset($_GET['ajax']) && $_GET['page'] == 'fw_theme_options'): ?>

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
                            <table class="table social">
                                <thead>
                                    <tr>
                                        <th>Social Network</th>
                                        <th>Link</th>
                                        <th>Sharing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php foreach( $html as $k => $v ): ?>
                                    <tr>
                                        <td><?php echo $v['label']; ?></td>
                                        <td>
                                            <?php echo $v['element']; ?>
                                            <span class="social-icon <?php echo $k; ?>"></span>
                                        </td>
                                        <td>
                                            <?php echo $v['status']['element']; ?>
                                        </td>
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

