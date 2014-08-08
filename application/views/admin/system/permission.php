<form action="/admin/system/permission" method="post">
<table class="TR_MOUSEOVER" style="width:100%;" >
	<caption>
		<span class="fl ml20">权限管理</span>
	</caption>
    <thead>
        <tr>
            <th width="20"></th>
            <th class="align_left"></th>
            <?php if(!empty($usergroups)): foreach ($usergroups as $key => $value): ?>
            <th class="align_center"><?php echo $value['groupname'];?></th>
            <?php endforeach;endif;?>
        </tr>
    </thead>

    <tfoot>
        <tr><th colspan="5" class="align_left"><input type="submit" class="beautyButton" value=" 保存修改 " /></th></tr>
    </tfoot>

    <tbody>
    	<?php foreach ($permissions as $controller => $permission): ?>
    	<tr>
    		<td colspan="5" class="align_left">
    			<div><b><?php echo $controller; ?></b></div>
    		</td>
    	</tr>
    	<?php foreach ($permission as $k => $v): ?>
    	<tr>
    		<td></td>
    		<td><?php echo $v['description']; ?></td>
    		<?php if(!empty($usergroups)): foreach ($usergroups as $key => $value): ?>
    		<td class="align_center">
    			<input type="checkbox" value="<?php echo $v['perid']; ?>" name="userpms[<?php echo $key;?>][]" 
    				<?php echo in_array($v['perid'], $value['permissions']) ? 'checked="checked"' : ''; ?> />
    		</td>
    		<?php endforeach;endif;?>
    	</tr>
    	<?php endforeach;?>
    	<?php endforeach;?>
    </tbody>
</table>
</form>
