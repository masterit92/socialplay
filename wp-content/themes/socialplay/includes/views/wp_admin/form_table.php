<table class="form-table">
	<tbody>
		<?php foreach($fields as $k=>$v):?>
		<tr valign="top">
			<th scope="row"><label for="blogname"><?php echo $v['label'];?></label></th>
			<td>
				<?php echo $v['element'];?>
				<p class="description"><?php echo $v['info'];?></p>
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>