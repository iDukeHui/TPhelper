<?php
/**
 * User: zhuyajie
 * Date: 12-10-26
 * Time: 下午9:51
 */
class AdminAction extends Action
{
	private $app_name;
	private $app_path; //即应用的APP_PATH目录
	private $app_index; //入口文件名
	private $error = array();
	private $applist = "Conf/applist.xml";

	public function index() {
		$this->display();
	}

	/**
	 * 添加需要TPbuilder管理的应用

	 */
	public function addAPP() {
		import( "@.class.CheckConfig", '', '.php' );
		$_POST['apppath'] = CheckConfig::dirModifier( $_POST['apppath'] );
		if ( $this->check( $_POST ) ) {
			$this->app_name  = $_POST['appname'];
			$this->app_path  = $_POST['apppath'];
			$this->app_index = $_POST['appindex'];
			var_dump( $_POST );
			$this->updateXML();
		} else {
			var_dump( $this->error );
			//提示错误
		}
	}

	public function listAPP() {
		import( "@.class.CheckConfig", '', '.php' );
		$doc  = new SimpleXMLElement($this->applist, null, true);
		$apps=$doc->app;
		$list=array();
		$wwwroot=CheckConfig::dirmodifier($_SERVER['DOCUMENT_ROOT']);//web目录
		foreach ( $apps as $app ) {
			if ( (string)$app['name'] && (string)$app['index'] && (string)$app['path']) {
				$localhost="http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/';
				$index=(string)$app['index'];
				$url=  strtr($index,array($wwwroot=>$localhost));
				$list[]=array('name'=>(string)$app['name'] ,'url'=>$url);
			}
		}
		if ( $list ) {
			$this->assign( 'list',$list );
		}
		$this->display();
		var_dump( $list );
	}

	public function removeAPP() {

	}

	protected function updateXML() {

		if ( is_file( $this->applist) ) {
			$doc  = new SimpleXMLIterator($this->applist, null, true);
			$apps = $doc->app;
			foreach ( $apps as $app ) {
				if ( $app['name']==$this->app_name ) {
					$this->error = "已经存在同名app";
					break;
				}
			}
			if ( !$this->error ) {
				$doc->addChild( "app" );
				$i = $doc->count()-1;
				$doc->app[$i]->addAttribute( "name", $this->app_name );
				$doc->app[$i]->addAttribute( "path", $this->app_path );
				$doc->app[$i]->addAttribute( "index", $this->app_index );
				$doc->asXML( $this->applist );
			}
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
				$this->error[] = "在该目录下找不到Conf目录";
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
