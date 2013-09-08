<?php

class AutoLoader {
	public static $loadPaths = array();
	public static function load($class){
		$fileName = '/' . $class . '.php';
                $fileName = str_replace('_', '/', $fileName);

                foreach (self::$loadPaths as $path){
			if (file_exists($src = $path . $fileName)){
				require $src;
				return;
			}
		}
	}
	public static function register($loadPaths){
		self::$loadPaths = $loadPaths;

		spl_autoload_register(array('AutoLoader', 'load'));
	}
}
