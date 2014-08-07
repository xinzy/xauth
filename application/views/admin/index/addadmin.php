<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">添加管理员</span>
	</caption>
	<tfoot>
		<tr>
			<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td class="align_right" width="100">登录名: </td>
			<td>
				<input type="text" name="username" /> <?php echo form_error('username'); ?>
			</td>
		</tr>
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
			</td>
		</tr>
		<tr>
			<td class="align_right">管理员类型: </td>
			<td>
				<input type="radio"  name="admintype" value="A" checked="checked" />A类　
				<input type="radio"  name="admintype" value="B" />B类　
				<input type="radio"  name="admintype" value="C" />C类　
				<?php echo form_error('admintype'); ?>
			</td>
		</tr>
	</tbody>
</table>
</form>
