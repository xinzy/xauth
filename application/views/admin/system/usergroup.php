<table style="width:100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">用户组列表</span>
		<span class="action-span mr20">
			<button type="button" class="beautyButton" onclick="window.location.href='<?php echo $base_url; ?>admin/system/add_usergroup'">添加 </button>
		</span>
	</caption>
    <thead>
        <tr>
            <th width="20"></th>
            <th class="align_right" width="50">ID</th>
            <th class="align_left">用户组名</th>
            <th width="200">操作</th>
        </tr>
    </thead>

    <tfoot>
        <tr><th colspan="4" class="align_left"></th></tr>
    </tfoot>

    <tbody>
    	<?php foreach ($usergroups as $group): ?>
    	<tr>
    		<td></td>
    		<td class="align_right"><?php echo $group['gid']; ?></td>
    		<td><?php echo $group['groupname']; ?></td>
    		<td>
    			<?php if ($group['gid'] != 1): ?>
    			<a href="<?php echo $base_url.'admin/system/edit_usergroup/'.$group['gid']; ?>">[编辑]</a>
    			<a href="<?php echo $base_url.'admin/system/delete_usergroup/'.$group['gid']; ?>" onclick="return confirm('确认删除?');">[删除]</a>
    			<a href="<?php echo $base_url.'admin/system/permission/'.$group['gid']; ?>">[编辑权限]</a>
    			<?php endif;?>
    		</td>
    	</tr>
    	<?php endforeach;?>
    </tbody>
</table>
