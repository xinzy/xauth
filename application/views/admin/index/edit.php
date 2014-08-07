<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">修改资料</span>
	</caption>
	<tfoot>
		<tr>
			<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td class="align_right" width="100">Email: </td>
			<td>
				<input type="text" name="email" /> <?php echo form_error('email'); ?>
			</td>
		</tr>
		<tr>
			<td class="align_right">密码: </td>
			<td>
				<input type="password"  name="password" /> <?php echo form_error('password'); ?>
				<span style="color: RED;">留空则不修改</span>
			</td>
		</tr>
	</tbody>
</table>
</form>
