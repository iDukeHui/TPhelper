<?php
/**
 * User: zhuyajie
 * Date: 12-11-6
 * Time: 上午2:32
 */
class ToolAction extends Action
{

	public function fragmentTest() {
		defined('IS_AJAX') or define('IS_AJAX',true);
		if ( !IS_AJAX ) {
			return;
		}
		$code = $_POST['code'];
		/*$code ="<?php var_dump(\$_SERVER)?>";*/
		$tmp = tempnam( sys_get_temp_dir(), 'php'.time() );
		chdir( sys_get_temp_dir() );
		file_put_contents( $tmp, $code );
		try{
			include $tmp;
		}catch (Exception $e){
			echo $e->getMessage();
		}
		unlink( $tmp );

	}
}
