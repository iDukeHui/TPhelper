<?php
/**
 * User: zhuyajie
 * Date: 12-10-26
 * Time: 下午9:51
 */
class AdminAction extends Action
{
	private $app_name; //应用命名，用于区分不同的项目
	private $app_path; //即应用的APP_PATH的realpah目录
	private $app_index; //入口文件完整路径
	private $applist = "Conf/applist.xml"; //应用列表存放位置
	private $appinfo = array(); //永远保存应用创建时需要的信息
	private $error = array();

	public function _initialize(){
		debug::start('PHP');
	}
	public function index() {
		$this->display();
	}

	/**
	 * 添加需要TPbuilder管理的应用

	 */
	public function addAPP() {
		if ( !IS_POST ) {
			die("非法请求");
		}
		$_POST['apppath'] = CheckConfig::dirModifier( $_POST['apppath'] );
		if ( $this->checkAPP( $_POST ) ) {
			$this->app_name  = $_POST['appname'];
			$this->app_path  = $_POST['apppath'];
			$this->app_index = $_POST['appindex'];
			if ( !$this->updateAPP() ) {
				var_dump( $this->error );
			}
			$this->redirect( "Index/index", array(), 3, "项目添加成功，即将返回首页" );
		} else {
			var_dump( $this->error );
			//提示错误
		}
	}

	public function listAPP() {
		try {
			if ( !is_readable( $this->applist ) || !is_writable( $this->applist ) || !is_file( $this->applist ) ) {
				throw new Exception("读取applist.xml遇到问题");
			}
			$doc = new SimpleXMLElement($this->applist, null, true);
		} catch ( Exception $e ) {
			$this->assign( 'noapp', "发生异常:".$e->getMessage()."  文件:".$e->getFile()."  行号:".$e->getLine() );
			debug::error( 'Admmin:listAPP' );
			return;
		}
		$apps = $doc->app;
		if ( $doc->app->count()==0 ) {
			$this->assign( 'noapp', "<span>还没有添加任何项目</span>" );
		} else {
			$list    = array();
			$wwwroot = CheckConfig::dirmodifier( $_SERVER['DOCUMENT_ROOT'] ); //web目录
			foreach ( $apps as $app ) {
				if ( (string)$app['name'] && (string)$app['index'] && (string)$app['path'] ) {
					$localhost = "http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/';
					$index     = (string)$app['index'];
					$url       = strtr( $index, array( $wwwroot=> $localhost ) );
					$list[]    = array(
						'name'   => (string)$app['name'],
						'url'    => $url,
						'index'  => (string)$app['index'],
						'path'   => CheckConfig::dirModifier((string)$app['path']),
					);
				}
			}
			debug::log( $list );
			$this->assign( 'listapp', $list );
		}
	}

	public function removeAPP() {
		if ( !IS_POST ) {
			die("非法请求");
		}
		try {
			if ( !is_readable( $this->applist ) || !is_writable( $this->applist ) || !is_file( $this->applist ) ) {
				throw new Exception("读取applist.xml遇到问题");
			}
			$doc = new SimpleXMLElement($this->applist, null, true);
			$i   = 0;
			debug::log( $doc );
			foreach ( $doc as $app ) {
				debug::log( $app );
				if ( $app['name']==$_POST['data'] ) {
					unset($doc->app[$i]);
					break;
				}
				$i++;
			}
			$doc->asXML( $this->applist );
			$this->ajaxReturn( array( 'success'=> "项目成功移除" ) );
		} catch ( Exception $e ) {
			$this->ajaxReturn( array( 'error'=> "发生异常:".$e->getMessage()."  文件:".$e->getFile()."  行号:".$e->getLine() ) );
			return;
		}
	}

	protected function updateAPP() {
		if ( is_file( $this->applist ) && is_writable( $this->applist ) ) {
			try {
				$doc = new SimpleXMLIterator($this->applist, null, true);
			} catch ( Exception $e ) {
				$this->error[] = "发生异常:".$e->getMessage()."  文件:".$e->getFile()."  行号:".$e->getLine();
				return false;
			}
			if ( $doc->getName()!="apps" ) {
				$this->error[] = "applist.xml的根元素必须是apps";
				return false;
			}
			$i = $doc->count();
			if ( $i!==0 ) {
				$apps = $doc->app;
				foreach ( $apps as $app ) {
					if ( $app['name']==$this->app_name ) {
						$this->error[] = "已经存在同名app";
						return false;
					}
				}
			}
			$doc->addChild( "app" );
			$doc->app[$i]->addAttribute( "name", $this->app_name );
			$doc->app[$i]->addAttribute( "path", $this->app_path );
			$doc->app[$i]->addAttribute( "index", $this->app_index );
			if ( $doc->asXML( $this->applist ) ) {
				return true;
			} else {
				$this->error[] = "Conf/applist.xml写入失败";
				return false;
			}
		} else {
			$this->error[] = "Conf/applist.xml不存在";
			return false;
		}
	}

