
<link rel="stylesheet" href="/pub/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/pub/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/pub/kindeditor/lang/zh_CN.js"></script>

<script type="text/javascript">
var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="content"]', {
		resizeType : 1,
		allowPreviewEmoticons : false,
		allowImageUpload : false,
		items : [
			'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link']
	});
});

</script>

<form method="post">
<table style="width: 100%" class="TR_MOUSEOVER">
	<caption>
		<span class="fl ml20">添加新页面</span>
	</caption>
	<tfoot>
		<tr>
			<th colspan="2" class="align_left"><input type="submit" class="beautyButton" value=" 提交 " /></th>
		</tr>
	</tfoot>

	<tbody>
		<tr>
			<td width="100" align="right">标题: </td>
			<td>
				<?php if (isset($page)):?>
				<input name="id" type="hidden" value="<?php echo $page['id']?>" /> 
				<?php endif;?>
				<input name="subject" style="width: 200px;" value="<?php echo isset($page) ? $page['subject'] : ''?>" /> <?php echo form_error('subject')?>
			</td>
		</tr>
		<tr>
			<td align="right">唯一标识码: </td>
			<td>
				<input name="unique" style="width: 200px;" value="<?php echo isset($page) ? $page['unique'] : ''?>"  /> <?php echo form_error('unique')?>
			</td>
		</tr>
		<tr>
			<td align="right">关键字: </td>
			<td>
				<input name="keyword" style="width: 200px;" value="<?php echo isset($page) ? $page['subject'] : ''?>"  />
			</td>
		</tr>
		<tr>
			<td align="right">简介: </td>
			<td>
				<input name="description" style="width: 350px;" value="<?php echo isset($page) ? $page['description'] : ''?>"  />
			</td>
		</tr>
		<tr>
			<td align="right">内容: </td>
			<td>
				<textarea name="content" style="width:700px;height:200px;visibility:hidden;"><?php echo isset($page) ? $page['content'] : ''?></textarea>
			</td>
		</tr>
	</tbody>
</table>
</form>