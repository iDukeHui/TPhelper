/**
 *
 * User: zhuyajie
 * Date: 12-10-19
 * Time: 下午9:23
 */

var checkFiled=function(name,field){
	if (/SESSION_OPTIONS\[(id|name|type|prefix)\]/.test(name)) {
		name='isword';
	}else if (/SESSION_OPTIONS\[(cache_expire|expire)\]/.test(name)) {
		name='numeric';
	}else if (/\w+\[k/.test(name)) {
		name = 'isword';
	}else if (/LOAD_EXT_CONFIG\[/.test(name)) {
		name = 'isword';
	}else if (/URL_ROUTE_RULES/.test(name)) {
		name = 'isMVCUrl';
	}

	switch(name){
		case 'URL_PATHINFO_DEPR':
			return checkMethod.isSingle(field);
			break;
		case 'DB_PWD':
		case 'TMPL_LAYOUT_ITEM':
			return checkMethod.isChars(field);
			break;
		case 'isMVCUrl':
			return checkMethod.isMVCUrl(field);
			break;
		case 'URL_PATHINFO_FETCH':
		case 'DEFAULT_FILTER':
		case 'VAR_FILTERS':
		case 'APP_GROUP_LIST':
		case 'DB_LIKE_FIELDS':
		case 'TMPL_DENY_FUNC_LIST'://TP引擎模板禁用函数
		case 'TAGLIB_BUILD_IN': //TP内置标签库名称
		case 'LOG_LEVEL':
		case 'TAGLIB_PRE_LOAD':
		case 'LOAD_EXT_FILE':
		case 'LOAD_EXT_CONFIG':
			return checkMethod.isWordList(field);
			break;
		case 'ERROR_PAGE':
		case 'URL_404_REDIRECT':
			return checkMethod.isUrl(field);
			break;
		case 'DB_HOST':
		case 'COOKIE_DOMAIN':
		case 'SESSION_OPTIONS[domain]':
			return checkMethod.isHost(field);
			break;
		case 'DB_PORT':
		case 'DB_MASTER_NUM':
		case 'DB_SLAVE_NO':
		case 'DB_SQL_BUILD_LENGTH'://SQL语句解析缓存解析队列长度
		case 'COOKIE_EXPIRE':
		case 'DATA_CACHE_TIME':
		case 'DATA_PATH_LEVEL':
		case 'LOG_FILE_SIZE':
		case 'TMPL_CACHE_TIME'://TP模板缓存有效期
		case 'TAG_NESTED_LEVEL': //TP标签嵌套层级数量
		case 'HTML_CACHE_TIME':
		case 'numeric':
			return !isNaN(field);
			break;
		case 'SESSION_OPTIONS[path]':
			return checkMethod.isSessionPath(field);
			break;
		case 'COOKIE_PATH':
			return checkMethod.isDir(field);
			break;
		case 'TMPL_TEMPLATE_SUFFIX':
		case 'TMPL_CACHFILE_SUFFIX'://TP引擎模板缓存后缀
		case 'HTML_FILE_SUFFIX':
			return checkMethod.isSuffix(field);
			break;
		case 'DB_DSN':
			return checkMethod.isDSN(field);
			break;
		case 'TMPL_CONTENT_TYPE':
			return checkMethod.isMIME(field);
			break;
		case 'TMPL_L_DELIM': //TP引擎左分割符
		case 'TMPL_R_DELIM': //TP引擎右分割符
		case 'TAGLIB_BEGIN': //TP标签库标签开始标记
		case 'TAGLIB_END':   //TP标签库标签结束标记
			return checkMethod.isNword(field);
			break;
		case 'TMPL_TRACE_FILE':
		case 'TMPL_ACTION_ERROR':
		case 'TMPL_ACTION_SUCCESS':
		case 'TMPL_EXCEPTION_FILE':

			return checkMethod.isConstPath(field);
		case 'URL_HTML_SUFFIX':
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
		case 'TMPL_CACHE_PREFIX':          // TP模板缓存前缀标识，可以动态改变
		case 'DEFAULT_GROUP':
		case 'DB_TYPE':
		case 'DEFAULT_TIMEZONE':
		case 'LAYOUT_NAME':
		case 'TOKEN_TYPE':
		case 'isword':
			return checkMethod.isWord(field);
			break;
		default :
			return true;
	}

};

var CheckMethod=function(){
	this.isSingle=function(data){
		return /^[-_/~@]$/.test(data);
	};
	this.isWord=function(data){
		return /^\w+$/.test(data);
	};
	this.isWordList=function(data){
		return /^\w+([,|]\w+)*$/.test(data);
	};
	this.isBool=function(data){
		return data.toLowerCase()=='true'||data.toLowerCase()=='false';
	};


	this.isQuery=function(data){
		return /^([a-zA-Z]\w*=[^&]*&)*([a-zA-Z]\w*=[^&]+)$/.test(data);
	};
	this.isMVCUrl=function(data){
		return /^(:?\w+(\^(\w+\|?)+)?((\\d)?\$?)\/?)+(\?(([a-zA-Z]\w*|:\d)=[^&]*&)*(([a-zA-Z]\w*|:\d)=[^&]*))?$/.test(data);
	};
	//支持http(s)带pahtinfo和query的url
	this.isUrl=function(data){
		return /^https?:\/\/((\w+(-\w+)*)*\.)+([a-zA-Z]{2,5})\/(\w+[-~!%\s]*\/?)+(\.\w+)?((\/\w+\/)?(\w+\/\w+\/?)+)?(\?([a-zA-Z]\w*=[^&]*&)+([a-zA-Z]\w*=[^&]*))?$/.test(data);
	};


	this.isSuffix=function(data){
		return /^\.\w+$/.test(data);
	};
	this.isFile=function(data){
		return /^(\w+\.?)+\w+$/.test(data);
	};
	this.isDir=function(data){
		return /^((\.{0,2})\/?)(\w+\s*\/?)*$/.test(data) ||/^(((\.{1,2})\\)?|[a-zA-Z]:\\)(\w+\s*\\?)+$/.test(data);
	};
	this.isPath=function(data){
		return /^((\.{0,2})\/)?(\w+\s*\/?)+(\w+\.?)+\w+$/.test(data)||/^(((\.{1,2})\\)?|[a-zA-Z]:\\)(\w+\s*\\?)+(\w+\.?)+\w+$/.test(data);
	};
	this.isConstPath=function(data){
		return /^\w+\.['"](\w+\s*(\/|\\)?)+(\w+\.?)+\w+['"]$/.test(data);
	};
	this.isSessionPath=function(data){
		if (this.isDir(data)) {
			return true;
		} else 	if(/^[1-9];/.test(data)) {
			return this.isDir(data.replace(/^[1-9];/,''));
		} else {
			return false;
		}
	};


	this.isIp=function(data){
		return /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/.test(data);
	};
	this.isDomain=function(data){
		return /^(\.?\w+(-\w+)*)*\.\w{2,5}$/.test(data);
	};
	this.isHost=function(data){
		return this.isDomain(data)||this.isIp(data)||data=='localhost';
	};


	this.isDSN=function(data){
		return /^(mysql):(host|dbname)=\w*(:\d*)?;(host|dbname)=\w*$/.test(data);
	};
	this.isMIME=function(data){
		return /^\w*\/[-.\w]*$/.test(data);
	};
	this.isNword=function(data){
		return /^\W+$/.test(data);
	};
	this.isEmail=function(data){
		return /^\w+([-+.]\w+)*@\w+((-|\.)\w+)*\.\w+(\w+)*$/.test(data);
	};
	this.isChars=function(data){
		return /^[!-~]+$/.test(data);
	};

};

var checkMethod=new CheckMethod;

