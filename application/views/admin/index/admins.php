<div class="NORMALBOX" style="width: 100%;">
    <div class="TITLE TOGGLE">搜索</div>
    <div class="CONTENT">
    	<form>
    		用户名: <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>"/>　
    		<input class="beautyButton" type="submit" value=" 查询 " />
    	</form>
    </div>
</div>

<table style="width:100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">用户列表</span>
		<span class="fl ml20">
			<select id="usergroup-selected">
				<option value="0" <?php echo $currgroupid == '0' ? 'selected="selected"' : ''?>>全部</option>
				<?php foreach ($usergroup as $k => $v): ?>
				<option value="<?php echo $k; ?>" <?php echo is_numeric($currgroupid)&&$currgroupid==$k ? 'selected="selected"' : ''?>><?php echo $v['groupname'];?></option>
				<?php endforeach; ?>
			</select>
		</span>
	</caption>
    <thead>
        <tr>
        	<th width="30" class="align_center"></th>
            <th class="align_right" width="30">ID</th>
            <th>用户名</th>
            <th>Email</th>
            <th>上次登录时间</th>
            <th>上次登录IP</th>
            <th>手机</th>
            <th>用户组</th>
            <th width="150">操作</th>
        </tr>
    </thead>

    <tbody>
    	<?php if(isset($users) && !empty($users)):
    			foreach($users as $item):?>
    		<tr>
    			<td class="align_center"> </td>
    			<td><?php echo $item['uid'];?></td>
    			<td><?php echo $item['username'];?></td>
    			<td><?php echo $item['email'];?>
    			<td><?php echo $item['lastlogin'] ? date('Y-m-d H:i', $item['lastlogin']) : '--'?></td>
			<td><?php echo $item['lastip']?> <?php if ($item['lastip']): ?>(<?php ipresolve($item['lastip'])?>)<?php endif;?></td>
			<td><?php echo $item['mobile'];?>
    			<td><?php echo $item['groupname'];?>
    			<td>
    				<a href="<?php echo $base_url.'admin/system/memberdetail/'.$item['uid'];?>">详细</a>
    				<?php if ($admin['groupid'] == 1 || ($item['groupid'] != 1)): ?>
    				　|　<a href="<?php echo $base_url.'admin/system/modifymember/'.$item['uid'];?>">修改</a>
    				<?php endif; ?>
    				<?php if (($admin['groupid'] == 1 || $item['groupid'] != 1) && $item['uid'] != $admin['uid']): ?>
    				　|　<a href="<?php echo $base_url.'admin/system/deletemember/'.$item['uid'];?>" onclick="return confirm('确认删除?');">删除</a>
    				<?php endif; ?>
    			</td>
    		</tr>
	    	<?php endforeach; 
    		endif;?>
    </tbody>
</table>

<div class="page"><?php echo $username == '' ? $pagination : ''; ?></div>

<script type="text/javascript">
$(function(){
	$("#usergroup-selected").change(function() {
		var status = $(this).val();
		window.location.href="<?php echo $base_url;?>admin/system/member/" + status;
	});
});

</script>
