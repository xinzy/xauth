
<?php // var_export($sitesettings)?>
<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">站点设置</span>
	</caption>
	<tfoot>
		<tr>
			<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td class="align_right" width="100">站点名称: </td>
			<td>
				<input type="text" name="st[site_name]" value="<?php echo $sitesettings['site_name']?>" /> 
			</td>
		</tr>
		<tr>
			<td class="align_right">站点标题: </td>
			<td>
				<input type="text" name="st[site_title]" value="<?php echo $sitesettings['site_title']?>" style="width: 150px;" /> 
			</td>
		</tr>
		<tr>
			<td class="align_right">站点关键字: </td>
			<td>
				<input type="text" name="st[site_keywords]" value="<?php echo $sitesettings['site_keywords']?>" style="width: 250px;" />
			</td>
		</tr>
		<tr>
			<td class="align_right">ICP备案证书号: </td>
			<td>
				<input type="text" name="st[icp]" value="<?php echo $sitesettings['icp']?>" />
			</td>
		</tr>
		<tr>
			<td class="align_right">关闭网站: </td>
			<td>
				<input type="radio" name="st[site_closed]" value="1" <?php echo $sitesettings['site_closed']==1?'checked="checked"':''?> /> 是
				<input type="radio" name="st[site_closed]" value="0" <?php echo $sitesettings['site_closed']==0?'checked="checked"':''?> /> 否
			</td>
		</tr>
		<tr>
			<td class="align_right">关闭原因: </td>
			<td>
				<textarea name="st[close_reason]" style="width: 500px; height: 150px;"><?php echo $sitesettings['close_reason']?></textarea>
			</td>
		</tr>
	</tbody>
</table>
</form>

