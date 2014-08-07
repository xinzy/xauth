<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">管理员列表</span>
	</caption>
	<thead>
		<tr>
			<th width="30">ID</th>
			<th width="150">登录名</th>
			<th width="">Email</th>
			<th width="100">级别</th>
			<th width="120">上次登录时间</th>
			<th width="180">上次登录IP</th>
			<th width="150">操作</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($admins as $item): ?>
		<tr>
			<td class="align_right"><?php echo $item['uid']?></td>
			<td><?php echo $item['username']?></td>
			<td><?php echo $item['email']?></td>
			<td><?php echo $item['admintype']?></td>
			<td><?php echo $item['lastlogin'] ? date('Y-m-d H:i', $item['lastlogin']) : '--'?></td>
			<td><?php echo $item['lastip']?> <?php if ($item['lastip']): ?>(<?php ipresolve($item['lastip'])?>)<?php endif;?></td>
			<td>
				<?php if ($item['uid'] != $curuser['uid']): ?>
				<a href="/admin/index/edit/<?php echo $item['uid']?>">[编辑]</a>
				<a href="/admin/index/delete/<?php echo $item['uid']?>" onclick="return confirm('确认删除么?')">[删除]</a>
				<?php endif;?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</form>
