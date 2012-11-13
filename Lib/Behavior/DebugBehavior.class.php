<?php
/**
 * User: zhuyajie
 * Date: 12-11-8
 * Time: 下午8:12
 * @property mixed inaction
 */
class DebugBehavior extends Behavior
{
	protected $options = array(
		'DEBUG_ON'        => true, );
	public static $action;

	/**
	 * 执行行为 run方法是Behavior唯一的接口
	 * @access public
	 *
	 * @param mixed $params  行为参数
	 *
	 * @return void
	 */
	public function run( &$params ) {
		if ( C( 'DEBUG_ON' ) ) {
			if ( self::$action!=ACTION_NAME ) {
				self::$action = ACTION_NAME;
				Debug::start(MODULE_NAME.'Action'. '::'.ACTION_NAME."()" );
			}
		}
	}
}
