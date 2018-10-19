<?php
/**
 * Leo Innovation Platform API Connection
 * Requires aws sdk version 3 which is installed into
 * a separate directory called aws3.
 */
namespace Leo;
use Aws\Signature\SignatureV4;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;

use Aws\Firehose\FirehoseClient;
use Aws\Kinesis\KinesisClient;
use Aws\S3\S3Client;
use Aws\CloudWatchLogs\CloudWatchLogsClient;

use Aws\Firehose\Exception\FirehoseException;
use Leo\lib\Config;

class Sdk {
	private $config;
	private $id;

	public function __construct($id) {

		$this->id = $id;
		$this->config = array_merge([
			'uploader'  => 'kinesis',
			'server'    => gethostname(),
		], Config::get());

		if (empty($this->config['leosdk'])) {
			die('leosdk config is not defined in the leo config. See LEO PHP SDK documentation for how the config should be setup.');
		} else if (empty($this->config['leoaws'])) {
			die('leoaws config is not defined in the leo config. See LEO PHP SDK documentation for how the config should be setup.');
		} else if (empty($this->config['leoauth'])) {
			die('leoauth is not defined in the leo config is not defined. See LEO PHP SDK documentation for how the config should be setup.');
		}

		if(!empty($this->config['enableLogging'])) {
			$this->enableLogging();
		}
	}

	/**
	*  @return Loader
	**/
	public function createLoader($checkpointer, $opts = []) {
		if (empty($opts['config'])) {
			$opts['config'] = [];
		}
		$opts['config'] = array_merge($this->config, $opts['config']);
		if(!$this->id) {
			throw new \Exception("You must specify a bot id");
		}
		$massuploader = $uploader = null;

		switch($opts['config']['uploader']) {
			case "firehose":
				$uploader = new lib\Firehose($this->id, $opts['config']);
				$massuploader = new lib\Mass($this->id, $opts['config'], $uploader);
				break;
			case "kinesis":
				$uploader = new lib\Kinesis($this->id, $opts['config']);
				$massuploader = new lib\Mass($this->id, $opts['config'], $uploader);
				break;
			case "mass":
				$kinesis = new lib\Kinesis($this->id, $opts['config']);
				$uploader = new lib\Mass($this->id, $opts['config'], $kinesis);
				break;
		}
		return new lib\Combiner($this->id, $opts, $uploader, $massuploader,$checkpointer);
	}

	public function createOffloader($queue, $opts=[]) {
		$opts = array_merge([
			"buffer"=>1000,
			"loops"=>100,
			"debug"=>false,
			"run_time"=>new \DateInterval('P4M')
		],$opts);

		if($opts['run_time'] instanceof \DateInterval) {
			$opts['end_time'] = (new \DateTime())->add($opts['run_time']);
		} else if(!empty($opts['run_time'])) {
			$opts['end_time'] = new \DateTime("+ " . $opts['run_time']);
		}

		$events = new lib\Events($this->config);
		return $events->getEventReader($this->id, $queue, $events->getEventRange($this->id, $queue,$opts),$opts);
	}

	public function createEnrichment($queue, $transform, $toQueue, $opts=[]) {
		$reader = $this->createOffloader($queue,$opts);
		$stream = $this->createLoader(function ($checkpoint) use ($reader){
			lib\Utils::log($checkpoint);
			$reader->checkpoint($checkpoint);
		});
		$lastEvent = null;
		foreach($reader->events as $i=>$event) {
			$newEvent = $transform($event['payload'], $event);
			if($newEvent) {
				$stream->write($toQueue,$newEvent, ["source"=>$queue, "start"=>$event['eid']]);
			}
		}

		$stream->end();
	}

	public function enableLogging() {
		if(!$this->id) {
			throw new \Exception("You must specify a bot id");
		}
		return new lib\Logger($this->id, $this->config);
	}
}