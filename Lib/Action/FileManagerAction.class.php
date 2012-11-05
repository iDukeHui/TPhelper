<?php
/**
 * User: zhuyajie
 * Date: 12-11-5
 * Time: 上午2:25
 */
class FileManagerAction extends Action
{

	public function _initialize() {
		debug::start( 'PHP' );
	}

	/**
	 * 扫描目录下的文件
	 * @param $dir
	 */
	protected function scanFile( $dir, $ext = '.js' ) {
//		debug::start( 'scanFile' );
//		debug::log( $dir, '目录' );
//		debug::log( $ext, '后缀名' );
		$rdi   = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS|FilesystemIterator::CURRENT_AS_SELF|FilesystemIterator::KEY_AS_FILENAME);
		$rii   = new RecursiveIteratorIterator($rdi, RecursiveIteratorIterator::LEAVES_ONLY);
		$files = array();
		foreach ( $rii as $k=> $v ) {
			if ( strrpos( $k, $ext ) ) {
				$files[] = $k;
			}
		}
//		$a = new SplFileObject('');
//		$a->getSize();
		return $files;
	}

	public function getFileList() {
		debug::start( 'getFileList' );
//		debug::log( $_GET['dir'], '$_GET[\'dir\']' );
		$dir = $_GET['dir'];
		if ( is_dir( $dir ) && is_writeable( $dir ) && is_readable( $dir ) && is_executable( $dir ) ) {
			$oldjsLib = $this->scanFile( $dir );
			chdir( 'public/jsLib' );
			$jsLib = new GlobIterator(realpath('.').'/*',GlobIterator::CURRENT_AS_PATHNAME|GlobIterator::KEY_AS_FILENAME);
			$jsLib = iterator_to_array( $jsLib );
			$this->assign( 'oldjsLib', $oldjsLib );
			$this->assign( 'jsLib', $jsLib );
//			debug::log( getcwd(), 'getcwd' );
//			debug::log( $oldjsLib, 'oldjsLib' );
//			debug::log( $jsLib, 'jsLib' );
		} else {
			debug::warn( '目录权限不足或不是目录' );
		}
		if ( file_exists( '.jsLib.xml' ) ) {
			$doc  = new SimpleXMLIterator('.jsLib.xml', null, true);
			$result=array();
			foreach ( $doc->jslib as  $v ) {
				$r=array();
				$r['file']=(string)$v->file;
				$r['desc']=(string)$v->desc;
				$r['site']=(string)$v->site;
				$r['size'] = (string)$v->size;
				$result[$r['file']]=$r;
			}
			$json = json_encode( $result );
//			debug::log( $doc, 'doc' );
//			debug::log( $result, 'result' );
//			debug::log( $json, 'json_encode' );
			$this->assign( 'json', $json );
		}
		$this->assign( 'dir', $dir );
		$this->assign( 'app_path', CheckConfig::dirModifier( $dir ).'Conf/config.php' );
		$this->display();
	}

	public function addlibs() {
		debug::log( $_POST );
		if ( isset($_POST['jslibs']) ) {
			if ( !file_exists( $_GET['dir'].'js/' ) ) {
				if(!mkdir( $_GET['dir'].'js/' )){
					debug::error( $_GET['dir'].'没有写入权限' );
					return;
				}
			} elseif ( !is_dir( $_GET['dir'].'js/' ) ) {
				debug::error( '保存目标不是一个目录' );
				return;
			}
			foreach ( $_POST['jslibs'] as $k=> $v ) {
				if (is_file('public/jsLib/'.$k)) {
					copy( 'public/jsLib/'.$k, $_GET['dir'].'js/'.$k );
				} elseif ( is_dir( 'public/jsLib/'.$k ) ) {
					self::dirCopy('public/jsLib/'.$k,$_GET['dir'].'js/'.$k);
				}
			}
		}
	}

	protected static function dirCopy($source,$dest) {
		debug::start( 'rcopy' );
		$rdi = new RecursiveDirectoryIterator($source,RecursiveDirectoryIterator::KEY_AS_PATHNAME|RecursiveDirectoryIterator::SKIP_DOTS|RecursiveDirectoryIterator::CURRENT_AS_SELF);
		$rii = new RecursiveIteratorIterator($rdi,RecursiveIteratorIterator::SELF_FIRST);
		$dest = CheckConfig::dirModifier( $dest );
		mkdir( $dest );
//		debug::log( $source, 'source' );
//		debug::log( $dest, 'dest' );
		foreach ( $rii as $k=> $v ) {
			if ( is_file( $k ) ) {
				copy($k,$dest.$v->getSubPathname());
			}elseif(is_dir($k)){
				mkdir($dest.$v->getSubPathname());
			}
		}
	}
}
