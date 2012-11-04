<?php
/**
 * User: zhuyajie
 * Date: 12-11-2
 * Time: 下午6:06
 */
class debug
{

	static function console() {
		if ( APP_DEBUG==false ) {
			return;
		}
		//监测类库包，函数包
		$fp = class_exists( 'FirePHP' );
		$fb = class_exists( 'FB' );
		$cp = class_exists( 'ChromePhp' );
		$pc = class_exists( 'PhpConsole' );
		//分配参数
		$args  = func_get_args();
		$count = count( $args );
		if ( $count==0 ) {
			return;
		} elseif ( $count==1 ) {
			$var = func_get_arg( 0 );
		} elseif ( $count==2 ) {
			$var   = func_get_arg( 0 );
			$label = func_get_arg( 1 );
		} else {
			$var     = func_get_arg( 0 );
			$label   = func_get_arg( 1 );
			$seurity = func_get_arg( 2 );
		}
		ob_start();
		if ( $pc ) {
			PhpConsole::start();
		}
		if ( $fp && $fb ) {
			switch ( $count ) {
				case 1:
					FB::send( $var );
					break;
				case 2:
					FB::send( $var, $label );
					break;
				case 3:
					FB::send( $var, $label, strtoupper( $seurity ) );
			}
		}
		if ( $cp ) {
			switch ( $count ) {
				case 1:
					ChromePhp::log( $var );
					break;
				case 2:
					ChromePhp::log( $label, $var );
					break;
				case 3:
					ChromePhp::log( $label, $var, strtolower( $seurity ) );
			}
		}
	}

	static function warn( $var,$label='WARNING' ) {
		self::console( $var, $label, 'WARN' );
	}

	static function error( $var,$label='ERROR  ' ) {
		self::console( $var, $label.'@ '.basename(__FILE__).' Line:'.__LINE__, 'ERROR' );

	}

	static function log( $var,$label='LOG    ' ) {
		self::console( $var, $label, 'LOG' );
	}

	static function info( $var,$label='INFO   ' ) {
		self::console( $var, $label, 'INFO' );
	}

	static function start($name='GROUP', $options=null) {
		self::console( $name, null, 'group' );
		FB::group( $name, (array)$options );
	}

	static function end() {
		self::console( 'END', null, 'GROUP_END' );
	}


}