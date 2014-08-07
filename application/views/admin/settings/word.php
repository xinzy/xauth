
<?php // var_export($words)?>

<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">词语过滤</span>
	</caption>
	
	<thead>
		<tr>
			<th width="50">ID</th>
			<th width="*">不良词语</th>
			<th width="*">替换为</th>
			<th width="120">操作员</th>
			<th width="150">操作</th>
		</tr>
	</thead>
	
	<tfoot>
		<tr>
			<th><button class="beautyButton" type="button" id="add-word">添加</button></th>
			<th colspan="4" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<?php foreach ($words as $val): ?>
		<tr>
			<td align="right"><?php echo $val['id']?></td>
			<td><input name="find[<?php echo $val['id']?>]" value="<?php echo $val['find']?>" /></td>
			<td><input name="replace[<?php echo $val['id']?>]" value="<?php echo $val['replace']?>" /></td>
			<td><?php echo $val['admin']?></td>
			<td>
				<a href="/admin/settings/delword/<?php echo $val['id']?>" onclick="return confirm('确认删除吗?');">[删除]</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</form>

<script type="text/javascript">
function removetr(obj) {
	$(obj).parent().parent().remove();
}

var $index = -1;
$(function(){
	$('#add-word').click(function(){
		var html = '<tr>';
		html += '<td class="align_right"></td>';
		html += '<td><input type="text" name="find['+$index+']" value="" /></td>';
		html += '<td><input type="text" name="replace['+$index+']" value="" /></td>';
		html += '<td></td>';
		html += '<td><a href="javascript:;" onclick="removetr(this)">[删除]</a></td>';

		$('table tbody').append(html);
		$index --;
	});
});
</script>
