<h3>登录</h3>
<form method="post">
	Identity: <input name="identity" type="text" /> <?php echo form_error('identity')?><br />
	Password: <input name="password" type="password" /> <?php echo form_error('password')?><br />
	<input type="checkbox" name="remember" value="1" />记住登陆<br />
	<?php echo $message;?><br />
	<input type="submit" value="登陆" />
</form>
<a href="/auth/register">Register</a>
<a href="/auth/forget_password">Forget Password</a>