<?php
	/**
	 * User: zhuyajie
	 * Date: 12-10-19
	 * Time: 上午3:04
	 */ 
class CheckConfig{

	public static function dirModifier( $data ) {
		if ( substr( $data, -1 )!=DIRECTORY_SEPARATOR ) {
			 $data.=DIRECTORY_SEPARATOR;
		}
		return $data;
	}
	static public function isBool( $data ) {
		if ( strtolower($data)=='true' || strtolower($data)=='false' ) {
			return true;
		}

		return false;
	}

	static public function isIsWord($data){
		if ( preg_match( '#^(?!\d)\w*$#', $data )  ) {
			return true;
		}
		return false;
	}

	static public function isFile($data){
		//第一个字符不允许数字，最后一个字符不允许点号
		if ( preg_match( '#^(?!\d)(\w+\.?)+(?<!\.)$#', $data )  ) {
			return true;
		}
		return false;
	}
	static public function isDir($data){
		if ( preg_match( '#^(?!\d)((\.{0,2})\/)?(\w*\/?)*$#', $data )  ) {
			return true;
		}
		return false;
	}

	static public function isPath($data){
		if ( preg_match( '#^(?!\d)((\.{0,2})\/)?(\w*\/?)*(\w+\.?\w+)(?<!\.)$#', $data )  ) {
			return true;
		}
		return false;
	}}
