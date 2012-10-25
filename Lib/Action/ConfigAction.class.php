<?php
/**
 * User: zhuyajie
 * Date: 12-10-19
 * Time: 上午4:40
 */
class ConfigAction extends Action
{

	protected $conf_info = array();
	protected $source=array('URL_ROUTE_RULES','TMPL_PARSE_STRING','LOAD_EXT_CONFIG','SESSION_OPTIONS');//需要合并处理的k/v键二维配置配置
	protected $tobefilter=array('SESSION_OPTIONS');//需要过滤的键为固定字符串的二维配置
	protected $error = array();

	public function index() {
		$this->display();
	}

	public function build() {
		import( "@.class.CheckConfig", '', '.php' );
		$this->source=array_unique(array_merge($this->source,explode(",",trim($_GET['filter'],', '))));
		$this->setConfig();
		$this->bulidConfig();
		$this->display();
var_dump( $_POST );
	}

	private function setConfig() {
		$this->conf_info = $_POST ;
		$this->mergeKV();
		foreach ( $this->tobefilter as $item ) {
			$this->conf_info[$item] = array_filter( $this->conf_info[$item] ,array($this,'filter'));
		}
		$this->conf_info = array_filter( $this->conf_info );
	}

	private function bulidConfig() {
		$config = var_export( $this->conf_info, true );
		$config = "<?php\nreturn ".strtr( $config, array(
														"'true'"  => 'true',
														"'false'" => "false" ) ).';';
		file_put_contents( 'test.php', $config );
	}

	private function mergeKV() {
		foreach ( $this->source as $item ) {
			$i       = 1;
			$compact = array();
			$count = count( $this->conf_info[$item] );
			$this->conf_info[$item]=array_filter( $this->conf_info[$item],array($this,'filter'));
			while ( true ) {
				if ( isset($this->conf_info[$item]['k'.$i]) && isset($this->conf_info[$item]['v'.$i])) {
					$compact[$this->conf_info[$item]['k'.$i]] = $this->conf_info[$item]['v'.$i];
				}
				if ( $i>=$count/2 ) {
					break;
				}
				$i++;
			}
			if ( $compact==false ) {
				continue;
			}
			$this->conf_info[$item] = $compact;
		}
	}

	public function sendConfig() {
		if ( $this->isAjax() ) { //可判断jQuery的ajax请求
			$file = $_POST['file'];

			if ( is_file( $file ) && is_readable( $file ) ) {
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


	/**
	 * array_filter使用的回调方法
	 * @param $v
	 *
	 * @return bool
	 */
	private function filter( $v ) {
		return $v==='' ? false : true;
	}
}
