<?php $icon = (isset($icon)) ? $icon : 'icon32';?>

<div id="icon-themes" class="<?php echo $icon;?>"><br></div>
	<h2 class="nav-tab-wrapper">
	<?php foreach($tabs as $tab=>$name):?>
		<?php $class = ($tab == $current) ? ' nav-tab-active' : '';?>
		<a class="nav-tab<?php echo $class;?>" href="?page=<?php echo $page;?>&tab=<?php echo $tab;?>"><?php echo $name;?></a>
	<?php endforeach;?>
	</h2>