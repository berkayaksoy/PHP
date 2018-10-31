<?php

 namespace Leo\lib;
 class Utils {
 	public static $format = 'var_export';
	public static function milliseconds() {
   		return round(microtime(true) * 1000);
 	}

 	public static function log($stuff, $format = '') {
		switch ($format ? $format : self::$format) {
			case 'json':
				fwrite(STDOUT, json_encode($stuff) . "\n");
			break;

			case 'print':
			case 'echo':
				fwrite(STDOUT, $stuff . "\n");
			break;

			default:
			case 'var_export':
				fwrite(STDOUT, var_export($stuff, true) . "\n");
			break;
		}
 	}
 }