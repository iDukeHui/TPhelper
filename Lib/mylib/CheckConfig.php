<?php
	/**
	 * User: zhuyajie
	 * Date: 12-10-19
	 * Time: 上午3:04
	 */ 
class CheckConfig{

	public static function dirModifier( $data ) {
		if(IS_WIN){
			$data=strtr($data ,'\\','/');//将L:/dir转为：L:\dir这样的标准Win路径格式
		}
		if ( substr( $data, -1 )!='/') {
			 $data.='/';
		}
		return $data;
	}

	public static function listModifier( $data ) {
		return $data = trim($data,',|');
	}

	static public function isBool( $data ) {
		switch(strtolower($data)){
			case 'on':
			case 'off':
			case 'true':
			case 'false':
			case '1':
			case '0':
				return true;
		}
		return false;
	}

	static public function isWord($data){
		if ( preg_match( '#^\w*$#', $data )  ) {
			return true;
		}
		return false;
	}

	static public function isWordList($data){
		//用,或|分隔的words
		if ( preg_match( '#^(\w+[,|]?)*(?<!,)$#', $data )  ) {
			return true;
		}
		return false;
	}

	static public function isDomain($data){
		if ( preg_match( '#^(\.?\w+)*$#', $data )  ) {
			return true;
		}
		return false;
	}
	static public function isFile($data){
		//第一个字符不允许数字，最后一个字符不允许点号,只允许字母数字下划线
		//^(?!\d)(\w+[-@!~`#$%^|&*()_+=\[\]{};:\'",<>?]*\.?)+(?<!\.)$
		if ( preg_match( '#^(\w+\.?)+(?<!\.)$#', $data )  ) {
			return true;
		}
		return false;
	}
	static public function isDir($data){
		if ( preg_match( '#^((\.{0,2})\/)?(\w*\/?)*$#', $data )  ) {
			return true;
		}
		return false;
	}

	static public function isPath($data){
		if ( preg_match( '#^((\.{0,2})\/)?(\w*\/?)*(\w+\.?\w+)(?<!\.)$#', $data )  ) {
			return true;
		}
		return false;
	}

	public static function isIp( $data ) {
		$ip = ip2long( $data );
		if ( $ip==-1 ||$ip==false  )  {
			return false;
		}
		return true;
	}

	public static function isChars( $data ) {
		if ( preg_match( '#^[^!-\/:-@\[-^`{-~]+$#', $data ) ) {
			return true;
		}
		return false;
	}
}