	public function createAPP() {
		if ( !IS_POST ) {
			die("非法请求");
		}
		$this->setIndex();
		$this->app_name  = $this->appinfo['project'];
		$this->app_path  = realpath( $this->appinfo['APP_PATH'] );
		$this->app_index = $this->appinfo['BASE_DIR'].$this->appinfo['INDEX_FILE'];
		chdir( APP_PATH );
		debug::log( getcwd(), 'getcwd' );
		if ( !$this->updateAPP() ) {
			var_dump( $this->error );
			exit;
		}
		chdir( $this->appinfo['BASE_DIR'] );
		$this->bulidIndex();
		$wwwroot   = CheckConfig::dirmodifier( $_SERVER['DOCUMENT_ROOT'] );
		$localhost = strtr( $this->appinfo['BASE_DIR'], array( $wwwroot=> "http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].'/' ) );
		$content   = file_get_contents( $localhost.$this->appinfo['INDEX_FILE'] );
		if ( $content===false ) {
			$this->redirect( "Index/index", array(), 3, "入口文件创建完成，请从浏览器访问该文件以创建项目结构" );
		} else {
			$this->redirect( "Index/index", array(), 3, "项目添加成功，即将返回首页" );
		}
	}

	public function setDefaultAPP() {
		if ( !IS_POST ) {
			die("非法请求");
		}
		try {
			if ( !is_writable( $this->applist ) ) {
				throw new Exception("{$this->applist}文件不可写入");
			}
			$doc = new DOMDocument('1.0', 'utf-8');
			if ( !$doc->load( $this->applist ) ) {
				throw new Exception("加载{$this->applist}文件失败");
			}
		} catch ( Exception $e ) {
			$this->ajaxReturn( array( 'error'=> "发生异常:".$e->getMessage()."  文件:".$e->getFile()."  行号:".$e->getLine() ) );
			return;
		}
		$apps  = $doc->getElementsByTagName( 'app' );
		$first = $apps->item( 0 )->cloneNode( true );
		foreach ( $apps as $node ) {
			$name = $node->getAttribute( 'name' );
			if ( $name==$_POST['data'] ) {
				$searched = $node->cloneNode( true );
				$node->parentNode->replaceChild( $first, $node );
				$apps->item( 0 )->parentNode->replaceChild( $searched, $apps->item( 0 ) );
			}
		}
		$doc->save( $this->applist );
		$this->ajaxReturn( array( 'success'=> "默认项目设置成功" ) );
	}

	protected function setIndex() {
		$appinfo['BASE_DIR']   = CheckConfig::dirModifier( $_POST['BASE_DIR'] );
		$appinfo['INDEX_FILE'] = $_POST['INDEX_FILE'];
		$appinfo['APP_NAME']   = $_POST['APP_NAME'];
		$appinfo['APP_PATH']   = CheckConfig::dirModifier( $_POST['APP_PATH'] );
		$appinfo['THINK_PATH'] = CheckConfig::dirModifier( $_POST['THINK_PATH'] );
		$appinfo['APP_DEBUG']  = $_POST['APP_DEBUG'];
		$appinfo['MODE_NAME']  = $_POST['MODE_NAME'];
		$appinfo['project']    = $_POST['project'];
		if ( !$this->checkIndex( $appinfo ) ) {
		}
		$this->appinfo = $appinfo;
	}

	protected function bulidIndex() {
		$file = new SplFileObject($this->appinfo['INDEX_FILE'], 'wb+');
		$file->fwrite( '<?php'.PHP_EOL );
		$file->fwrite( "define('APP_NAME','"."{$this->appinfo['APP_NAME']}');".PHP_EOL );
		$file->fwrite( "define('APP_DEBUG',"."{$this->appinfo['APP_DEBUG']});".PHP_EOL );
		$file->fwrite( "define('APP_PATH','"."{$this->appinfo['APP_PATH']}');".PHP_EOL );
		$file->fwrite( "define('THINK_PATH','"."{$this->appinfo['THINK_PATH']}');".PHP_EOL );
		if ( $this->appinfo['MODE_NAME'] ) {
			$file->fwrite( "define('MODE_NAME','"."{$this->appinfo['MODE_NAME']}');".PHP_EOL );
		}
		$file->fwrite( "require_once THINK_PATH.'ThinkPHP.php';".PHP_EOL );
		$git = dirname(dirname(__DIR__))."/.gitignore";
		$BASE_DIR = $this->appinfo['BASE_DIR'];
		copy( $git, $BASE_DIR.'.gitignore' );
	}

	protected function checkIndex( $appinfo ) {
		if ( file_exists( $appinfo['BASE_DIR']  ) ) {
			if ( !is_dir($appinfo['BASE_DIR'])|| !is_writable( $appinfo['BASE_DIR'] ) ) {
				$this->error[] = "项目目录不可写入：".$appinfo['BASE_DIR'];
			}
		} else {
			mkdir( $appinfo['BASE_DIR'], 0777, true );
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
			$this->error[] = "APP_DEBUG需要布尔值true或false";
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
		if ( !CheckConfig::isChars( $appinfo['project'] ) ) {
			$this->error[] = "项目命名不允许特殊字符";
		}
		if ( $this->error ) {
			return false;
		}
		return true;
	}

	protected function checkAPP( $appinfo ) {
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
