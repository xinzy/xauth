
<form action="/admin/system/permission" method="post">
<div class="NORMALBOX">
    <div class="TITLE">权限管理 [<?php echo $usergroup['groupname']; ?>]</div>
    <div class="CONTENT clearfix">
    	<?php foreach ($permissions as $controller => $permission): ?>
    	<div class="fl" style="margin-left: 10px; min-width: 125px;">
    		<span><b><?php echo $controller; ?></b></span>
	    	<ul style="margin-top: 10px;">
	    		<?php foreach ($permission as $k => $v): ?>
	    		<li style="margin: 5px 0 5px 0;">
	    			<input id="cb-<?php echo $v['perid']; ?>" type="checkbox" value="<?php echo $v['perid']; ?>" name="userpms[<?php echo $usergroup['gid']; ?>][]" 
	    				<?php echo in_array($v['perid'], $usergroup['permissions']) ? 'checked="checked"' : ''; ?> />
	    			<label for="cb-<?php echo $v['perid']; ?>"><?php echo $v['description']; ?></label>
	    		</li>
	    		<?php endforeach; ?>
	    	</ul>
    	</div>
    	<?php endforeach;?>
    </div>
    <div class="" style="background-color: #F2FAFC; padding: 5px 0 5px 15px;">
    	<input type="submit" class="beautyButton" value=" 保存修改 " />
    </div>
</div>
</form>
