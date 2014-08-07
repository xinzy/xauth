
<?php // var_export($sitesettings)?>
<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">用户设置</span>
	</caption>
	<tfoot>
		<tr>
			<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td class="align_right" width="120">注册设置: </td>
			<td>
				<input type="radio" name="st[register_valid]" value="1" <?php echo $sitesettings['register_valid']==1?'checked="checked"':''?> /> 需审核
				<input type="radio" name="st[register_valid]" value="0" <?php echo $sitesettings['register_valid']==0?'checked="checked"':''?> /> 无需审核
				<input type="radio" name="st[register_valid]" value="-1" <?php echo $sitesettings['register_valid']==-1?'checked="checked"':''?> /> 关闭注册
			</td>
		</tr>
		<tr>
			<td class="align_right">回复需审核: </td>
			<td>
				<input type="radio" name="st[post_valid]" value="1" <?php echo $sitesettings['post_valid']==1?'checked="checked"':''?> /> 是
				<input type="radio" name="st[post_valid]" value="0" <?php echo $sitesettings['post_valid']==0?'checked="checked"':''?> /> 否
			</td>
		</tr>
		<tr>
			<td class="align_right">回复词语过滤: </td>
			<td>
				<input type="radio" name="st[post_filter]" value="0" <?php echo $sitesettings['post_filter']==0?'checked="checked"':''?> /> 是
				<input type="radio" name="st[post_filter]" value="1" <?php echo $sitesettings['post_filter']==1?'checked="checked"':''?> /> 否
			</td>
		</tr>
		<tr>
			<td class="align_right">小说审核: </td>
			<td>
				<input type="radio" name="st[write_valid]" value="0" <?php echo $sitesettings['write_valid']==0?'checked="checked"':''?> /> 是
				<input type="radio" name="st[write_valid]" value="1" <?php echo $sitesettings['write_valid']==1?'checked="checked"':''?> /> 否
			</td>
		</tr>
		<tr>
			<td class="align_right">小说词语过滤: </td>
			<td>
				<input type="radio" name="st[wite_valid]" value="0" <?php echo $sitesettings['wite_valid']==0?'checked="checked"':''?> /> 是
				<input type="radio" name="st[wite_valid]" value="1" <?php echo $sitesettings['wite_valid']==1?'checked="checked"':''?> /> 否
			</td>
		</tr>
	</tbody>
</table>
</form>

