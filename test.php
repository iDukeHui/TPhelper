<?php
$dir   = '/home/zhuyajie/Dropbox/tpbuilder';
$rdi     = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS|FilesystemIterator::CURRENT_AS_SELF|FilesystemIterator::KEY_AS_PATHNAME);
$rii    = new RecursiveIteratorIterator($rdi, RecursiveIteratorIterator::LEAVES_ONLY);
$files = array();
foreach ( $rii as $k=> $v ) {
	if ( strrpos( $k, '.js' ) ) {
		$files[] = $k;
	}
}
print_r( $files );

