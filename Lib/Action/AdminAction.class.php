<?php
/**
 * User: zhuyajie
 * Date: 12-10-26
 * Time: 下午9:51
 */
class AdminAction extends Action
{
	private $app_name;
	private $app_path;//即应用的APP_PATH目录
	private $app_index;//入口文件名

	private $error=array();

	public function index() {
		$this->display();
	}
	/**
	 * 添加需要TPbuilder管理的应用

	 */
	public function addAPP() {
		$_POST['apppath']=CheckConfig::dirModifier($_POST['apppath']);
		if ( $this->check( $_POST ) ) {
			$this->app_name = $_POST['appname'];
			$this->app_path = $_POST['apppath'];
			$this->app_index = $_POST['appindex'];
			$this->updateXML();
		} else {
			//提示错误
		}

	}

	protected function updateXML() {
		$file="Conf/applist.xml";
		$doc = new SimpleXMLIterator($file, null, true);
		if ( $doc->app['name'] ) {
			$this->error = "已经存在同名app";
		} else {
			$doc->addChild("app");
			$doc->app->addAtrribute( "name" );
			$doc->app->addAtrribute( "path" );
			$doc->app->addAtrribute( "index" );
			$doc->app['name']=$this->app_name;
			$doc->app['name']=$this->app_name;
			$doc->app['path']=$this->app_index;
			$doc->asXML($file);
		}
	}


	protected function check( $appinfo ) {

		if ( !CheckConfig::isChars( $appinfo['appname'] ) ) {
			$this->error[] = "APPNAME不允许特殊字符";
		}

		if ( !is_dir( $appinfo['apppath'] ) ) {
			$this->error[] = "项目目录不正确";
		}

		$file = $_POST['apppath']."Conf/config.php";

		if ( is_readable( $file ) ) {
			$config = include $file;
			if ( !is_array( $config ) ) {
				$this->error[] = "应用的配置不是合法的ThinkPHP配置文件";
			}
		} else {
			$this->error[] = "没有找到您的应用的配置文件";
		}

		if ( $this->error ) {
			return false;
		}

		return true;
	}

}
