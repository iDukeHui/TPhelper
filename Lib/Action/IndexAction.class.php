<?php
class IndexAction extends Action
{
	protected $appinfo = array();
	protected $error = array();

	public function _initialize(){
		debug::start('PHP');
	}

	//表单模板
	private $formtpl = array(
		'createapp'=> "./asset/form/createapp.html",
		'addapp'   => "./asset/form/addapp.html",
		'listapp'  => './asset/form/listapp.html', );

	public function index() {
		$listapp = new AdminAction();
		$listapp->listAPP();
		$this->tVar = array_merge( $this->tVar, $listapp->tVar );
		//在解析子模板之前线获取默认app_path
		if ( isset($_GET['app_path']) ) {
			$this->assign( 'app_path', $_GET['app_path'] );
		} else {
			$default = $this->tVar['listapp'][0];
			$this->assign( 'app_path', $default['path'].'Conf/config.php' );
		}

		//解析需引入的子模板
		foreach ( $this->formtpl as $key=> $val ) {
			$this->assign( $key, $this->fetch( $this->formtpl[$key] ) );
		}
		//默认app信息显示
		$this->display('index');
	}

	public function phpinfo() {
		phpinfo();
	}

	public function constinfo(){
		$abc='hhh';
		debug::start();
		debug::log($abc);
		debug::error( $_REQUEST );
		$const = get_defined_constants( true );
		echo "<pre>";
		print_r( $const['user'] );
	}

	public function _empty() {
		$this->index();
	}

}