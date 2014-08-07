(function($){
	$.fn.cat = function(params){
		var o = $(this);
		var input = null;
		var buffer = {};
		var settings = $.extend({
			c_id:'0',
			input_id:'p_id'
		},params);

		init();
		function init() {
			input = $(document.createElement('input')).attr({'type':'hidden','id':settings.input_id,'name':settings.input_id}).val(settings.c_id);
			o.append(input);

			if(settings.c_id > 0){
				levels(settings.c_id);
			} else if(settings.c_id == 0) {
				level(settings.c_id, 0, 1);
			}
		}

		function create_element(data, selected_id, level) {
			var select = $(document.createElement('select')).css({'margin-left':'5px'});
			select.bind('change',function(){on_change($(this))});

			if(level == 1){
				select.append('<option value="0">作为根分类</option>');
			} else {
				select.append('<option value="-1">请选择</option>');
			}

			$.each(data,function(i, v){
				if(selected_id == v.id){
					select.append('<option value="'+v.id+'" selected>'+v.val+'</option>');
				}else{
					select.append('<option value="'+v.id+'">'+v.val+'</option>');
				}
			});
			o.append(select);
		}

		function level(pid, selected_id, level) {
			$.ajax({
		        type: "POST",
		        url: "/v_admin/categories/get_json",
		        data: "pid=" + pid,
		        dataType: "json",
		        async: false,
		        success: function(data){
		        	if(data.length > 0) {
		        		create_element(data,selected_id, level);
		        	}
		        }
		    });
		}

		function levels(id) {
			$.ajax({
		        type: "POST",
		        url: "/v_admin/categories/get_detail_json",
		        data: "id=" + id,
		        dataType: "json",
		        success: function(data){
		        	if(data) {
		        		if(data.p_id == 0) {
		        			level(data.p_id, 0, 1);
		        		} else {
		        			var path = data.path.split(',');
		        			if(path[path.length-1] == '') path[path.length-1] = data.id;
							$.each(path,function(i,v){
			        			if (i < path.length-2){
			        				level(v,path[i+1], i+1);
			        			}
			        		})
		        		}
		        	}
		        }
		    });
		}

		function on_change(obj) {
			obj.nextAll().remove();
			var pid = obj.val();
			if(pid >= 0) {
				input.val(obj.val());
				if(pid == 0) return;
			} else {
				input.val($(obj).prev().val());
				return;
			}

			level(pid,-1);
		}
	}
})(jQuery);