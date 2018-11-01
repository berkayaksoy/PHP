<?php

namespace Leo\lib;

class Utils
{
	public static $format = 'var_export';
	private static $error_types = [
		'error' => 'e',
		'debug' => 'd',
		'info' => 'i',
		'time' => 't',
	];

	public static function milliseconds()
	{
		return round(microtime(true) * 1000);
	}

	public static function log($log, $logType = 'debug')
	{
		switch (self::$format) {
			case 'json':
				self::writeLog(json_encode($log), $logType);
				break;

			default:
				self::writeLog(var_export($log, true), $logType);
				break;
		}
	}

	public static function error($log)
	{
		self::log($log, 'error');
	}

	public static function info($log)
	{
		self::log($log, 'info');
	}

	public static function debug($log)
	{
		self::log($log, 'debug');
	}

	public static function time($log)
	{
		self::log($log, 'time');
	}

	private static function writeLog($log, $type)
	{
		if (defined('LEO_LOGGER')) {
			$leo_logger = LEO_LOGGER;
		} else {
			$leo_logger = ini_get('LEO_LOGGER');
		}

		if ($leo_logger && (strpos($leo_logger, 'a') !== false || strpos($leo_logger, self::$error_types[$type]) !== false)) {
			if (php_sapi_name() == 'cli') {
				echo 'logging to stderr';
				fwrite(STDERR, $log . PHP_EOL);
			} else {
				error_log($log);
			}
		}
	}
}
