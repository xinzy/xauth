<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">修改密码</span>
	</caption>
	<tfoot>
		<tr>
			<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td class="align_right" width="100">原密码: </td>
			<td>
				<input type="password" name="password" /> <?php echo form_error('password'); ?>
			</td>
		</tr>
		<tr>
			<td class="align_right">新密码: </td>
			<td>
				<input type="password"  name="newpass" /> <?php echo form_error('newpass'); ?>
			</td>
		</tr>
		<tr>
			<td class="align_right">确认密码: </td>
			<td>
				<input type="password"  name="confirm" /> <?php echo form_error('confirm'); ?>
			</td>
		</tr>
	</tbody>
</table>
</form>
