<?php

namespace Leo\lib;

class Config
{
	private $config;
	static private $instance;

	public function __construct($config_file = '')
	{
		if (empty(self::$instance)) {
			self::$instance = $this;
		}

		if ($config_file) {
			$this->loadConfig($config_file);
		}
	}

	static public function get()
	{
		if (empty(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance->getConfig();
	}

	public function getConfig()
	{
		if (!empty($this->config)) {
			return $this->config;
		}

		return $this->loadConfig();
	}

	private function loadConfig($config_file = '')
	{
		if ($config_file) {
			require(realpath($config_file));
		} else {
			$path = realpath($_SERVER['DOCUMENT_ROOT']);

			require($path . '/leo_config.php');
		}

		if (empty($config)) {
			throw new \Exception('leo_config.php does not contain a valid configuration.');
		}
		return $this->config = $config;
	}
}
