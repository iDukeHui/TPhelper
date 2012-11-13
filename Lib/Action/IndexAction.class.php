<?php
class IndexAction extends CommonAction
{
	protected $appinfo = array();
	protected $error = array();
	//include模板
	protected $include_tpl = array(
		'createapp'=> "./Tpl/Common/form/createapp.html",
		'addapp'   => "./Tpl/Common/form/addapp.html",
		'summary'  => './Tpl/Common/form/summary.html',
		'listapp'  => './Tpl/Common/form/listapp.html', );

	public function index() {
		$this->assign( 'extensions', get_loaded_extensions() );
		$this->assign( 'pagetitle', '首页--ThinkPHP助手' );
		$this->assign( 'php_version', PHP_VERSION );
		$this->assign('mysql_client',mysql_get_client_info());
		$this->assign( 'mysql_host', mysql_get_host_info() );
		$this->assign( 'mysql_server', mysql_get_server_info() );
		$this->display( 'index' );
	}

	public function fragmentTest() {
		$this->display();
	}

	public function pregTest() {
		$this->display();
	}

	public function phpinfo() {
		phpinfo();
	}

	public function constinfo() {
		$const = get_defined_constants( true );
		echo "<pre>";
		print_r( $const['user'] );
	}

	public function _empty() {
		$this->index();
	}
}