<?php
/**
 * User: zhuyajie
 * Date: 12-10-19
 * Time: 上午4:54
 */
class JsonAction extends Action
{
	public function sendConfig() {
		if ( $this->isAjax() ) {
			$file = $_POST['file'];
			if ( is_file( $file ) && is_readable( $file ) ) {
				$arr = include $file;
				$this->ajaxReturn( $arr );
			} else {
				exit($file.'文件不可读或不存在');
			}
		}else{
			exit('该url只接受ajax请求');
		}
	}
}