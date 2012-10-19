/**
 *
 * User: zhuyajie
 * Date: 12-10-19
 * Time: 下午9:23
 */

var checkFiled=function(field){
	var checkMethod=new CheckMethod;

	switch(field){
		case 'URL_PATHINFO_DEPR':
			return checkMethod.isSingle(field);
			break;
		case 'URL_PATHINFO_FETCH':
		case 'DEFAULT_FILTER':
		case 'VAR_FILTERS':
		case 'APP_GROUP_LIST':
		case 'DB_LIKE_FIELDS':
			break;
			return checkMethod.isWordList(field);
			break;
		case 'URL_404_REDIRECT':
			return checkMethod.isUrl(field);
			break;
		case 'DB_HOST':
		case 'COOKIE_DOMAIN':
			return checkMethod.isHost(field);
			break;
		case 'DB_PORT':
		case 'DB_MASTER_NUM':
		case 'DB_SLAVE_NO':
		case 'DB_SQL_BUILD_LENGTH':
		case 'COOKIE_EXPIRE':
		case 'DATA_CACHE_TIME':
		case 'DATA_PATH_LEVEL':
		case 'LOG_FILE_SIZE':
			return !isNaN(field);
			break;
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
			return checkMethod.isWord(field);
			break;
		case 'COOKIE_PATH':
			return checkMethod.isDir(field);
			break;
		case 'ERROR_PAGE':
			return checkMethod.isPath(field);
			break;
		case 'TMPL_TEMPLATE_SUFFIX':
			return checkMethod.isSuffix(field);
			break;
		case 'DB_DSN':
			return checkMethod.isDSN(field);
			break;
		default :
			return true;
	}
	return true;

};

var CheckMethod=function(){
	this.isWord=function(data){
		return /^\w+$/.test(data);
	};
	this.isWordList=function(data){
		return /^\w+([,|]\w+)*$/.test(data);
	};
	this.isBool=function(data){
		return data.toLowerCase()=='true'||data.toLowerCase()=='false';
	};
	this.isDomain=function(data){
		return /^(\.?\w+(-\w+)*)*\.\w{2,5}$/.test(data);
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
	this.isIp=function(data){
		return /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/.test(data);
	};
	this.isSingle=function(data){
		return /^[-_/~@]$/.test(data);
	};
	this.isQuery=function(data){
		return /^([a-zA-Z]\w*=[^&]*&)+([a-zA-Z]\w*=[^&]*)$/.test(data);
	};
	this.isMVCUrl=function(data){
		return /^(:?\w+\/)?(:?\w+\/:?\w+\/?)+(\?([a-zA-Z]\w*=[^&]*&)+([a-zA-Z]\w*=[^&]*))?$/.test(data);
	};
	//支持http(s)带pahtinfo和query的url
	this.isUrl=function(data){
		return /^https?:\/\/((\w+(-\w+)*)*\.)+([a-zA-Z]{2,5})\/(\w+[-~!%\s]*\/?)+(\.\w+)?((\/\w+\/)?(\w+\/\w+\/?)+)?(\?([a-zA-Z]\w*=[^&]*&)+([a-zA-Z]\w*=[^&]*))?$/.test(data);
	};

	this.isHost=function(data){
		return this.isDomain(data)||this.isIp(data)||data=='localhost';
	};
	this.isSuffix=function(data){
		return /^\.\w+$/.test(data);
	};
	this.isDSN=function(data){
		return /^(mysql):(host|dbname)=\w*(:\d*)?;(host|dbname)=\w*$/.test(data);
	};


};


