<div id="right">
	<form method="post">
	<table style="width: 100%" class="TR_MOUSEOVER">
		<caption>
			<span class="fl ml20">添加用户组</span>
		</caption>
		<tfoot>
			<tr>
				<th colspan="3" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
			</tr>
		</tfoot>

		<tbody>
			<tr>
				<td class="align_right" width="100">用户组名称: </td>
				<td class="align-left"><input name="name" value="<?php if(set_value('name')) echo set_value('name'); elseif(!empty($data['groupname'])) echo $data['groupname']; ?>" /></td>
				<td><div class="red"><?php echo form_error('name');?></div></td>
			</tr>
		</tbody>
	</table>
	</form>
</div>