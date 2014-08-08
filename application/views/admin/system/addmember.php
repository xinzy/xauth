<div class="NORMALBOX" style="100%">
	<form method="post">
    <table style="width:100%" class="TR_MOUSEOVER">
		<caption>
			<span class="fl ml20">添加用户</span>
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
	    		<td><input name="user[username]" type="text" /></td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">Email: </td>
	    		<td>
	    			<input name="user[email]" type="text" />
	    		</td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">密码: </td>
	    		<td>
	    			<input name="user[password]" type="password" />
	    		</td>
	    	</tr>
	    	<tr>
	    		<td class="align_right">用户组: </td>
	    		<td>
	    			<select name="user[groupid]" id="groupid-select" style="float: left;">
	    				<option value="0">--请选择--</option>
	    				<?php foreach ($usergroup as $group) : ?>
	    				<option value="<?php echo $group['gid']?>"><?php echo $group['groupname']?></option>
	    				<?php endforeach; ?>
	    			</select>
	    		</td>
	    	</tr>
	    </tbody>
	</table>
	</form>
</div>
