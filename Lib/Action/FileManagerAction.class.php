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
		return $files;
	}

	public function getFileList() {
		debug::start( 'getFileList' );
		$dir = cookie('base_dir');//项目的基础目录
		debug::log( $dir, 'dir' );
		if ( is_dir( $dir ) && is_writeable( $dir ) && is_readable( $dir ) && is_executable( $dir ) ) {
			$oldjsLib = $this->scanFile( $dir );//扫描项目下面的js文件
			$jsLib = new GlobIterator(realpath('public/jsLib').'/*',GlobIterator::CURRENT_AS_PATHNAME|GlobIterator::KEY_AS_FILENAME);
			$jsLib = iterator_to_array( $jsLib );//扫描TP助手下面的js库
			$this->assign( 'oldjsLib', $oldjsLib );
			$this->assign( 'jsLib', $jsLib );
			debug::log( getcwd(), 'getcwd' );
			debug::log( $oldjsLib, 'oldjsLib' );
			debug::log( $jsLib, 'jsLib' );
		} else {
			debug::warn( '目录权限不足或不是目录' );
		}
		if ( file_exists( 'public/jsLib/.jsLib.xml' ) ) {
			$doc  = new SimpleXMLIterator('public/jsLib/.jsLib.xml', null, true);
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
			debug::log( $json, 'json_encode' );
			$this->assign( 'json', $json );
		}
		$this->display();
	}

	public function addlibs() {
		debug::log( $_POST );
		if ( isset($_POST['jslibs']) ) {
			$dir = cookie( 'base_dir' );
			if ( !file_exists( $dir.'js/' ) ) {
				if(!mkdir( $dir.'js/' )){
					debug::error( $dir.'---没有写入权限' );
					return;
				}
			} elseif ( !is_dir( $dir.'js' ) ) {
				debug::error( $dir.'js---不是一个目录' );
				return;
			}
			foreach ( $_POST['jslibs'] as $k=> $v ) {
				if (is_file('public/jsLib/'.$k)) {
					copy( 'public/jsLib/'.$k, $dir.'js/'.$k );
				} elseif ( is_dir( 'public/jsLib/'.$k ) ) {
					self::dirCopy('public/jsLib/'.$k,$dir.'js/'.$k);
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
