<?php
/**
 * User: zhuyajie
 * Date: 12-11-6
 * Time: 上午2:32
 */
class ToolAction extends CommonAction
{

	public function _initialize() {
		Debug::$on=false;
		parent::_initialize();
	}

	public function fragmentTest() {
		defined( 'IS_AJAX' ) or define('IS_AJAX', true);
		if ( !IS_AJAX ) {
			$this->error( '非法请求' );
			return;
		}
		$code = $_POST['code'];
		$tmp = tempnam( sys_get_temp_dir(), 'php'.time() );
		chdir( sys_get_temp_dir() );
		file_put_contents( $tmp, $code );
		try {
//			C( 'SHOW_ERROR_MSG', true );
			include $tmp;
		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );
		}
		unlink( $tmp );
	}

	public function pregTest() {
		defined( 'IS_AJAX' ) or define('IS_AJAX', true);
		if ( !IS_AJAX ) {
			$this->error( '非法请求' );
			return;
		}
		$matches = null;
		$count   = 0;
		switch ( $_POST['preg_func'] ) {
			case 'preg_match':
			case 'preg_match_all':
				if ( $_POST['flag']=='' ) {
					$result = $_POST['preg_func']( $_POST['pattern'], $_POST['strinput'], $matches  );
				} else {
					$result = $_POST['preg_func']( $_POST['pattern'], $_POST['strinput'], $matches, $_POST['flag'] );
				}
				break;
			case 'preg_replace':
				$result = $_POST['preg_func']( $_POST['pattern'], $_POST['replacement'], $_POST['strinput'], -1, $count );
				break;
			default:
				exit('暂不支持这个函数');
		}
		if ( $result===false ) {
			switch ( preg_last_error() ) {
				case PREG_INTERNAL_ERROR:
					exit("PCRE内部错误");
				case PREG_BACKTRACK_LIMIT_ERROR:
					exit('超出回溯限制');
				case PREG_RECURSION_LIMIT_ERROR:
					exit('超出递归限制');
				case PREG_BAD_UTF8_ERROR:
					exit('异常的utf-8数据');
				case PREG_BAD_UTF8_OFFSET_ERROR:
					exit('偏移量与合法的urf-8代码不匹配');
				default:
					exit('请检查您的正则表达式');
			}
		} elseif ( is_int( $result ) ) {
			print_r( $matches );
		} else {
			echo "发生{$count}次替换,替换结果如下:\n". $result ;
		}
	}
}
