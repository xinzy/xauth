<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $title;?></title>
<meta name="Robots" content="none" />
<style>
html{zoom:expression(function(ele){ele.style.zoom="1";document.execCommand("BackgroundImageCache",false,true);}(this));}
* { font: 12px Verdana; }
body { margin: 0; background: #464646; }
img { border: 0; }
form { margin: 0; }
p { margin: 10px 0; }
input#loginame, input#loginpwd { width: 150px; font-weight: bold; border: 1px solid #000; padding: 4px; background: url(<?php echo $base_url; ?>pub/admin/images/login_input_bg.gif) left top no-repeat; }
input#vc { width: 45px; font-weight: 700; border: 1px solid #000; padding: 4px; background: url(<?php echo $base_url; ?>pub/admin/images/login_input_bg.gif) left top no-repeat; }
#login { height: 350px; width: 700px; margin: 80px auto; }
    #logo { float: left; width: 300px; }
    #right { color: #fff; float: right; width: 370px; margin: 110px 0 0 0; }
        .login { border: 1px solid #FFFDEE; border-right: 1px solid #FDB939; border-bottom: 1px solid #FDB939; background: #FFF8C5; +padding: 3px 2px 0; }
        .wid { margin-left: 46px; }
        #right img { vertical-align: top; margin-top: 3px; cursor: pointer; }
</style>
</head>

<body>
<div id="login">
    <div id="logo"><img src="<?php echo $base_url; ?>pub/admin/images/login.png" alt="管理后台" /></div>
    <div id="right">
        <p>　帐号: <input id="loginame" type="text" name="username" value="<?php echo isset($curuser) ? $curuser['username'] : ''?>" /><span id="namemsg" style="margin-left: 10px; color:#FF0;"></span></p>
        <p>　密码: <input id="loginpwd" type="password" name="password" /><span id="passmsg" style="margin-left: 10px; color:#FF0;"></span></p>
        <p>验证码: <input name="vdcode" id="vc" type="text" maxlength="4" /> 
        	<img src="<?php echo $base_url . 'admin/logging/vercode'; ?>" id="ic" alt="点击更换验证码" />
        	<span id="vercodemsg" style="margin-left: 10px; color:#FF0;"></span>
        </p>
        <span class="wid"></span>
        <input type="button" id="subbutton" name="submit" value="登陆" class="login" /><span id="logingmsg" style="margin-left: 10px; color:#FF0;"></span>
    </div>
</div>

<script type="text/javascript" src="/pub/js/jquery.js"></script>
<script>
$("input[name='username']").focus();
var $usernameMsg = $("#namemsg");
var $passwordMsg = $("#passmsg");
var $vercodeMsg = $('#vercodemsg');
var $loginmsg = $('#logingmsg');
$loginmsg.hide();
$("input[name='username']").focus(function(){
	$(this).css('borderColor', '#F93');
	$usernameMsg.hide();
}).blur(function(){
	$(this).css('borderColor', '#000');
	if ($(this).val() == ''){
		$usernameMsg.show().text('用户名不能为空');
	} else {
		$usernameMsg.hide();
    }
}).keydown(function(e){
	var keycode = e.which;
	if (keycode == 13){
		submit();
	}
});

$("input[name='password']").focus(function(){
	$(this).css('borderColor', '#F93');
	$passwordMsg.hide();
}).blur(function(){
	$(this).css('borderColor', '#000');
	if ($(this).val() == ''){
		$passwordMsg.show().text('密码不能为空');
	} else {
		$passwordMsg.hide();
    }
}).keydown(function(e){
	var keycode = e.which;
	if (keycode == 13){
		login();
	}
});

$("input[name='vdcode']").focus(function(){
	$(this).css('borderColor', '#F93');
	$vercodeMsg.hide();
}).blur(function(){
	$(this).css('borderColor', '#000');
	if ($(this).val() == ''){
		$vercodeMsg.show().text('验证码不能为空');
	} else {
		$vercodeMsg.hide();
    }
}).keydown(function(e){
	var keycode = e.which;
	if (keycode == 13){
		login();
	}
});

$('#subbutton').click(function(){
	login();
});

function login()
{
	var username = $('#loginame').val();
	var pass = $('#loginpwd').val();
	var vdcode = $('#vc').val();
	if (username == '') {
		$usernameMsg.show().text('用户名不能为空');
		return false;
	}
	if (pass == '') {
		$passwordMsg.show().text('密码不能为空');
		return false;
	}
	if (vdcode == '') {
		$vercodeMsg.show().text('验证码不能为空');
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '<?php echo $base_url; ?>admin/logging/auth',
		data: 'user[username]=' + username + "&user[password]=" + pass + '&user[vercode]=' + vdcode,
		success: function(data) {
			if (data == 1) {
				location.href = "<?php echo $base_url; ?>admin/index";
			} else {
				$('#loginpwd').val('');
				$('#vc').val('');
				$('#ic').attr('src', '<?php echo $base_url . 'admin/logging/vercode'; ?>?ran='+Math.random());
				if (data == 3) {
					$loginmsg.show().text('用户名不能为空 ');
				} else if (data == 4) {
					$loginmsg.show().text('密码不能为空 ');
				} else if (data == 2) {
					$loginmsg.show().text('用户名或密码错误 ');
				} else if (data == 5) {
					$loginmsg.show().text('验证码为空 ');
				} else if (data == 6) {
					$loginmsg.show().text('验证码不正确');
				} else if (data == 7) {
					$loginmsg.show().text('验证码已过期 ');
				} else {
					$loginmsg.show().text('服务器故障');
				}
			}
		}, 
		error: function(data) {
			$loginmsg.show().text('服务器故障');
		}
	});
}

$('#ic').click(function(){
	$(this).attr('src', '<?php echo $base_url . 'admin/logging/vercode'; ?>?ran='+Math.random());
	$('#vc').focus();
});
</script>
</body>
</html>