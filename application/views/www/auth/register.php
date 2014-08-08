<h3>注册</h3>
<form method="post">
	Email: <input name="email" type="text" /> <?php echo form_error('email')?><br />
	Username: <input name="username" type="text" /> <?php echo form_error('username')?><br />
	Password: <input name="password" type="password" /> <?php echo form_error('password')?><br />
	<?php foreach ($groups as $group): ?>
	<input type="radio" name="groupid" value="<?php echo $group['groupid']?>" /><?php echo $group['groupname']?>
	<?php endforeach; ?>
	<br />
	 <?php echo form_error('groupid')?><br />
	<input type="submit" value="注册" />
</form>