/**
 *
 * User: zhuyajie
 * Date: 12-10-19
 * Time: 下午9:23
 */

var checkFiled = function (id, field) {

	switch(id){
		case 'base_dir':
		case 'project':
		case 'index_file':
		case 'app_name':
		case 'think_path':
		case 'app_path':
		case 'appname':
		case 'appindex':
		case 'apppath':
			if (!checkMethod.mustinput(field)) {
				return false;
			}
			break
		default :
	}

	if (/SESSION_OPTIONS_(id|name|type|prefix)/.test(id)) {
		id = 'isword';
	} else if (/SESSION_OPTIONS_(cache_expire|expire)/.test(id)) {
		id = 'numeric';
	} else if (/LOAD_EXT_CONFIG_k|TMPL_PARSE_STRING_k|REST_OUTPUT_TYPE_k/.test(id)) {
		id = 'isword';
	} else if (/REST_OUTPUT_TYPE_/.test(id)) {
		id = 'isMIME';
	}
	switch (id) {
		case 'URL_PATHINFO_DEPR':
			return checkMethod.isSingle(field);
		case 'DB_PWD':
		case 'TMPL_LAYOUT_ITEM':
			return checkMethod.isChars(field);
		case 'isMVCUrl':
			return checkMethod.isMVCUrl(field);
		case 'URL_HTML_SUFFIX':
		case 'URL_PATHINFO_FETCH':
		case 'DEFAULT_FILTER':
		case 'VAR_FILTERS':
		case 'APP_GROUP_LIST':
		case 'DB_LIKE_FIELDS':
		case 'TMPL_DENY_FUNC_LIST':
		case 'TAGLIB_BUILD_IN':
		case 'LOG_LEVEL':
		case 'TAGLIB_PRE_LOAD':
		case 'LOAD_EXT_FILE':
		case 'LOAD_EXT_CONFIG':
		case 'REST_METHOD_LIST':
		case 'REST_CONTENT_TYPE_LIST':
			return checkMethod.isWordList(field);
		case 'ERROR_PAGE':
		case 'URL_404_REDIRECT':
			return checkMethod.isUrl(field);
		case 'DB_HOST':
		case 'COOKIE_DOMAIN':
		case 'SESSION_OPTIONS_domain':
			return checkMethod.isHost(field);
		case 'DB_PORT':
		case 'DB_MASTER_NUM':
		case 'DB_SLAVE_NO':
		case 'DB_SQL_BUILD_LENGTH':
		case 'COOKIE_EXPIRE':
		case 'DATA_CACHE_TIME':
		case 'DATA_PATH_LEVEL':
		case 'LOG_FILE_SIZE':
		case 'TMPL_CACHE_TIME':
		case 'TAG_NESTED_LEVEL':
		case 'HTML_CACHE_TIME':
		case 'numeric':
			return !isNaN(field);
		case 'SESSION_OPTIONS_path':
			return checkMethod.isSessionPath(field);
		case 'COOKIE_PATH':

		case 'app_path':
		case 'think_path':
			return checkMethod.isDir(field);
		case 'TMPL_TEMPLATE_SUFFIX':
		case 'TMPL_CACHFILE_SUFFIX':
		case 'HTML_FILE_SUFFIX':
			return checkMethod.isSuffix(field);
		case 'DB_DSN':
			return checkMethod.isDSN(field);
		case 'TMPL_CONTENT_TYPE':
		case 'isMIME':
			return checkMethod.isMIME(field);
		case 'TMPL_L_DELIM': //TP引擎左分割符
		case 'TMPL_R_DELIM': //TP引擎右分割符
		case 'TAGLIB_BEGIN': //TP标签库标签开始标记
		case 'TAGLIB_END':   //TP标签库标签结束标记
			return checkMethod.isNword(field);
		case 'TMPL_TRACE_FILE':
		case 'TMPL_ACTION_ERROR':
		case 'TMPL_ACTION_SUCCESS':
		case 'TMPL_EXCEPTION_FILE':
			return checkMethod.isConstPath(field);
		case 'DB_NAME':
		case 'DB_USER':
		case 'DB_PREFIX':
		case 'COOKIE_PREFIX':
		case 'DEFAULT_JSONP_HANDLER':
		case 'DATA_CACHE_PREFIX':
		case 'SESSION_TYPE':
		case 'SESSION_PREFIX':
		case 'VAR_AJAX_SUBMIT':
		case 'VAR_JSONP_HANDLER':
		case 'APP_GROUP_PATH':
		case 'TMPL_ENGINE_TYPE':
		case 'TMPL_CACHE_PREFIX':
		case 'DEFAULT_GROUP':
		case 'DB_TYPE':
		case 'LAYOUT_NAME':
		case 'TOKEN_TYPE':
		case 'isword':
		case 'app_name':
			return checkMethod.isWord(field);
		case 'index_file':
			return checkMethod.isFile(field);
		case 'project':
		case 'appname':
			return checkMethod.isnotXMLchars(field);
		case 'appindex':
			return checkMethod.isAbsPath(field);
		case 'apppath':
		case 'base_dir':
			return checkMethod.isAbsDir(field);
		case 'DEFAULT_TIMEZONE':
			return checkMethod.isMIME(field) || checkMethod.isWord(field);
		default :
			return true;
	}
};
var CheckMethod = function () {

	this.mustinput=function(data){
		console.log(data);
		return data != '';
	};

	this.isSingle = function (data) {
		return /^[-_/~@]$/.test(data);
	};
	this.isWord = function (data) {
		return /^\w+$/.test(data);
	};
	this.isWordList = function (data) {
		return /^\w+([,|]\w+)*$/.test(data);
	};
	this.isBool = function (data) {
		return data.toLowerCase() == 'true' || data.toLowerCase() == 'false';
	};
	this.isQuery = function (data) {
		return /^([a-zA-Z]\w*=[^&]*&)*([a-zA-Z]\w*=[^&]+)$/.test(data);
	};
	this.isMVCUrl = function (data) {
		return /^(:?\w+(\^(\w+\|?)+)?((\\d)?\$?)\/?)+(\?(([a-zA-Z]\w*|:\d)=[^&]*&)*(([a-zA-Z]\w*|:\d)=[^&]*))?$/.test(data);
	};
	//支持http(s)带pahtinfo和query的url
	this.isUrl = function (data) {
		return this.isPath(data)||/^https?:\/\/(((\w+(-\w+)*)*\.)+([a-zA-Z]{2,5})|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|localhost)(:\d+)?\/(\w+[-~!%\s]*\/?)+(\.\w+)?((\/\w+\/)?(\w+\/\w+\/?)+)?(\?([a-zA-Z]\w*=[^&]*&)+([a-zA-Z]\w*=[^&]*))?$/.test(data);
	};
	this.isSuffix = function (data) {
		return /^\.\w+$/.test(data);
	};
	this.isFile = function (data) {
		return /^(\w+\.?)+\w+$/.test(data);
	};
	this.isDir = function (data) {
		if (/(\w+\.?)+\.\w+$/.test(data)) {
			return false
		}
		return /^((\.{0,2})\/?)(\w+\s*\/?)*$/.test(data) || /^(((\.{1,2})\\)?|[a-zA-Z]:\\)(\w+\s*\\?)+$/.test(data) ;
	};
	this.isAbsDir=function(data){
		if (/(\w+\.?)+\.\w+$/.test(data)) {
			return false
		}
		return /^\/(\w+\s*\/?)*$/.test(data) || /^([a-zA-Z]:\\)(\w+\s*\\?)+$/.test(data) ;
	};
	this.isPath = function (data) {
		return /^((\.{0,2})\/)?(\w+\s*\/?)+(\w+\.?)+\w+$/.test(data) || /^(((\.{1,2})\\)?|[a-zA-Z]:\\)(\w+\s*\\?)+(\w+\.?)+\w+$/.test(data);
	};
	this.isAbsPath = function (data) {
		return /^\/(\w+\s*\/?)+(\w+\.?)+\w+$/.test(data) || /^([a-zA-Z]:\\)(\w+\s*\\?)+(\w+\.?)+\w+$/.test(data);
	};

	this.isConstPath = function (data) {
		return /^\w+\.['"](\w+\s*(\/|\\)?)+(\w+\.?)+\w+['"]$/.test(data) || this.isPath(data);
	};
	this.isSessionPath = function (data) {
		if (this.isDir(data)) {
			return true;
		} else if (/^[1-9];/.test(data)) {

			return this.isDir(data.replace(/^[1-9];/, ''));
		} else {
			return false;
		}
	};
	this.isIp = function (data) {
		return /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/.test(data);
	};
	this.isDomain = function (data) {
		return /^(\.?\w+(-\w+)*)*\.\w{2,5}$/.test(data);
	};
	this.isHost = function (data) {
		return this.isDomain(data) || this.isIp(data) || data == 'localhost';
	};
	this.isDSN = function (data) {
		return /^(mysql):(host|dbname)=\w*(:\d*)?;(host|dbname)=\w*$/.test(data);
	};
	this.isMIME = function (data) {
		return /^\w*\/[-.\w]*$/.test(data);
	};
	this.isNword = function (data) {
		return /^\W+$/.test(data);
	};
	this.isEmail = function (data) {
		return /^\w+([-+.]\w+)*@\w+((-|\.)\w+)*\.\w+(\w+)*$/.test(data);
	};
	this.isChars = function (data) {
		return /^[!-~]+$/.test(data);
	};
	this.isnotXMLchars=function(data){
		return !/[<>&'"]/.test(data);
	};
};
var checkMethod = new CheckMethod;
var check = function (obj) {
	var id = obj.id, value = $.trim(obj.value);//取出两边空白
	if (obj.id == "") {
		id = "array_key";
	}
	obj.value = value;//更新去除两边空白后的value
	obj = $(obj);
	if (value == '') {

		switch(id){
			case 'base_dir':
			case 'project':
			case 'index_file':
			case 'app_name':
			case 'think_path':
			case 'app_path':
			case 'appname':
			case 'appindex':
			case 'apppath':
				if (!checkMethod.mustinput(value)) {
					obj.removeClass('check_pass conf');
					obj.addClass('check_fail');
					updateAlert();
				}
				mysubmit();
				return;
			default :
				obj.removeClass('check_pass check_fail');
				mysubmit();//改为空值时候，检查submit
				return;
		}

	}
	if (!checkFiled(id, value)) {
		obj.removeClass('check_pass conf');
		obj.addClass('check_fail');
		updateAlert();
	} else {
		obj.removeClass('check_fail conf');
		obj.addClass('check_pass');
		updateAlert();
	}
	mysubmit();//修改完value后检查sbumit
};
var mysubmit = function () {
	if ($(".check_fail").get(0) != null) {
		submit_btn.attr('disabled', 'on').addClass('disabled');
	} else {
		submit_btn.removeAttr('disabled').removeClass('disabled');
	}
};
$(function () {
	window.submit_btn = $("input:submit");
	if (app_path == 'noapp') {
		submit_btn.attr('disabled', 'on');
	}


});
