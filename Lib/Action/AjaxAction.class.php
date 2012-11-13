<?php
/**
 * User: zhuyajie
 * Date: 12-10-19
 * Time: 上午4:54
 */
class AjaxAction extends CommonAction
{
	public function sendConfig() {
		if ( $this->isAjax() ) { //可判断jQuery的ajax请求
			$file = $_POST['file'];

			if ( substr($file,0,4)=="http" || is_file( $file ) && is_readable( $file ) ) {
				if ( $_POST['accept']==true ) {
					echo  file_get_contents( $file );
				} else {
					$data = include $file;
					$this->ajaxReturn( $data);
				}
			} else {
				$this->ajaxReturn(array('error'=>$file.'文件不可读或不存在'));
			}
		}else{
			exit('该url只接受ajax请求');
		}
	}
}