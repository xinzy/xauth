
<?php // var_export($sitesettings)?>
<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">系统单页</span>
		<span class="action-span mr20">
			<button type="button" class="beautyButton" onclick="window.location.href='/admin/settings/addpage'">添加新页面 </button>
		</span>
	</caption>
	
	<thead>
		<tr>
			<th width="35">ID</th>
			<th width="">标题</th>
			<th width="100">唯一标识码</th>
			<th width="80">发布人</th>
			<th width="100">发布时间</th>
			<th width="150">操作</th>
		</tr>
	</thead>
	
	<tfoot>
		<tr>
			<th colspan="6" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<?php foreach ($pages as $page): ?>
		<tr>
			<td><?php echo $page['id']?></td>
			<td><?php echo $page['subject']?></td>
			<td><?php echo $page['unique']?></td>
			<td><?php echo $page['author']?></td>
			<td><?php echo date('Y-m-d H:i', $page['dateline'])?></td>
			<td>
				<a href="/admin/settings/editpage/<?php echo $page['id']?>">[编辑]</a>
				<!--  | 
				<a href="/admin/settings/delpage/<?php echo $page['id']?>" onclick="return confirm('确认删除吗?');">[删除]</a>
				 -->
			</td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
</form>

