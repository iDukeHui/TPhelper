/**
 * User: zhuyajie
 * Date: 12-10-29
 * Time: 上午3:21
 */
$(function () {
	window.ajaxvisit = function (obj, act) {
		//解析url
		var href = obj.href.replace(/index\.php.*#|Index\/index#|#/, 'index.php'), result = "";
		//ajax访问
		$.post(href, {data:$(obj).parent().parent().attr('data')}, function (data, status) {
			if (status == "success") {
				result = data;
			}
			check();
		});
		//定义ajax执行结果检测函数
		var check = function () {
			if (result != "") {
				action(act);
				result = "";
			} else {
				setTimeout(function () {
					check();
				}, 50);
			}
		};
		//结果处理函数
		var action = function (act) {
			switch (act) {
				case 'putContent':
					//访问结果放置到右边
					$("#right_content").html(result);
					break;
				case 'removeAPP':
					if (result.success) {
						alert(result.success);
						$(obj).parent().parent().remove();
					} else {
					}
					break;
				case 'setDefaultAPP':
					if (result.success) {
						alert(result.success);
						location.href = APP;
					} else {
						alert(result.error)
					}
					break;
				default :
					break
			}
		};
	};
	$(".showform").each(function () {
		$(this).on('click', function () {
			var hidden = $(".hidden_form");
			switch (this.id) {
				case "addapp":
					hidden.css('display', 'none');
					$("#form_addapp").css('display', 'block');
					break;
				case 'createapp':
					hidden.css('display', 'none');
					$("#form_createapp").css('display', 'block');
					break;
				case 'applist':
					hidden.css('display', 'none');
					$('#listapp').css('display', 'block');
					break;
			}
		})
	});
});