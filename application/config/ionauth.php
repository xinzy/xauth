<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 | -------------------------------------------------------------------------
| Hash Method (sha1 or bcrypt)
| -------------------------------------------------------------------------
| Bcrypt is available in PHP 5.3+
|
| IMPORTANT: Based on the recommendation by many professionals, it is highly recommended to use
| bcrypt instead of sha1.
|
| NOTE: If you use bcrypt you will need to increase your password column character limit to (80)
|
| Below there is "default_rounds" setting.  This defines how strong the encryption will be,
| but remember the more rounds you set the longer it will take to hash (CPU usage) So adjust
| this based on your server hardware.
|
| If you are using Bcrypt the Admin password field also needs to be changed in order login as admin:
| $2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36
|
| Be careful how high you set max_rounds, I would do your own testing on how long it takes
| to encrypt with x rounds.
|
| salt_prefix: Used for bcrypt. Versions of PHP before 5.3.7 only support "$2a$" as the salt prefix
| Versions 5.3.7 or greater should use the default of "$2y$".
*/
$config['hash_method']    = 'bcrypt';	// sha1 or bcrypt, bcrypt is STRONGLY recommended
$config['default_rounds'] = 8;		// This does not apply if random_rounds is set to true
$config['random_rounds']  = FALSE;
$config['min_rounds']     = 5;
$config['max_rounds']     = 9;
$config['salt_prefix']    = '$2y$';

/*
 | -------------------------------------------------------------------------
| Salt options
| -------------------------------------------------------------------------
| salt_length Default: 22
|
| store_salt: Should the salt be stored in the database?
| This will change your password encryption algorithm,
| default password, 'password', changes to
| fbaa5e216d163a02ae630ab1a43372635dd374c0 with default salt.
*/
$config['salt_length'] = 22;
$config['store_salt']  = FALSE;

/*
 | -------------------------------------------------------------------------
| Authentication options.
| -------------------------------------------------------------------------
| maximum_login_attempts: This maximum is not enforced by the library, but is
| used by $this->ion_auth->is_max_login_attempts_exceeded().
| The controller should check this function and act
| appropriately. If this variable set to 0, there is no maximum.
*/
$config['site_title']                 = 'Example';      	 // 
$config['manual_activation']          = FALSE;               // Manual Activation for registration
$config['remember_users']             = TRUE;                // Allow users to be remembered and enable auto-login
$config['user_extend_on_login']       = FALSE;               // Extend the users cookies every time they auto-login


/**
 * email, username, email|username
 */
$config['identity']                   = 'email';             // 登陆的认证
$config['email_activation']           = false;                // 注册后是否需要邮箱激活
$config['user_expire']                = 86400 * 30; 		 // 记住密码有效期

$config['track_login_attempts']       = FALSE;                // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['track_login_ip_address']     = TRUE;                // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['maximum_login_attempts']     = 3;                   // 最大尝试登陆次数
$config['lockout_time']               = 60 * 15;             // 登陆失败后等待时间

$config['forgot_password_expiration'] = 60 * 60;             // 重置密码有效期

$config['min_password_length']        = 6;                   // Minimum Required Length of Password
$config['max_password_length']        = 20;                  // Maximum Allowed Length of Password
$config['min_username_length']        = 4;                   // Minimum Required Length of Password
$config['max_username_length']        = 16;                  // Maximum Allowed Length of Password

/*
 | -------------------------------------------------------------------------
| Cookie options.
| -------------------------------------------------------------------------
| remember_cookie_name Default: remember_code
| identity_cookie_name Default: identity
*/
$config['remember_cookie_name'] = 'remember_code';
$config['identity_cookie_name'] = 'identity';

/**
 * 邮件激活内容
 * 
 * %username%	用户名
 * %email%		邮箱
 * %url%		激活地址
 * %date%		邮件发送时间
 */
$config['email_activate'] = <<<EOT
亲爱的%username%，你好: <br />
　　欢迎加入{$config['site_title']}，您的登陆邮箱为%email%.<br />
　　请点击以下链接验证你的邮箱地址，验证后就可以使用本站的所有功能啦! <br />
　　<a href="%url%">%url%</a> <br />
　　如果以上链接无法点击，请将该网址复制并粘贴至新的浏览器窗口中。<br />
　　祝您每天都有一个好心情！<br />
　　{$config['site_title']}团队 <br />
　　%date%
EOT;

/**
 * 重置密码邮件内容
 * 
 * %url%		重置url
 * %date%		邮件发送时间
 */
$config['email_forgot_password'] = <<< EOT
亲爱的{$config['site_title']}用户，你好: <br />
　　这是您{$config['site_title']}重置密码申请.如果您未申请重置密码，请无视此邮件。  <br />
　　点击一下链接可以重置您的密码 <br />
　　<a href="%url%">%url%</a> <br />
　　如果以上链接无法点击，请将该网址复制并粘贴至新的浏览器窗口中。<br />
　　祝您每天都有一个好心情！<br />
　　{$config['site_title']}团队 <br />
　　%date%
EOT;

/* End of file ion_auth.php */
/* Location: ./application/config/ionauth.php */
