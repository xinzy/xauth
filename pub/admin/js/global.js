/**
 * table的class使用说明：
 * TR_EVEN          偶数行背景色区分
 * TR_MOUSEOVER     行的背景色鼠标响应
 * TR_SELECTED      行的鼠标点击选中
 * TD_SELECTED      单元格的鼠标点击选中
 * TOGGLE           caption的点击收放
 * noborder         去除表格边框
 * ------------
 * 表单的class使用说明：
 * nostyle          不使用样式(默认使用样式)
 * ------------
 * div的class使用说明：
 * TOGGLE           点击收缩下面一个class="CONTENT"的div
 **/
 
//导航菜单
$(document).ready(function(){
    $(".nav").mouseover(function(){
        $(this).addClass("nav_over");
    }).mouseout(function(){
        $(this).removeClass("nav_over");
    }).click(function(){
        $(".nav_current").removeClass("nav_current");
        $(this).addClass("nav_current");
        $(".menu").hide();
        $("#s_"+$(this).attr("id")).show();
    });
});

//表格容器
$(document).ready(function(){
    $(".TR_EVEN tr:even").not("thead tr").not("tfoot tr").addClass("even");

    $(".TR_MOUSEOVER tr").mouseover(function(){$(this).addClass("TR_MOUSEOVER_BG")}).mouseout(function(){$(this).removeClass("TR_MOUSEOVER_BG")});

    $(".TR_SELECTED tr").click(function(){
        $(this).toggleClass("TR_SELECTED_BG");
    });

    $(".TD_SELECTED td").click(function(){
        if(!$(this).closest("table").hasClass("TR_SELECTED")){
            $(this).toggleClass("TD_SELECTED_BG");
        }
    });

    $(".TOGGLE caption").css("cursor","pointer");
    $(".TOGGLE caption").click(function(){
        $(this).nextAll("thead,tfoot,tbody").toggle();
    });

    $("input[type='text'],textarea").not(".nostyle").focus(function(){
        $(this).addClass("styleform");
    }).blur(function(){
        $(this).removeClass("styleform");
    });

    $("div.TOGGLE").css("cursor","pointer");
    $("div.TOGGLE").click(function(){
        $(this).next(".CONTENT").toggle();
    });

    $("input[type='radio'],input[type='checkbox']").css("border","0");
});

$(function(){
	$('.number').keyup(function(){
		var tmptxt = $(this).val();
        $(this).val(tmptxt.replace(/\D|^0/g, ''));
	}).bind("paste",function(){     
        var tmptxt=$(this).val();     
        $(this).val(tmptxt.replace(/\D|^0/g, ''));     
    });
});
