<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	protected $appinfo=array();
	protected $error=array();
	protected $notice=array();

    public function index(){
		$this->display();
	}


	public function build(){
		import( "@.class.CheckConfig", '', '.php' );

		$this->setApp();
		$this->bulidIndex();

		$wwwroot=CheckConfig::dirmodifier($_SERVER['DOCUMENT_ROOT']);

		$localhost=strtr( $this->appinfo['BASE_DIR'],
			array($wwwroot=> "http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/'));
//		if ( !function_exists( 'curl_setopt_array' ) ) {
		$content= file_get_contents( $localhost.$this->appinfo['INDEX_FILE'] );
		if ( $content===false ) {
			echo "入口文件创建完成，请从浏览器访问该文件以创建项目结构";
		}
//		}
//		else {
//			$ch=curl_init( $localhost.$this->appinfo['INDEX_FILE'] );
//			curl_setopt_array( $ch, array(CURLOPT_NOBODY=>true,CURLOPT_RETURNTRANSFER) );
//			curl_exec( $ch );
//			if ( curl_errno( $ch ) ) {
//				echo "入口文件创建完成，请从浏览器访问该文件以创建项目结构";
//				echo "\n";
//			}
//			curl_close( $ch );
//		}
	}

	public function setApp(){
		$appinfo['BASE_DIR']     = CheckConfig::dirModifier($_POST['BASE_DIR']);
		$appinfo['INDEX_FILE']   = $_POST['INDEX_FILE'];
		$appinfo['APP_NAME']     = $_POST['APP_NAME'];
		$appinfo['APP_PATH']     = CheckConfig::dirModifier($_POST['APP_PATH']);
		$appinfo['THINK_PATH']   = CheckConfig::dirModifier($_POST['THINK_PATH']);
		$appinfo['APP_DEBUG']    = $_POST['APP_DEBUG'];
//		$appinfo['MODE_NAME']    = $_POST['MODE_NAME'];
//		$appinfo['RUNTIME_PATH'] = $_POST['RUNTIME_PATH'];
		if (!$this->check( $appinfo )) {
			$this->error();
		}
		$this->appinfo=$appinfo;
	}

	protected function bulidIndex(){
		$file = new SplFileObject($this->appinfo['INDEX_FILE'], 'wb+');
		$file->fwrite( '<?php'.PHP_EOL);
		$file->fwrite("define('APP_NAME','"."{$this->appinfo['APP_NAME']}');".PHP_EOL);//在前端检测
		$file->fwrite("define('APP_DEBUG',"."{$this->appinfo['APP_DEBUG']});".PHP_EOL);//下拉选择
		$file->fwrite("define('APP_PATH','"."{$this->appinfo['APP_PATH']}');".PHP_EOL);
		$file->fwrite("define('THINK_PATH','"."{$this->appinfo['THINK_PATH']}');".PHP_EOL);
//		$file->fwrite("//define('MODE_NAME','"."{$this->appinfo['MODE_NAME']}');".PHP_EOL);//下拉选择
//		$file->fwrite("//define('RUNTIME_PATH','"."{$this->appinfo['RUNTIME_PATH']}');".PHP_EOL);
		$file->fwrite( "require_once THINK_PATH.'ThinkPHP.php';".PHP_EOL );
	}

	protected function check($appinfo) {
		if(!is_writable($appinfo['BASE_DIR'])){
			$this->error[]="项目目录不可写入：".$appinfo['BASE_DIR'];
		}
		chdir( $appinfo['BASE_DIR'] );
		$app_path = dirname( $appinfo['APP_PATH'] );
		if(!is_writable($app_path)){
			$this->error[]="无法创建目录：".$appinfo['APP_PATH'];
		}
		if ( !is_file( $appinfo['THINK_PATH'].'ThinkPHP.php' ) ) {
			$this->error[]= "无法找到框架核心文件";
		}
		if ( !CheckConfig::isBool( $appinfo['APP_DEBUG'] ) ) {
			$this->error[] = "需要布尔值true或false";
		}
		if ( !CheckConfig::isIsWord( $appinfo['APP_NAME'] ) ) {
			$this->error[] = "APP_NAME 只允许英文字符数字和下划线，不允许数字开头";
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

	protected function error(){
		foreach ( $this->error as $err ) {
			echo $err,"<br>";
		}
		exit;
	}

	public function _empty() {
		var_dump($_SERVER);
	}
}