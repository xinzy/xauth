<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>
<meta name="Robots" content="none" />
<link rel="stylesheet" href="<?php echo $base_url; ?>pub/admin/css/global.css" />
<script src="<?php echo $base_url; ?>pub/js/jquery.js"></script>
<script src="<?php echo $base_url; ?>pub/js/jquery.cookie.js"></script>
<script src="<?php echo $base_url; ?>pub/admin/js/global.js"></script>
<script src="<?php echo $base_url; ?>pub/admin/js/cat.js"></script>
</head>

<body>
<div id="header">
    <div id="logo"><img src="<?php echo $base_url; ?>pub/admin/images/adminLogo.gif" alt="" /></div>
    <div id="header_right">
        <ul id="nav">
        	<?php 
        		$fs = $this->uri->segment(2); 
        		$ss = $this->uri->segment(3);
        	?>
            <li id="menu1" class="nav<?php if($fs == '' || $fs == 'index'){echo ' nav_current';}?>" onclick="location.href='<?php echo $base_url . 'admin/index'?>';">首页</li>
            <li id="menu6" class="nav<?php if($fs == 'settings'){echo ' nav_current';}?>" onclick="location.href='<?php echo $base_url . 'admin/settings'?>';">设置</li>

        </ul>

        <div id="status">
            <span>您好, <strong><?php echo $curuser['username']; ?></strong> [<a href="<?php echo $base_url;?>admin/logging/logout">退出</a>] [<a target="_blank" href="<?php echo $base_url;?>">网站首页</a>]</span>
            首页 » 
        </div>
    </div>
</div>
