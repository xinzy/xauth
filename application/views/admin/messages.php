<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style>
	body { background-color: #F5F5F5; }
	.NORMALBOX { margin: 100px auto; width: 400px; height: 200px;border: 1px solid #cdcdcd; background: #fff; border-radius: 5px 5px 0 0; }
	.TITLE { width:390px;height:30px; padding:10px 0 0 10px;font-size:14px;font-weight:bold;background: url(/pub/images/bg-title.gif) top left repeat-x transparent; }
	.CONTENT { width: 100%; height: 120px; padding-top: 40px; text-align: center; }
	.CONTENT p.message { color: red; font-weight: bold; font-size: 14px; }
	.CONTENT p a { color: #529214; }
</style>
<Meta http-equiv=Refresh Content="3;url=<?php echo $get_array['url'];?>"> 
<div class="NORMALBOX">
    <div class="TITLE">系统信息</div>
    <div class="CONTENT">
    	<p class="message"><?php echo $messages;?> </p>
    	<p class="status"><?php if ($type=='success'){ ?>操作成功！<?php  } else {?>操作失败<?php }?></p> 
    	<p><a href="<?php echo $get_array['url'];?>" >如果浏览器长时间没有跳转请点击这里</a></p>
    	<?php if (isset($redirect) && ! empty($redirect)): ?>
    	<p><a href="<?php echo $redirect['url'];?>" ><?php echo $redirect['message']?></a></p>
    	<?php endif; ?>
	</div>
</div>
