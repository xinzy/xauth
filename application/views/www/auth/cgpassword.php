<h3>修改密码</h3>
<form method="post">
	Password: <input name="password" type="password" /> <?php echo form_error('password')?><br />
	NewPasswd: <input name="newpasswd" type="password" /> <?php echo form_error('newpasswd')?><br />
	Confirm: <input name="confirm" type="password" /> <?php echo form_error('confirm')?><br />
	<div><?php echo $message?></div>
	<input type="submit" value="改密" />
</form>