<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$fs = $this->uri->segment(2);
	$ss = $this->uri->segment(3);
?>
<div id="left">
    <ul id="s_menu1" class="menu" style="<?php if($fs == '' || $fs == 'index'){echo 'display:block';}?>">
        <li><a href="/admin/index" class="<?php if ($fs == '' || ($fs == 'index' && ($ss == '' || $ss == 'index'))) {echo 'selected';} ?>">首页</a></li>
        <li><a href="/admin/index/modifypass" class="<?php if ($fs == 'index' && $ss == 'modifypass') {echo 'selected';} ?>">修改密码</a></li>
        <?php if (! ENABLE_PERMISSION):?>
        <li><a href="/admin/index/admins" class="<?php if ($fs == 'index' && $ss == 'admins') {echo 'selected';} ?>">管理员列表</a></li>
        <li><a href="/admin/index/addadmin" class="<?php if ($fs == 'index' && $ss == 'addadmin') {echo 'selected';} ?>">添加管理员</a></li>
        <?php endif; ?>
    </ul>
    <?php if (ENABLE_PERMISSION):?>
    <ul id="s_menu2" class="menu" style="<?php if($fs == 'system'){echo 'display:block';}?>">
        <li><a href="<?php echo $base_url;?>admin/system/member" class="<?php if($fs == 'system' && $ss == 'member'){echo 'selected';}?>">用户</a></li>
        <li><a href="<?php echo $base_url;?>admin/system/usergroup" class="<?php if($fs == 'system' && $ss == 'usergroup'){echo 'selected';}?>">用户组</a></li>
        <li><a href="<?php echo $base_url;?>admin/system/permission" class="<?php if($fs == 'system' && $ss == 'permission'){echo 'selected';}?>">权限管理</a></li>
    </ul>
    <?php endif; ?>
    <ul id="s_menu6" class="menu" style="<?php if($fs == 'settings'){echo 'display:block';}?>">
        <li><a href="/admin/settings/index" class="<?php if ($fs == 'settings' && ($ss == 'index' || $ss == '')) {echo 'selected';} ?>">站点设置</a></li>
        <li><a href="/admin/settings/page" class="<?php if ($fs == 'settings' && $ss == 'page') {echo 'selected';} ?>">系统单页</a></li>
    </ul>
</div>

<div id="right">