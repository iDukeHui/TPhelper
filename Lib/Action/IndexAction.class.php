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
		//默认app信息显示
		$this->display();
	}

	public function createAPP() {
		import( "@.class.CheckConfig", '', '.php' );
		$this->setApp();
		$this->bulidIndex();
		$wwwroot = CheckConfig::dirmodifier( $_SERVER['DOCUMENT_ROOT'] );
		$localhost = strtr( $this->appinfo['BASE_DIR'], array( $wwwroot=> "http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/' ) );
		$content   = file_get_contents( $localhost.$this->appinfo['INDEX_FILE'] );
		if ( $content===false ) {
			echo "入口文件创建完成，请从浏览器访问该文件以创建项目结构";
		}
	}

	protected function setApp() {
		$appinfo['BASE_DIR'] = CheckConfig::dirModifier( $_POST['BASE_DIR'] );
		$appinfo['INDEX_FILE'] = $_POST['INDEX_FILE'];
		$appinfo['APP_NAME'] = $_POST['APP_NAME'];
		$appinfo['APP_PATH'] = CheckConfig::dirModifier( $_POST['APP_PATH'] );
		$appinfo['THINK_PATH'] = CheckConfig::dirModifier( $_POST['THINK_PATH'] );
		$appinfo['APP_DEBUG'] = $_POST['APP_DEBUG'];
		if ( !$this->check( $appinfo ) ) {
			$this->error();
		}
		$this->appinfo = $appinfo;
	}

	protected function bulidIndex() {
		$file = new SplFileObject($this->appinfo['INDEX_FILE'], 'wb+');
		$file->fwrite( '<?php'.PHP_EOL );
		$file->fwrite( "define('APP_NAME','"."{$this->appinfo['APP_NAME']}');".PHP_EOL ); //在前端检测
		$file->fwrite( "define('APP_DEBUG',"."{$this->appinfo['APP_DEBUG']});".PHP_EOL ); //下拉选择
		$file->fwrite( "define('APP_PATH','"."{$this->appinfo['APP_PATH']}');".PHP_EOL );
		$file->fwrite( "define('THINK_PATH','"."{$this->appinfo['THINK_PATH']}');".PHP_EOL );
		$file->fwrite( "require_once THINK_PATH.'ThinkPHP.php';".PHP_EOL );
	}

	protected function check( $appinfo ) {
		if ( !is_writable( $appinfo['BASE_DIR'] ) ) {
			$this->error[] = "项目目录不可写入：".$appinfo['BASE_DIR'];
		}
		chdir( $appinfo['BASE_DIR'] );
		$app_path = dirname( $appinfo['APP_PATH'] );
		if ( !is_writable( $app_path ) ) {
			$this->error[] = "无法创建目录：".$appinfo['APP_PATH'];
		}
		if ( !is_file( $appinfo['THINK_PATH'].'ThinkPHP.php' ) ) {
			$this->error[] = "无法找到框架核心文件";
		}
		if ( !CheckConfig::isBool( $appinfo['APP_DEBUG'] ) ) {
			$this->error[] = "需要布尔值true或false";
		}
		if ( !CheckConfig::isWord( $appinfo['APP_NAME'] ) ) {
			$this->error[] = "APP_NAME 只允许英文字符数字和下划线";
		}
		if ( is_file( $appinfo['INDEX_FILE'] ) ) {
			$this->error[] = "入口文件已存在";
		}
		if ( is_dir( $appinfo['APP_PATH']."Lib" ) ) {
			$this->error[] = "项目目录似乎已存在，请更改目录";
		}
		if ( !CheckConfig::isFile( $appinfo['INDEX_FILE'] ) ) {
			$this->error[] = "入口文件 只允许英文字符、数字、点和下划线，不允许数字开头";
		}
		if ( $this->error ) {
			return false;
		}
		return true;
	}

	protected function error() {
		foreach ( $this->error as $err ) {
			echo $err, "<br>";
		}
		exit;
	}

	public function _empty() {
//		var_dump( $_SERVER );
		$const = get_defined_constants( true );
		echo "<pre>";
		print_r( $const['user'] );
	}
}