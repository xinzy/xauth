<div class="NORMALBOX" style="100%">
    <table style="width:100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">用户详情</span>
		<span class="action-span mr20">
			<button type="button" class="beautyButton" id="edit_bt" onclick="window.location.href='/admin/system/modifymember/<?php echo $user['uid']; ?>';">修改 </button>
		</span>
	</caption>
    <tbody>
    	<tr>
    		<td width="100" class="align_right">用户ID: </td>
    		<td><?php echo $user['uid'];?> </td>
    	</tr>
    	<tr>
    		<td class="align_right">用户名: </td>
    		<td><?php echo $user['username'];?> </td>
    	</tr>
    	<tr>
    		<td class="align_right">手机: </td>
    		<td>
    			<?php echo $user['mobile'] ;?>
    		</td>
    	</tr>
    	<tr>
    		<td class="align_right">电子邮箱: </td>
    		<td>
    			<?php echo $user['email'] ;?>
    		</td>
    	</tr>
    	<tr>
    		<td class="align_right">上次登录时间: </td>
    		<td>
    			<?php echo $user['lastlogin'] == 0 ? '--' : date('Y-m-d H:i', $user['lastlogin']);?>
    		</td>
    	</tr>
    	<tr>
    		<td class="align_right">用户组: </td>
    		<td>
    			<?php echo $user['groupname'];?>
    		</td>
    	</tr>
    </tbody>
</table>
</div>
