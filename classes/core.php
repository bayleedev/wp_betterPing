<?php

class core {

	/**
	 * Will render a view
	 */
	public static function render($view, $args = array()) {
		extract($args);
		include(__DIR__ . '/../views/' . $view . '.html.php');
	}

	/**
	 * A list of all currently created helpers.
	 */
	protected static $helpers = array();

	/**
	 * Will create or give back an existing helper
	 * @param string $name The name of the helper
	 * @return mixed
	 */
	public static function helper($name) {
		$class = $name . 'Helper';
		$file = __DIR__ . '/../helpers/' . $class . '.php';

		if(!isset(self::$helpers[$class])) {
			// Find/create
			if(file_exists($file)) {
				include_once($file); // include
				if(class_exists($class)) {
					self::$helpers[$class] = new $class; // return a new instance
				} else {
					throw new Exception('Class does not exist: ' . $class);
				}
			} else {
				throw new Exception('File does not exist: ' . $file);
			}
		}
		return self::$helpers[$class];
	}
}