<h3>重置密码</h3>
<form method="post">
	Password: <input name="password" type="password" /> <?php echo form_error('password')?><br />
	Confirm: <input name="confirm" type="password" /> <?php echo form_error('confirm')?><br />
	<input type="submit" value="重置" />
</form>