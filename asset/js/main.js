/**
 * User: zhuyajie
 * Date: 12-10-29
 * Time: 上午3:21
 */
$(function () {
	/** listapp.html模板 (首页 项目列表-移除项目-设为默认项目功能调用)*/
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
	/**顶部警告栏*/
	var top_alert = $('.top-alert');
	var content = $('.content');
	top_alert.find('.close').on('click', function () {
		$('.top-alert').removeClass('block').slideUp(200);
		content.animate({marginTop:'-=36'},200);
	})

	window.updateAlert = function () {
		if ($('.check_fail').length > 0) {
			if (top_alert.hasClass('block')) {
			} else {
				top_alert.addClass('block').slideDown(200);
				content.animate({marginTop:'+=36'},200);
			}
		} else {
			if (top_alert.hasClass('block')) {
				top_alert.removeClass('block').slideUp(200);
				content.animate({marginTop:'-=36'},200);
			}
		}
	}
});
