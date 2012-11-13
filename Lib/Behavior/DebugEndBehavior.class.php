<?php
/**
 * User: zhuyajie
 * Date: 12-11-8
 * Time: 下午10:51
 */
class DebugEndBehavior extends Behavior
{

	/**
	 * 执行行为 run方法是Behavior唯一的接口
	 * @access public
	 *
	 * @param mixed $params  行为参数
	 *
	 * @return void
	 */
	public function run( &$params ) {
		Debug::end();
		// TODO: Implement run() method.
}}
