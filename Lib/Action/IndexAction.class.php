<?php
class IndexAction extends Action
{
	protected $appinfo = array();
	protected $error = array();

	public function _initialize() {
		debug::start( 'PHP' );
	}

	//表单模板
	private $formtpl = array(
		'createapp'=> "./asset/form/createapp.html",
		'addapp'   => "./asset/form/addapp.html",
		'listapp'  => './asset/form/listapp.html', );

	public function index() {
		debug::start( 'index' );
		//在解析子模板之前线获取默认app_path
		$listapp = new AdminAction();
		$listapp->listAPP();
		$this->tVar = array_merge( $this->tVar, $listapp->tVar );
		//每次回到首页，就把cookie恢复成默认项目的设置
		$default = $this->tVar['listapp'][0];
		cookie( 'config_path', $default['path'].'Conf/config.php' );
		cookie( 'base_dir',CheckConfig::dirModifier($default['path']));
		cookie( 'think_path', THINK_PATH );
		cookie( 'tp_helper', APP_PATH );
		debug::log( $default['path'], 'base_dir' );
		//解析需引入的子模板
		foreach ( $this->formtpl as $key=> $val ) {
			$this->assign( $key, $this->fetch( $this->formtpl[$key] ) );
		}
		//默认app信息显示
		$this->display( 'index' );
	}

	public function fragmentTest() {
		$this->display();
	}

	public function phpinfo() {
		phpinfo();
	}

	public function constinfo() {
		$abc = 'hhh';
		debug::start();
		debug::log( $abc );
		debug::error( $_REQUEST );
		$const = get_defined_constants( true );
		echo "<pre>";
		print_r( $const['user'] );
	}

	public function _empty() {
		$this->index();
	}
}