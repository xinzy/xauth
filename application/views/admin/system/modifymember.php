<div class="NORMALBOX" style="100%">
	<form action="/admin/system/modifymember" method="post">
    <table style="width:100%" class="TR_MOUSEOVER">
		<caption>
			<span class="fl ml20">修改用户</span>
			<span class="action-span mr20">
				<button type="button" class="beautyButton" onclick="history.back();">返回</button>
			</span>
		</caption>
		
		<tfoot>
			<tr>
				<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
			</tr>
		</tfoot>
		
	    <tbody>
	    	<tr>
	    		<td class="align_right" width="100">用户名: </td>
	    		<td>
	    			<input name="user[username]" type="text" value="<?php echo $user['username']; ?>" />
	    			<input name="user[uid]" type="hidden" value="<?php echo $user['uid']; ?>" />
	    		</td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">密码: </td>
	    		<td>
	    			<input name="user[password]" type="password" />
	    			<span class="ml10">密码留空不修改</span>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">用户组: </td>
	    		<td>
	    			<select name="user[groupid]" id="groupid-select" style="float: left;">
	    				<option value="0">--请选择--</option>
	    				<?php foreach ($usergroup as $group) : ?>
	    				<option value="<?php echo $group['gid']?>" <?php echo $group['gid']==$user['gid']?'selected="selected"':''; ?>><?php echo $group['groupname']?></option>
	    				<?php endforeach; ?>
	    			</select>
	    		</td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">手机: </td>
	    		<td>
	    			<input name="user[mobile]" type="text" value="<?php echo $user['mobile']; ?>" />
	    		</td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">电子邮箱: </td>
	    		<td>
	    			<input name="user[email]" type="text" value="<?php echo $user['email']; ?>" />
	    		</td>
	    	</tr>
	    </tbody>
	</table>
	</form>
</div>
