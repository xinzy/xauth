<h3>Index</h3>

<?php if ($islogin):?>
Welcome <?php echo $user['username'];?> 
Group <?php echo $group['groupname']?>
<hr />
<a href="/auth/logout">Logout</a>
<a href="/auth/cgpasswd">Change Password</a>
<?php else: ?>
<a href="/auth/login">Login</a>
<a href="/auth/register">Register</a>
<a href="/auth/forget_password">Forget Password</a>
<?php endif; ?>