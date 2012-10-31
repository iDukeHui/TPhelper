<?php
class IndexAction extends Action
{
	protected $appinfo = array();
	protected $error = array();

	//表单模板
	private $formtpl = array(
		'createapp'=> "./asset/form/createapp.html",
		'addapp'   => "./asset/form/addapp.html",
		'listapp'  => './asset/form/listapp.html', );

	public function index() {
		$listapp = new AdminAction();
		$listapp->listAPP();
		$this->tVar = array_merge( $this->tVar, $listapp->tVar );
		$default = $this->tVar['listapp'][0];
		//此段不能往下移动，否则得到的模板变量是解析以后的变量了
		//为默认app分配变量

		//解析需引入的子模板
		foreach ( $this->formtpl as $key=> $val ) {
			$this->assign( $key, $this->fetch( $this->formtpl[$key] ) );
		}
		$this->assign( 'app_path', $default['path'] );
		//默认app信息显示
		$this->display('index');
	}

	public function phpinfo() {
		phpinfo();
	}

	public function constinfo(){
		$const = get_defined_constants( true );
		echo "<pre>";
		print_r( $const['user'] );
	}

	public function _empty() {
		$this->index();
	}
}